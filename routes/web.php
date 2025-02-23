<?php

declare(strict_types=1);

Route::group([
    'middleware' => ['web'],
    'namespace' => 'Diviky\Security\Http\Controllers',
], function (): void {
    Route::post('2fa', 'Security\Controller@verify2fa')->name('2fa')->middleware('2fa');
    Route::post('2fa/remember', 'Security\Controller@verify2fa')->name('security.2fa.remember')->middleware('2fa.remember');
});

Route::group([
    'middleware' => ['web', 'auth'],
    'namespace' => 'Diviky\Security\Http\Controllers',
    'prefix' => 'security',
    'as' => 'security.',
], function (): void {
    Route::any('/', 'Security\Controller@index');
    Route::any('2fa', 'Security\Controller@twofa')->name('2fa');
    Route::any('logins', 'Security\Controller@logins')->name('logins');
    Route::any('sessions', 'Security\Controller@sessions')->name('sessions');
    Route::delete('sessions/delete/{id?}', 'Security\Controller@delete')->name('delete');
});
