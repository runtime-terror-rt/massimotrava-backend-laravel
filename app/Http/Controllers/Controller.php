<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * @OA\Info(title="My First API", version="0.1")
 */
abstract class Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @OA\Info(
     *     title="My API",
     *     version="1.0.0",
     *     description="API documentation for my Laravel project"
     * )
     */

    /**
     * @OA\Server(
     *     url="https://api.biovuedigitalwellness.com",
     *     description="Production API"
     * )
     */

}
