<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('generateToken')) {
    function generateToken(): string
    {
        return md5(rand(1, 10) . microtime());
    }
}

if (!function_exists('myCacheFunction')) {
    function myCacheFunction($name, $callback)
    {
        static $cache = [];

        if(isset($cache[$name])) return $cache[$name];
        return $cache[$name] = $callback();
    }

}

if (!function_exists('setIsValidField')) {
    function setIsValidField(string $field, $errors): string
    {
        return $errors->has($field)
            ? ' is-invalid'
            : ($errors->count() > 0 ? ' is-valid' : '');
    }
}


if (!function_exists('writeErrorToFile')) {
    function writeErrorToFile(string $errorMessage)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        $callingFile = $backtrace[0]['file'];
        $callingLine = $backtrace[0]['line'];
        $callingMethod = $backtrace[1]['function'];
        $errorMsg = sprintf("Error in %s, method %s, line %d. %s", $callingFile, $callingMethod, $callingLine, $errorMessage);
        Log::error($errorMsg);
    }
}


