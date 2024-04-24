<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ViewErrorBag;

if (!function_exists('generateToken')) {
    function generateToken(): string
    {
        return md5(rand(1, 10) . microtime());
    }
}

//myCacheFunction - функция кеширования, чтобы не дергалась бд много раз.
//$priceIncrease = myCacheFunction(
//    'price_increase',
//    fn() => Setting::firstWhere('prop_key', 'price_increase')->prop_value
//);
if (!function_exists('myCacheFunction')) {
    function myCacheFunction(string $name, callable $callback): string
    {
        static $cache = [];
        if (isset($cache[$name])) {
            return $cache[$name];
        }
        return $cache[$name] = $callback();
    }

}

if (!function_exists('setIsValidField')) {
    function setIsValidField(string $field, ViewErrorBag $errors): string
    {
        return $errors->has($field)
            ? ' is-invalid'
            : ($errors->count() > 0 ? ' is-valid' : '');
    }
}

if (!function_exists('writeErrorToFile')) {
    function writeErrorToFile(string $errorMessage): void
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        $callingFile = $backtrace[0]['file'];
        $callingLine = $backtrace[0]['line'];
        $callingMethod = $backtrace[1]['function'];
        $errorMsg = sprintf("Error in %s, method %s, line %d. %s", $callingFile, $callingMethod, $callingLine, $errorMessage);
        Log::error($errorMsg);
    }
}

if (!function_exists('formatPrice')) {
    function formatPrice(int $price, string $label): string
    {
        return ($price !== 0)
            ? ($label . " <span style='color:red;'>" . ($price > 0 ? ' +' : ' ') . $price . ' %</span>')
            : ($label . ' ' . $price . ' %');
    }

}

if (!function_exists('formatPriceToDataAttribute')) {
    function formatPriceToDataAttribute(int $price, string $label): string
    {
        return $label . ($price > 0 ? ' +' : ' ') . $price . ' %';
    }
}

if (!function_exists('formatErrorMessage')) {
    function formatErrorMessage(string $method, int $line, string $exceptionMessage): string
    {
        return sprintf("Error in %s, line %d. %s", $method, $line, $exceptionMessage);
    }
}
