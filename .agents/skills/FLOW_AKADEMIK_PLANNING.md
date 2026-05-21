# 🎓 Planning Implementasi Flow Akademik

> **Status:** Lanjutan dari `ERP_UNIVERSITAS_PLANNING_PROMPT.md`. Fondasi sudah terbangun (RBAC, 64 model+resource, auto-create user, import Excel). Dokumen ini fokus pada **otomasi & workflow** agar akademik bisa berjalan operasional.

---

## 🎯 Tujuan

Membuat sistem yang **otonom mengurus siklus semester**: dari pembukaan KRS sampai input nilai akhir, dengan minimal intervensi admin.

---

## 📊 Status Saat Ini

| Komponen | Status |
|---|---|
| Master data CRUD (64 resource) | ✅ |
| RBAC + 12 role + 840 permission (Shield) | ✅ |
| Auto-create User saat tambah Dosen/Mahasiswa | ✅ |
| Excel Import untuk 8 master akademik | ✅ |
| Auto-generate kode (FAK-2026-0001 dst) | ✅ |
| Audit log Spatie (created_by/updated_by auto) | ✅ |
| Form Indonesian + Section consistent | ✅ |
| **KRS Workflow** (submit→approve→lock) | ❌ |
| **Grading Flow** (input→lock→transcript) | ❌ |
| **Attendance Recording** | ❌ |
| **Auto-generate Class Sessions** | ❌ |
| **Bulk Invoice Generator** | ❌ |
| **Payment Flow + VA** | ❌ |
| **E-Office Workflow** (surat→approval→e-sign) | ❌ |
| **Dashboard per Role** | ❌ |
| **Student & Lecturer Portal** | ❌ |
| **PDDikti Integration** | ❌ |

---

## 🗺️ Roadmap Sprint-by-Sprint

### Sprint 1 — Auto-Generate Sesi Kelas (2-3 hari)
**Goal:** Setelah jadwal dibuat, otomatis bikin 14 sesi pertemuan per kelas.

**Tugas:**
1. Action `GenerateSessionsAction` di Schedule resource:
   - Input: tanggal mulai semester, jumlah pertemuan (default 14), exclude tanggal libur (dari kalender akademik).
   - Loop dari `lecture_start` semester sampai 14 pertemuan terisi, skip Minggu/libur.
2. Listener `Schedule::created` → trigger generate otomatis.
3. Manual regenerate via bulk action.

**Acceptance:** Jadwal "Senin 08:00-10:30 di R301" → 14 ClassSession terbentuk tanggal Senin berurutan.

---

### Sprint 2 — KRS Workflow (1 minggu)

**Goal:** Mahasiswa pilih MK → submit → Dosen Wali approve.

**Tugas:**
1. **Filament Panel Mahasiswa** (`/student`):
   - Setup `StudentPanelProvider` (path: `student`)
   - Login dengan role `Mahasiswa`
   - Resource: KRS (read+create+edit own)
2. **EnrollmentResource (mahasiswa side):**
   - Form: pilih MK dari `course_offerings` semester aktif, filter kurikulum mahasiswa
   - Validasi:
     - MK belum pernah lulus
     - Prasyarat terpenuhi (cek `prerequisites`)
     - Jadwal tidak bentrok dengan MK lain di KRS
     - Total SKS ≤ max_sks (default 24, bisa override)
     - Tunggakan pembayaran = 0
   - Status: Draft → Submitted
3. **Dosen Wali approval:**
   - Filter `Enrollment::where('status', 'Submitted')` di mana student.academic_advisor_id = auth user lecturer
   - Action: Approve / Reject + notes
   - Status: Submitted → Approved → Locked (mahasiswa tidak bisa edit lagi)
4. **Notification:** mahasiswa dapat WA/email saat di-approve/reject.

**Acceptance:** Mahasiswa bisa submit 18 SKS, dosen wali approve dalam 1 klik, KRS lock.

---

### Sprint 3 — Attendance Recording (3-4 hari)

**Goal:** Dosen input presensi per sesi.

**Tugas:**
1. **Filament Panel Dosen** (`/lecturer`).
2. **Class Session detail page** untuk dosen:
   - Tampilkan list mahasiswa di KRS aktif untuk MK ini.
   - Bulk toggle: Hadir / Sakit / Izin / Alpa / Terlambat.
   - QR code per sesi (mahasiswa scan untuk absen mandiri).
   - Update `class_session.status` → Conducted setelah disubmit.
3. **Mahasiswa absen mandiri (panel student):**
   - Scan QR / input kode → record `Attendance`.
   - Validasi: lokasi (geofence ≤ 50m dari kampus), waktu (dalam jam kelas).
4. **Rekap presensi** (admin akademik & mahasiswa):
   - % kehadiran per MK.
   - Highlight mahasiswa <75% (auto-disqualify UAS).

**Acceptance:** Dosen tandai 30 mahasiswa hadir dalam 1 layar. Mahasiswa scan QR → presensi tercatat.

---

### Sprint 4 — Input Nilai & Auto-Transkrip (1 minggu)

**Goal:** Dosen input nilai → lock → IPS/IPK update otomatis.

**Tugas:**
1. **Grade input page (panel dosen):**
   - List mahasiswa per MK.
   - Kolom: Tugas, UTS, UAS, Kehadiran (auto dari attendance %), Tambahan.
   - Auto-hitung total_score = bobot × komponen (bobot configurable per MK).
   - Auto-convert ke huruf via `grade_schemas` (80-100=A dst).
   - Validasi: kehadiran <75% → otomatis E.
2. **Lock & Submit:**
   - Dosen review → klik "Submit Final" → grades.is_locked=true.
   - Setelah lock, edit perlu izin Kaprodi (GradeAppeal flow).
3. **Auto-recalculate Transcript:**
   - Listener `Grade::saved` (jika is_locked → recalc transcripts).
   - IPS = sum(grade_point × sks) / sum(sks) per semester.
   - IPK kumulatif dari semua transcripts.
4. **Akademik Status auto-update:**
   - IPK <2.0 → "Peringatan"
   - IPK <1.5 setelah 2 semester → "DO Risk".
5. **Mahasiswa lihat nilai (panel student).**

**Acceptance:** Dosen input 1 MK (30 mahasiswa) selesai <5 menit. IPS+IPK ter-update otomatis di transkrip mahasiswa.

---

### Sprint 5 — Bulk Invoice Generator (3-4 hari)

**Goal:** Generate invoice masal untuk semua mahasiswa aktif tiap awal semester.

**Tugas:**
1. **Action `GenerateInvoicesAction` di Semester resource:**
   - Input: semester target, komponen tagihan yang di-include (UKT, SPP, dll).
   - Queue job: loop mahasiswa aktif × cari billing_rate sesuai prodi+angkatan+ukt_group → bikin Invoice + InvoiceItems.
   - Auto-apply beasiswa aktif → kurangi total.
   - Batch progress di Filament notification.
2. **Reminder otomatis** (Scheduled):
   - H-7 dari due_date → notif WA/email.
   - H+1 setelah due_date → status Overdue + auto-fine.
3. **Mahasiswa lihat invoice** (panel student): list invoice, klik untuk lihat VA.

**Acceptance:** 1000 mahasiswa → 1000 invoice tergenerate <2 menit. Beasiswa auto-deduct.

---

### Sprint 6 — Payment Flow (Manual Reconciliation) (3 hari)

**Goal:** Admin Keuangan rekonsiliasi manual dari rekening koran bank.

**Tugas:**
1. **PaymentResource form:** Pilih invoice, isi jumlah, metode (Transfer/VA/Cash), bank reference, tanggal.
2. **Auto-update invoice:**
   - Listener `Payment::created` (status=Success) → invoice.paid_amount += amount.
   - Jika paid_amount >= total → invoice.status = Paid.
3. **Import payment dari rekening koran** (Excel/CSV):
   - PaymentImporter dengan kolom: VA, amount, tanggal, ref.
   - Matching otomatis ke invoice via VA.
4. **Bukti pembayaran** (PDF) auto-generate.

**Acceptance:** Import 500 baris rekening → 500 invoice ter-update statusnya.

> Note: Webhook bank real-time = Sprint nanti (perlu integrasi vendor).

---

### Sprint 7 — E-Office Workflow (1 minggu)

**Goal:** Mahasiswa ajukan surat → workflow approval digital → PDF + QR.

**Tugas:**
1. **LetterTemplate management** (admin):
   - Buat template dengan placeholder `{{nama}}`, `{{nim}}`, `{{semester}}`, dll.
   - Pilih default workflow (e.g., Dosen Wali → Kaprodi → Dekan).
2. **LetterRequest form (panel student):**
   - Pilih jenis surat → isi form sesuai template fields.
   - Submit → status: In Progress, set current_step ke step 1.
3. **Approval action (panel approver):**
   - List letter_requests di mana current_step.approver_role = my role.
   - Approve → maju ke step berikutnya. Reject → kembali ke pengaju.
   - Step terakhir → generate PDF (DomPDF) + QR code + tanda tangan digital (mock dulu, BSrE nanti).
4. **Verifikasi publik:** `/verify/{qr_code}` → tampilkan ringkasan surat (tanpa login).
5. **SLA tracker:** highlight surat tertahan >3 hari.

**Acceptance:** Mahasiswa ajukan Surat Aktif Kuliah → 3 approval → PDF ber-QR siap download.

---

### Sprint 8 — Dashboard per Role (3-4 hari)

**Goal:** Landing page sesuai role, KPI relevan.

**Tugas:**
1. **Super Admin / IT Admin:** Total user, mahasiswa aktif per prodi, dosen aktif, jumlah role, recent activity log.
2. **Rektor / Wakil Rektor:** Mahasiswa aktif, IPK rata-rata, rasio dosen-mhs, tunggakan total, kelulusan, PMB.
3. **Dekan:** Sama tapi filter fakultas sendiri.
4. **Kaprodi:** Filter prodi, list mahasiswa peringatan/DO risk, draft KRS pending.
5. **Dosen:** MK diampu semester ini, jadwal hari ini, KRS walian pending, nilai belum di-input.
6. **Admin Akademik:** KRS submitted (untuk monitoring), surat pending, kalender berikutnya.
7. **Admin Keuangan:** Total tagihan, terbayar, piutang, top 10 penunggak.
8. **Mahasiswa:** IPS terakhir, IPK, tagihan jatuh tempo, surat in-progress, nilai semester lalu.

Implement via Filament Widgets. Pakai package `filament/widgets` (sudah built-in v4).

**Acceptance:** Login berdasar role → dashboard relevan tampil tanpa klik.

---

### Sprint 9 — Student & Lecturer Panel (1 minggu)

**Goal:** Panel khusus mahasiswa & dosen (terpisah dari admin).

**Tugas:**
1. **`/student` panel** (StudentPanelProvider):
   - Menu: Dashboard, Profil, KRS, Nilai, Transkrip, Tagihan, Surat, Skripsi.
   - Theme color berbeda (biru).
   - Brand: nama universitas + logo.
2. **`/lecturer` panel** (LecturerPanelProvider):
   - Menu: Dashboard, MK Diampu, Jadwal Hari Ini, Presensi, Input Nilai, Mhs Bimbingan, BKD, Surat-saya.
   - Theme color: hijau.
3. **Logout redirect** sesuai panel.
4. **Auth middleware:** mahasiswa tidak bisa akses /admin, dst.

**Acceptance:** Login dgn role mahasiswa → otomatis ke `/student`. Login dgn role dosen → ke `/lecturer`. Akses /admin → 403.

---

### Sprint 10 — Skripsi & Bimbingan (1 minggu)

**Goal:** Siklus penuh Tugas Akhir.

**Tugas:**
1. **Pengajuan judul (panel student):**
   - Form: judul, abstrak, kata kunci, bidang.
   - Similarity check vs `thesis_topics` & `research_repository` (TF-IDF sederhana).
2. **Matching pembimbing:**
   - Algoritma: cocokkan kata kunci skripsi dgn `lecturer.expertise_keywords`, urutkan by beban (max 10 mhs/dosen).
   - Kaprodi assign 1-2 pembimbing.
3. **Logbook digital:** mahasiswa input sesi bimbingan, dosen verifikasi.
4. **Eligibility check sidang:**
   - Min 8x bimbingan verified
   - SKS terkumpul ≥ syarat
   - Lunas tagihan
   - Lulus semua MK wajib
5. **Sidang scheduler & input nilai sidang.**
6. **Auto-upload ke `research_repository`** setelah lulus.

**Acceptance:** Mahasiswa siap sidang otomatis lolos eligibility tanpa cek manual.

---

### Sprint 11 — Notifikasi Center & Pengumuman (3-4 hari)

**Goal:** Multi-channel notif (Email + WA + In-App).

**Tugas:**
1. **Setup queue worker** untuk send notif async.
2. **Template events** (KRS_OPEN, GRADE_RELEASED, BILL_ISSUED, LETTER_DONE).
3. **Audience segmentation** di Announcement (filter JSON: role+prodi+angkatan+status).
4. **Provider abstraction** (interface): Email (SendGrid/SES), WA (Meta API mock dulu).
5. **In-app notif bell** di Filament topbar.

**Acceptance:** Buka KRS → 1000 mahasiswa dapat WA "KRS dibuka".

---

### Sprint 12 — Polish, Performance, Security (1 minggu)

1. Activity log viewer + filter advanced.
2. Slow query log → tambah index sesuai workload.
3. Redis cache untuk dashboard widget.
4. Backup automation (Spatie Backup) — daily ke S3.
5. Rate limiting API.
6. Pen-test OWASP ZAP scan.
7. User manual per modul (markdown).

---

## 🚦 Urutan Prioritas (Critical Path)

```
Sprint 1 (Sesi otomatis)
  ↓
Sprint 2 (KRS) ←─────────── Sprint 9 (Panel Student & Lecturer)
  ↓                              │
Sprint 3 (Presensi)              │
  ↓                              │
Sprint 4 (Nilai + Transkrip) ────┘
  ↓
Sprint 5 (Invoice) → Sprint 6 (Payment)
  ↓
Sprint 7 (E-Office) → Sprint 10 (Skripsi)
  ↓
Sprint 8 (Dashboard) → Sprint 11 (Notif) → Sprint 12 (Polish)
```

**Critical path:** Sprint 1→2→3→4 = 1 semester berjalan. Estimasi **3-4 minggu** untuk MVP operasional.

---

## 📋 Setup Awal Sebelum Sprint 1

Yang harus disiapkan admin (data dummy untuk testing):

```
1. /admin/users → buat 1 user untuk masing-masing role
2. /admin/faculties → 1 Fakultas Teknik
3. /admin/study-programs → 1 Prodi Informatika (Fakultas Teknik, S1)
4. /admin/curricula → Kurikulum 2026 v1, total 144 SKS
5. /admin/courses → 6 MK semester 1 (PBO, Kalkulus, Bhs Indonesia, dll)
6. /admin/lecturers → 5 dosen, 1 dgn structural_position=Dekan
7. /admin/faculties → edit Fakultas → pilih dekan
8. /admin/students → 30 mahasiswa (atau import Excel)
9. /admin/classrooms → 3 ruang
10. /admin/academic-calendars → 2026/2027
11. /admin/semesters → Ganjil 2026 dengan tanggal lengkap, is_active=true
12. /admin/course-offerings → tawarkan 6 MK semester ini
13. /admin/schedules → jadwalkan 6 MK ke 6 slot waktu/ruang
14. Sprint 1: auto-generate 6×14 = 84 ClassSession
```

---

## 🧪 Acceptance per Phase

| Phase | Acceptance Criteria |
|---|---|
| Master ready | 30 mahasiswa, 5 dosen, 6 MK, 6 jadwal |
| Sprint 1-2 done | Mahasiswa bisa submit KRS, dosen wali approve |
| Sprint 3 done | Dosen mark 30 mahasiswa hadir per sesi |
| Sprint 4 done | Nilai 1 MK selesai, IPS auto-recalc |
| Sprint 5-6 done | Invoice masal + rekonsiliasi import |
| Sprint 7 done | Surat keluar dgn QR |
| Sprint 8-9 done | Dashboard per role + panel terpisah |
| MVP READY | 1 semester penuh bisa dijalankan end-to-end |

---

## 🛠️ Tooling yang Sudah Tersedia

- ✅ Spatie Permission (RBAC) + Shield UI
- ✅ Spatie Activitylog (audit)
- ✅ Spatie Media Library (file upload)
- ✅ Spatie Backup
- ✅ Maatwebsite Excel
- ✅ Laravel DomPDF
- ✅ simplesoftwareio/simple-qrcode
- ✅ Filament v4 (panel + importer + media plugin)

**Belum dipasang (akan ditambah saat dibutuhkan):**
- Laravel Horizon (queue UI) — Sprint 11
- WhatsApp provider (Meta Cloud / Onesender) — Sprint 11
- BSrE / PrivyID — Sprint 7 (mock dulu)
- Bank VA API (BNI/BSI/Mandiri) — post-MVP

---

## 📝 Convention untuk Setiap Sprint

1. **Migration first** jika butuh kolom baru.
2. **Eloquent observer/event** untuk business rules (lock setelah submit, recalc transcript, dll).
3. **Filament Action** untuk operasi 1-klik (Generate Sessions, Send Reminders).
4. **Queue Job** untuk operasi bulk (bulk invoice, bulk notification).
5. **Test Pest** untuk happy path setiap workflow.
6. **Pint format** sebelum commit.

---

**Next step:** mulai dari Sprint 1 (auto-generate ClassSession dari Schedule). Bilang **"mulai sprint 1"** untuk eksekusi.
