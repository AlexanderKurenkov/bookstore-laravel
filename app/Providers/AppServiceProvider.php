<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void {}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		// Ensure session is started.
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		// Initializes an empty 'cart' value in the session if it doesn't exist.
		if (!Session::has('cart')) {
			Session::put('cart', []);
		}
	}
}
