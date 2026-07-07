<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the Accept-Language header (e.g., "fr-FR,fr;q=0.9,en;q=0.8")
        $acceptLanguage = $request->header('Accept-Language', 'en');
        
        // Extract the first locale code (2 characters)
        $isoCode = strtolower(substr(trim($acceptLanguage), 0, 2));
        
        $langMap = [
            'en' => 1,
            'fr' => 3,
        ];
        
        $idLang = $langMap[$isoCode] ?? 1; 
        
        // Save to config so it can be dynamically queried by Eloquent models
        config(['app.prestashop_lang' => $idLang]);
        
        return $next($request);
    }
}
