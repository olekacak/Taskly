<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        'register',  // Add other routes here as needed
        'login',
        'logout',
        '/user/postUser',
        '/user/getUser',
        '/workspace/postWorkspace',
        '/workspace/deleteWorkspace/*',
        '/task/postTask',
        '/task/postComplete',
        '/task/deleteTask/*'
    ];
}
