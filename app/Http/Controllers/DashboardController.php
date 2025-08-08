<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Membership;
use App\Models\CheckIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener estadísticas reales
        $stats = $this->getStats();
        
        return view('dashboard', compact('stats'));
    }
    
    private function getStats()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        
        // Socios activos
        $activeMembers = Member::where('status', true)->count();
        
        // Ingresos mensuales (simulado por ahora)
        $monthlyRevenue = Membership::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('price') ?? 0;
        
        // Clases hoy (simulado)
        $classesToday = 0; // Esto se implementará cuando tengamos el modelo de clases
        
        // Asistencia promedio
        $attendanceRate = 0; // Esto se calculará cuando tengamos más datos
        
        // Actividad reciente
        $recentActivity = $this->getRecentActivity();
        
        return [
            'activeMembers' => $activeMembers,
            'monthlyRevenue' => $monthlyRevenue,
            'classesToday' => $classesToday,
            'attendanceRate' => $attendanceRate,
            'recentActivity' => $recentActivity,
        ];
    }
    
    private function getRecentActivity()
    {
        $activities = [];
        
        // Últimos miembros registrados
        $recentMembers = Member::latest()->take(3)->get();
        foreach ($recentMembers as $member) {
            $activities[] = [
                'type' => 'member_registered',
                'title' => 'Nuevo socio registrado',
                'description' => $member->full_name,
                'time' => $member->created_at->diffForHumans(),
                'icon' => 'bi-person-plus',
                'color' => 'bg-primary'
            ];
        }
        
        // Últimos check-ins
        $recentCheckIns = CheckIn::with('member')->latest()->take(3)->get();
        foreach ($recentCheckIns as $checkIn) {
            $activities[] = [
                'type' => 'check_in',
                'title' => 'Check-in registrado',
                'description' => $checkIn->member->full_name,
                'time' => $checkIn->created_at->diffForHumans(),
                'icon' => 'bi-people',
                'color' => 'bg-info'
            ];
        }
        
        // Ordenar por fecha más reciente
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        return array_slice($activities, 0, 4);
    }
}

