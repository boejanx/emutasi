<x-app-layout>
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">AJUKAN MUTASI</h1>

        @if($hasActiveUsulan)
            <div class="alert alert-warning fade show p-4 shadow-sm" role="alert" style="border-left: 5px solid #ffc107;">
                <div class="d-flex flex-column flex-md-row align-items-center text-center text-md-start">
                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mb-3 mb-md-0 me-md-4 shadow-sm" style="width: 60px; height: 60px;">
                        <i class="fa fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="alert-heading fw-bold mb-1 text-dark">Pengajuan Sedang Diproses</h4>
                        <p class="mb-0 text-dark">Anda saat ini memiliki usulan mutasi yang sedang dalam tahap verifikasi. Anda tidak dapat mengajukan usulan baru hingga proses ini selesai.</p>
                    </div>
                    <div class="mt-3 mt-md-0 ms-md-4 flex-shrink-0">
                        <a href="{{ route('pns.tracking.detail', $lastUsulan->id_usulan) }}" class="btn btn-warning shadow-sm border border-warning fw-bold px-4 py-2" style="border-radius: 50px; background-color: #f7b924; color: #fff;">
                            <i class="fa fa-calendar-check me-2"></i> Lihat Detail Progres
                        </a>
                    </div>
                </div>
            </div>
        @elseif($lastUsulan)
            @if($lastUsulan->status == 4 || $lastUsulan->status == 5)
                <div class="alert alert-success fade show p-4 shadow-sm" role="alert" style="border-left: 5px solid #28a745;">
                    <div class="d-flex flex-column flex-md-row align-items-center text-center text-md-start">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mb-3 mb-md-0 me-md-4 shadow-sm" style="width: 60px; height: 60px;">
                            <i class="fa fa-check-circle fa-2x"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="alert-heading fw-bold mb-1">Usulan Terakhir: Selesai / SK Terbit</h4>
                            <p class="mb-0">Pengajuan mutasi Anda sebelumnya telah selesai diproses. Anda diperbolehkan untuk mengajukan usulan baru jika diperlukan.</p>
                        </div>
                    </div>
                </div>
            @elseif($lastUsulan->status == 99)
                <div class="alert alert-danger fade show p-4 shadow-sm" role="alert" style="border-left: 5px solid #dc3545;">
                    <div class="d-flex flex-column flex-md-row align-items-center text-center text-md-start">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mb-3 mb-md-0 me-md-4 shadow-sm" style="width: 60px; height: 60px;">
                            <i class="fa fa-times-circle fa-2x"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="alert-heading fw-bold mb-1">Usulan Terakhir: Ditolak</h4>
                            <p class="mb-0">Pengajuan mutasi Anda sebelumnya ditolak. Anda dapat memperbaiki berkas dan mengajukan ulang usulan baru melalui form di bawah ini.</p>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Persyaratan Mutasi PNS</h5>
                        <h6 class="card-subtitle text-muted">Pastikan Anda telah menyiapkan dokumen-dokumen berikut sebelum melanjutkan pengajuan mutasi.</h6>
                    </div>
                    
                    <div class="card-body pt-0">
                        <table class="table table-striped table-hover mb-4">
                            <tbody>
                                @foreach($dokumenSyarat as $index => $dokumen)
                                <tr>
                                    <td style="width: 5%"><strong>{{ $index + 1 }}.</strong></td>
                                    <td>{{ $dokumen->nama_dokumen }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-end">
                            @if($hasActiveUsulan)
                                <button class="btn btn-secondary d-flex align-items-center px-4 py-2" disabled style="cursor: not-allowed; opacity: 0.7;">
                                    <i class="align-middle fa fa-lock me-2"></i> Pengajuan Sedang Aktif
                                </button>
                            @else
                                <a href="{{ route('pns.usulan.create') }}" class="btn btn-primary d-flex align-items-center px-4 py-2 shadow-sm">
                                    <i class="align-middle fa fa-paper-plane me-2"></i> Ajukan Mutasi Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ session('error') }}',
                });
            @endif
        });
    </script>
</x-app-layout>
