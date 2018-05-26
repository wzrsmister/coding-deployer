<?php

namespace app\http\middleware;

class Logger
{
    public function handle($request, \Closure $next)
    {
        $response = $next($request);
        return $response;
    }
}
