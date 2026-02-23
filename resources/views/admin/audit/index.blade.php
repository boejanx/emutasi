<x-app-layout>
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">AUDIT TRAIL / LOG AKTIVITAS</h1>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header border-bottom bg-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0 fw-bold">Riwayat Log Keamanan Sistem</h5>
                            <h6 class="card-subtitle text-muted mt-2">Daftar rekaman aktivitas pengguna secara keseluruhan di dalam sistem.</h6>
                        </div>
                        <span class="badge bg-danger shadow-sm px-3 py-2"><i class="fa fa-user-secret me-1"></i> Data Sensitif / Rahasia</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle shadow-sm table-bordered" style="font-size: 0.85rem;" id="auditTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 15%">Waktu Aktivitas</th>
                                        <th style="width: 25%">Username / Peran</th>
                                        <th style="width: 15%">Aksi Modul</th>
                                        <th style="width: 30%">Deskripsi Detail</th>
                                        <th style="width: 15%">IP Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                    <tr>
                                        <td class="text-nowrap">
                                            <strong>{{ $log->created_at->format('d/m/Y') }}</strong><br>
                                            <span class="text-muted">{{ $log->created_at->format('H:i:s') }}</span>
                                        </td>
                                        <td>
                                            @if($log->user)
                                                <div class="fw-bold text-primary">{{ $log->user->name }}</div>
                                                <span class="text-muted small">
                                                    @if($log->user->role == 0) Kepala BKPSDM
                                                    @elseif($log->user->role == 1) Super Admin / Verifikator
                                                    @elseif($log->user->role == 2) Admin OPD
                                                    @elseif($log->user->role == 3) PNS / Pegawai
                                                    @elseif($log->user->role == 4) Kepala Bidang Mutasi
                                                    @endif
                                                </span>
                                            @else
                                                <span class="fst-italic text-secondary">Tamu / Sistem</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->action == 'LOGIN')
                                                <span class="badge bg-success w-100 py-2"><i class="fa fa-sign-in-alt me-1"></i> LOGIN</span>
                                            @elseif($log->action == 'LOGOUT')
                                                <span class="badge bg-secondary w-100 py-2"><i class="fa fa-sign-out-alt me-1"></i> LOGOUT</span>
                                            @elseif(str_contains($log->action, 'DELETE'))
                                                <span class="badge bg-danger w-100 py-2"><i class="fa fa-trash me-1"></i> {{ $log->action }}</span>
                                            @else
                                                <span class="badge bg-info w-100 py-2"><i class="fa fa-cog me-1"></i> {{ $log->action }}</span>
                                            @endif
                                        </td>
                                        <td class="text-wrap">
                                            {{ $log->description }}
                                        </td>
                                        <td class="text-center font-monospace small bg-light">
                                            {{ $log->ip_address ?? '-' }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat aktivitas sistem.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
