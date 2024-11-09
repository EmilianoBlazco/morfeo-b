<?php

namespace App\Http\Middleware;

use App\Models\Attendance;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class EnsureHasEntry
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->input('user_id');

        // Verificar si existe una entrada para hoy
        $existingAttendance = Attendance::where('user_id', $userId)
            ->whereDate('entry_time', Carbon::today())
            ->first();

        if ($existingAttendance) {
            // Añadir información para que el controlador sepa que debe registrar la salida
            $request->attributes->set('action', 'exit');
        } else {
            // Añadir información para que el controlador sepa que debe registrar la entrada
            $request->attributes->set('action', 'entry');
        }

        return $next($request);
    }
}
