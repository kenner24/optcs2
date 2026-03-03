<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;

/**
 * @OA\Info(title="Saas API", version="0.1")
 * @OA\SecurityScheme(
 *    securityScheme="bearerAuth",
 *    in="header",
 *    name="bearerAuth",
 *    type="http",
 *    scheme="bearer",
 *    bearerFormat="JWT",
 * ),
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Send json response on success
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($message, $data = null, $code = HTTP_OK)
    {
        $res = [
            'success' => true,
            'message' => $message
        ];

        if ($data !== null) {
            $res['data'] = $data;
        }

        return Response::json($res, $code);
    }

    /**
     * Send json response on error
     * @param $message
     * @param optional array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($message, $code = HTTP_NOT_FOUND, array $data = [])
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $res['data'] = $data;
        }

        return Response::json($res, $code);
    }
}
