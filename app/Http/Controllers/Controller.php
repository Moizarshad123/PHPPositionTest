<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponserTrait;
use App\Traits\GeneralHelperTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiResponserTrait, GeneralHelperTrait;
}

