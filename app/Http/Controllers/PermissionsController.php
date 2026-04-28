<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PermissionRepository;
use Illuminate\Http\Request;


class PermissionsController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api');
    }
}