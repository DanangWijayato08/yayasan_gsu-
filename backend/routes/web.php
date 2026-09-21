<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'Gerakan Sedekah Umat API',
        'status' => 'ok',
    ]);
});
