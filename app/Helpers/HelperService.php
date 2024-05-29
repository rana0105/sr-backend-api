<?php

namespace App\Helpers;


class HelperService
{
    //response exception message
    public static function responseException(\Exception $e, bool $json = true)
    {
        if ($json) {
            return response()->json([
                "success" => false,
                "msg" => config("constants.MSG.500"),
                "exceptionMsg" => [
                    "message" => (config("constants.APP.DEBUG") ? $e->getMessage() : NULL),
                    "line" => (config("constants.APP.DEBUG") ? $e->getLine() : NULL),
                    "file" => (config("constants.APP.DEBUG") ? $e->getFile() : NULL),
                ]
            ], 500);
        }
    }
}
