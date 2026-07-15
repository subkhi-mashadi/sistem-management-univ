# 🗺️ FLOW PLAN — Univercity ERP

> **Tujuan:** acuan tunggal urutan implementasi modul. Tiap layer dirilis berurutan,
> jangan lompat. Mark layer sebagai **[DONE]** / **[WIP]** / **[TODO]** seiring progress.
>
> Detail input per menu lihat [FLOW_AKADEMIK.md](FLOW_AKADEMIK.md).

---

## 📊 Status Saat Ini

| Layer | Modul | Status | Bukti |
|---|---|---|---|
| 1 | Institusi (Fakultas, Prodi) | ✅ DONE | DemoFacultySeeder |
| 2 | Periode (Kalender, Semester) | ✅ DONE | DemoAcademicCalendarSeeder |
| 3 | Fasilitas (Ruangan) | ✅ DONE | DemoClassroomSeeder |
| 4 | SDM (Dosen, Pimpinan) | ✅ DONE | DemoLecturerSeeder + LecturerObserver sync |
| 5 | Kurikulum (Kurikulum, MK, Prasyarat, Skema Nilai) | ✅ DONE | DemoCurriculumSeeder |
| 6 | PMB (Mahasiswa) | ✅ DONE | DemoStudentSeeder |
| 7 | Pre-Semester (Penawaran MK, Jadwal) | ✅ DONE | DemoCourseOfferingSeeder |
| 8 | **Keuangan Pra-Kuliah** | ✅ DONE | DemoFinanceSeeder + auto-gen Invoice number + Observer auto-calc |
| 9 | KRS | ✅ DONE | StudentPanel + LecturerPanel + KrsItemObserver + validasi tunggakan/prereq/jadwal |
| 10 | Perkuliahan Aktif (Sesi, Presensi, Nilai) | ✅ DONE | Generate 14 sesi, bulk presensi, bulk nilai + auto letter_grade, Lock → Transcript auto-recalc |
| 11 | Layanan — E-Office (Surat) | ✅ DONE | LetterWorkflowService (submit/approve/reject) + LetterIssuanceService (PDF+QR) + public verify route + Student/Lecturer/Admin panel actions |
| 11 | Layanan — Beasiswa | ✅ DONE | ScholarshipApplicationService (apply/approve/reject) + granted_amount auto-hitung dari coverage + Student panel pengajuan + Admin approve/reject |
| 11 | Layanan — Skripsi (Topik & Logbook) | ✅ DONE | ThesisTopicService (submit/approve/reject/revisi) + LogbookService (create/verify) + Student/Lecturer panel |
| 11 | Layanan — MBKM, Riset, Komunikasi | ⏳ TODO | Model+CRUD resource ada, business logic belum |
| 12 | HRIS Operasional (Payroll, Honor, Pajak) | ⏳ TODO | — |

---

## 🎯 KONSEP TIAP LAYER

### Layer 1 — Institusi
> Pondasi organisasi. Tanpa ini, tidak ada apa-apa.

**Output:** Fakultas, Program Studi
**PIC:** Rektorat / IT Admin
**Dependency:** —

---

### Layer 2 — Periode Akademik
> Penanda waktu. Sistem perlu tahu "sekarang semester apa".

**Output:** Kalender Akademik, Semester (dengan `is_active`)
**PIC:** BAA
**Dependency:** —
**Aturan:** Hanya 1 semester `is_active=true` pada satu waktu.

---

### Layer 3 — Fasilitas
> Tempat fisik untuk kuliah.

**Output:** Ruangan (Kelas/Lab/Studio/Auditorium)
**PIC:** BAU / Sarpras
**Dependency:** —

---

### Layer 4 — SDM
> Pelaku kampus.

**Output:** Dosen + Karyawan, akun User otomatis
**PIC:** Admin SDM
**Dependency:** Layer 1 (Prodi untuk homebase dosen)
**Observer:** [LecturerObserver](../app/Observers/LecturerObserver.php) auto-sync Dekan/Kaprodi → Fakultas/Prodi.

---

### Layer 5 — Kurikulum
> Apa yang diajarkan.

**Output:** Skema Nilai, Kurikulum, Mata Kuliah, Prasyarat MK
**PIC:** Kaprodi
**Dependency:** Layer 1 (Prodi)
**Aturan:** 1 Prodi bisa punya banyak versi Kurikulum (per angkatan).

---

### Layer 6 — PMB (Penerimaan Mahasiswa Baru)
> Calon pelajar masuk sistem.

**Output:** Mahasiswa (assigned ke Prodi + Kurikulum + Dosen Wali + Golongan UKT)
**PIC:** Panitia PMB
**Dependency:** Layer 1 (Prodi), Layer 4 (Dosen wali), Layer 5 (Kurikulum), Golongan UKT

---

### Layer 7 — Pre-Semester
> "Buka kelas" — siapa ngajar apa di mana kapan.

**Output:** Penawaran MK (Course Offering) + Jadwal (Schedule)
**PIC:** BAA + Kaprodi
**Dependency:** Layer 2 (Semester aktif), Layer 3 (Ruangan), Layer 4 (Dosen), Layer 5 (MK)

---

### Layer 8 — Keuangan Pra-Kuliah
> Mahasiswa harus bayar sebelum bisa kuliah.

**Output:**
- Billing Component (UKT, SPP, Pengembangan, Praktikum, KKN, Wisuda)
- Billing Rate (tarif per Prodi × Angkatan × Golongan UKT)
- Invoice (otomatis untuk tiap mahasiswa aktif tiap awal semester)
- Discount, Fine, Refund

**PIC:** Bag. Keuangan
**Dependency:** Layer 1 (Prodi), Layer 2 (Semester), Layer 6 (Mahasiswa)

**Auto-Calc (via [InvoiceItemObserver](../app/Observers/InvoiceItemObserver.php)):**
- `invoice.subtotal` = SUM(items.total)
- `invoice.total_amount` = subtotal + fine - discount - scholarship

**Sub-flow:**
1. Setup Billing Component (sekali setup, dipakai banyak semester)
2. Setup Tarif (matrix Prodi × Angkatan × Golongan UKT × Komponen)
3. **Bulk Generate Invoice** (action: untuk semester aktif, untuk semua mhs aktif)
4. Mahasiswa lihat tagihan + Virtual Account
5. Pembayaran masuk → [PaymentObserver](../app/Observers/PaymentObserver.php) auto-update `invoice.paid_amount` & `status`

---

### Layer 9 — KRS 🟡 NEXT
> Mahasiswa pilih MK yang mau diambil.

**Output:** Enrollment (per mahasiswa per semester) + KrsItem (per MK)
**PIC:** Mahasiswa (panel `/student`) atau Admin Akademik (panel `/admin`)
**Dependency:** Layer 7 (Penawaran MK), Layer 8 (mhs sudah lunas)

**Auto-Calc (via [KrsItemObserver](../app/Observers/KrsItemObserver.php)):**
- `course_offering.enrolled_count` += 1
- `enrollment.total_sks_taken` += MK.total_sks

**Validasi yang Perlu Diimplementasi:**
- Prasyarat MK terpenuhi (sudah lulus prereq)
- Jadwal tidak bentrok dgn MK lain di KRS
- Total SKS ≤ max_sks (default 24)
- Mahasiswa tidak punya tunggakan
- Kuota MK belum penuh

**Sub-flow:** Draft → Submitted → Approved (oleh Dosen Wali) → Locked

---

### Layer 10 — Perkuliahan Aktif
> Eksekusi kuliah.

**Output:**
- ClassSession (14× per Schedule, auto-generate)
- Attendance (presensi per sesi)
- Grade (input nilai dosen)
- Transcript (auto-recalc IPS/IPK/SKS)
- GradeAppeal (banding nilai)
- Payment (pembayaran manual / VA)
- ExamSchedule (UTS/UAS)

**PIC:** Dosen (panel `/lecturer`) + Admin Akademik
**Dependency:** Layer 7 (Jadwal), Layer 8 (Invoice), Layer 9 (KRS)

**Auto-Calc (via [GradeObserver](../app/Observers/GradeObserver.php)):**
- Saat `grade.is_locked = true` → `transcript` ter-update otomatis (IPS, IPK, SKS cumulative, status akademik Normal/Peringatan/DO Risk)

**Action yang Perlu Diimplementasi:**
- Bulk auto-generate ClassSession dari Schedule
- QR code per sesi untuk presensi mahasiswa
- Validasi kehadiran <75% → otomatis nilai E
- Lock semua nilai per kelas

---

### Layer 11 — Layanan
> Pelayanan mahasiswa selama studi.

**Output:**
- **E-Office:** LetterTemplate, Workflow, WorkflowStep, LetterRequest, Approval, LetterArchive, ESignature
- **Beasiswa:** Scholarship, ScholarshipRecipient
- **Skripsi:** ThesisTopic, ThesisAdvisor, Logbook, ThesisDefense
- **MBKM:** MbkmProgram, MbkmEnrollment, SksConversion
- **Riset:** ResearchRepository
- **Komunikasi:** NotificationTemplate, Audience, Announcement, ErpNotification, DeliveryLog

**PIC:** Admin Akademik + Mahasiswa
**Dependency:** Layer 4 (Dosen), Layer 6 (Mahasiswa), Layer 2 (Semester)

**Workflow E-Office:**
```
Mahasiswa ajukan surat
  → Step 1: Dosen Wali approve
  → Step 2: Kaprodi approve
  → Step 3: Dekan approve (opsional)
  → Auto-generate PDF + QR
  → Archive
```

---

### Layer 12 — HRIS Operasional
> Mengurus pegawai & dosen.

**Output:**
- AttendanceRecord (absen pegawai harian)
- LeaveRequest (cuti)
- EmploymentHistory (mutasi/promosi)
- PayrollPeriod (periode payroll bulanan)
- SalaryComponent (Gaji Pokok, Tunjangan, BPJS, PPh21, dll)
- Salary + SalaryDetail (gaji per pegawai per periode)
- TeachingHonor (honor mengajar dosen per pertemuan/SKS)
- TaxCalculation (PPh21 YTD)

**PIC:** Admin SDM + Bag. Keuangan
**Dependency:** Layer 4 (Karyawan), Layer 10 (ClassSession untuk hitung honor)

---

## 🚦 PRIORITAS BERIKUTNYA

### Sprint A: Layer 8 — Keuangan (1 minggu)
- [ ] Demo seeder: BillingComponent (UKT, SPP, Pengembangan, Praktikum, KKN, Wisuda)
- [ ] Demo seeder: BillingRate (matrix tarif)
- [ ] Action di SemesterResource: "Generate Invoice Masal" → bulk create Invoice untuk semua mhs aktif
- [ ] Form PaymentResource yang mudah (pilih invoice → auto-isi student/amount)
- [ ] Test: Invoice tergenerate, mhs lihat tagihan, bayar, status auto-update

### Sprint B: Layer 9 — KRS Workflow (1-2 minggu)
- [ ] StudentPanelProvider (`/student`) — panel khusus mahasiswa
- [ ] EnrollmentResource di panel student (read+create+edit own KRS)
- [ ] Validasi prasyarat + bentrok jadwal + max SKS + lunas
- [ ] LecturerPanelProvider (`/lecturer`) untuk approval KRS
- [ ] Action: Submit, Approve, Reject, Lock

### Sprint C: Layer 10 — Perkuliahan (1-2 minggu)
- [ ] Action: Auto-generate ClassSession dari Schedule (14× per jadwal)
- [ ] Form Attendance bulk (tandai 30 mhs hadir dalam 1 klik)
- [ ] Form Grade bulk per kelas (input nilai 30 mhs dalam 1 layar)
- [ ] Test: Lock nilai → Transcript auto-recalc (verifikasi GradeObserver jalan)

### Sprint D: Layer 11 — Layanan (1-2 minggu)
- [ ] LetterTemplate dengan placeholder dinamis (`{{nama}}`, `{{nim}}`)
- [ ] Workflow engine (multi-step approval)
- [ ] PDF generator + QR code verifikasi publik
- [ ] Scholarship + auto-deduct invoice
- [ ] Thesis flow (proposal → sidang → upload repo)

### Sprint E: Layer 12 — Payroll (1 minggu)
- [ ] Action: Generate Payroll periode (loop karyawan, hitung gaji)
- [ ] Action: Hitung TeachingHonor dari ClassSession yang sudah Conducted
- [ ] PPh21 calculator (PTKP, tarif berlapis)

---

## 🧰 INFRASTRUKTUR LINTAS LAYER

| Komponen | Status | Catatan |
|---|---|---|
| RBAC (Shield) + 12 Role | ✅ DONE | RolePermissionSeeder |
| Audit Log (Spatie Activitylog) | ✅ DONE | HasAuditable trait |
| Soft Delete | ✅ DONE | Semua model resource |
| Auto Code Generation | 🟡 PARTIAL | Sudah: UktGroup (GL-001), Payment (PAY-YYMM-XXXXXX). Belum: Fakultas, Prodi, Kurikulum, MK, NIM, NIDN, NIP, Invoice, Refund, Letter |
| Observer Auto-Calc | ✅ DONE | InvoiceItem, Payment, KrsItem, Grade, Lecturer, Faculty, StudyProgram |
| Filament Navigation Group | ✅ PARTIAL | Akademik, Keuangan sudah |
| Notifikasi Multi-Channel | ⏳ TODO | Sprint D |
| Dashboard per Role | ⏳ TODO | Setelah semua layer selesai |
| Student & Lecturer Portal | ✅ DONE | Sprint B — StudentPanel + LecturerPanel |
| PDDikti Integration | ⏳ FUTURE | Post-MVP |

---

## 📐 KONVENSI KODE

| Entitas | Pola | Status |
|---|---|---|
| Fakultas | 2-5 huruf (FT, FEB) | Manual |
| Prodi | 2-4 huruf (IF, SI, AKT) | Manual |
| Kurikulum | KUR-{prodi}-{tahun} | Manual |
| Mata Kuliah | {prodi}{sem}{urut} (IF1101) | Manual |
| Semester | {tahun}{term} (20261, 20262) | Manual |
| Ruangan | bebas (R301, LAB-IF1) | Manual |
| NIM | {tahun}{urut5} (202600001) | Manual |
| NIDN | 10 digit | Manual |
| Invoice | INV-{semester}-{nim} | Manual |
| Payment | PAY-{YYMM}-{urut6} | ✅ Auto |
| Refund | REF-{urut6} | Manual |
| Golongan UKT | GL-{urut3} (GL-001) | ✅ Auto |

---

## 🆘 GLOSARIUM SINGKAT

| Istilah | Arti |
|---|---|
| **Kurikulum** | Kerangka studi 144 SKS / 8 semester per Prodi |
| **MK** | Mata Kuliah, dimiliki Kurikulum |
| **Penawaran MK** | Instance MK yang dibuka di semester tertentu (1 MK bisa multiple kelas) |
| **Jadwal** | Slot waktu + ruangan untuk Penawaran MK |
| **ClassSession** | 1× pertemuan dari Jadwal (14× per semester) |
| **Enrollment** | "KRS" mahasiswa per semester (header) |
| **KrsItem** | 1 baris MK di KRS |
| **Transcript** | Rekap nilai mahasiswa per semester (IPS/IPK) |
| **Workload** | Beban kerja dosen per semester (SKS mengajar/wali/riset/pengabdian) |

---

**Source of truth:** dokumen ini.
**Update saat:** mulai/selesai sprint, ada perubahan dependency, ada modul baru.
**Update terakhir:** 2026-06-11
