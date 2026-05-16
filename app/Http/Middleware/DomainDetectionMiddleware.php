<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainDetectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        
        // Skip for main domain
        $mainDomain = parse_url(config('app.url'), PHP_URL_HOST);
        
        if ($host !== $mainDomain && $host !== 'localhost' && $host !== '127.0.0.1') {
            $company = DB::table('company')
                ->where('CustomDomain', $host)
                ->first();
                
            if ($company) {
                // Store detected ROID in request attributes for easy access
                $request->attributes->set('detected_roid', $company->KodePartner);
                
                // If it's a custom domain, we might want to override some routes 
                // but for now we just make the ROID available.
            }
        }

        return $next($request);
    }
}
