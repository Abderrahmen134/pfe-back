<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Client;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken(); // récupère le token depuis l'en-tête Authorization: Bearer {token}

        if (!$token || ! $client = Client::where('api_token', $token)->first()) {
            return response()->json(['message' => 'Non autorisé'], 401);
        }

        // Tu peux attacher le client à la requête si besoin
        $request->merge(['auth_client' => $client]);

        return $next($request);
    }
}
