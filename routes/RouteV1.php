<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use \Illuminate\Support\Facades\Artisan;
use \App\Exceptions\V1\RequestValidationException;
use \Illuminate\Http\Request;

$router->group(['prefix' => 'v1', 'namespace' => 'V1'], function () use ($router) {

    //Authentication
    $router->group(['prefix' => 'auth', 'middleware' => 'client_credentials'], function () use ($router) {
        $router->post('/login', 'AuthenticationController@login');
    });

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->delete('/logout', 'AuthenticationController@logout');
        $router->put('/password', 'AuthenticationController@changePassword');
    });

    //Protected Routes
    $router->group(['middleware' => ['auth']], function () use ($router) {

        //User Management apis
        $router->group(['prefix' => 'users'], function () use ($router) {
            $router->get('/', 'UserController@get');
            $router->get('/{id}', 'UserController@first');
            $router->post('/', 'UserController@store');
            $router->put('/{id}', 'UserController@update');
            $router->put('/status/{id}', 'UserController@toggleStatus');
            $router->delete('/{id}', 'UserController@destroy');
        });

        // Roles apis
        $router->group(['prefix' => 'roles'], function () use ($router) {
            $router->get('/', 'RoleController@get');
//                $router->get('/{id}', 'RoleController@first');
//                $router->post('/', 'RoleController@store');
//                $router->put('/{id}', 'RoleController@update');
//                $router->delete('/{id}', 'RoleController@destroy');
        });

        // Permissions apis
        $router->get('/permissions', 'PermissionController@get');

        //Services apis
        $router->group(['prefix' => 'leads'], function () use ($router) {
            $router->get('/', 'LeadController@get');
            $router->get('/{id}', 'LeadController@first');
            $router->post('/', 'LeadController@store');
            $router->put('/{id}', 'LeadController@update');
            $router->delete('/{id}', 'LeadController@destroy');
            $router->put('/status/{id}', 'LeadController@toggleStatus');
            $router->post('/assign', 'LeadController@assignLeads');
            $router->post('/comments', 'LeadController@comments');
            $router->get('/comments/{id}', 'LeadController@getLeadComments');
        });
    });
});

$router->post('/run-cmd', ['as' => 'cmd', function (Request $request) {
    if (!$request->filled("cmd")) {
        throw RequestValidationException::errorMessage("cmd parameter is required", 422);
    }
    try {
        $command = $request->get("cmd");
        Artisan::call($command);
        dd(Artisan::output());
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
}]);
