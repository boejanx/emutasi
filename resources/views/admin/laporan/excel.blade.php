<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Mutasi</title>
</head>
<body>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th colspan="6" style="text-align: center; font-size: 16pt; font-weight: bold;">
                    REKAPITULASI LAPORAN MUTASI TAHUN {{ $tahun == 'all' ? 'SEMUA' : $tahun }} BULAN {{ $bulan == 'all' ? 'SEMUA' : $bulan }}
                </th>
            </tr>
            <tr>
                <th>No</th>
                <th>Nama PNS</th>
                <th>NIP</th>
                <th>No Registrasi / Surat</th>
                <th>Tanggal Masuk</th>
                <th>Progress Berkas</th>
                <th>Status Akhir</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($usulans as $u)
                @foreach($u->details as $detail)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $detail->nama ?? '-' }}</td>
                    <td style="mso-number-format:'\@';">{{ $detail->nip ?? '-' }}</td>
                    <td>{{ $u->no_surat }}</td>
                    <td>{{ $u->created_at->format('d M Y') }}</td>
                    <td>
                        @if($u->status == 1) Verifikasi Instansi (OPD)
                        @elseif($u->status == 2) Menunggu Disposisi Asisten/Sekda
                        @elseif($u->status == 3) Penelaahan Teknis BKPSDM
                        @elseif($u->status == 4) Pencetakan & TTE SK
                        @elseif($u->status == 5) Arsip Akhir
                        @else Ditolak / Kembali @endif
                    </td>
                    <td>
                        @if($u->status == 5) Selesai / SK Terbit
                        @elseif($u->status == 99) Ditolak
                        @else Dalam Proses
                        @endif
                    </td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <!-- Empty row for spacing -->
                <td colspan="7"></td>
            </tr>
            <tr>
                <td colspan="6" align="right">Total Selesai:</td>
                <td>{{ $rekap['selesai'] }}</td>
            </tr>
            <tr>
                <td colspan="6" align="right">Total Diproses:</td>
                <td>{{ $rekap['proses'] }}</td>
            </tr>
            <tr>
                <td colspan="6" align="right">Total Ditolak:</td>
                <td>{{ $rekap['ditolak'] }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
