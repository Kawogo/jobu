<?php

use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\api\RequirementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\RoutePath;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
$limiter = config('fortify.limiters.login');

Route::post(RoutePath::for('login', '/login'), [AuthenticatedSessionController::class, 'store'])
->middleware(array_filter([
    'guest:'.config('fortify.guard'),
    $limiter ? 'throttle:'.$limiter : null,
]));

Route::post(RoutePath::for('logout', '/logout'), [AuthenticatedSessionController::class, 'destroy'])
->name('logout');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::apiResource('jobs', JobController::class);
    Route::apiResource('jobs.requirements', RequirementController::class)->scoped(['job_id' => 'requirement']);
});
