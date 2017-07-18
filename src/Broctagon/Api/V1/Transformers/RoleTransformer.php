<?php

namespace Fox\Transformers;

use League\Fractal;

class RoleTransformer extends Fractal\TransformerAbstract {

    public function transform($data) {
       
        return [
            'id' => $data['id'],
            'role' => $data['role'],
            'role_slug' => $data['role_slug'],
            'links' => [
                'rel' => 'self',
                'uri' => 'api/v1/roles/' . $data['id']
            ]
        ];
    }

}
