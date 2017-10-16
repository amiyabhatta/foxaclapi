<?php

namespace Fox\Transformers;

use League\Fractal;
use Fox\Models\user_server_access;
use Fox\Models\UserHasRole;

class UserTransformer extends Fractal\TransformerAbstract {

    public function transform($user) {
       $srever_id = user_server_access::select('server_id')
                                       ->where('user_id',$user['id'])->get()->toArray();
       
       $user_role   = UserHasRole::select('roles_id')
                        ->where('user_id', '=', $user['id'])->get();
       
       if (count($user_role)) {
            $role_id =  $user_role[0]->roles_id;
        } else {
            $role_id =  '';
        }
       
        return [
            'id' => $user['id'],
            'name' => $user['name'],
            'manager_id' => $user['manager_id'],
            'email' => $user['email'],
            'groups' => $user['groups'],
            'server_id' => $srever_id,
            'role_id'  => $role_id,
            'links' => [
                'rel' => 'self',
                'uri' => 'api/v1/users/' . $user['id']
            ]
        ];
    }

}
