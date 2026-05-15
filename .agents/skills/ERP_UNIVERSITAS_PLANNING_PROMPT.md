# 🎓 Planning Prompt — ERP Universitas (Management Dashboard Back-Office)

> **Sumber:** PRD · ERD · System Analyst Requirements v1.0 (Mei 2026, 69 halaman, Status: Draft Final)
>
> **Scope:** Management Dashboard Back-Office untuk universitas. Front-end mahasiswa & dosen di luar scope, tapi sistem ini **menyediakan API** yang dikonsumsi front-end tersebut.
>
> **Tech Stack (sesuai keputusan):** Laravel 11 + Blade + MySQL 8 + Redis + TailwindCSS
> **Alternatif yang direkomendasikan dokumen:** PostgreSQL 16, tapi karena Anda pilih Laravel + MySQL, planning ini mengikuti pilihan Anda.

---

## 📑 Daftar Isi Planning

1. Konteks & Tujuan Sistem
2. Stakeholder & Persona (7 role)
3. 9 Modul Fungsional (overview + scope per modul)
4. Arsitektur Sistem
5. Database Schema (67+ tabel di 9 domain)
6. Routing & API Structure
7. Non-Functional Requirements
8. Integrasi Eksternal (7 integrasi)
9. 5 Workflow Bisnis Utama
10. Roadmap Implementasi (4 fase, 18-24 bulan)
11. Testing Strategy
12. Sprint-by-Sprint Task untuk AI Coding Assistant

---

## 1. 🎯 Konteks & Tujuan Sistem

**Masalah yang dipecahkan:** sistem kampus yang berjalan silo (keuangan, akademik, SDM terpisah) → duplikasi data, laporan tidak akurat, antrean loket, rawan manipulasi.

**5 Tujuan Utama:**
1. **Efisiensi operasional** — kurangi waktu administratif hingga 70% via otomasi
2. **Akurasi data** — single source of truth, hilangkan duplikasi
3. **Keamanan & akuntabilitas** — audit trail penuh untuk data kritikal (nilai, keuangan, status mahasiswa)
4. **Compliance** — laporan PDDikti & akreditasi BAN-PT/LAM
5. **Pengambilan keputusan** — dashboard analitik real-time

---

## 2. 👥 Stakeholder & Persona

| Role | Kepentingan Utama |
|---|---|
| **Rektor** | Executive dashboard, KPI, drill-down makro→detail, akses mobile |
| **Wakil Rektor** | Laporan per bidang (akademik/keuangan/SDM) |
| **Dekan** | Statistik fakultas, persetujuan dokumen |
| **Kaprodi** | Kurikulum, plotting dosen, monitoring mahasiswa, approve surat |
| **Dosen** | Input nilai, BKD, bimbingan skripsi |
| **Admin Akademik** | Penjadwalan, KRS, presensi, surat |
| **Admin Keuangan** | Invoice, rekonsiliasi, beasiswa |
| **Admin SDM** | Payroll, presensi, kepegawaian |
| **IT Admin** | User management, backup, monitoring, integrasi |

**Pain points lintas role (dari persona dokumen):**
- Laporan dari biro berbeda format & sering terlambat (Rektor)
- Plotting dosen manual via Excel, jadwal bentrok, surat menumpuk (Kaprodi)
- Manual matching pembayaran bank ↔ data mahasiswa (Admin Keuangan)
- Antrean panjang surat mahasiswa (Admin Akademik)
- Banyak request reset password manual (IT Admin)

---

## 3. 🧩 9 Modul Fungsional

### Modul 1 — Manajemen User & Hak Akses (RBAC)
**Inti:** Authentication + Authorization fondasi sistem.

**Fitur kritikal:**
- 9 role bawaan + custom role
- Permission matrix granular: module × action (view/create/edit/delete/approve/export)
- Audit log immutable (user_id, IP, timestamp, before-after, modul, aksi)
- Bulk import user dari Excel/CSV → auto-generate username & password → kirim via email
- **MFA wajib** untuk: IT Admin, Rektor, Dekan
- Auto-logout idle 30 menit
- Password policy: min 8 char, kombinasi, expired 90 hari, lock 15 menit after 5 failed attempts

**Business Rules kritikal:**
- BR-RBAC-01: Audit log **tidak dapat dihapus siapa pun**, archive setelah 2 tahun
- BR-RBAC-02: Super Admin maksimal 2 akun aktif
- BR-RBAC-04: Perubahan permission role butuh **approval kedua dari Super Admin lain**

### Modul 2 — Core Akademik (Master Data)
**Inti:** Jantung sistem akademik. Master data referensi semua modul lain.

**Fitur kritikal:**
- Hierarki: Universitas → Fakultas → Prodi → Konsentrasi
- Kurikulum **bervers**i (mahasiswa angkatan lama tetap pakai kurikulum lama)
- Prasyarat MK berjenjang dengan **AND/OR logic** (Kalkulus 2 wajib lulus Kalkulus 1 min C)
- Plotting dosen dengan dukungan **team-teaching** (>1 dosen per MK)
- Master ruangan: kode, gedung, lantai, kapasitas, jenis (kelas/lab/studio), fasilitas (JSON)
- Kalender akademik per tahun: KRS, kuliah, UTS, UAS, minggu tenang, input nilai, libur
- Skema konversi nilai (80-100 = A = 4.0) → **mendukung skema berbeda per fakultas**

**Business Rules kritikal:**
- BR-ACA-01: Kurikulum yang sudah dipakai ≥1 mahasiswa → **hanya boleh arsipkan**, tidak dihapus
- BR-ACA-02: MK yang sudah pernah dapat nilai → kode & SKS-nya **tidak dapat diubah**
- BR-ACA-03: Kalender akademik aktif hanya bisa diubah dengan approval Wakil Rektor I

### Modul 3 — Penjadwalan Otomatis (Scheduling Engine)
**Inti:** Algoritma constraint-satisfaction untuk generate jadwal tanpa bentrok.

**Fitur kritikal:**
- Auto-scheduler (genetic algorithm / constraint propagation): mempertimbangkan ketersediaan dosen, kapasitas ruang, jenis MK, prasyarat fasilitas, jumlah mahasiswa
- Real-time conflict detection saat adjustment manual
- Multi-channel presensi: QR/barcode, biometrik, manual
- **Auto-lock UAS** jika kehadiran < 75%
- BKD auto-calc (mengajar + bimbingan + penelitian + pengabdian + tugas tambahan)
- Sub-engine khusus jadwal UTS/UAS (mahasiswa tidak 2 ujian bersamaan, jeda min 1 jam)

**Business Rules:**
- Default: 50 menit/SKS teori, 100 menit/SKS praktikum
- Minimum kehadiran UAS: 75% (configurable)
- BKD min: 12 SKS, max: 16 SKS per semester
- Acceptance: jadwal 200+ MK selesai < 5 menit, conflict rate < 1%

### Modul 4 — Keuangan & Billing (Smart Finance)
**Inti:** Siklus tagihan: invoice → VA → pembayaran → rekonsiliasi → beasiswa → laporan.

**Fitur kritikal:**
- Master komponen tagihan: UKT, SPP, biaya pengembangan, praktikum, KKN, wisuda, denda
- **Rate per prodi × angkatan × UKT group (1-8)**
- Invoice generator massal (target: 5000 invoice < 10 menit)
- Skema cicilan: full / 2x / 3x
- Webhook bank VA: BNI, Mandiri, BCA, BSI, BRI
- Daily auto-reconciliation H+1 jam 00:01 WIB
- Master beasiswa: KIP-K, Bidikmisi, Prestasi, Anak Pegawai → auto-deduct
- Denda otomatis (flat / % per hari)
- **Auto-block akses KRS** jika tunggakan > X hari
- Refund workflow untuk cuti/mengundurkan diri
- Audit trail keuangan: setiap pembebasan denda/diskon manual tercatat siapa approve

**Business Rules kritikal:**
- BR-FIN-01: Invoice lunas **tidak boleh diubah/dihapus** → koreksi via refund
- BR-FIN-02: Pembebasan denda manual butuh approval min Manajer Keuangan
- BR-FIN-03: Beasiswa apply ke **tagihan pokok dulu**, sisa baru ke denda
- BR-FIN-04: Mahasiswa cuti **tidak menerima tagihan UKT** (kecuali admin cuti)

### Modul 5 — Layanan Mandiri & Persuratan (E-Office)
**Inti:** Hilangkan antrean loket. Surat online + workflow approval digital.

**Fitur kritikal:**
- Template surat repository (Cuti, Aktif Kuliah, Pengantar Magang, Rekomendasi, Yudisium)
- **Visual workflow designer** (admin bisa buat workflow baru tanpa coding)
- Approval one-click via dashboard / email / push notification
- E-Signature sesuai UU ITE → integrasi BSrE
- Auto-numbering pola: `001/UN.x/FT/2026`
- **QR Code validation** di tiap surat (link verifikasi publik tanpa login)
- SLA tracking + notifikasi otomatis jika tertahan > 3 hari
- **Yudisium engine** validasi otomatis: total SKS, IPK min, MK wajib, lunas keuangan, skripsi, TOEFL, magang
- Arsip digital dengan metadata, searchable full-text

**Business Rules:**
- BR-EOF-01: Surat sudah ditandatangani digital → tidak dapat diubah, hanya dibatalkan via surat pembatalan baru
- BR-EOF-02: QR validation aktif **5 tahun** setelah penerbitan
- Acceptance: surat workflow standar selesai < 2 hari kerja

### Modul 6 — Tugas Akhir, Skripsi & MBKM
**Inti:** Siklus penuh TA: pengajuan judul → pembimbing → bimbingan → sidang → repository.

**Fitur kritikal:**
- Pengajuan judul + **similarity check anti-duplikat** (detect ≥70%)
- Algoritma matching mahasiswa↔pembimbing: keyword expertise + beban dosen + topik
- Logbook digital bimbingan
- Eligibility check otomatis untuk pengajuan sidang
- Mini-scheduler khusus sidang
- Multi-penilai dengan agregasi otomatis
- MBKM: tabel ekuivalensi SKS by Kaprodi, mendukung 20+ SKS/semester
- Plagiarism check (Turnitin / Plagscan) threshold ≤25%
- Repository karya ilmiah dengan review perpus sebelum publish

**Business Rules:**
- BR-TA-01: Wajib **min 8x bimbingan** tercatat sebelum sidang
- BR-TA-02: Max **10 mahasiswa bimbingan** per dosen per semester
- BR-TA-03: Skripsi lulus wajib upload ke repository dalam 30 hari

### Modul 7 — Komunikasi & Notifikasi Center
**Inti:** Pusat distribusi pengumuman multi-channel.

**Fitur kritikal:**
- Audience segmentation: role × fakultas × prodi × angkatan × status + filter custom (IPK<2.0)
- Compose rich text + lampiran + schedule send
- **Omnichannel**: Email + WhatsApp + Push + In-app inbox (single message)
- WhatsApp Business API (template approved Meta)
- Template event-based: KRS_OPEN, BILL_ISSUED, GRADE_RELEASED, LETTER_DONE
- Delivery tracking per channel (sent/delivered/read/failed)
- Opt-in/opt-out preferensi (kecuali mandatory)
- **Emergency broadcast mode** (bypass preferensi)

**Business Rules:**
- BR-NOT-01: Blast > 1000 penerima → approval atasan
- BR-NOT-02: WA dibatasi max 1000 pesan/jam (hindari spam ban)
- Acceptance: delivery rate WA & email > 95%, latency < 2 menit

### Modul 8 — SDM & Payroll (HRIS)
**Inti:** Kepegawaian + presensi + penggajian terintegrasi dengan akademik.

**Fitur kritikal:**
- Master pegawai: NIDN/NIP, biodata, jabatan fungsional+struktural, golongan, status (PNS/Tetap/Kontrak/Honorer)
- E-Presensi multi-channel: fingerprint, QR, face recognition, mobile geolocation
- Shift master + jadwal kerja
- Cuti workflow (tahunan/sakit/besar) + saldo otomatis
- Payroll engine: gaji pokok + tunjangan + **honor mengajar (dari modul akademik)** - BPJS - PPh21 - iuran
- Auto-calc honor: pertemuan terlaksana × tarif per SKS sesuai jabatan
- Slip gaji PDF dengan TTE → email
- PPh21 auto + generate 1721-A1
- Integrasi BKD ke SISTER/PDDikti

**Business Rules:**
- BR-HRIS-01: Payroll yang sudah closing **tidak bisa diubah** → koreksi via adjustment bulan berikutnya
- BR-HRIS-02: Honor mengajar dihitung dari pertemuan yang sudah ditandai 'hadir'
- BR-HRIS-03: Cuti tahunan max 12 hari, carry-over max 6 hari
- Acceptance: payroll 500 pegawai < 5 menit, akurasi PPh21 100%

### Modul 9 — Integrasi & Pelaporan (Compliance)
**Inti:** Integrasi pemerintah + dashboard analitik strategis.

**Fitur kritikal:**
- **PDDikti Feeder**: sinkronisasi Mahasiswa, Dosen, Aktivitas Mengajar, AKM, Lulusan (push & pull)
- SISTER integration untuk data dosen
- Data mapping & validator (cek error sebelum kirim)
- Bank integration (VA + disbursement payroll)
- API Management: REST + GraphQL, OAuth 2.0
- Executive Dashboard: KPI mahasiswa aktif, IPK avg per fakultas, rasio dosen-mhs, tunggakan, kelulusan, PMB
- Chart library: line/bar/pie/scatter/heatmap/funnel + drill-down universitas→fakultas→prodi→individu
- Custom report builder (no-code)
- Scheduled reports (harian/mingguan/bulanan via email)
- **Akreditasi module**: bundling data borang BAN-PT/LAM 9 kriteria

**Business Rules:**
- BR-RPT-01: Sync PDDikti otomatis tiap akhir semester (2x/tahun)
- BR-RPT-03: Executive Dashboard hanya Rektor, Wakil Rektor, Dekan
- Acceptance: sync 1 semester < 30 menit, dashboard load < 3 detik, error PDDikti < 0.1%

---

## 4. 🏗️ Arsitektur Sistem

### Pilihan Arsitektur
**Fase 1 (MVP):** Modular Monolith — lebih cepat development, opsi ekstraksi ke microservices nanti.

### 5 Layer (sesuai dokumen)
1. **Presentation** — Web dashboard (Blade), Mobile (eksekutif), Portal Mahasiswa (terpisah, di luar scope)
2. **API Gateway** — Single entry point: auth, rate limiting, routing
3. **Application/Service** — Business logic 9 modul, satu service class per modul
4. **Domain** — Entity, value object, domain service (pure business logic)
5. **Infrastructure** — DB access, external API, file storage, email/WA gateway

### Stack Adaptasi (Laravel + MySQL Path)
| Komponen | Pilihan |
|---|---|
| Backend | **Laravel 11** |
| Frontend dashboard | **Blade + TailwindCSS + Alpine.js** (atau Livewire untuk interaktivitas) |
| Database | **MySQL 8** (dokumen rekomen PostgreSQL 16, tapi MySQL 8 di-list sebagai alternatif resmi) |
| Cache/Session | **Redis 7** |
| Queue | **Redis Queue** (atau RabbitMQ untuk skala besar) |
| File Storage | **MinIO** (self-host) atau S3-compatible |
| Web Server | **Nginx** |
| Search | **Meilisearch** (lebih ringan dari Elasticsearch untuk monolith) |
| Container | **Docker** + Docker Compose (Kubernetes saat scale) |
| Monitoring | **Prometheus + Grafana** |
| Log | **ELK Stack** atau Loki + Grafana |
| CI/CD | **GitHub Actions** atau GitLab CI |
| API Docs | **Swagger/OpenAPI** (gunakan `darkaonline/l5-swagger`) |
| Auth | **Laravel Sanctum** (JWT) + Laravel Fortify untuk MFA |

### Hardware Production (Recommended)
- App Server: 8 vCPU, 16 GB RAM, 200 GB SSD × 3 instances
- DB Primary: 16 vCPU, 64 GB RAM, 1 TB NVMe high-IOPS
- DB Replica: sama
- Redis: 4 vCPU, 16 GB RAM, cluster 3 nodes
- File Storage: 500 GB - 2 TB
- Load Balancer: 4 vCPU, 8 GB RAM HA mode

---

## 5. 🗄️ Database Schema — 9 Domain, 67+ Tabel

> **Catatan konversi MySQL:** dokumen pakai PostgreSQL types beberapa tempat. Adaptasi: `JSON` → `JSON` (MySQL 8 native), `TIMESTAMP` → `TIMESTAMP`, `BIGINT` → `BIGINT UNSIGNED`. Semua tabel pakai `id`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at` (soft delete).

### Domain 1 — RBAC & Users (5 tabel)
`users`, `roles`, `permissions`, `role_permissions` (junction N:N), `audit_logs`

**Key fields `users`:** username, email, password_hash (bcrypt), full_name, role_id, user_type (ENUM: student/lecturer/staff/admin), external_id (NIM/NIDN/NIP), is_active, mfa_enabled, last_login_at, password_expires_at.

**`audit_logs`:** user_id, module, action, entity_type, entity_id, before_value (JSON), after_value (JSON), ip_address, user_agent, created_at. **Partisi by month**, archive 2 tahun.

### Domain 2 — Akademik Master (12 tabel)
`faculties`, `study_programs`, `curriculums`, `courses`, `prerequisites`, `lecturers`, `students`, `classrooms`, `academic_calendars`, `semesters`, `grade_schemas`, `course_offerings`

**Highlight:**
- `study_programs.pddikti_code` — wajib untuk integrasi PDDikti
- `prerequisites` self-reference: `course_id`, `prerequisite_course_id`, `minimum_grade`, `logic_operator` (AND/OR), `group_no`
- `lecturers`: nidn, nidk, functional_position, education_level (S2/S3), expertise_keywords (untuk matching skripsi), employment_status
- `students`: nim, curriculum_id, enrollment_year, academic_advisor_id, status (Aktif/Cuti/Lulus/DO/Mundur), entry_path (SNBP/SNBT/Mandiri), parent_phone
- `classrooms.facilities` JSON: `['proyektor','AC','papan tulis']`
- `course_offerings.lecturer_ids` JSON array (team-teaching)

### Domain 3 — Penjadwalan & Kehadiran (5 tabel)
`schedules`, `class_sessions`, `attendances`, `lecturer_workloads`, `exam_schedules`

**Highlight:**
- `class_sessions.status`: Scheduled/Conducted/Cancelled/Substituted
- `attendances.check_method`: QR/Fingerprint/Manual; **idx composite (class_session_id, student_id)**
- `lecturer_workloads.total_sks` = COMPUTED (teaching+advisory+research+service+additional)

### Domain 4 — KRS & Nilai (5 tabel)
`enrollments` (header KRS), `krs_items` (detail), `grades`, `transcripts`, `grade_appeals`

**Highlight:**
- `grades` punya: assignment_score, mid_score, final_score, attendance_score, total_score, letter_grade, grade_point, **is_locked** (untuk edit perlu izin Kaprodi)
- `transcripts`: sks_acquired, sks_attempted, semester_gpa (IPS), cumulative_gpa (IPK), sks_cumulative
- `grade_appeals`: status Pending/Approved/Rejected, original_grade, revised_grade

### Domain 5 — Keuangan & Billing (9 tabel)
`billing_components`, `billing_rates`, `invoices`, `invoice_items`, `payments`, `scholarships`, `scholarship_recipients`, `discounts`, `fines`, `refunds`

**Highlight:**
- `billing_rates`: component_id × study_program_id × enrollment_year × **ukt_group (1-8)** → amount
- `invoices`: invoice_number, virtual_account (UNIQUE), bank_code, status (Unpaid/Partial/Paid/Overdue/Cancelled), paid_amount, subtotal/discount/fine/total
- `payments`: status Pending/Success/Failed/Refunded, bank_reference, reconciled_at
- `scholarships.covered_components` JSON (komponen yang ditanggung)

### Domain 6 — E-Office & Persuratan (7 tabel)
`letter_templates`, `workflows`, `workflow_steps`, `letter_requests`, `approvals`, `e_signatures`, `letter_archives`

**Highlight:**
- `workflow_steps`: step_order, approver_role_id, **is_parallel** (approval paralel), can_reject, sla_hours
- `letter_requests`: letter_number (auto-pattern), form_data JSON, pdf_url, qr_code, current_step_id
- `e_signatures`: certificate_data, provider (BSrE/PrivyID/VIDA), valid_from/until

### Domain 7 — Tugas Akhir & MBKM (9 tabel)
`thesis_topics`, `thesis_advisors`, `logbooks`, `thesis_defenses`, `mbkm_programs`, `mbkm_enrollments`, `sks_conversions`, `research_repository`

**Highlight:**
- `thesis_topics.similarity_score` — hasil cek anti-duplikat
- `thesis_advisors.advisor_order` (1=utama, 2=pendamping)
- `logbooks`: session_date, duration_minutes, topic_discussed, lecturer_feedback, is_verified
- `thesis_defenses.defense_type`: Proposal/Hasil/Tutup; plagiarism_score
- `mbkm_programs.type`: Magang/Pertukaran/Riset/KKN/Mengajar/Wirausaha
- `sks_conversions`: mbkm_enrollment_id × course_id, sks_converted, letter_grade, approved_by

### Domain 8 — Komunikasi (5 tabel)
`notification_templates`, `announcements`, `notifications`, `audiences`, `delivery_logs`

**Highlight:**
- `notification_templates.code` event-based: KRS_OPEN, GRADE_RELEASED, BILL_ISSUED, dst
- `notification_templates.is_mandatory` (tidak bisa opt-out)
- `announcements.audience_filter` JSON, `channels` JSON, `is_emergency` (bypass preferensi)
- `delivery_logs.status`: Queued/Sent/Delivered/Read/Failed; **partisi by month**, archive 1 tahun

### Domain 9 — HRIS & Payroll (10 tabel)
`employees`, `employment_histories`, `attendance_records`, `leave_requests`, `payroll_periods`, `salary_components`, `salaries`, `salary_details`, `teaching_honors`, `tax_calculations`

**Highlight:**
- `employees`: nip, nidn, nik, employee_type (PNS/Tetap Yayasan/Kontrak/Honorer), is_lecturer, structural_position, rank_grade, base_salary, bank_account, npwp, bpjs_number
- `attendance_records`: check_in_method (Fingerprint/QR/Face/Mobile), check_in_location (geolocation)
- `salaries`: base_salary + total_allowance + teaching_honor + other_income = gross_total - bpjs_deduction - tax_pph21 - other_deductions = net_total
- `teaching_honors.class_session_id` — link langsung ke sesi yang dihadiri (audit transparan)

### Indexing Strategy (dari dokumen, section 6.6)
- `users`: username, email, external_id, role_id
- `students`: nim, study_program_id, status, enrollment_year
- `enrollments`: composite (student_id, semester_id)
- `invoices`: virtual_account, status, due_date, student_id
- `payments`: bank_reference, payment_date
- `audit_logs`: user_id, entity_type, entity_id, created_at (**partition by month**)
- `notifications`: recipient_id, is_read, created_at
- `attendances`: composite (class_session_id, student_id)

---

## 6. 🛣️ Routing & API Structure

### Web Routes (Dashboard Back-Office)
```php
// routes/web.php
Route::middleware(['auth', 'mfa'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    
    // Modul 1 - RBAC
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::get('audit-logs', [AuditLogController::class, 'index']);
    
    // Modul 2 - Akademik Master
    Route::resource('faculties', FacultyController::class);
    Route::resource('study-programs', StudyProgramController::class);
    Route::resource('curriculums', CurriculumController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('lecturers', LecturerController::class);
    Route::resource('students', StudentController::class);
    Route::resource('classrooms', ClassroomController::class);
    Route::resource('academic-calendars', AcademicCalendarController::class);
    
    // Modul 3 - Scheduling
    Route::post('schedules/auto-generate', [ScheduleController::class, 'autoGenerate']);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('exam-schedules', ExamScheduleController::class);
    Route::get('lecturer-workloads', [WorkloadController::class, 'index']);
    
    // Modul 4 - Finance
    Route::post('invoices/bulk-generate', [InvoiceController::class, 'bulkGenerate']);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('scholarships', ScholarshipController::class);
    Route::resource('refunds', RefundController::class);
    Route::get('finance/reconciliation', [ReconciliationController::class, 'index']);
    Route::get('finance/dashboard', [FinanceDashboardController::class, 'index']);
    
    // Modul 5 - E-Office
    Route::resource('letter-templates', LetterTemplateController::class);
    Route::resource('workflows', WorkflowController::class);
    Route::resource('letter-requests', LetterRequestController::class);
    Route::post('letter-requests/{id}/approve', [ApprovalController::class, 'approve']);
    Route::post('yudisium/run-validation', [YudisiumController::class, 'validate']);
    
    // Modul 6 - TA & MBKM
    Route::resource('thesis-topics', ThesisTopicController::class);
    Route::post('thesis-topics/{id}/check-similarity', [ThesisTopicController::class, 'checkSimilarity']);
    Route::resource('mbkm-programs', MbkmProgramController::class);
    Route::resource('sks-conversions', SksConversionController::class);
    
    // Modul 7 - Komunikasi
    Route::resource('announcements', AnnouncementController::class);
    Route::resource('notification-templates', NotificationTemplateController::class);
    Route::post('announcements/{id}/send', [AnnouncementController::class, 'send']);
    
    // Modul 8 - HRIS & Payroll
    Route::resource('employees', EmployeeController::class);
    Route::resource('attendance-records', AttendanceRecordController::class);
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::post('payroll/run', [PayrollController::class, 'run']);
    Route::resource('payroll-periods', PayrollPeriodController::class);
    
    // Modul 9 - Pelaporan & Integrasi
    Route::post('pddikti/sync', [PddiktiController::class, 'sync']);
    Route::get('executive-dashboard', [ExecutiveDashboardController::class, 'index']);
    Route::get('reports/builder', [ReportBuilderController::class, 'index']);
    Route::get('reports/akreditasi', [AkreditasiController::class, 'generate']);
});

// Public verification (no auth)
Route::get('/verify-letter/{qr_code}', [LetterVerificationController::class, 'verify']);
```

### API Routes (untuk Portal Mahasiswa & Mobile)
```php
// routes/api.php
Route::prefix('v1')->group(function () {
    Route::post('auth/login', [Api\AuthController::class, 'login']);
    Route::post('auth/refresh', [Api\AuthController::class, 'refresh']);
    
    Route::middleware('auth:sanctum')->group(function () {
        // Student endpoints
        Route::prefix('student')->group(function () {
            Route::get('profile', [Api\StudentController::class, 'profile']);
            Route::get('krs/available-courses', [Api\KrsController::class, 'availableCourses']);
            Route::post('krs/submit', [Api\KrsController::class, 'submit']);
            Route::get('grades', [Api\GradeController::class, 'index']);
            Route::get('transcript', [Api\TranscriptController::class, 'show']);
            Route::get('invoices', [Api\InvoiceController::class, 'index']);
            Route::get('invoices/{id}/va', [Api\InvoiceController::class, 'virtualAccount']);
            Route::post('letter-requests', [Api\LetterController::class, 'request']);
            Route::get('letter-requests/{id}/track', [Api\LetterController::class, 'track']);
        });
        
        // Lecturer endpoints
        Route::prefix('lecturer')->group(function () {
            Route::get('classes', [Api\LecturerController::class, 'classes']);
            Route::post('classes/{id}/grades', [Api\GradeController::class, 'store']);
            Route::post('attendance', [Api\AttendanceController::class, 'store']);
            Route::get('advisees', [Api\AdvisorController::class, 'advisees']);
            Route::get('payslip', [Api\PayslipController::class, 'show']);
        });
    });
    
    // Webhook endpoints (HMAC-protected, no auth middleware)
    Route::post('webhooks/bank/{bank_code}', [Api\BankWebhookController::class, 'callback']);
    Route::post('webhooks/wa-delivery', [Api\WaWebhookController::class, 'delivery']);
});
```

---

## 7. 🚦 Non-Functional Requirements

### Performance Targets
| Aspek | Target |
|---|---|
| Page load (P95) | < 2 detik |
| API response (P95) | < 500ms |
| Concurrent users | 5.000+ (puncak saat KRS) |
| Generate 5000 invoice | < 10 menit |
| Auto-scheduler (1 prodi, 200 MK) | < 5 menit |
| DB query (P95) | < 200ms |
| Report generation | < 30 detik |

### Security (wajib)
- **Auth:** OAuth 2.0 / OpenID Connect + MFA untuk role kritikal
- **RBAC:** granular hingga **level field** (nilai/keuangan butuh permission khusus)
- **Encryption:** TLS 1.3 in-transit, AES-256 at-rest untuk data sensitif (password, NIK, keuangan)
- **Password:** Bcrypt/Argon2 + salting, **tidak menyimpan plain-text**
- **API:** rate limiting, API key + JWT, IP whitelisting untuk integrasi internal
- **Audit:** 100% aktivitas kritikal, log **immutable**
- **Compliance:** UU PDP No. 27/2022, ISO 27001 baseline, UU ITE No. 11/2008
- **Pen-test:** sebelum go-live + setiap 6 bulan

### Reliability
- **Uptime:** 99.5%
- **Maintenance window:** Minggu 02:00-04:00 WIB
- **Failover:** Active-passive untuk DB & app server
- **Backup:** Daily incremental, weekly full. **RPO 1 jam, RTO 4 jam**
- **Retention:** Daily 30 hari, weekly 12 minggu, monthly 12 bulan, yearly 5 tahun
- **Offsite backup:** ke datacenter sekunder / cloud (S3 Glacier)
- **DR drill:** setiap 6 bulan

### Scalability
- Stateless app server → horizontal scaling
- DB read replica untuk reporting
- **Partitioning** untuk tabel besar (audit_logs, transaksi, delivery_logs, attendances)
- Redis untuk session + frequent query + dashboard cache
- CDN untuk static asset & file upload (skripsi)

### Usability
- WCAG 2.1 Level AA
- Bahasa: ID (default) + EN opsional
- Tooltip, help text, video tutorial in-app
- Guided tour onboarding

### Maintainability
- Unit test coverage **> 70%**, SonarQube quality gate
- API docs otomatis (Swagger)
- CI/CD blue-green deployment + rollback
- Structured logging JSON, centralized (ELK)

---

## 8. 🔌 Integrasi Eksternal (7 integrasi wajib)

### 8.1 PDDikti Feeder
- **Protocol:** SOAP/REST (cek versi terbaru)
- **Frequency:** event-based real-time + batch akhir semester
- **Data:** Mahasiswa, Dosen, Aktivitas Mengajar, AKM, Lulusan, Kurikulum, MK
- **Constraint:** format ketat → wajib **validator pre-send**
- **Implementasi:** Service class `App\Services\Integration\PddiktiFeeder`, queue job untuk batch

### 8.2 Bank (Virtual Account) — BNI, BSI, Mandiri, BCA, BRI
- **Protocol:** REST API + webhook callback
- **Use cases:** generate VA, callback pembayaran, query status, disbursement payroll
- **Security:** HMAC signature, IP whitelisting, encrypted payload
- **Implementasi:** Adapter pattern — `BankAdapterInterface` dengan implementasi per bank
- **Tabel terkait:** `invoices.virtual_account`, `payments.bank_reference`

### 8.3 WhatsApp Business API
- **Provider opsi:** Meta Cloud API, Twilio, Wati, Onesender
- **Use cases:** notif nilai, tagihan, pengumuman, OTP
- **Constraint:** template wajib approved Meta, rate limit per tier (max 1000/jam universitas)
- **Implementasi:** queue worker `SendWaNotificationJob`

### 8.4 Email
- **Provider:** SendGrid / Amazon SES / Mailgun
- **Setup wajib:** SPF, DKIM, DMARC untuk deliverability
- **Use cases:** notif formal, slip gaji, surat resmi PDF

### 8.5 E-Signature (BSrE / PrivyID / VIDA)
- **Compliance:** UU ITE 11/2008
- **Use cases:** TTE surat keluar (cuti, transkrip, ijazah)
- **Tabel:** `e_signatures` (certificate_data, valid_from/until)

### 8.6 Plagiarism Checker (Turnitin / Plagscan)
- **Use cases:** cek skripsi & jurnal sebelum sidang/publish
- **Threshold:** max 25% (BR-TA-04)

### 8.7 Sistem Presensi
- **Hardware:** mesin fingerprint, face recognition camera, QR scanner
- **Protocol:** SDK vendor (umumnya REST/Java/C++)
- **Sync:** real-time ke `attendances` (mahasiswa) atau `attendance_records` (pegawai)

---

## 9. 🔄 5 Workflow Bisnis Utama (Wajib Implementasi)

### 9.1 Workflow KRS
```
1. Admin Akademik buka periode KRS sesuai kalender
2. Sistem cek prasyarat: lunas tagihan ✓ + sudah evaluasi semester lalu ✓
3. Mahasiswa login & lihat MK tersedia
4. Sistem filter MK sesuai kurikulum mahasiswa + validasi prasyarat
5. Mahasiswa pilih MK + kelas → sistem cek kuota & bentrok jadwal
6. Submit KRS
7. Sistem hitung total SKS (max sesuai IPS sebelumnya)
8. Dosen Wali approve/reject
9. KRS final → mahasiswa terdaftar di kelas
```

### 9.2 Workflow Input Nilai
```
1. Dosen login portal
2. Pilih kelas yang diampu
3. Sistem tampilkan list mahasiswa + komponen nilai (Tugas/UTS/UAS/Kehadiran)
4. Dosen input per komponen
5. Sistem auto-hitung total + konversi huruf (grade_schemas)
6. Dosen review & submit
7. Sistem LOCK nilai (edit perlu izin Kaprodi)
8. Notifikasi auto ke mahasiswa
9. Update transcripts (IPS & IPK)
```

### 9.3 Workflow Generate Invoice & Pembayaran
```
1. Admin Keuangan trigger generate invoice di awal semester
2. Sistem loop seluruh mahasiswa aktif
3. Per mahasiswa: ambil rate sesuai prodi + angkatan + UKT group
4. Apply beasiswa/diskon
5. Generate invoice + VA via API bank
6. Kirim notifikasi WA + email
7. Mahasiswa bayar via VA
8. Bank callback ke webhook
9. Sistem update status invoice → 'Paid'
10. Notif konfirmasi ke mahasiswa
11. Daily batch reconciliation H+1 jam 00:01 WIB cross-check rekening bank
```

### 9.4 Workflow Pengajuan Surat (E-Office)
```
1. User login & pilih jenis surat
2. Isi form sesuai template
3. Submit → status: Pending Step 1
4. Approver Step 1 (e.g., Dosen Wali) terima notif
5. Approver review & approve/reject
6. Jika approve → lanjut Step 2 (e.g., Kaprodi)
7. Step terakhir (e.g., Dekan) → tanda tangan digital
8. Sistem generate PDF + QR Code + nomor surat (pattern: 001/UN.x/FT/2026)
9. Kirim ke email user + arsip digital
```

### 9.5 Workflow Auto-Scheduler
```
1. Admin set parameter: semester target, prodi target
2. Sistem ambil: list MK ditawarkan, dosen + preferensi, ruangan + kapasitas, estimasi mahasiswa
3. Constraint solver: place setiap MK ke slot waktu + ruangan + dosen
4. Validasi: no bentrok dosen, no bentrok ruang, ruang match jenis MK
5. Optimize: minimize jam kosong dosen, balance beban
6. Output: draft jadwal
7. Kaprodi review & adjust manual jika perlu
8. Publish jadwal final
```

---

## 10. 📅 Roadmap Implementasi (4 Fase, 18-24 Bulan)

### Fase 1 — Foundation (Bulan 1-6)
**Goal:** sistem bisa login, kelola user, master data, terbitkan invoice
- Infrastruktur (server, network, security)
- **Modul 1:** RBAC & User Management
- **Modul 2:** Master Data Akademik (basic: faculties, prodi, courses, lecturers, students)
- **Modul 4:** Keuangan dasar (invoice + VA integration)
- Migrasi data master

### Fase 2 — Core Academic (Bulan 7-12)
**Goal:** operasional akademik full siklus
- **Modul 2:** Lanjutan (kurikulum, prerequisites, kalender akademik)
- **Modul 3:** Penjadwalan & Presensi
- **KRS & Nilai** (Domain 4)
- **Modul 5:** E-Office basic
- **Modul 7:** Komunikasi & Notifikasi

### Fase 3 — Advanced Features (Bulan 13-18)
**Goal:** sistem ERP lengkap + PDDikti
- **Modul 4:** Keuangan advanced (beasiswa, refund, reporting)
- **Modul 5:** E-Office lanjutan (e-signature, yudisium)
- **Modul 6:** TA & MBKM
- **Modul 8:** HRIS & Payroll
- **Modul 9:** Reporting & Integrasi PDDikti

### Fase 4 — Optimization & Mobile (Bulan 19-24)
- Mobile app eksekutif
- Advanced analytics dengan ML (predictive dropout, scoring)
- Optimasi performa
- Pen-test final
- Hand-over & training intensif

### Tim Implementasi (dari dokumen)
1 PM, 1-2 System Analyst, 1 Solution Architect, 1 DB Architect, 4-6 Backend Dev, 2-3 Frontend Dev, 1 Mobile Dev, 1 UX, 2 QA, 1 DevOps, 1 Security (part-time), 1 Tech Writer, 1-2 Change Management.

**Budget range dokumen:** Rp 3-8 Miliar.

---

## 11. ✅ Testing Strategy

| Jenis Test | Tool | Target |
|---|---|---|
| Unit | PHPUnit/Pest | Coverage > 70% |
| Integration | PHPUnit Feature Test | Interaksi modul + DB |
| API | Postman/Newman | 100% endpoint |
| E2E | Cypress/Playwright | 100% critical path |
| Performance | k6/JMeter | 3x kapasitas produksi |
| Security | OWASP ZAP + pen-test | OWASP Top 10 |
| UAT | User nyata di staging | Per modul sign-off |
| Smoke | Otomatis post-deploy | Basic functionality |

---

## 12. 🚀 Sprint-by-Sprint Task untuk AI Coding Assistant

> Copy bagian ini ke Claude Code / Copilot Chat di VS Code, **satu sprint per session** agar fokus.

### Sprint 0 — Setup
```
Setup Laravel 11 project untuk ERP Universitas:
- Install: Laravel 11, Sanctum, Fortify, Telescope (dev), Horizon, Spatie Permission, Spatie Activitylog, Spatie Backup, Maatwebsite Excel, Laravel DomPDF
- Setup TailwindCSS + Alpine.js + Livewire
- Setup Docker Compose: app, mysql 8, redis 7, minio, mailhog
- Setup GitHub Actions CI: phpunit + pint + larastan
- Buat folder structure: app/Modules/{Rbac,Academic,Scheduling,Finance,EOffice,Thesis,Communication,Hris,Reporting}
- Konfigurasi queue driver Redis + Horizon
```

### Sprint 1 — Modul 1 RBAC
```
Implement Modul 1 RBAC & User Management sesuai PRD section 3.1:
1. Migration: users, roles, permissions, role_permissions, audit_logs (partitioned by month)
2. Model + factory + seeder: 9 role bawaan (Rektor, Wakil Rektor, Dekan, Kaprodi, Dosen, Admin Akademik, Admin Keuangan, Admin SDM, IT Admin)
3. Auth: Laravel Fortify dengan MFA wajib untuk Rektor/Dekan/IT Admin
4. Password policy: min 8 char + kompleks + expired 90 hari + lock 15 menit setelah 5 failed
5. Bulk import user dari Excel → auto-generate username & password → email kredensial
6. Middleware permission granular (module × action) pakai Spatie Permission
7. Audit log immutable via Spatie Activitylog observer di semua model kritikal
8. Unit test: lock mechanism, MFA flow, audit log immutability
Acceptance: 1000 user import sukses, audit log catat before-after value JSON
```

### Sprint 2 — Modul 2 Master Akademik
```
Implement Modul 2 Core Akademik sesuai PRD 3.2:
1. Migration 12 tabel domain Akademik (faculties s/d course_offerings)
2. CRUD masing-masing entity dengan Blade + Livewire
3. Kurikulum versioning (BR-ACA-01: tidak boleh hapus jika dipakai mahasiswa)
4. Prasyarat MK self-reference dengan AND/OR logic + group_no
5. Validasi BR-ACA-02: lock kode & SKS MK yang sudah pernah dapat nilai
6. Kalender akademik visual bulanan (FullCalendar.js)
7. Seeder: 1 fakultas, 1 prodi, 1 kurikulum, 10 MK, 5 dosen, 100 mahasiswa dummy
8. Feature test: kurikulum versioning, prerequisite validation
```

### Sprint 3-4 — Modul 4 Finance Dasar
```
Implement Modul 4 Keuangan dasar sesuai PRD 3.4:
1. Migration 9 tabel domain Keuangan
2. Master billing_components & billing_rates (per prodi × angkatan × UKT group 1-8)
3. Invoice generator massal dengan queue job (target: 5000 invoice < 10 menit)
4. Integrasi 1 bank dulu (BNI VA) — adapter pattern siapkan untuk multi-bank
5. Webhook callback endpoint dengan HMAC verification + IP whitelist
6. Daily reconciliation scheduled job (H+1 jam 00:01 WIB)
7. Master beasiswa + auto-apply (BR-FIN-03: pokok dulu, sisa ke denda)
8. Finance dashboard: tagihan/terbayar/piutang/top 10 penunggak
9. Auto-block KRS jika tunggakan > 30 hari
Acceptance: generate 1000 invoice < 2 menit, webhook idempotent
```

### Sprint 5 — Modul 3 Scheduling
```
Implement Modul 3 Penjadwalan sesuai PRD 3.3:
1. Migration 5 tabel domain Penjadwalan
2. Auto-scheduler engine: constraint propagation algorithm
3. Konflik detection real-time saat adjust manual
4. Multi-channel presensi (QR + manual dulu)
5. Auto-lock UAS rule (kehadiran < 75%)
6. BKD auto-calc per dosen per semester
7. Mini-scheduler UTS/UAS (jeda min 1 jam antar ujian per mahasiswa)
Acceptance: 200 MK selesai dijadwalkan < 5 menit, conflict < 1%
```

### Sprint 6 — KRS & Nilai (Domain 4 dari ERD)
```
Implement KRS & Nilai sesuai workflow 7.6.1 & 7.6.2:
1. Migration 5 tabel: enrollments, krs_items, grades, transcripts, grade_appeals
2. API KRS untuk portal mahasiswa: GET available courses, POST submit
3. Validasi: prasyarat lulus min C, lunas tagihan, max SKS sesuai IPS
4. Approval flow Dosen Wali
5. Input nilai oleh dosen (web + API) dengan auto-calc total + grade_point
6. Lock nilai mechanism (edit perlu izin Kaprodi)
7. Auto-update transcripts (IPS & IPK kumulatif)
8. Grade appeal workflow
```

### Sprint 7 — Modul 7 Komunikasi
```
Implement Modul 7 Komunikasi sesuai PRD 3.7:
1. Migration 5 tabel komunikasi
2. Audience segmentation engine (filter JSON dinamis)
3. Notification templates event-based (KRS_OPEN, GRADE_RELEASED, BILL_ISSUED, dll)
4. Omnichannel: Email (SendGrid) + WA (Meta Cloud API) + In-app
5. Queue worker dengan rate limit WA 1000/jam
6. Delivery tracking per channel
7. Approval flow untuk blast > 1000 penerima
8. Emergency broadcast mode
```

### Sprint 8 — Modul 5 E-Office
```
Implement Modul 5 E-Office sesuai PRD 3.5 + workflow 7.6.4:
1. Migration 7 tabel E-Office
2. Visual workflow designer (drag-drop dengan Vue Flow / Drawflow.js)
3. Template surat dengan placeholder + auto-numbering pattern
4. Approval one-click via email (signed URL Laravel)
5. Generate PDF + QR Code (simplesoftwareio/simple-qrcode)
6. Public verification endpoint by QR (5 tahun aktif)
7. E-signature integration (BSrE adapter)
8. Yudisium engine: validasi otomatis SKS + IPK + MK wajib + lunas + skripsi + TOEFL
9. SLA tracker + notif jika tertahan > 3 hari
```

### Sprint 9 — Modul 6 TA & MBKM
```
Implement Modul 6 TA & MBKM sesuai PRD 3.6:
1. Migration 9 tabel TA & MBKM
2. Similarity check judul (TF-IDF atau integrasi Plagiarisma API)
3. Matching mhs↔pembimbing: keyword expertise + beban dosen
4. Logbook digital + verifikasi pembimbing
5. Eligibility check sidang (min 8x bimbingan, lunas, lulus MK, persetujuan pembimbing)
6. Plagiarism check integrasi (Turnitin/Plagscan API) — threshold 25%
7. MBKM enrollment + SKS conversion table
8. Repository karya ilmiah dengan workflow review perpus
```

### Sprint 10 — Modul 8 HRIS & Payroll
```
Implement Modul 8 HRIS & Payroll sesuai PRD 3.8:
1. Migration 10 tabel HRIS
2. Master pegawai + employment history
3. E-presensi integration (QR + fingerprint adapter)
4. Cuti workflow + saldo otomatis
5. Payroll engine: pull honor mengajar dari teaching_honors (terkait pertemuan 'hadir')
6. PPh21 calc sesuai aturan terbaru → generate 1721-A1
7. Slip gaji PDF + TTE → email
8. Closing bulan: lock data, koreksi via adjustment
Acceptance: payroll 500 pegawai < 5 menit, akurasi PPh21 100%
```

### Sprint 11 — Modul 9 Pelaporan & Integrasi
```
Implement Modul 9 sesuai PRD 3.9:
1. PDDikti Feeder adapter (mock dulu untuk testing)
2. Data mapping & validator (validasi pre-send)
3. Executive dashboard: KPI mhs aktif, IPK avg, rasio dosen-mhs, tunggakan, kelulusan
4. Drill-down universitas → fakultas → prodi → individu
5. Custom report builder (query builder no-code)
6. Scheduled reports via email
7. Akreditasi module: bundling data borang BAN-PT 9 kriteria
```

### Sprint 12 — Polish, Performance, Security
```
1. Load test dengan k6 (skenario KRS 5000 concurrent)
2. Optimasi query slow (slowlog MySQL + add index)
3. Redis caching dashboard + frequent query
4. Backup automation (Spatie Backup) — daily incremental + weekly full
5. Pen-test (OWASP ZAP scan + manual)
6. Documentation API (Swagger)
7. User manual per modul (markdown)
8. CI/CD pipeline blue-green deployment
```

---

## 📋 Catatan Penting untuk System Analyst

1. **Modul 1 (RBAC) HARUS selesai dulu** — semua modul lain bergantung padanya.
2. **Modul 2 (Akademik Master) adalah jantung** — referensi untuk hampir semua modul lain.
3. **Modul 9 (PDDikti) baru aktifkan setelah data master stabil**, karena format-nya ketat.
4. **Migrasi data legacy** kemungkinan butuh **1 sprint khusus** sebelum Fase 2 — siapkan ETL script.
5. **Parallel run** wajib **1 semester** dengan sistem lama untuk validasi data.
6. **Change management** dimulai dari Fase 2 — training champion per unit, sandbox dengan data dummy.

---

**End of Planning Prompt** — pindahkan file ini sebagai `PLANNING.md` di root proyek VS Code Anda. Untuk setiap sprint, copy section "Sprint X" sebagai prompt ke Claude Code / Copilot Chat agar fokus. 🚀
