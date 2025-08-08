<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportMemberController extends Controller
{
    public function exportToExcel()
    {
        $members = Member::with(['user', 'activeMembership.membershipType'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'socios_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
            'Content-Encoding' => 'UTF-8',
            'Content-Transfer-Encoding' => 'binary',
        ];

        $columns = [
            'ID', 'Nombre Completo', 'DNI', 'Email', 'Teléfono', 
            'Dirección', 'Membresía', 'Estado', 'Fecha de Registro'
        ];

        $callback = function() use ($members, $columns) {
            // Añadir BOM para compatibilidad con Excel
            echo "\xEF\xBB\xBF";
            
            $file = fopen('php://output', 'w');
            
            // Escribir encabezados
            fputcsv($file, $columns, ';');

            foreach ($members as $member) {
                $row = [
                    $member->id,
                    $this->sanitizeForCSV($member->full_name),
                    $this->sanitizeForCSV($member->dni),
                    $this->sanitizeForCSV($member->email),
                    $this->sanitizeForCSV($member->phone),
                    $this->sanitizeForCSV($member->address),
                    $this->sanitizeForCSV(optional($member->activeMembership->first()->membershipType ?? null)->name ?? 'Sin membresía'),
                    $member->status ? 'Activo' : 'Inactivo',
                    $member->created_at->format('d/m/Y H:i')
                ];

                fputcsv($file, $row, ';');
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
    
    /**
     * Sanitiza los valores para CSV
     * 
     * @param string|null $value
     * @return string
     */
    private function sanitizeForCSV($value)
    {
        if (is_null($value)) {
            return '';
        }
        
        // Reemplazar saltos de línea y retornos de carro
        $value = str_replace(["\r\n", "\r", "\n"], ' ', $value);
        
        // Reemplazar punto y coma para evitar problemas con el delimitador
        $value = str_replace(';', ',', $value);
        
        return $value;
    }
}
