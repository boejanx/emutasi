<x-app-layout>
    <div class="container-fluid p-0">
        <h1 class="h3 mb-3">BUAT USULAN MUTASI KOLEKTIF</h1>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">

                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title fw-bold text-primary mb-0">Formulir Pengajuan Admin OPD</h5>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btn-load-draft" style="display:none;">
                                <i class="fa fa-undo-alt me-1"></i> Pulihkan Draft Tersimpan
                            </button>
                        </div>
                        <p class="text-muted small mb-4">Anda dapat mendaftarkan beberapa PNS sekaligus dalam satu surat usulan. <br><span class="text-success small"><i class="fa fa-save"></i> Fitur Auto-Save aktif. Form yang Anda isi otomatis tersimpan di browser Anda.</span></p>

                            <!-- Navigation Tabs -->
                        <ul class="nav nav-pills mb-4 justify-content-center" id="opd-steps" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="step-surat-tab" data-bs-toggle="pill" data-bs-target="#step-surat" type="button" role="tab">
                                    <span class="step-num shadow-sm">1</span> Data Surat
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link disabled" id="step-pns-tab" data-bs-toggle="pill" data-bs-target="#step-pns" type="button" role="tab">
                                    <span class="step-num shadow-sm">2</span> Daftar PNS
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link disabled" id="step-dokumen-tab" data-bs-toggle="pill" data-bs-target="#step-dokumen" type="button" role="tab">
                                    <span class="step-num shadow-sm">3</span> Berkas Persyaratan
                                </button>
                            </li>
                        </ul>

                        <form id="formUsulanOpd" action="{{ route('opd.usulan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content" id="opdTabContent">
                                
                                <!-- STEP 1: Data Surat -->
                                <div class="tab-pane fade show active p-4 border rounded bg-light-50" id="step-surat" role="tabpanel">
                                    <h4 class="mb-4 d-flex align-items-center"><i class="fa fa-envelope-open-text text-primary me-3"></i> Informasi Surat Pengantar</h4>
                                    
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Nomor Surat Usulan <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="no_surat" id="no_surat" placeholder="Contoh: 800/123/2024" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Tanggal Surat <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="tanggal_surat" id="tanggal_surat_picker" placeholder="Pilih Tanggal ..." required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Perihal <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="perihal" id="perihal" rows="3" placeholder="Contoh: Usul Mutasi PNS di Lingkungan Dinas Pendidikan..." required></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-bold">No. WhatsApp Narahubung / Admin OPD <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="no_whatsapp" id="no_whatsapp" placeholder="Contoh: 081234567890" required>
                                            <small class="text-muted">Nomor ini akan digunakan untuk mengirim notifikasi status proses usulan mutasi kolektif ini.</small>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-5">
                                        <button type="button" class="btn btn-primary px-4 btn-next" data-next="step-pns">Selanjutnya <i class="fa fa-arrow-right ms-2"></i></button>
                                    </div>
                                </div>

                                <!-- STEP 2: Daftar PNS -->
                                <div class="tab-pane fade p-4 border rounded bg-light-50" id="step-pns" role="tabpanel">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h4 class="mb-0 d-flex align-items-center"><i class="fa fa-users text-primary me-3"></i> PNS yang Diusulkan</h4>
                                        <button type="button" class="btn btn-success btn-sm shadow-sm" id="btn-add-pns">
                                            <i class="fa fa-user-plus me-1"></i> Tambah PNS
                                        </button>
                                    </div>

                                    <div class="accordion" id="pns-list-container">
                                        <!-- PNS Entry 1 (Default) -->
                                        <div class="accordion-item pns-entry shadow-sm border mb-3" data-index="0">
                                            <h2 class="accordion-header" id="headingPns0">
                                                <button class="accordion-button fw-bold text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePns0" aria-expanded="true" aria-controls="collapsePns0">
                                                    <i class="fa fa-user me-2"></i> Pegawai #1
                                                </button>
                                            </h2>
                                            <div id="collapsePns0" class="accordion-collapse collapse show" aria-labelledby="headingPns0" data-bs-parent="#pns-list-container">
                                                <div class="accordion-body bg-white">
                                                    <div class="row g-4">
                                                        <div class="col-md-4">
                                                            <label class="form-label mb-1 fw-bold">NIP <span class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control nip-input" name="details[0][nip]" placeholder="18 Digit NIP" required>
                                                                <button class="btn btn-outline-primary btn-cari-asn" type="button" data-index="0"><i class="fa fa-search"></i> Cari</button>
                                                            </div>
                                                            <small class="text-muted d-none loading-text mt-1"><i class="fa fa-spinner fa-spin"></i> Mencari API...</small>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label mb-1 fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="details[0][nama]" placeholder="Nama Lengkap" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label mb-1 fw-bold">Jabatan <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="details[0][jabatan]" placeholder="Jabatan Saat Ini" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label mb-1 fw-bold">Unit Kerja Awal <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="details[0][lokasi_awal]" placeholder="Asal Instansi" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label mb-1 fw-bold">Unit Kerja Tujuan <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="details[0][lokasi_tujuan]" placeholder="Tujuan Instansi" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-secondary px-4 btn-prev" data-prev="step-surat"><i class="fa fa-arrow-left me-2"></i> Sebelumnya</button>
                                        <button type="button" class="btn btn-primary px-4 btn-next" id="btn-to-docs" data-next="step-dokumen">Selanjutnya <i class="fa fa-arrow-right ms-2"></i></button>
                                    </div>
                                </div>

                                <!-- STEP 3: Berkas Persyaratan -->
                                <div class="tab-pane fade p-4 border rounded bg-light-50" id="step-dokumen" role="tabpanel">
                                    <h4 class="mb-4 d-flex align-items-center"><i class="fa fa-file-upload text-primary me-3"></i> Upload Berkas Pendukung</h4>
                                    
                                    <div class="alert alert-warning mb-4">
                                        <i class="fa fa-info-circle me-1"></i> Satu paket dokumen harus diunggah untuk <strong>setiap PNS</strong> yang Anda daftarkan. Gunakan format PDF (Maks. 2MB).
                                    </div>

                                    <div class="accordion" id="docs-upload-container">
                                        <!-- Will be populated via JS based on PNS entries -->
                                    </div>

                                    <div class="d-flex justify-content-between mt-5">
                                        <button type="button" class="btn btn-secondary px-4 btn-prev" data-prev="step-pns"><i class="fa fa-arrow-left me-2"></i> Sebelumnya</button>
                                        <button type="submit" class="btn btn-success px-5 py-2 fw-bold shadow-sm">
                                            <i class="fa fa-save me-2"></i> SIMPAN & KIRIM USULAN MUTASI
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Template for PDF Entry JS -->
    <template id="pns-entry-template">
        <div class="accordion-item pns-entry shadow-sm border mb-3" data-index="INDEX">
            <h2 class="accordion-header" id="headingPnsINDEX">
                <button class="accordion-button fw-bold text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePnsINDEX" aria-expanded="true" aria-controls="collapsePnsINDEX">
                    <i class="fa fa-user me-2"></i> Pegawai #NUMBER
                </button>
            </h2>
            <div id="collapsePnsINDEX" class="accordion-collapse collapse show" aria-labelledby="headingPnsINDEX" data-bs-parent="#pns-list-container">
                <div class="accordion-body bg-white position-relative">
                    <button type="button" class="btn btn-danger btn-sm shadow-sm btn-remove-pns position-absolute" style="top: 15px; right: 15px; z-index: 10;"><i class="fa fa-trash-alt me-1"></i> Hapus</button>
                    <div class="row g-4 mt-1">
                        <div class="col-md-4">
                            <label class="form-label mb-1 fw-bold">NIP <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control nip-input" name="details[INDEX][nip]" placeholder="18 Digit NIP" required>
                                <button class="btn btn-outline-primary btn-cari-asn" type="button" data-index="INDEX"><i class="fa fa-search"></i> Cari</button>
                            </div>
                            <small class="text-muted d-none loading-text mt-1"><i class="fa fa-spinner fa-spin"></i> Mencari API...</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-1 fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="details[INDEX][nama]" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label mb-1 fw-bold">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="details[INDEX][jabatan]" placeholder="Jabatan Saat Ini" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-bold">Unit Kerja Awal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="details[INDEX][lokasi_awal]" placeholder="Asal Instansi" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1 fw-bold">Unit Kerja Tujuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="details[INDEX][lokasi_tujuan]" placeholder="Tujuan Instansi" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <style>
        .nav-pills .nav-link { border: 1px solid #dee2e6; background: #fff; margin: 0 5px; border-radius: 50px; padding: 10px 25px; color: #6c757d; font-weight: 600; }
        .nav-pills .nav-link.active { background: #3b7ddd; color: #fff; border-color: #3b7ddd; }
        .step-num { display: inline-flex; width: 25px; height: 25px; background: #eee; color: #333; border-radius: 50%; align-items: center; justify-content: center; margin-right: 8px; font-size: 0.8rem; }
        .nav-link.active .step-num { background: #fff; color: #3b7ddd; }
        .accordion-button:not(.collapsed) {
            background-color: #f1f7ff;
            color: #3b7ddd;
            box-shadow: inset 0 -1px 0 rgba(0,0,0,.125);
        }
        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(0,0,0,.125);
        }
        .bg-light-50 { background-color: #fafbfc; }
        .doc-row { border-bottom: 1px dashed #eee; padding: 15px 0; }
        .doc-row:last-child { border-bottom: none; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#tanggal_surat_picker", { dateFormat: "Y-m-d" });

            const pnsContainer = document.getElementById('pns-list-container');
            const btnAddPns = document.getElementById('btn-add-pns');
            const template = document.getElementById('pns-entry-template').innerHTML;
            let pnsCount = 1;

            // Add PNS
            btnAddPns.addEventListener('click', function() {
                const newPns = template.replace(/INDEX/g, pnsCount).replace(/NUMBER/g, pnsCount + 1);
                pnsContainer.insertAdjacentHTML('beforeend', newPns);
                pnsCount++;
            });

            // Remove PNS
            pnsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.btn-remove-pns')) {
                    e.target.closest('.pns-entry').remove();
                }
            });

            // Handle Tab Transitions
            const nextBtns = document.querySelectorAll('.btn-next');
            const prevBtns = document.querySelectorAll('.btn-prev');

            nextBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const pane = this.closest('.tab-pane');
                    const required = pane.querySelectorAll('[required]');
                    let valid = true;
                    
                    required.forEach(input => {
                        if (input.value.trim() === '') {
                            if (input.type === 'hidden' && input.id.startsWith('hidden_dokumen_')) {
                                const fileInput = input.parentElement.querySelector('.doc-upload');
                                if (fileInput) fileInput.classList.add('is-invalid');
                            } else {
                                input.classList.add('is-invalid');
                            }
                            valid = false;
                        } else {
                            if (input.type === 'hidden' && input.id.startsWith('hidden_dokumen_')) {
                                const fileInput = input.parentElement.querySelector('.doc-upload');
                                if (fileInput) fileInput.classList.remove('is-invalid');
                            } else {
                                input.classList.remove('is-invalid');
                            }
                        }
                    });

                    if (valid) {
                        const target = this.getAttribute('data-next');
                        if (target === 'step-dokumen') {
                            buildDocUploads();
                        }
                        const targetTab = document.getElementById(target + '-tab');
                        targetTab.classList.remove('disabled');
                        targetTab.click();
                        
                        // Scroll to top of panel
                        window.scrollTo({top: targetTab.offsetTop - 50, behavior: 'smooth'});
                    } else {
                        Swal.fire('Perhatian', 'Harap isi semua kolom yang bertanda bintang (*)', 'warning');
                    }
                });
            });

            prevBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const target = this.getAttribute('data-prev');
                    document.getElementById(target + '-tab').click();
                });
            });

            // Build Document Upload Sections Dynamically
            function buildDocUploads() {
                const docContainer = document.getElementById('docs-upload-container');
                docContainer.innerHTML = ''; // Clear
                
                const pnsEntries = document.querySelectorAll('.pns-entry');
                const reqDocs = @json($dokumenSyarat);

                if(pnsEntries.length === 0) {
                     docContainer.innerHTML = '<div class="alert alert-danger">Silakan tambahkan minimal 1 PNS terlebih dahulu secara manual.</div>';
                     return;
                }

                pnsEntries.forEach((entry, i) => {
                    const idx = entry.getAttribute('data-index');
                    const name = entry.querySelector('input[name="details['+idx+'][nama]"]').value || 'PNS Belum Diberi Nama';
                    const nip = entry.querySelector('input[name="details['+idx+'][nip]"]').value || '-';
                    const isFirst = i === 0 ? 'show' : '';

                    let html = `
                        <div class="accordion-item shadow-sm border mb-3">
                            <h2 class="accordion-header" id="headingDoc${idx}">
                                <button class="accordion-button ${isFirst ? '' : 'collapsed'} fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDoc${idx}" aria-expanded="${isFirst ? 'true' : 'false'}" aria-controls="collapseDoc${idx}">
                                    <i class="fa fa-folder-open me-2"></i> Berkas Pendukung: <strong>${name} (${nip})</strong>
                                </button>
                            </h2>
                            <div id="collapseDoc${idx}" class="accordion-collapse collapse ${isFirst}" aria-labelledby="headingDoc${idx}" data-bs-parent="#docs-upload-container">
                                <div class="accordion-body bg-white p-4">
                                    <div class="row">
                    `;

                    reqDocs.forEach(doc => {
                        html += `
                            <div class="col-md-6 doc-row">
                                <label class="fw-bold mb-2">${doc.nama_dokumen} <span class="text-danger">*</span></label>
                                <input type="file" class="form-control doc-upload" accept="application/pdf" data-idx="${idx}" data-docid="${doc.id_dokumen}">
                                <input type="hidden" name="file_dokumen_temp_${idx}_${doc.id_dokumen}" id="hidden_dokumen_${idx}_${doc.id_dokumen}" required>
                                <small class="text-muted d-block mt-1">Format: PDF (Max. 2MB)</small>
                                <span id="status_dokumen_${idx}_${doc.id_dokumen}" class="upload-status d-block mt-1"></span>
                            </div>
                        `;
                    });

                    html += `</div></div></div></div>`;
                    docContainer.insertAdjacentHTML('beforeend', html);
                });
            }

            // Auto-Save Draft
            const form = document.getElementById('formUsulanOpd');
            const btnLoadDraft = document.getElementById('btn-load-draft');

            function saveDraft() {
                const draft = {
                    no_surat: document.getElementById('no_surat').value,
                    tanggal_surat: document.getElementById('tanggal_surat_picker').value,
                    perihal: document.getElementById('perihal').value,
                    no_whatsapp: document.getElementById('no_whatsapp').value,
                    pnsCount: pnsCount,
                    pnsHTML: pnsContainer.innerHTML
                };
                
                // Copy values into HTML string so they load with the nodes
                // In a true robust app we'd save JSON, but for this simpler UI, saving innerHTML + inputs is faster
                const pnsNodes = document.querySelectorAll('.pns-entry');
                let inputsArray = [];
                pnsNodes.forEach((node, i) => {
                    const inputs = node.querySelectorAll('input');
                    let pnsVals = {};
                    inputs.forEach(inp => pnsVals[inp.name] = inp.value);
                    inputsArray.push(pnsVals);
                });
                draft.pnsVals = inputsArray;

                localStorage.setItem('usulan_draft', JSON.stringify(draft));
            }

            // Restore Draft
            function checkDraft() {
                if(localStorage.getItem('usulan_draft')) {
                    btnLoadDraft.style.display = 'block';
                }
            }
            checkDraft(); // on load
            
            btnLoadDraft.addEventListener('click', function() {
                const draft = JSON.parse(localStorage.getItem('usulan_draft'));
                if(draft) {
                    document.getElementById('no_surat').value = draft.no_surat;
                    document.getElementById('tanggal_surat_picker').value = draft.tanggal_surat;
                    document.getElementById('perihal').value = draft.perihal;
                    document.getElementById('no_whatsapp').value = draft.no_whatsapp;
                    
                    if(draft.pnsHTML) {
                        pnsContainer.innerHTML = draft.pnsHTML;
                        pnsCount = draft.pnsCount;
                        
                        // Restore values
                        const pnsNodes = document.querySelectorAll('.pns-entry');
                        pnsNodes.forEach((node, i) => {
                            if(draft.pnsVals && draft.pnsVals[i]) {
                                const inputs = node.querySelectorAll('input');
                                inputs.forEach(inp => {
                                    if(draft.pnsVals[i][inp.name]) inp.value = draft.pnsVals[i][inp.name];
                                });
                            }
                        });
                    }
                    Swal.fire('Sukses', 'Draft Formulir berhasil dipulihkan.', 'success');
                    btnLoadDraft.style.display = 'none';
                }
            });

            // Event Listener for SIASN Integration & Auto Save Trigger
            document.addEventListener('input', function(e) {
                if(e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                    saveDraft();
                }
            });

            document.addEventListener('click', function(e) {
                // Delete also triggers save
                if (e.target.closest('.btn-remove-pns')) {
                    saveDraft();
                }

                // SIASN API Check
                const btnCari = e.target.closest('.btn-cari-asn');
                if(btnCari) {
                    const index = btnCari.getAttribute('data-index');
                    const entry = btnCari.closest('.pns-entry');
                    const nipInput = entry.querySelector(`input[name="details[${index}][nip]"]`);
                    const loadingText = entry.querySelector('.loading-text');

                    const nipVal = nipInput.value.trim();
                    if(nipVal.length !== 18) {
                        Swal.fire('Oops!', 'NIP harus berisi 18 Karakter Angka!', 'warning');
                        return;
                    }

                    // Loading State
                    btnCari.disabled = true;
                    loadingText.classList.remove('d-none');

                    fetch('{{ url("/test-siasn") }}/' + nipVal)
                        .then(response => response.json())
                        .then(res => {
                            if(res.status === 'success' && res.data && res.data.success) {
                                const pns = res.data.data;
                                
                                // Format Nama dengan Gelar
                                let namaLengkap = pns.nama || '';
                                if (pns.gelarDepan && pns.gelarDepan !== '-') {
                                    namaLengkap = pns.gelarDepan + ' ' + namaLengkap;
                                }
                                if (pns.gelarBelakang && pns.gelarBelakang !== '-') {
                                    namaLengkap = namaLengkap + ', ' + pns.gelarBelakang;
                                }

                                entry.querySelector(`input[name="details[${index}][nama]"]`).value = namaLengkap;
                                entry.querySelector(`input[name="details[${index}][jabatan]"]`).value = pns.jabatanNama || pns.namaJabatan || '-';
                                entry.querySelector(`input[name="details[${index}][lokasi_awal]"]`).value = pns.unorIndukNama || pns.lokasiKerja || '-';
                                
                                Swal.fire('Berhasil!', `Data Pegawai ditemukan.`, 'success');
                                saveDraft();
                            } else {
                                Swal.fire('Gagal', res.message || 'Data tidak ditemukan di SIASN BKN', 'error');
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error');
                        })
                        .finally(() => {
                            btnCari.disabled = false;
                            loadingText.classList.add('d-none');
                        });
                }
            });

            // AJAX File Upload Registration
            document.addEventListener('change', function(e) {
                if(e.target.classList.contains('doc-upload')) {
                    const input = e.target;
                    const file = input.files[0];
                    if (!file) return;

                    const idx = input.getAttribute('data-idx');
                    const docId = input.getAttribute('data-docid');
                    const statusText = document.getElementById('status_dokumen_' + idx + '_' + docId);
                    const hiddenInput = document.getElementById('hidden_dokumen_' + idx + '_' + docId);

                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire('Oops', 'Ukuran file maksimal 2MB', 'warning');
                        input.value = '';
                        return;
                    }

                    statusText.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mengunggah...';
                    statusText.className = 'upload-status text-warning mt-1 d-block small';
                    input.disabled = true;

                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    fetch('{{ route("opd.usulan.uploadTemp") }}', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(res => {
                        input.disabled = false;
                        if (res.status === 'success') {
                            hiddenInput.value = res.path;
                            statusText.innerHTML = '<i class="fa fa-check text-success"></i> Berhasil diunggah';
                            statusText.className = 'upload-status text-success mt-1 d-block small';
                            hiddenInput.classList.remove('is-invalid');
                            input.classList.remove('is-invalid');
                        } else {
                            statusText.innerHTML = '<i class="fa fa-times text-danger"></i> Gagal: ' + (res.message || 'Error');
                            statusText.className = 'upload-status text-danger mt-1 d-block small';
                            input.value = '';
                        }
                    })
                    .catch(err => {
                        input.disabled = false;
                        statusText.innerHTML = '<i class="fa fa-times text-danger"></i> Terjadi kesalahan jaringan';
                        statusText.className = 'upload-status text-danger mt-1 d-block small';
                        input.value = '';
                        console.error(err);
                    });
                }
            });

            // Form Submit Validation & Loading
            document.getElementById('formUsulanOpd').addEventListener('submit', function(e) {
                // Remove required properties for files if they are not seen just in case, wait they are generated
                if(document.getElementById('docs-upload-container').innerHTML.trim() === '' || document.getElementById('docs-upload-container').innerHTML.includes('PNS yang Diusulkan')) {
                    buildDocUploads();
                }

                // Jika simpan sukses, hapus draft dari localstorage
                localStorage.removeItem('usulan_draft');

                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang mengunggah data surat usulan dan seluruh berkas PNS. Proses ini membutuhkan waktu beberapa saat...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
            });
        });
    </script>
</x-app-layout>
