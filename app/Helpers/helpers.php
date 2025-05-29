<?php

if (!function_exists('isOwner')) {
    function isOwner()
    {
        $user = session('user_data');
        return $user && $user->peran === 1;
    }
}

if (!function_exists('userRole')) {
    function userRole()
    {
        $user = session('user_data');
        return $user ? $user->peran : null;
    }
}

