<x-app-layout>
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">AJUKAN MUTASI</h1>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Pratinjau Usulan Mutasi PNS</h5>
                        <h6 class="card-subtitle text-muted">Periksa kembali data Anda sebelum disubmit secara permanen.</h6>
                    </div>
                    
                    <div class="card-body">
                        @if(!$isEligible)
                            <div class="alert alert-danger">
                                <h4 class="alert-heading">Usulan belum memenuhi syarat!</h4>
                                <p>Silakan lengkapi data berikut sebelum melakukan submit:</p>
                                <ul>
                                    @foreach($errors as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <a href="{{ route('pns.usulan.edit', $usulan->id_usulan) }}" class="btn btn-warning mt-2"><i class="fa fa-edit me-1"></i> Kembali ke Mode Edit</a>
                            </div>
                        @else
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle me-2"></i> Usulan sudah memenuhi syarat dan siap untuk disubmit.
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <table class="table table-bordered bg-light">
                                    <thead class="table-primary">
                                        <tr><th colspan="2">Data Surat Usulan</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td width="35%"><strong>Nomor Surat</strong></td><td>{{ $usulan->no_surat }}</td></tr>
                                        <tr><td><strong>Tanggal Surat</strong></td><td>{{ $usulan->tanggal_surat ? $usulan->tanggal_surat->format('d/m/Y') : '' }}</td></tr>
                                        <tr><td><strong>Perihal</strong></td><td>{{ $usulan->perihal }}</td></tr>
                                        <tr><td><strong>No. WhatsApp</strong></td><td>{{ $usulan->no_whatsapp }}</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            @php
                                $detail = $usulan->details->first();
                            @endphp
                            
                            <div class="col-md-6 mb-3">
                                <table class="table table-bordered bg-light">
                                    <thead class="table-info">
                                        <tr><th colspan="2">Data PNS</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td width="35%"><strong>NIP</strong></td><td>{{ $detail->nip ?? '-' }}</td></tr>
                                        <tr><td><strong>Nama Lengkap</strong></td><td>{{ $detail->nama ?? '-' }}</td></tr>
                                        <tr><td><strong>Tempat, Tgl Lahir</strong></td><td>{{ $detail->tempat_lahir ?? '-' }}, {{ $detail->tanggal_lahir ?? '-' }}</td></tr>
                                        <tr><td><strong>Pangkat/Gol.</strong></td><td>{{ $detail->pangkat_akhir ?? '-' }} / {{ $detail->tmt_gol_akhir ?? '-' }}</td></tr>
                                        <tr><td><strong>Pendidikan Terakhir</strong></td><td>{{ $detail->pendidikan_terakhir_nama ?? '-' }}</td></tr>
                                        <tr><td><strong>Jabatan</strong></td><td>{{ $detail->jabatan_nama ?? $detail->jabatan ?? '-' }}</td></tr>
                                        <tr><td><strong>Unit Kerja Induk</strong></td><td>{{ $detail->unor_induk_nama ?? '-' }}</td></tr>
                                        <tr><td><strong>Lokasi Awal</strong></td><td>{{ $detail->lokasi_awal ?? '-' }}</td></tr>
                                        <tr><td><strong>Lokasi Tujuan</strong></td><td>{{ $detail->nama_unor_tujuan ?? '-' }}</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-12 mt-3">
                                <h5>Dokumen Persyaratan</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Dokumen Syarat</th>
                                            <th>Status Berkas</th>
                                            <th>Preview File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dokumenSyarat as $dokumen)
                                            @php
                                                $berkas = $detail ? $detail->berkas->where('id_dokumen', $dokumen->id_dokumen)->first() : null;
                                            @endphp
                                            <tr>
                                                <td>{{ $dokumen->nama_dokumen }}</td>
                                                <td>
                                                    @if($berkas)
                                                        <span class="badge bg-success">Telah Diunggah</span>
                                                    @else
                                                        <span class="badge bg-danger">Belum Diunggah</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($berkas)
                                                        <a href="{{ Storage::url($berkas->path_dokumen) }}" class="btn btn-sm btn-primary" target="_blank"><i class="fa fa-eye me-1"></i> Tinjau File</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4 border-top pt-3">
                            <a href="{{ route('pns.usulan.edit', $usulan->id_usulan) }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Kembali Edit Draft</a>
                            
                            @if($isEligible)
                                <form action="{{ route('pns.usulan.submit', $usulan->id_usulan) }}" method="POST" id="formRealSubmit">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg" id="btn-final-submit"><i class="fa fa-paper-plane me-2"></i> Kirim Usulan ke BKPSDM</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Custom Style & Script for Wizard -->
    <style>
        .nav-pills .nav-link {
            color: #6c757d;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .nav-pills .nav-link.active {
            color: #fff;
            background: #3b7ddd !important;
            border-color: #3b7ddd;
        }
        .nav-pills .nav-link.active .badge.bg-primary {
            background-color: #fff !important;
            color: #3b7ddd !important;
        }
        .nav-pills .nav-link.disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Handle Form Submit with Loading
            const formRealSubmit = document.getElementById('formRealSubmit');
            if (formRealSubmit) {
                formRealSubmit.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i> Mengirim...';
                    
                    Swal.fire({
                        title: 'Memproses Pengiriman...',
                        html: 'Usulan Anda sedang dikirim ke BKPSDM.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                });
            }
        });
    </script>
</x-app-layout>
