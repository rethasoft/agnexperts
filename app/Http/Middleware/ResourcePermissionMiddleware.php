<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Exceptions\UnauthorizedException;

class ResourcePermissionMiddleware
{
    public function handle($request, Closure $next)
    {
        $action = Route::currentRouteAction();
        if ($action != null) {
            list($controller, $method) = explode('@', $action);
            $controller = strtolower(class_basename($controller));

            if (preg_match('/(\w+)controller$/', $controller, $matches)) {
                $resource = strtolower($matches[1]);

                $permission = $this->mapMethodToPermission($method, $resource);

                // Use the custom guard 'employe'
                /** @var \App\Models\Employe $user */
                $user = Auth::guard('employee')->user();

                if (!$user || !$user->can($permission)) {
                    return redirect('/employe')->withErrors(['msg' => 'U beschikt niet over de juiste machtigingen.']);
                }
            }
        }
        return $next($request);
    }

    protected function mapMethodToPermission($method, $resource)
    {
        switch ($method) {
            case 'index':
            case 'show':
                return "read_{$resource}";
            case 'create':
            case 'store':
                return "create_{$resource}";
            case 'edit':
            case 'update':
                return "edit_{$resource}";
            case 'destroy':
                return "delete_{$resource}";
            default:
                return "access_{$resource}";
        }
    }
}
