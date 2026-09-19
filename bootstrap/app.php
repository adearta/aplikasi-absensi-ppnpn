<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware):void {
    // Daftarkan alias middleware di sini
    $middleware->redirectTo(
        guests: '/loginauth'
        );
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class, // Sesuaikan dengan nama class middleware Anda
    ]);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
        
        $exceptions->render(function(TokenMismatchException $e, $request){
            return redirect()->route('loginauth')->with('error','sesi berakhir, silahkan login kembali');
        });
    })->create();
