<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Makam;
use App\Models\BlokMakam;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMakam = Makam::count();
        $totalBlok = BlokMakam::count();
        $makamBulanIni = Makam::whereMonth('tanggal_wafat', Carbon::now()->month)
                              ->whereYear('tanggal_wafat', Carbon::now()->year)
                              ->count();
        
        $makamTerbaru = Makam::with('blok')
                             ->orderBy('created_at', 'desc')
                             ->take(5)
                             ->get();
        
        $bloks = BlokMakam::withCount('makam')->get();

        return view('admin.dashboard', compact('totalMakam', 'totalBlok', 'makamBulanIni', 'makamTerbaru', 'bloks'));
    }
}
