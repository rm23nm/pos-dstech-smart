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
        $mainDomain = parse_url(config('app.url'), PHP_URL_HOST);
        
        if ($host !== $mainDomain && $host !== 'localhost' && $host !== '127.0.0.1') {
            $company = DB::table('company')
                ->where('CustomDomain', $host)
                ->orWhere('CustomDomainBooking', $host)
                ->orWhere('CustomDomainQueue', $host)
                ->orWhere('CustomDomainKDS', $host)
                ->first();
                
            if ($company) {
                $request->attributes->set('detected_roid', $company->KodePartner);
                
                // Determine the context type
                if ($host === $company->CustomDomain) {
                    $request->attributes->set('domain_context', 'STORE');
                } elseif ($host === $company->CustomDomainBooking) {
                    $request->attributes->set('domain_context', 'BOOKING');
                } elseif ($host === $company->CustomDomainQueue) {
                    $request->attributes->set('domain_context', 'QUEUE');
                } elseif ($host === $company->CustomDomainKDS) {
                    $request->attributes->set('domain_context', 'KDS');
                }
            }
        }

        return $next($request);
    }
}
