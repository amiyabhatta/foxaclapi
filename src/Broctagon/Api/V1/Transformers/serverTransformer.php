<?php

namespace Fox\Transformers;

use League\Fractal;

class serverTransformer extends Fractal\TransformerAbstract {

    public function transform($data) {
       
        return [
            'servername' => $data['servername'],
            'ipaddress' => $data['ipaddress'],
            'username' => $data['username'],
            'password' => $data['password'],
            'databasename' => $data['databasename'],
            'Gateway Name' => $data['gateway_name'],
            'links' => [
                'rel' => 'self',
                'uri' => 'api/v1/server/' . $data['id']
            ]
        ];
    }

}
