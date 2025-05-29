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

if (!function_exists('isUserLoggedIn')) {
    /**
     * Check if a user is currently logged in based on session data.
     *
     * @return bool
     */
    function isUserLoggedIn()
    {
        return session()->has('user_data') && session('user_data') !== null;
    }
}

