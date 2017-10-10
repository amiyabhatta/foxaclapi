<?php

namespace Fox\Transformers;

use League\Fractal;
use Fox\Models\RoleHasPermission;

class RoleTransformer extends Fractal\TransformerAbstract {

    public function transform($data) {
       $role_permissions = RoleHasPermission::select('permissions_id','action')
                                       ->where('role_id',$data['id'])->get()->toArray();
        return [
            'id' => $data['id'],
            'role' => $data['role'],
            'role_slug' => $data['role_slug'],
            'role_permissions' => $role_permissions,
            'links' => [
                'rel' => 'self',
                'uri' => 'api/v1/roles/' . $data['id']
            ]
        ];
    }

}
