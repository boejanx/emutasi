<?php

namespace App\Services;

use App\Models\Usulan;
use App\Models\UsulanLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\SystemLog;

class UsulanService
{
    /**
     * Create a new Usulan with its related details and berkas.
     *
     * @param array $data
     * @param int $userId
     * @return Usulan
     * @throws \Exception
     */
    public function createUsulan(array $data, int $userId): Usulan
    {
        DB::beginTransaction();
        try {
            $usulan = Usulan::create([
                'no_surat' => $data['no_surat'],
                'tanggal_surat' => $data['tanggal_surat'],
                'perihal' => $data['perihal'],
                'no_whatsapp' => $data['no_whatsapp'] ?? null,
                'id_user' => $userId,
                'status' => 1,
                'disposisi' => 0,
            ]);

            if (isset($data['details']) && is_array($data['details'])) {
                foreach ($data['details'] as $detailData) {
                    $detail = $usulan->details()->create([
                        'nip'              => $detailData['nip'],
                        'nama'             => $detailData['nama'],
                        'jabatan'          => $detailData['jabatan'],
                        'lokasi_awal'      => $detailData['lokasi_awal'],
                        'lokasi_tujuan'    => $detailData['lokasi_tujuan'], // Usually the logic text from the form, but let's keep it for compatibility
                        'catatan'          => $detailData['catatan'] ?? null,
                        'siasn_id'         => $detailData['siasn_id'] ?? null,
                        'unor_id_tujuan'   => $detailData['unor_id_tujuan'] ?? null,
                        'nama_unor_tujuan' => $detailData['nama_unor_tujuan'] ?? null,
                    ]);

                    if (isset($detailData['berkas']) && is_array($detailData['berkas'])) {
                        foreach ($detailData['berkas'] as $berkasData) {
                            $detail->berkas()->create([
                                'path_dokumen' => $berkasData['path_dokumen'],
                                'id_dokumen' => $berkasData['id_dokumen'] ?? null,
                            ]);
                        }
                    }
                }
            }

            // Log the creation
            $this->logUsulan(
                $usulan->id_usulan,
                'PENGIRIMAN_USULAN',
                'Diajukan',
                'Usulan mutasi diajukan ke BKPSDM.',
                $userId
            );

            DB::commit();
            
            // Reload with relations to return complete data
            $usulan->load('details.berkas');
            return $usulan;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create usulan.', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update an existing Usulan.
     * 
     * @param string $id
     * @param array $data
     * @return Usulan
     * @throws \Exception
     */
    public function updateUsulan(string $id, array $data): Usulan
    {
         DB::beginTransaction();
         try {
             $usulan = Usulan::findOrFail($id);
             
             // Update main Usulan record
             $usulan->update([
                 'no_surat' => $data['no_surat'] ?? $usulan->no_surat,
                 'tanggal_surat' => $data['tanggal_surat'] ?? $usulan->tanggal_surat,
                 'perihal' => $data['perihal'] ?? $usulan->perihal,
                 'no_whatsapp' => $data['no_whatsapp'] ?? $usulan->no_whatsapp,
                 'status' => $data['status'] ?? $usulan->status,
                 'disposisi' => $data['disposisi'] ?? $usulan->disposisi,
             ]);
 
             // Optional: Handle updating details and berkas if needed
             // This can get complex depending on whether you want to sync, add, or delete related records
             // For simplicity, this example does not sync detailed relations automatically.
 
             SystemLog::create([
                 'id_user' => auth()->id() ?? 1,
                 'action' => 'UPDATE_USULAN',
                 'description' => 'Memperbarui data Usulan No. ' . ($usulan->no_surat ?? $id),
                 'ip_address' => request()->ip() ?? '127.0.0.1',
                 'user_agent' => request()->userAgent() ?? 'System',
             ]);

             DB::commit();
             return $usulan;
         } catch (\Exception $e) {
             DB::rollBack();
             Log::error('Failed to update usulan.', ['error' => $e->getMessage(), 'usulan_id' => $id]);
             throw $e;
         }
    }

    /**
     * Delete an Usulan.
     *
     * @param string $id
     * @return bool
     * @throws \Exception
     */
    public function deleteUsulan(string $id): bool
    {
         DB::beginTransaction();
         try {
             $usulan = Usulan::findOrFail($id);
             // UsulanDetail and UsulanBerkas should be soft deleted or cascade deleted automatically 
             // depending on how onDelete('cascade') affects soft deletes in your setup, 
             // usually deleting the parent triggers soft deletes on children if configured correctly.
             
             SystemLog::create([
                 'id_user' => auth()->id() ?? 1,
                 'action' => 'HAPUS_USULAN',
                 'description' => 'Menghapus Usulan No. ' . ($usulan->no_surat ?? $id),
                 'ip_address' => request()->ip() ?? '127.0.0.1',
                 'user_agent' => request()->userAgent() ?? 'System',
             ]);

             $deleted = $usulan->delete();
             
             DB::commit();
             return $deleted;
         } catch (\Exception $e) {
             DB::rollBack();
             Log::error('Failed to delete usulan.', ['error' => $e->getMessage(), 'usulan_id' => $id]);
             throw $e;
         }
    }

    /**
     * Create a log entry for an Usulan.
     * 
     * @param string $idUsulan
     * @param string $aksi
     * @param string|null $statusUsulan
     * @param string|null $keterangan
     * @param int|null $userId
     * @return UsulanLog
     */
    public function logUsulan(string $idUsulan, string $aksi, ?string $statusUsulan = null, ?string $keterangan = null, ?int $userId = null): UsulanLog
    {
        $log = UsulanLog::create([
            'id_usulan' => $idUsulan,
            'aksi' => $aksi,
            'status_usulan' => $statusUsulan,
            'keterangan' => $keterangan,
            'id_user' => $userId ?? auth()->id() ?? 1,
        ]);

        $usulan = Usulan::find($idUsulan);

        // Catat juga ke Audit Trail (SystemLog)
        try {
            SystemLog::create([
                'id_user' => $userId ?? auth()->id() ?? 1,
                'action' => $aksi,
                'description' => "Usulan No. " . ($usulan->no_surat ?? $idUsulan) . " - " . ($statusUsulan ?? '') . ": " . ($keterangan ?? '-'),
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => request()->userAgent() ?? 'System',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to write SystemLog', ['error' => $e->getMessage()]);
        }

        try {
            if ($usulan && !empty($usulan->no_whatsapp)) {
                $waAksi = $aksi;
                $waKeterangan = $keterangan ?? '-';

                // Custom rule specific for User Request (Remove 'disposisi' wording)
                if ($aksi === 'DISPOSISI_SURAT') {
                    if (stripos($keterangan, 'Kepala Bidang') !== false) {
                        $waAksi = 'Surat Usulan Diterima';
                        $waKeterangan = 'Dokumen usulan Anda telah diterima oleh BKPSDM Kabupaten Pekalongan.';
                    } elseif (stripos($keterangan, 'Staf Teknis') !== false) {
                        $waAksi = 'Usulan Diproses';
                        $waKeterangan = 'Usulan Anda sedang diproses oleh BKPSDM Kabupaten Pekalongan.';
                    } else {
                        $waAksi = 'Usulan Diproses';
                        $waKeterangan = 'Usulan Anda sedang diproses dan ditindaklanjuti.';
                    }
                }

                $waService = new \App\Services\WhatsappService();
                $message = "*e-Mutasi Notifikasi*\n\n"
                         . "Usulan dengan No. Surat *" . $usulan->no_surat . "* terdapat pembaruan status.\n\n"
                         . "Aksi: *" . $waAksi . "*\n"
                         . "Status: *" . ($statusUsulan ?? '-') . "*\n"
                         . "Keterangan: _" . $waKeterangan . "_\n\n"
                         . "Silakan cek dashboard secara berkala.\nTerima kasih.";
                         
                $waService->sendMessage($usulan->no_whatsapp, $message);
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim WA notif', ['error' => $e->getMessage()]);
        }

        return $log;
    }
}
