<?php

namespace Fox\Transformers;

use League\Fractal;

class serverTransformer extends Fractal\TransformerAbstract {

    public function transform($data) {
       
        return [
            'id' => $data['id'],
            'servername' => $data['servername'],
            'ipaddress' => $data['ipaddress'],
            'username' => $data['username'],
            'password' => $data['password'],
            'databasename' => $data['databasename'],
            'gateway_name' => $data['gateway_name'],
            'gateway_id' => $data['GatewayID'],
            'master_id' => $data['masterid'],
            'port' => $data['port'],
            'mt4api' => $data['mt4api'],
            'links' => [
                'rel' => 'self',
                'uri' => 'api/v1/server/' . $data['id']
            ]
        ];
    }

}
