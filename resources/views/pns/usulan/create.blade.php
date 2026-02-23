<x-app-layout>
    <div class="container-fluid p-0">

        <h1 class="h3 mb-3">AJUKAN MUTASI</h1>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Formulir Pengajuan Usulan Mutasi PNS</h5>
                        <h6 class="card-subtitle text-muted">Lengkapi data di bawah ini secara bertahap.</h6>
                    </div>
                    
                    <div class="card-body">
                        <!-- Navigation Tabs -->
                        <ul class="nav nav-pills mb-3 mt-3 justify-content-center" id="mutasi-steps" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="step-surat-tab" data-bs-toggle="pill" data-bs-target="#step-surat" type="button" role="tab" aria-controls="step-surat" aria-selected="true" style="border-radius: 20rem; margin-right: 15px;">
                                    <span class="badge bg-primary rounded-circle me-1">1</span> Surat Usulan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link disabled" id="step-pns-tab" data-bs-toggle="pill" data-bs-target="#step-pns" type="button" role="tab" aria-controls="step-pns" aria-selected="false" style="border-radius: 20rem; margin-right: 15px;">
                                    <span class="badge bg-secondary rounded-circle me-1">2</span> Data PNS
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link disabled" id="step-dokumen-tab" data-bs-toggle="pill" data-bs-target="#step-dokumen" type="button" role="tab" aria-controls="step-dokumen" aria-selected="false" style="border-radius: 20rem; margin-right: 15px;">
                                    <span class="badge bg-secondary rounded-circle me-1">3</span> Upload Dokumen
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link disabled" id="step-review-tab" data-bs-toggle="pill" data-bs-target="#step-review" type="button" role="tab" aria-controls="step-review" aria-selected="false" style="border-radius: 20rem;">
                                    <span class="badge bg-secondary rounded-circle me-1">4</span> Review & Submit
                                </button>
                            </li>
                        </ul>

                        <form id="formUsulan" action="{{ route('pns.usulan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content" id="myTabContent">
                                
                                <!-- STEP 1: Surat Usulan -->
                                <div class="tab-pane fade show active p-4 border rounded" id="step-surat" role="tabpanel" aria-labelledby="step-surat-tab">
                                    <h4 class="mb-4 text-primary"><i class="fa fa-file-signature me-2"></i>Informasi Surat Pengantar</h4>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="no_surat" id="no_surat" placeholder="Masukkan Nomor Surat Pengantar" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="tanggal_surat" id="tanggal_surat" placeholder="Pilih Tanggal ..." required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Perihal Surat <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="perihal" id="perihal" rows="3" placeholder="Contoh: Permohonan Pindah Wilayah Kerja an. John Doe" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">No. WhatsApp Pengusul <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="no_whatsapp" id="no_whatsapp" placeholder="Contoh: 081234567890" required>
                                        <small class="text-muted">Nomor ini akan digunakan untuk mengirim notifikasi progres usulan mutasi.</small>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-primary btn-next" data-next="step-pns">Selanjutnya <i class="fa fa-arrow-right ms-2"></i></button>
                                    </div>
                                </div>

                                <!-- STEP 2: Data PNS -->
                                <div class="tab-pane fade p-4 border rounded" id="step-pns" role="tabpanel" aria-labelledby="step-pns-tab">
                                    <h4 class="mb-4 text-primary"><i class="fa fa-user-tie me-2"></i>Data PNS yang Diusulkan</h4>
                                    <div class="alert alert-info">
                                        Untuk sementara silakan isi manual data PNS yang akan di mutasi.
                                    </div>

                                    <!-- Wrap in a wrapper if you want to allow multiple PNS in the future -->
                                    <div class="pns-wrapper border p-3 rounded bg-light mb-3">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">NIP <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control bg-light" name="details[0][nip]" id="nip_pns" placeholder="NIP 18 Digit" value="{{ Auth::user()->email }}" readonly required>
                                                    <button class="btn btn-outline-primary" type="button" id="btn-get-siasn">Get Data <i class="fa fa-search ms-1"></i></button>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control bg-light" name="details[0][nama]" id="nama_pns" placeholder="Nama tanpa gelar" value="{{ Auth::user()->name }}" readonly required>
                                            </div>

                                            <input type="hidden" name="details[0][siasn_id]" id="siasn_id">
                                            <input type="hidden" name="details[0][siasn_nip_baru]" id="siasn_nip_baru">
                                            <input type="hidden" name="details[0][unor_induk_nama]" id="unor_induk_nama">

                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Jabatan Saat Ini <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control bg-light" name="details[0][jabatan]" id="jabatan_pns" placeholder="Contoh: Guru Ahli Pertama" readonly required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Lokasi Kerja Awal (Saat Ini) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="details[0][lokasi_awal]" id="lokasi_awal" placeholder="Contoh: SMPN 1 Kedungwuni" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <select class="form-select select2" id="select_lokasi_tujuan" required>
                                                    <option value="">-- Ketik Nama UNOR Tujuan --</option>
                                                </select>
                                                <input type="hidden" name="details[0][lokasi_tujuan]" id="lokasi_tujuan">
                                                <input type="hidden" name="details[0][unor_id_tujuan]" id="unor_id_tujuan">
                                                <input type="hidden" name="details[0][nama_unor_tujuan]" id="nama_unor_tujuan">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-secondary btn-prev" data-prev="step-surat"><i class="fa fa-arrow-left me-2"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-primary btn-next" data-next="step-dokumen">Selanjutnya <i class="fa fa-arrow-right ms-2"></i></button>
                                    </div>
                                </div>

                                <!-- STEP 3: Dokumen -->
                                <div class="tab-pane fade p-4 border rounded" id="step-dokumen" role="tabpanel" aria-labelledby="step-dokumen-tab">
                                    <h4 class="mb-2 text-primary"><i class="fa fa-file-upload me-2"></i>Unggah Dokumen Persyaratan</h4>
                                    <p class="text-muted mb-4">Pastikan dokumen yang diunggah berformat PDF dan ukuran maksimal 2MB per file.</p>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Nama Dokumen</th>
                                                    <th style="width: 30%">Aksi Upload</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($dokumenSyarat as $dokumen)
                                                <tr>
                                                    <td>{{ $dokumen->nama_dokumen }} <span class="text-danger">*</span></td>
                                                    <td>
                                                        <input class="form-control form-control-sm doc-upload" type="file" data-id="{{ $dokumen->id_dokumen }}" accept="application/pdf">
                                                        <input type="hidden" name="file_dokumen_temp_{{ $dokumen->id_dokumen }}" id="hidden_dokumen_{{ $dokumen->id_dokumen }}" required>
                                                        <small class="upload-status text-primary mt-1 d-block" id="status_dokumen_{{ $dokumen->id_dokumen }}"></small>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="2" class="text-center text-danger">Data master dokumen persyaratan belum dikonfigurasi.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <small class="text-info">* Daftar dokumen digenerate otomatis berdasarkan pengaturan master database.</small>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-secondary btn-prev" data-prev="step-pns"><i class="fa fa-arrow-left me-2"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-primary btn-next" data-next="step-review" id="btn-to-review">Review Usulan <i class="fa fa-arrow-right ms-2"></i></button>
                                    </div>
                                </div>

                                <!-- STEP 4: Review -->
                                <div class="tab-pane fade p-4 border rounded" id="step-review" role="tabpanel" aria-labelledby="step-review-tab">
                                    <h4 class="mb-4 text-primary"><i class="fa fa-check-circle me-2"></i>Konfirmasi & Submit Usulan</h4>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card bg-light shadow-none border">
                                                <div class="card-header pb-0 bg-transparent border-0"><h5 class="mb-0">Data Surat</h5></div>
                                                <div class="card-body">
                                                    <table class="table table-sm table-borderless">
                                                        <tr><td width="35%">No. Surat</td><td width="5%">:</td><td id="rev_no_surat">-</td></tr>
                                                        <tr><td>Tgl. Surat</td><td>:</td><td id="rev_tgl_surat">-</td></tr>
                                                        <tr><td>Perihal</td><td>:</td><td id="rev_perihal">-</td></tr>
                                                        <tr><td>No. WhatsApp</td><td>:</td><td id="rev_no_whatsapp">-</td></tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light shadow-none border">
                                                <div class="card-header pb-0 bg-transparent border-0"><h5 class="mb-0">Data PNS</h5></div>
                                                <div class="card-body">
                                                    <table class="table table-sm table-borderless">
                                                        <tr><td width="35%">NIP</td><td width="5%">:</td><td id="rev_nip">-</td></tr>
                                                        <tr><td>Nama</td><td>:</td><td id="rev_nama">-</td></tr>
                                                        <tr><td>Tujuan</td><td>:</td><td id="rev_tujuan">-</td></tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-warning mt-3">
                                        <i class="fa fa-exclamation-triangle me-2"></i> Dengan menekan tombol Submit, Anda menyatakan bahwa seluruh data yang diisikan adalah benar dan dapat dipertanggungjawabkan.
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-secondary btn-prev" data-prev="step-dokumen"><i class="fa fa-arrow-left me-2"></i> Sebelumnya</button>
                                        <button type="submit" class="btn btn-success"><i class="fa fa-save me-2"></i> Kirim Usulan Mutasi</button>
                                    </div>
                                </div>
                            </div>
                        </form>
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

    <!-- Load jQuery & Select2 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Flatpickr for Date Inputs
            flatpickr("#tanggal_surat", {
                dateFormat: "Y-m-d",
                allowInput: true
            });

            // Inisialisasi Select2 Lokasi Tujuan (Unor)
            $('#select_lokasi_tujuan').select2({
                theme: 'bootstrap-5',
                placeholder: "-- Ketik Nama UNOR Tujuan --",
                minimumInputLength: 3,
                ajax: {
                    url: '/test-siasn/referensi/unor',
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                }
            }).on('change', function() {
                const selData = $(this).select2('data');
                if (selData && selData.length > 0) {
                    const id = selData[0].id;
                    const text = selData[0].text;
                    
                    document.getElementById('lokasi_tujuan').value = text;
                    document.getElementById('unor_id_tujuan').value = id;
                    document.getElementById('nama_unor_tujuan').value = text;
                }
            });

            // Get Data SIASN
            const btnGetSiasn = document.getElementById('btn-get-siasn');
            if (btnGetSiasn) {
                btnGetSiasn.addEventListener('click', function() {
                    const nip = document.getElementById('nip_pns').value;
                    if (!nip) {
                        Swal.fire('Oops', 'NIP tidak boleh kosong', 'warning');
                        return;
                    }

                    // Tampilkan loading di tombol
                    const originalText = btnGetSiasn.innerHTML;
                    btnGetSiasn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
                    btnGetSiasn.disabled = true;

                    fetch(`/test-siasn/${nip}`)
                        .then(response => response.json())
                        .then(res => {
                            btnGetSiasn.innerHTML = originalText;
                            btnGetSiasn.disabled = false;

                            if (res.status === 'success' && res.data && res.data.success) {
                                const pns = res.data.data;
                                
                                // Format Nama dengan Gelar
                                let namaLengkap = pns.nama || '';
                                if (pns.gelarDepan && pns.gelarDepan !== '-') {
                                    namaLengkap = pns.gelarDepan + ' ' + namaLengkap;
                                }
                                if (pns.gelarBelakang && pns.gelarBelakang !== '-') {
                                    namaLengkap = namaLengkap + ', ' + pns.gelarBelakang;
                                }

                                document.getElementById('nama_pns').value = namaLengkap;
                                document.getElementById('jabatan_pns').value = pns.jabatanNama || pns.namaJabatan || '-';
                                document.getElementById('lokasi_awal').value = pns.unorIndukNama || pns.lokasiKerja || '-';
                                
                                // Hidden fields requirement
                                document.getElementById('siasn_id').value = pns.id || '';
                                document.getElementById('siasn_nip_baru').value = pns.nipBaru || '';
                                document.getElementById('unor_induk_nama').value = pns.unorIndukNama || '';

                                Swal.fire('Berhasil', 'Data berhasil ditarik dari SIASN', 'success');
                            } else {
                                Swal.fire('Gagal', res.message || 'Data tidak ditemukan / Gagal menarik data dari server', 'error');
                            }
                        })
                        .catch(err => {
                            btnGetSiasn.innerHTML = originalText;
                            btnGetSiasn.disabled = false;
                            Swal.fire('Error', 'Terjadi kesalahan sistem saat menghubungi server', 'error');
                            console.error(err);
                        });
                });
            }

            // File Upload Auto (AJAX)
            const docUploads = document.querySelectorAll('.doc-upload');
            docUploads.forEach(input => {
                input.addEventListener('change', function() {
                    if (!this.files || this.files.length === 0) return;

                    const file = this.files[0];
                    const docId = this.getAttribute('data-id');
                    const statusText = document.getElementById('status_dokumen_' + docId);
                    const hiddenInput = document.getElementById('hidden_dokumen_' + docId);

                    // Validasi ukuran max 2MB
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire('Oops', 'Ukuran file maksimal 2MB', 'warning');
                        this.value = '';
                        return;
                    }

                    // Tampilkan status uploading
                    statusText.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mengunggah...';
                    statusText.className = 'upload-status text-warning mt-1 d-block';
                    input.disabled = true;

                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    fetch('/pns/usulan/upload-temp', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(res => {
                        input.disabled = false;
                        if (res.status === 'success') {
                            hiddenInput.value = res.path;
                            statusText.innerHTML = '<i class="fa fa-check text-success"></i> Berhasil diunggah';
                            statusText.className = 'upload-status text-success mt-1 d-block';
                            // Remove required invalid class on hidden wrapper if any
                            hiddenInput.classList.remove('is-invalid');
                        } else {
                            statusText.innerHTML = '<i class="fa fa-times text-danger"></i> Gagal: ' + (res.message || 'Error');
                            statusText.className = 'upload-status text-danger mt-1 d-block';
                            this.value = '';
                        }
                    })
                    .catch(err => {
                        input.disabled = false;
                        statusText.innerHTML = '<i class="fa fa-times text-danger"></i> Terjadi kesalahan jaringan';
                        statusText.className = 'upload-status text-danger mt-1 d-block';
                        this.value = '';
                        console.error(err);
                    });
                });
            });

            // Handle Next buttons
            const nextBtns = document.querySelectorAll('.btn-next');
            nextBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const currentTabPane = e.target.closest('.tab-pane');
                    // Check standard inputs + our specific hidden inputs for files
                    const inputs = currentTabPane.querySelectorAll('input[required]:not([type="hidden"]), textarea[required], select[required], input[type="hidden"][required]');
                    let isValid = true;
                    
                    inputs.forEach(input => {
                        if(!input.value) {
                            // Untuk hidden file upload, kita tambahkan is-invalid ke parent (atau alert umum)
                            if (input.type === 'hidden' && input.id.startsWith('hidden_dokumen_')) {
                                const fileInput = input.parentElement.querySelector('.doc-upload');
                                if (fileInput) fileInput.classList.add('is-invalid');
                            } else {
                                input.classList.add('is-invalid');
                            }
                            isValid = false;
                        } else {
                            if (input.type === 'hidden' && input.id.startsWith('hidden_dokumen_')) {
                                const fileInput = input.parentElement.querySelector('.doc-upload');
                                if (fileInput) fileInput.classList.remove('is-invalid');
                            } else {
                                input.classList.remove('is-invalid');
                            }
                        }
                    });

                    if(isValid) {
                        const targetId = this.getAttribute('data-next');
                        goToTab(targetId);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Harap lengkapi semua field / unggah dokumen yang wajib diisi pada tahap ini.',
                        });
                    }
                });
            });

            // Handle Form Submit with Loading
            const formUsulan = document.getElementById('formUsulan');
            if (formUsulan) {
                formUsulan.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i> Mengirim...';
                    
                    Swal.fire({
                        title: 'Memproses Usulan...',
                        html: 'Mohon tunggu sementara data dan dokumen sedang diunggah.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                });
            }

            // Handle Prev buttons
            const prevBtns = document.querySelectorAll('.btn-prev');
            prevBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-prev');
                    goToTab(targetId);
                });
            });

            // Populate Review Data
            document.getElementById('btn-to-review').addEventListener('click', function() {
                document.getElementById('rev_no_surat').innerText = document.getElementById('no_surat').value;
                document.getElementById('rev_tgl_surat').innerText = document.getElementById('tanggal_surat').value;
                document.getElementById('rev_perihal').innerText = document.getElementById('perihal').value;
                document.getElementById('rev_no_whatsapp').innerText = document.getElementById('no_whatsapp').value;
                document.getElementById('rev_nip').innerText = document.getElementById('nip_pns').value;
                document.getElementById('rev_nama').innerText = document.getElementById('nama_pns').value;

                // Dapatkan teks deskripsi yg dipilih dari hidden input karena validasinya butuh text
                document.getElementById('rev_tujuan').innerText = document.getElementById('lokasi_tujuan').value;
            });

            function goToTab(tabId) {
                // Enable the target tab in navbar
                const targetTabBtn = document.getElementById(tabId + '-tab');
                targetTabBtn.classList.remove('disabled');
                
                // Use Bootstrap Tab API (simulated via click for simplicity)
                targetTabBtn.click();
            }
        });
    </script>
</x-app-layout>
