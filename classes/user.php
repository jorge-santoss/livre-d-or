<?php
class User {
    private $username;
    private $user_id;
    private $access_lvl;

    public function __construct($u){
        $this->username = $u['login'];
        $this->user_id = $u['user_id'];
        $this->access_lvl = $u['access_lvl'];
    }
}