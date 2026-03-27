<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()];
});

Route::get('/test-db', function () {

    if(DB::connection()->getPdo()) return "Databse success";
     
});


