<?php

namespace Fox\Transformers;

use League\Fractal;

class GatewayTransformer extends Fractal\TransformerAbstract {

    public function transform($data) {
       
        return [
            'id' => $data['id'],
            'name' => $data['gateway_name'],
            'host' => $data['host'],
            'port' => $data['port'],
            'username' => $data['username'],
            'password' => $data['master_password'],
            'links' => [
                'rel' => 'self',
                'uri' => 'api/v1/gateway/' . $data['id']
            ]
        ];
    }

}
