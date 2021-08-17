<?php

namespace App\Http\Controllers\v1;

use App\Traits\ApiResponder;

use App\Http\Controllers\Controller;

class PingController extends Controller
{

    use ApiResponder;

    public function index()
    {
        return $this->jsonSuccess(['pong' => now()]);
    }
}
