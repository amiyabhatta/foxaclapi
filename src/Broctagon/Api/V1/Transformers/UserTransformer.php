<?php

namespace Fox\Transformers;

use League\Fractal;

class UserTransformer extends Fractal\TransformerAbstract {

    public function transform($user) {
       
        return [
            'id' => $user['id'],
            'name' => $user['name'],
            'manager_id' => $user['manager_id'],
            'email' => $user['email'],            
            'links' => [
                'rel' => 'self',
                'uri' => 'api/v1/users/' . $user['id']
            ]
        ];
    }

}
