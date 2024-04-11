<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class MyHttpResponseException extends HttpResponseException
{
    public function __construct(string $message, string $errorText = null, int $code)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        $callingFile = $backtrace[0]['file'];
        $callingLine = $backtrace[0]['line'];
        $callingMethod = $backtrace[1]['function'];

        if ($code === 422) {
            $errorMsg = sprintf("Error in %s, method %s, line %d. %s. %s", $callingFile, $callingMethod, $callingLine, $message, $errorText);
            Log::info('[Validation error] ' .  $errorMsg);
        }

        if ($code === 500) {
            $errorMsg = sprintf("Error in %s, method %s, line %d. %s", $callingFile, $callingMethod, $callingLine, $errorText);
            Log::error($errorMsg);
        }

        $responseData = ['message' => $message];

        if ($code === 422) {
            $responseData['success'] = false;
        }

        $response = response()->json($responseData)->setStatusCode($code);

        parent::__construct($response);
    }
}
