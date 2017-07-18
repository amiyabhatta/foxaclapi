<?php

/* 
 * This is for WL Dashboard 
 */
namespace Fox\Services\Contracts;

interface UserContract {
    
    
    /**
     * list all users
     */
    public function getUsers();
    
    /**
     * User Login
     * @param type $request
     */
    public function login($request);
}

