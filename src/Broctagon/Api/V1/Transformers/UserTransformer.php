<?php

namespace Fox\Transformers;

use League\Fractal;
use Fox\Models\user_server_access;
use Fox\Models\UserHasRole;

class UserTransformer extends Fractal\TransformerAbstract {

    public function transform($user) {
       $srever_id = user_server_access::select('server_id')
                                       ->where('user_id',$user['id'])->get();
       
       $serId = [];
       $in = 0;
       foreach($srever_id as $srever_ids){
          $serId[$in]['server_id'] = (int) $srever_ids->server_id;
          $in++;
       }

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
            'manager_id' => (int) $user['manager_id'],
            'email' => $user['email'],
            'groups' => $user['groups'],
            'server_id' => $serId,
            'role_id'  => (int) $role_id,
            'links' => [
                'rel' => 'self',
                'uri' => 'api/v1/users/' . $user['id']
            ]
        ];
    }

}
