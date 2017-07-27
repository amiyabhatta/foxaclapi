<?php

namespace Fox\Transformers;

use League\Fractal;
use Fox\Models\user_server_access;

class UserTransformer extends Fractal\TransformerAbstract {

    public function transform($user) {
       $srever_id = user_server_access::select('server_id')
                                       ->where('user_id',$user['id'])->get()->toArray();
       
       
        return [
            'id' => $user['id'],
            'name' => $user['name'],
            'manager_id' => $user['manager_id'],
            'email' => $user['email'],
            'server_id' => $srever_id,
            'links' => [
                'rel' => 'self',
                'uri' => 'api/v1/users/' . $user['id']
            ]
        ];
    }

}
