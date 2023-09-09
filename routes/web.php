<?php

declare(strict_types=1);

Route::group([
    'middleware' => ['web'],
    'namespace' => 'Diviky\Security\Http\Controllers',
], function (): void {
    Route::post('2fa', 'Security\Controller@verify2fa')->name('2fa')->middleware('2fa');
    Route::post('2fa/remember', 'Security\Controller@verify2fa')->middleware('2fa.remember');
});

Route::group([
    'middleware' => ['web', 'auth'],
    'namespace' => 'Diviky\Security\Http\Controllers',
    'prefix' => 'security',
], function (): void {
    Route::any('/', 'Security\Controller@index');
    Route::any('2fa', 'Security\Controller@twofa');
    Route::any('logins', 'Security\Controller@logins');
    Route::any('sessions', 'Security\Controller@sessions');
    Route::get('sessions/delete/{id?}', 'Security\Controller@delete');
});
