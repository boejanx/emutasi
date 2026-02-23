<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usulan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $data = [
            'user' => $user,
        ];

        // Statistik untuk Admin, Kabid, dan Pimpinan (Role 0, 1, 4)
        if (in_array($user->role, [User::ROLE_PIMPINAN, User::ROLE_ADMIN, User::ROLE_KABID])) {
            $data['stats'] = [
                'total'         => Usulan::count(),
                'menunggu_pimpinan' => Usulan::where('disposisi', 0)->where('status', 1)->count(),
                'menunggu_kabid'    => Usulan::where('disposisi', 1)->count(),
                'menunggu_staf'     => Usulan::where('disposisi', 2)->where('status', 3)->count(),
                'menunggu_sk'       => Usulan::where('status', 4)->count(),
                'selesai'           => Usulan::where('status', 5)->count(),
                'ditolak'           => Usulan::where('status', 99)->count(),
            ];
            
            // Ambil 5 usulan terbaru untuk ditampilkan di dashboard
            $data['latest_usulans'] = Usulan::with('user')->latest()->take(5)->get();

            // Setup Default Chart Data (12 Bulan)
            $monthly_data = array_fill(0, 12, 0);
            $chart_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

            // Query Data Bulanan Tahun Ini
            $currentYear = date('Y');
            $monthlyStats = \Illuminate\Support\Facades\DB::table('tb_usulan')
                ->select(\Illuminate\Support\Facades\DB::raw('MONTH(created_at) as month'), \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->whereYear('created_at', $currentYear)
                ->groupBy(\Illuminate\Support\Facades\DB::raw('MONTH(created_at)'))
                ->get();

            // Isi array monthly_data dengan hasil query (month index dari 1..12 jadi dikurang 1)
            foreach ($monthlyStats as $stat) {
                $monthly_data[$stat->month - 1] = $stat->total;
            }

            $data['chart_labels'] = $chart_labels;
            $data['monthly_data'] = $monthly_data;
        } 
        // Statistik untuk PNS (Role 3)
        elseif ($user->role == User::ROLE_USER) {
            $data['my_usulan'] = Usulan::where('id_user', $user->id)
                ->with(['details', 'logs'])
                ->latest()
                ->first();
            $data['total_pengajuan'] = Usulan::where('id_user', $user->id)->count();
            $data['dokumenSyarat']  = \App\Models\RefDokumen::where('status', 1)->get();
        } 
        // Khusus Admin Instansi (Role 2) langsung arahkan ke dashboard OPD
        elseif ($user->role == User::ROLE_ADMIN_INSTANSI) {
            return redirect()->route('opd.index');
        }

        return view('dashboard', $data);
    }
}
