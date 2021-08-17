<?php

namespace App\Traits;
trait ApiResponder
{
    // erreur ou succès ?
    function jsonGet($message, $datas = null) {
        if ($datas) {
            return $this->jsonSuccess($datas);
        }
        return $this->jsonError($message);
    }

    function jsonSuccess($datas, $message = null, $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'time' => now(),
            'message' => $message,
            'datas' => $datas
        ], $code);
    }

    function jsonError($message, $code = 404, $datas=null) {
        return response()->json([
            'status' => 'error',
            'time' => now(),
            'message' => $message,
            'datas' => $datas
        ], $code);
    }

    function jsonSuccessNoDatas($message, $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'time' => now(),
            'message' => $message
        ], $code);
    }
}
