<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Si l'utilisateur n'est pas connecté ou si son rôle n'est pas dans la liste des rôles autorisés
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. You need proper privileges.'], 403);
            }
            
            // Redirection différente selon le rôle
            if ($request->user() && $request->user()->role === 'client') {
                return redirect()->route('dashboard')
                    ->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
            }
            
            return redirect()->route('home')
                ->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
        }

        return $next($request);
    }
} 