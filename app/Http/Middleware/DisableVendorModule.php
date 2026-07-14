<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DisableVendorModule
{
    public function handle($request, Closure $next)
    {
        if (config('features.vendor_module_enabled', false)) {
            return $next($request);
        }

        $route = $request->route();
        $routeName = $route ? (string) $route->getName() : '';
        $routeAction = $route ? (string) $route->getActionName() : '';
        $path = trim($request->path(), '/');

        if ($this->isVendorRoute($routeName, $routeAction, $path)) {
            abort(404);
        }

        if (Auth::check() && $this->isVendorUser(Auth::user()) && $this->isProtectedSurface($routeName, $path)) {
            abort(403, 'Vendor module is disabled.');
        }

        return $next($request);
    }

    private function isVendorRoute(string $routeName, string $routeAction, string $path): bool
    {
        return Str::startsWith($routeName, 'vendor.')
            || Str::contains($routeName, '.vendors.')
            || Str::contains(strtolower($routeAction), ['\\vendor\\', 'auth\\vendor'])
            || Str::startsWith($path, 'vendor')
            || in_array($path, ['pincodes', 'pincodes/store', 'pincodes/add-region', 'pincodes/add-multiple'], true)
            || Str::startsWith($path, 'pincodes/');
    }

    private function isVendorUser($user): bool
    {
        $accountType = strtolower((string) ($user->account_type ?? ''));
        $userType = strtolower((string) ($user->user_type ?? ''));

        return $accountType === 'vendor'
            || $userType === 'vendor'
            || (method_exists($user, 'hasRole') && $user->hasRole('vendor'));
    }

    private function isProtectedSurface(string $routeName, string $path): bool
    {
        return Str::startsWith($path, 'admin')
            || Str::startsWith($path, 'media-manager')
            || Str::startsWith($routeName, 'admin.')
            || Str::startsWith($routeName, 'uppy.');
    }
}
