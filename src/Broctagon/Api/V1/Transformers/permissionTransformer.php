<?php

namespace Fox\Transformers;

use League\Fractal;

class permissionTransformer extends Fractal\TransformerAbstract {

    public function transform($data) {
       
        return [
            'id' => $data['id'],
            'name' => $data['name'],
            'user_type' => $data['user_type'],            
            'links' => [
                'rel' => 'self',
                'uri' => 'api/v1/permission/' . $data['id']
            ]
        ];
    }

}
