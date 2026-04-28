<?php

namespace App\Http\Controllers;

use App\Http\Repositories\Dashboard\DashboardRepository;
use Illuminate\Http\Request;
use Validator;

class DashboardController extends Controller
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