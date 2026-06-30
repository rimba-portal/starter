<?php

namespace Bites\Identity\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSetupIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) return $next($request);

        $user = auth()->user();
        if (!$user->isFullySetup()) {
            $allowed = ['admin/login', 'admin/logout', 'admin/profile', 'admin/profile/mfa'];
            foreach ($allowed as $path) {
                if ($request->is($path) || $request->is("$path/*")) return $next($request);
            }
            return redirect()->route('filament.admin.pages.profile')
                ->with('warning', 'Complete Face and TOTP setup first.');
        }
        return $next($request);
    }
}