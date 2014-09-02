<?php

class Db_Generic_Logger
{
    /**
     * array of array findLibraryCaller()
     * Return part of stacktrace before calling first library method.
     * Used in debug purposes (query logging etc.).
     */
    function findLibraryCaller()
    {
        $caller = call_user_func(
            array(&$this, 'debug_backtrace_smart'),
            $this->ignoresInTraceRe,
            true
        );
        return $caller;
    }




    /**
     * void _logQuery($query, $noTrace=false)
     * Must be called on each query.
     * If $noTrace is true, library caller is not solved (speed improvement).
     */
    function _logQuery($query, $noTrace=false)
    {
        if (!$this->_logger) return;
        $this->_expandPlaceholders($query, false);
        $args = array();
        $args[] =& $this;
        $args[] = $query[0];
        $args[] = $noTrace? null : $this->findLibraryCaller();
        return call_user_func_array($this->_logger, $args);
    }


        /**
     * array debug_backtrace_smart($ignoresRe=null, $returnCaller=false)
     *
     * Return stacktrace. Correctly work with call_user_func*
     * (totally skip them correcting caller references).
     * If $returnCaller is true, return only first matched caller,
     * not all stacktrace.
     *
     * @version 2.03
     */
    function debug_backtrace_smart($ignoresRe=null, $returnCaller=false)
    {
        if (!is_callable($tracer='debug_backtrace')) return array();
        $trace = $tracer();

        if ($ignoresRe !== null) $ignoresRe = "/^(?>{$ignoresRe})$/six";
        $smart = array();
        $framesSeen = 0;
        for ($i=0, $n=count($trace); $i<$n; $i++) {
            $t = $trace[$i];
            if (!$t) continue;

            // Next frame.
            $next = isset($trace[$i+1])? $trace[$i+1] : null;

            // Dummy frame before call_user_func* frames.
            if (!isset($t['file'])) {
                $t['over_function'] = $trace[$i+1]['function'];
                $t = $t + $trace[$i+1];
                $trace[$i+1] = null; // skip call_user_func on next iteration
            }

            // Skip myself frame.
            if (++$framesSeen < 2) continue;

            // 'class' and 'function' field of next frame define where
            // this frame function situated. Skip frames for functions
            // situated in ignored places.
            if ($ignoresRe && $next) {
                // Name of function "inside which" frame was generated.
                $frameCaller = (isset($next['class'])? $next['class'].'::' : '') . (isset($next['function'])? $next['function'] : '');
                if (preg_match($ignoresRe, $frameCaller)) continue;
            }

            // On each iteration we consider ability to add PREVIOUS frame
            // to $smart stack.
            if ($returnCaller) return $t;
            $smart[] = $t;
        }
        return $smart;
    }
}