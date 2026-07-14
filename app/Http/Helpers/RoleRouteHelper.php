<?php

if (!function_exists('role_route')) {
    function role_route($name) {
        $user = auth()->user();
        if ($user->hasRole('vendor')) {
            return route('vendor.' . $name);
        }
        return route('admin.' . $name);
    }
}
