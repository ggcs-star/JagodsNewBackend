<?php

namespace App\Rules\Traits;
use Illuminate\Http\Response;




trait ApiResponse
{
    /*
     * @param  array $data
     * @return json
     */
    public function successResponse($data, $code = Response::HTTP_OK)
    {
        return response()->json(['data' => $data], $code);
    }

    public function errorResponse(string $message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

}
