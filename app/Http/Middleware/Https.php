<?php

namespace App\Http\Middleware;

use Closure;

class Https
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guardHttps
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!$request->secure()) {
            if ($request->wantsJson()) {
                return error('Si prega di aggiornare la pagina');
            } else {
                return redirect()->to($request->getRequestUri(), 301,
                        $request->header(), true);
            }
        }

        return $next($request);
    }
}
