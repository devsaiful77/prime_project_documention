<?php

namespace App\Services\CI;

use App\CIAPIRequestResponse;
use Carbon\Carbon;

class CIRequestResponseService
{
    public static function ciRequestResponse($params = array())
    {
        $modelName = new CIAPIRequestResponse;
        $modelName->cif_number = (!empty($params['cif_number'])) ? $params['cif_number'] : '';
        $modelName->myPrimeId = (!empty($params['myPrimeId'])) ? $params['myPrimeId'] : '';
        $modelName->type = (!empty($params['type'])) ? $params['type'] : '';
        $modelName->product_type = (!empty($params['product_type'])) ? $params['product_type'] : 0;
        $modelName->status_code = (!empty($params['status_code'])) ? $params['status_code'] : '';
        $modelName->status_msg = (!empty($params['status_msg'])) ? $params['status_msg'] : '';
        $modelName->endpoint = (!empty($params['endpoint'])) ? $params['endpoint'] : '';
        $modelName->json_node = (!empty($params['json_node'])) ? $params['json_node'] : '';
        $modelName->execution_time = (!empty($params['execution_time'])) ? $params['execution_time'] : 0;
        $modelName->save();
    }
}
