<?php

namespace App\Traits;
use Illuminate\Support\Facades\Log;

trait LogCurlError
{
    public static function logCurlError($ch, $restUrl, $CIFNumber = null, $product_type = null)
    {
        $errorMsg = curl_error($ch);
        $errorNo = curl_errno($ch);

        $errorMessage = sprintf(
            "cURL Error [%d]: %s | URL: %s | CIF: %s | ProductType: %s",
            $errorNo,
            $errorMsg,
            $restUrl,
            $CIFNumber ?? 'N/A',
            $product_type ?? 'N/A'
        );

        Log::error($errorMessage);
    }

    public static function logHttpResponse($httpCode, $response, $url, $CIFNumber = null, $product_type = null)
    {
        // Log only error responses
        if ($httpCode < 200 || $httpCode >= 300) {
            Log::error("HTTP ERROR | Code: $httpCode | URL: $url | CIF: $CIFNumber | Product: $product_type | Response: $response");
        }
    }

}
