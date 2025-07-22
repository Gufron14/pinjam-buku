<?php

namespace App\Http\Controllers;

use App\Models\LoanHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Display the print report page
     */
    public function cetakLaporan(Request $request)
    {
        $type = $request->get('type', 'peminjaman');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $status = $request->get('status', '');
        
        // Validate parameters
        if (!$startDate || !$endDate) {
            return redirect()->back()->with('error', 'Tanggal mulai dan tanggal akhir harus diisi');
        }
        
        // Validate type
        if (!in_array($type, ['peminjaman', 'pengembalian', 'denda'])) {
            return redirect()->back()->with('error', 'Jenis laporan tidak valid');
        }
        
        // Get data based on type
        $data = $this->getReportData($type, $startDate, $endDate, $status);
        
        return view('livewire.admin.cetak-laporan', compact(
            'type', 
            'startDate', 
            'endDate', 
            'status', 
            'data'
        ));
    }
    
    /**
     * Get report data based on type and filters
     */
    private function getReportData($type, $startDate, $endDate, $status = '')
    {
        $query = LoanHistory::with(['user', 'book']);
        
        switch ($type) {
            case 'peminjaman':
                $query->when($status, function($q) use ($status) {
                    $q->where('status', $status);
                })
                ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
                ->orderBy('tanggal_pinjam', 'desc');
                break;
                
            case 'pengembalian':
                $query->where('status', 'dikembalikan')
                ->whereBetween('tanggal_kembali', [$startDate, $endDate])
                ->orderBy('tanggal_kembali', 'desc');
                break;
                
            case 'denda':
                $query->where(function($q) {
                    $q->where('status', 'terlambat')
                      ->orWhere('denda', '>', 0);
                })
                ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
                ->orderBy('denda', 'desc');
                break;
        }
        
        return $query->get();
    }
    
    /**
     * Get statistics for the report
     */
    private function getStatistics($type, $startDate, $endDate, $status = '')
    {
        $baseQuery = LoanHistory::whereBetween('tanggal_pinjam', [$startDate, $endDate]);
        
        $stats = [
            'total_records' => $baseQuery->count(),
            'total_peminjaman' => $baseQuery->count(),
            'total_pengembalian' => $baseQuery->where('status', 'dikembalikan')->count(),
            'total_terlambat' => $baseQuery->where('status', 'terlambat')->count(),
            'total_denda' => $baseQuery->sum('denda'),
            'denda_terbayar' => $baseQuery->where('denda_dibayar', true)->sum('denda'),
        ];
        
        return $stats;
    }
}
