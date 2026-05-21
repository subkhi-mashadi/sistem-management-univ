# 🎓 Flow Operasional Kampus — Checklist Input Manual

> **Cara pakai:** Ikuti urutan layer 1 → 12. **JANGAN** loncat. Setiap layer punya prasyarat.
> Setelah `migrate:fresh` hanya ada Super Admin. Login → ikuti checklist ini.

---

## ⚙️ Prinsip Fondasi

### Aturan #1: Urutan = Dependency
Nomor layer = urutan eksekusi. Layer N hanya bisa diisi setelah semua Layer < N terisi.

### Aturan #2: Auto-Calculation Aktif
Sistem punya **Observer** yang otomatis update data turunan. Anda **TIDAK** perlu hitung manual:

| Saat Anda… | Sistem auto-update… |
|---|---|
| Tambah/edit **Item Invoice** | `invoice.subtotal` & `total_amount` |
| Catat **Pembayaran** (status Success) | `invoice.paid_amount` & `status` (Unpaid/Partial/Paid) |
| Tambah/drop **KRS Item** | `course_offering.enrolled_count` & `enrollment.total_sks_taken` |
| Lock **Nilai** | `transcript` mahasiswa (IPS, IPK, SKS, status akademik) |
| Buat **Pembayaran baru** | `payment_number` auto-generate jika kosong |

Referensi: [app/Observers/](../app/Observers/)

### Aturan #3: Kode Manual untuk Saat Ini
Saat ini Anda input kode (FT, IF, KUR-IF-2026, dll) **manual** di form. Konvensi disarankan tertulis di bagian "Konvensi Kode" di bawah.

---

## 🗺️ 12 Layer

```
LAYER 1  Institusi          Fakultas, Program Studi
LAYER 2  Periode Akademik   Kalender, Semester
LAYER 3  Fasilitas          Ruangan
LAYER 4  SDM                Dosen, Karyawan, assign Dekan & Kaprodi
LAYER 5  Kurikulum          Kurikulum, Mata Kuliah, Prasyarat, Skema Nilai
LAYER 6  PMB                Mahasiswa baru
LAYER 7  Pre-Semester       Penawaran MK, Jadwal
LAYER 8  Keuangan Pra       Komponen Tagihan, Tarif, Invoice
LAYER 9  KRS                Enrollment + KRS Item, Workload Dosen, Jadwal Ujian
LAYER 10 Aktivitas          Sesi Kelas, Presensi, Nilai, Transkrip, Pembayaran
LAYER 11 Layanan            Surat, Beasiswa, Skripsi, MBKM, Riset, Notifikasi
LAYER 12 HRIS Operasional   Payroll, Honor Mengajar, Pajak, Absensi Pegawai, Cuti
```

---

## ✅ LAYER 1 — Institusi (5 menit)

**PIC:** Rektorat / IT Admin

| # | Menu | Yang Diisi | Wajib? |
|---|---|---|---|
| 1.1 | **Akademik › Fakultas** | Kode (FT), Nama (Fakultas Teknik) | ✅ |
| 1.2 | **Akademik › Program Studi** | Kode (IF), Nama, Fakultas, Jenjang (S1) | ✅ |

> ⚠️ Dean & Kaprodi kosong dulu — akan diisi di Layer 4.

---

## ✅ LAYER 2 — Periode Akademik (3 menit)

**PIC:** BAA

| # | Menu | Yang Diisi | Wajib? |
|---|---|---|---|
| 2.1 | **Akademik › Kalender Akademik** | Tahun Ajaran (2026/2027), tgl mulai-akhir | ✅ |
| 2.2 | **Akademik › Semester** | Kode (20261), Term (Ganjil), tgl KRS/UTS/UAS, **is_active = true** | ✅ |

> ⚠️ Hanya **1 Semester** boleh `is_active=true` pada satu waktu.

---

## ✅ LAYER 3 — Fasilitas (2 menit)

**PIC:** BAU / Sarpras

| # | Menu | Yang Diisi |
|---|---|---|
| 3.1 | **Akademik › Ruangan** | Kode (R301), Nama, Gedung, Kapasitas, Tipe (Kelas/Lab) |

---

## ✅ LAYER 4 — SDM (10 menit)

**PIC:** Admin SDM

| # | Menu | Yang Diisi | Catatan |
|---|---|---|---|
| 4.1 | **Akademik › Dosen** | NIDN, Nama, Prodi, Jabatan Fungsional | Buat dulu calon Dekan & Kaprodi |
| 4.2 | **HRIS › Karyawan** | NIP, Nama, Tipe Pegawai | Staff non-dosen |
| 4.3 | **Akademik › Fakultas › Edit** | Pilih **Dekan** dari user dosen | Wajib setelah 4.1 |
| 4.4 | **Akademik › Program Studi › Edit** | Pilih **Kaprodi** dari user dosen | Wajib setelah 4.1 |

> 💡 Saat tambah Dosen/Mahasiswa/Karyawan, **User akun otomatis dibuat** dengan role sesuai.

---

## ✅ LAYER 5 — Kurikulum (15 menit)

**PIC:** Kaprodi

| # | Menu | Yang Diisi | Catatan |
|---|---|---|---|
| 5.1 | **Akademik › Skema Nilai** | A=80-100, B=70-79, dst | Cukup buat sekali, dipakai semua semester |
| 5.2 | **Akademik › Kurikulum** | Kode (KUR-IF-2026), Versi, Prodi, Total SKS | 1 prodi bisa punya banyak versi |
| 5.3 | **Akademik › Mata Kuliah** | Kode (IF1101), Nama, SKS, Semester ke-N, Tipe (Wajib/Pilihan) | Loop untuk semua MK |
| 5.4 | **Akademik › Prasyarat MK** | MK utama → MK syarat, Nilai minimum (C) | Isi setelah semua MK ada |

---

## ✅ LAYER 6 — PMB / Mahasiswa Baru (per orang ±2 menit)

**PIC:** Panitia PMB

| # | Menu | Yang Diisi |
|---|---|---|
| 6.1 | **Akademik › Mahasiswa** | NIM, Nama, Prodi, Kurikulum, Angkatan, Dosen Wali, Jalur Masuk, Golongan UKT |

> 💡 Bisa **Import Excel** untuk batch. Dosen Wali wajib dipilih dari list dosen di Layer 4.

---

## ✅ LAYER 7 — Pre-Semester (per MK ±3 menit)

**PIC:** BAA + Kaprodi

| # | Menu | Yang Diisi | Auto |
|---|---|---|---|
| 7.1 | **Akademik › Penawaran MK** | MK, Semester aktif, Kelas (A/B/C), Dosen Pengampu, Kuota | `enrolled_count`=0 |
| 7.2 | **Penjadwalan › Jadwal** | Penawaran MK, Hari, Jam mulai-akhir, Ruangan, Jumlah pertemuan (14) | — |

---

## ✅ LAYER 8 — Keuangan Pra-Kuliah (15 menit)

**PIC:** Bag. Keuangan

| # | Menu | Yang Diisi |
|---|---|---|
| 8.1 | **Keuangan › Komponen Tagihan** | UKT, Pengembangan, Praktikum, dll |
| 8.2 | **Keuangan › Tarif Tagihan** | Komponen, Prodi, Angkatan, Golongan UKT, Nominal |
| 8.3 | **Keuangan › Invoice** | Mahasiswa, Semester, Virtual Account, Tgl Tagihan, Tgl Jatuh Tempo | |
| 8.4 | **Keuangan › Item Invoice** | Invoice, Komponen, Qty, Amount | **Auto:** `invoice.subtotal` + `total_amount` |

> 🤖 Setelah Anda tambah Item Invoice, sistem otomatis hitung subtotal & total. Tidak perlu input manual di Invoice.

---

## ✅ LAYER 9 — KRS

**PIC:** Mahasiswa (via portal) / Admin Akademik

| # | Menu | Yang Diisi | Auto |
|---|---|---|---|
| 9.1 | **KRS › Enrollment** | Mahasiswa, Semester, Max SKS (default 24), Status (Draft) | |
| 9.2 | **KRS › KRS Item** | Enrollment, Penawaran MK | **Auto:** `course_offering.enrolled_count` ↑, `enrollment.total_sks_taken` ↑ |
| 9.3 | **Akademik › Beban Dosen** | Dosen, Semester, SKS mengajar/wali/riset/pengabdian | |
| 9.4 | **Penjadwalan › Jadwal Ujian** | Penawaran MK, Tipe (UTS/UAS), Tgl, Jam, Ruang | |

---

## ✅ LAYER 10 — Aktivitas Perkuliahan

**PIC:** Dosen + Admin Akademik

| # | Menu | Yang Diisi | Auto |
|---|---|---|---|
| 10.1 | **Penjadwalan › Sesi Kelas** | Jadwal, Pertemuan ke-N, Tgl, Topik | Bisa auto-generate dari Jadwal |
| 10.2 | **Penjadwalan › Presensi** | Sesi Kelas, Mahasiswa, Status (Hadir/Sakit/Izin/Alpa) | |
| 10.3 | **Akademik › Nilai** | Mahasiswa, Penawaran MK, Komponen nilai, Lock (✓) | **Auto:** `transcript` IPS/IPK/status |
| 10.4 | **Akademik › Banding Nilai** | Nilai, Alasan banding | |
| 10.5 | **Keuangan › Pembayaran** | Invoice, Mahasiswa, Amount, Metode, Status=Success | **Auto:** `invoice.paid_amount` + `status`, `payment_number` |

---

## ✅ LAYER 11 — Layanan

**PIC:** Admin Akademik

| # | Menu | Yang Diisi |
|---|---|---|
| 11.1 | **E-Office › Workflow** + **Step Workflow** | Definisikan alur approval (Dosen Wali → Kaprodi → Dekan) |
| 11.2 | **E-Office › Template Surat** | Kode, Nama, Body dengan placeholder, Workflow default |
| 11.3 | **E-Office › Pengajuan Surat** | Mahasiswa pilih template, isi form, submit |
| 11.4 | **E-Office › Approval** | Approver review → Approve/Reject |
| 11.5 | **Keuangan › Beasiswa** | Kode, Nama, Tipe (KIP-K/Prestasi/Yayasan), Cakupan |
| 11.6 | **Keuangan › Penerima Beasiswa** | Beasiswa, Mahasiswa, Semester |
| 11.7 | **Skripsi › Topik Skripsi** | Mahasiswa, Judul, Abstrak, Status |
| 11.8 | **Skripsi › Pembimbing** | Topik, Dosen, Urutan |
| 11.9 | **Skripsi › Logbook** | Topik, Tgl Sesi, Topik Bahasan |
| 11.10 | **Skripsi › Sidang** | Topik, Tipe (Proposal/Hasil/Tutup), Jadwal, Penguji |
| 11.11 | **MBKM › Program MBKM** | Kode, Nama, Tipe (Magang/KKN/Mengajar), SKS |
| 11.12 | **MBKM › Pendaftaran MBKM** | Program, Mahasiswa, Semester |
| 11.13 | **Komunikasi › Template Notifikasi** | Kode (KRS_OPEN), Channel, Subject, Body |
| 11.14 | **Komunikasi › Pengumuman** | Judul, Body, Audience, Channel |

---

## ✅ LAYER 12 — HRIS Operasional

**PIC:** Admin SDM + Keuangan

| # | Menu | Yang Diisi |
|---|---|---|
| 12.1 | **HRIS › Periode Payroll** | Kode (PR-202605), Bulan, Tahun, Cutoff |
| 12.2 | **HRIS › Komponen Gaji** | Gaji Pokok, Tunjangan, BPJS, PPh21 |
| 12.3 | **HRIS › Gaji** | Periode, Karyawan, total dihitung |
| 12.4 | **HRIS › Detail Gaji** | Gaji, Komponen, Nominal |
| 12.5 | **HRIS › Honor Mengajar** | Dosen, Periode, Rate per SKS, Jumlah pertemuan |
| 12.6 | **HRIS › Perhitungan Pajak** | Periode, Karyawan, PTKP, PPh21 YTD |
| 12.7 | **HRIS › Absensi Pegawai** | Karyawan, Tgl, Check-in, Check-out |
| 12.8 | **HRIS › Pengajuan Cuti** | Karyawan, Tipe Cuti, Tgl, Alasan |

---

## 📐 Konvensi Kode (Disarankan)

Konsistensi penamaan kode bantu pencarian & laporan:

| Entitas | Pola | Contoh |
|---|---|---|
| Fakultas | 2-4 huruf | `FT`, `FEB`, `FISIP` |
| Program Studi | 2-4 huruf | `IF`, `SI`, `MNJ`, `AKT` |
| Kurikulum | `KUR-<prodi>-<tahun>` | `KUR-IF-2026` |
| Mata Kuliah | `<prodi><sem><urut>` | `IF1101`, `IF2102` |
| Semester | `<tahun><term>` (1=Ganjil, 2=Genap) | `20261`, `20262` |
| Ruangan | `<gedung><lantai><urut>` | `R301`, `LAB-IF` |
| NIM | `<angkatan><urut5>` | `202600001` |
| NIDN | 10 digit | `0301017001` |
| NIP | 18 digit standar PNS | `198501012010011001` |
| Invoice | `INV-<semester>-<nim>` | `INV-20261-202600001` |
| Payment | `PAY-<yyyymm>-<urut6>` | `PAY-202605-000001` (auto) |
| Refund | `REF-<urut6>` | `REF-000001` |
| Beasiswa | uppercase singkat | `KIPK`, `PRES`, `YYS` |
| Workflow | `WF-<nama>` | `WF-SURAT-AKTIF` |
| Letter Template | `TPL-<nama>` | `TPL-AKTIF`, `TPL-MAGANG` |

---

## ⚠️ Dependency Tree (Visual)

```
LAYER 1 Institusi ───┬─→ LAYER 4 SDM ──┬─→ LAYER 6 Mahasiswa ──┬─→ LAYER 8 Invoice
                     │                  │                       │
                     └─→ LAYER 5 MK ────┤                       │
                                        │                       │
LAYER 2 Semester ─────────────────────────┼──┬──→ LAYER 9 KRS ←─┘
                                          │  │
LAYER 3 Ruangan ──→ LAYER 7 Jadwal ←──────┘  │
                            │                 │
                            └─→ LAYER 10 Sesi/Nilai/Bayar ←──┘
                                        │
                                        ├─→ LAYER 11 Surat/Beasiswa/Skripsi
                                        │
                                        └─→ LAYER 12 Payroll/Honor
```

---

## 🆘 Troubleshooting

| Gejala | Penyebab | Solusi |
|---|---|---|
| Dropdown Prodi kosong saat tambah Dosen | Layer 1 belum diisi | Isi Fakultas + Prodi dulu |
| Dropdown Semester kosong di Penawaran MK | Layer 2 belum, atau `is_active=false` | Centang Semester aktif |
| Mahasiswa tidak lihat MK saat KRS | Penawaran MK belum dibuat (Layer 7) | Buat Penawaran MK di Semester aktif |
| Invoice total = 0 setelah tambah item | Cache | Refresh halaman — Observer auto-update |
| IPS tidak terupdate | Nilai belum di-lock | Centang **is_locked** di form Nilai |
| Course offering enrolled_count tidak naik | Cache | Refresh — Observer otomatis update saat KRS Item dibuat |

---

## 🚀 Setelah Migrate Fresh

```bash
php artisan migrate:fresh --seed
```

Hanya akan membuat:
- ✅ Role + Permission (Shield)
- ✅ Super Admin (`superadmin@univercity.test` / `password`)

Selebihnya **input manual** mengikuti checklist Layer 1-12 di atas.

---

**Update terakhir:** 2026-05-16
