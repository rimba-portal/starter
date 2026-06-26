<?php

declare(strict_types=1);

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/', function (): Factory|View {
    return view('welcome');
});

Route::get('/pic/{filename}', function ($filename) {

    $url =
        "http://10.40.3.41:8080/{$filename}.jpg";

    $response =
        Http::get($url);

    if (! $response->ok()) {
        abort(404);
    }

    return response(
        $response->body()
    )->header(
        'Content-Type',
        'image/jpeg'
    );

})->name('staff.pic');