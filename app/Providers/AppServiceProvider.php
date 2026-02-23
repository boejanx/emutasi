<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $notifications = collect();
            $notifCount = 0;
            $headerPesans = collect();
            $pesanCount = 0;

            if (auth()->check()) {
                $user = auth()->user();

                // Notifikasi Logika Sebelumnya
                // Role 0: Pimpinan (Menunggu Disposisi)
                if ($user->role == 0) {
                    $notifications = \App\Models\Usulan::where('disposisi', 0)->where('status', 1)->latest()->take(5)->get();
                    $notifCount = \App\Models\Usulan::where('disposisi', 0)->where('status', 1)->count();
                } 
                // Role 4: Kabid (Menunggu Disposisi)
                elseif ($user->role == 4) {
                    $notifications = \App\Models\Usulan::where('disposisi', 1)->latest()->take(5)->get();
                    $notifCount = \App\Models\Usulan::where('disposisi', 1)->count();
                } 
                // Role 1: Admin BKPSDM (Menunggu Verifikasi)
                elseif ($user->role == 1) {
                    $notifications = \App\Models\Usulan::where('disposisi', 2)->where('status', 3)->latest()->take(5)->get();
                    $notifCount = \App\Models\Usulan::where('disposisi', 2)->where('status', 3)->count();
                    
                    // Pesan untuk Admin (Dari PNS/OPD)
                    // Ambil 5 pesan terbaru dari usulan yang sedang aktif dan bukan dikirim oleh user login
                    $headerPesans = \App\Models\UsulanPesan::with('user', 'usulan')->whereHas('usulan', function($q){
                        $q->whereIn('status', [3, 99]);
                    })->where('id_user', '!=', $user->id)->latest()->take(5)->get();
                    $pesanCount = $headerPesans->count();

                } 
                // Role 2 & 3: Admin OPD & PNS (Pemberitahuan Status Terbaru)
                elseif (in_array($user->role, [2, 3])) {
                    $notifications = \App\Models\UsulanLog::whereHas('usulan', function($query) use ($user) {
                        $query->where('id_user', $user->id);
                    })->where('id_user', '!=', $user->id)->latest()->take(5)->get();
                    
                    // Kita asumsikan 5 log terakhir adalah notifikasi terbarunya
                    $notifCount = $notifications->count(); 

                    // Pesan untuk PNS/OPD (Dari Admin Verifikator)
                    $headerPesans = \App\Models\UsulanPesan::with('user', 'usulan')->whereHas('usulan', function($q) use ($user){
                        $q->where('id_user', $user->id);
                    })->where('id_user', '!=', $user->id)->latest()->take(5)->get();
                    $pesanCount = $headerPesans->count();
                }
            }

            $view->with('notifications', $notifications)
                 ->with('notifCount', $notifCount)
                 ->with('headerPesans', $headerPesans)
                 ->with('pesanCount', $pesanCount);
        });
    }
}
