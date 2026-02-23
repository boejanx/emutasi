<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanExport implements FromView, ShouldAutoSize
{
    protected $rekap;
    protected $usulans;
    protected $bulan;
    protected $tahun;

    public function __construct(array $rekap, $usulans, $bulan, $tahun)
    {
        $this->rekap = $rekap;
        $this->usulans = $usulans;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        return view('admin.laporan.excel', [
            'rekap' => $this->rekap,
            'usulans' => $this->usulans,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);
    }
}
