<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
            if ($student->is_final_submitted) {
                $studCode = $student->studentCode->first();
                if ($studCode && $studCode->is_paid == 1) {
                    return redirect()->route('student.payment');
                }
                return redirect()->route('student.payment');
            }
            return $next($request);
        } elseif (!Auth::guard('student')->check())
            return redirect('/');
    }
}
