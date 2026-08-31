<?php


namespace App\Services\CI;

use App\CustomerInterfaceToken;
use App\IssueConfig;
use App\issueFieldsetGroup;
use Throwable;

class TokenValidatedService
{
    public static function validatedToken($token = '')
    {
        try {
            $cusInterfaceInfo = CustomerInterfaceToken::where('token', $token)
                ->where('is_verify', 1)
                ->first();
            if ($cusInterfaceInfo){
                return $cusInterfaceInfo;
            } else {
                return false;
            }
        } catch (Throwable $e) {
            //dd($e->getMessage());
            return false;
        }
    }
}
