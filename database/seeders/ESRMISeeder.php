<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Fee;
use App\Models\ParentProfile;
use App\Models\Schedule;
use App\Models\School;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ESRMISeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Clear all non-admin data ─────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('schedules')->truncate();
        DB::table('attendances')->truncate();
        DB::table('exam_results')->truncate();
        DB::table('fees')->truncate();
        DB::table('exams')->truncate();
        DB::table('announcements')->truncate();
        DB::table('parent_student')->truncate();
        DB::table('students')->truncate();
        DB::table('parents')->truncate();
        DB::table('teachers')->truncate();
        DB::table('class_subject')->truncate();
        DB::table('classes')->truncate();
        DB::table('subjects')->truncate();
        DB::table('academic_years')->truncate();
        User::where('role', '!=', 'admin')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ── 2. Update school to ESRMI ────────────────────────────────────
        $school = School::find(1);
        $school->update([
            'name'              => 'ESRMI – École Supérieure de Rabat en Management et Ingénierie',
            'slug'              => 'esrmi',
            'email'             => 'contact@esrmi.ma',
            'phone'             => '+212 537 68 42 10',
            'address'           => 'Av. Allal El Fassi, Madinat Al Irfane',
            'city'              => 'Rabat',
            'country'           => 'Maroc',
            'subscription_plan' => 'enterprise',
        ]);

        // ── 3. Academic Years ────────────────────────────────────────────
        $year2324 = AcademicYear::create([
            'school_id'  => 1,
            'name'       => '2023-2024',
            'start_date' => '2023-09-11',
            'end_date'   => '2024-06-28',
            'is_current' => false,
        ]);

        $year2425 = AcademicYear::create([
            'school_id'  => 1,
            'name'       => '2024-2025',
            'start_date' => '2024-09-09',
            'end_date'   => '2025-06-27',
            'is_current' => true,
        ]);

        // ── 4. Subjects ──────────────────────────────────────────────────
        $subjects = [];
        foreach ([
            ['Mathématiques Avancées',          'MA-101'],
            ['Algorithmique et Programmation',  'AP-102'],
            ['Bases de Données',                'BD-103'],
            ['Réseaux et Systèmes',             'RS-104'],
            ['Développement Web',               'DW-105'],
            ['Génie Civil et BTP',              'GC-106'],
            ['Thermodynamique et Énergie',      'TE-107'],
            ['Électronique et Circuits',        'EC-108'],
            ['Management des Organisations',    'MO-201'],
            ['Comptabilité et Finance',         'CF-202'],
            ['Marketing Digital',               'MK-203'],
            ['Économie Générale',               'EG-204'],
            ['Anglais Professionnel',           'AP-301'],
            ['Communication et Soft Skills',    'CS-302'],
            ['Gestion de Projet',               'GP-303'],
        ] as [$name, $code]) {
            $subjects[$code] = Subject::create(['school_id' => 1, 'name' => $name, 'code' => $code]);
        }

        // ── 5. Teachers ──────────────────────────────────────────────────
        $teacherData = [
            ['Mohammed',   'Alami',        'male',   'm.alami@esrmi.ma',        'T-001', '2018-09-01', 'Doctorat en Informatique',       'Algorithmique & IA',       7],
            ['Fatima Zahra','Benali',      'female', 'f.benali@esrmi.ma',       'T-002', '2017-01-15', 'Doctorat en Mathématiques',      'Analyse & Probabilités',   8],
            ['Youssef',    'Kadiri',       'male',   'y.kadiri@esrmi.ma',       'T-003', '2019-09-01', 'Ingénieur Génie Civil',          'Structures & BTP',         6],
            ['Nadia',      'Chraibi',      'female', 'n.chraibi@esrmi.ma',      'T-004', '2016-02-01', 'MBA Management',                 'Stratégie & Marketing',    9],
            ['Hassan',     'El Fassi',     'male',   'h.elfassi@esrmi.ma',      'T-005', '2015-09-01', 'Doctorat en Économie',           'Finance & Marchés',        10],
            ['Salma',      'Bousfiha',     'female', 's.bousfiha@esrmi.ma',     'T-006', '2020-09-01', 'Master Langues Appliquées',      'Anglais des Affaires',     5],
            ['Omar',       'Benchekroun',  'male',   'o.benchekroun@esrmi.ma',  'T-007', '2018-03-01', "Doctorat en Génie Électrique",   "Électronique & Énergie",   7],
            ['Imane',      'Berrada',      'female', 'i.berrada@esrmi.ma',      'T-008', '2021-09-01', "Master Réseaux & Sécurité",      "Systèmes Distribués",      4],
        ];

        $teachers = [];
        foreach ($teacherData as [$fn, $ln, $gender, $email, $empId, $hire, $qual, $spec, $exp]) {
            $user = User::create([
                'school_id'  => 1,
                'first_name' => $fn,
                'last_name'  => $ln,
                'email'      => $email,
                'password'   => Hash::make('esrmi2025'),
                'role'       => 'teacher',
                'gender'     => $gender,
                'is_active'  => true,
            ]);
            $teachers[$empId] = Teacher::create([
                'user_id'          => $user->id,
                'employee_id'      => $empId,
                'hire_date'        => $hire,
                'qualification'    => $qual,
                'specialization'   => $spec,
                'experience_years' => $exp,
                'salary'           => rand(12000, 22000),
                'status'           => 'active',
            ]);
        }

        // ── 6. Classes (2024-2025) ───────────────────────────────────────
        $classData = [
            ['1ère Année', 'Génie Informatique',        35, $teachers['T-001']->user_id],
            ['1ère Année', 'Génie Civil & BTP',         30, $teachers['T-003']->user_id],
            ['1ère Année', 'Génie Électrique & Énergie',28, $teachers['T-007']->user_id],
            ['2ème Année', 'Génie Informatique',        32, $teachers['T-008']->user_id],
            ['2ème Année', 'Management & Finance',      35, $teachers['T-004']->user_id],
            ['3ème Année', 'Génie Informatique',        30, $teachers['T-001']->user_id],
            ['3ème Année', 'Management & Finance',      30, $teachers['T-005']->user_id],
        ];

        $classes = [];
        foreach ($classData as [$name, $section, $capacity, $teacherUserId]) {
            $key = $name . '-' . $section;
            $classes[$key] = ClassRoom::create([
                'school_id'        => 1,
                'academic_year_id' => $year2425->id,
                'name'             => $name,
                'section'          => $section,
                'capacity'         => $capacity,
                'class_teacher_id' => $teacherUserId,
            ]);
        }

        // ── 6b. Sync Subjects → Classes ─────────────────────────────────
        $classSubjectMap = [
            '1ère Année-Génie Informatique'         => ['MA-101', 'AP-102', 'BD-103', 'AP-301'],
            '1ère Année-Génie Civil & BTP'          => ['MA-101', 'GC-106', 'TE-107', 'AP-301'],
            '1ère Année-Génie Électrique & Énergie' => ['MA-101', 'EC-108', 'TE-107', 'AP-301'],
            '2ème Année-Génie Informatique'         => ['BD-103', 'RS-104', 'DW-105', 'GP-303'],
            '2ème Année-Management & Finance'       => ['MO-201', 'CF-202', 'MK-203', 'EG-204'],
            '3ème Année-Génie Informatique'         => ['RS-104', 'DW-105', 'GP-303', 'CS-302'],
            '3ème Année-Management & Finance'       => ['CF-202', 'MK-203', 'EG-204', 'GP-303'],
        ];

        foreach ($classSubjectMap as $classKey => $subjectCodes) {
            $classObj   = $classes[$classKey];
            $subjectIds = array_map(fn($code) => $subjects[$code]->id, $subjectCodes);
            $classObj->subjects()->sync($subjectIds);
        }

        // ── 6c. Schedules ────────────────────────────────────────────────
        // Teacher map: emp_id → user_id
        $teacherUserId = fn(string $empId) => $teachers[$empId]->user_id;

        // [class_key, subject_code, teacher_emp_id, day(1-5), start_time, end_time, room]
        $scheduleData = [
            // ── 1ère Année – Génie Informatique (GI-1) ──────────────────
            ['1ère Année-Génie Informatique', 'MA-101', 'T-002', 1, '08:30', '10:00', 'Salle A101'],
            ['1ère Année-Génie Informatique', 'MA-101', 'T-002', 3, '08:30', '10:00', 'Salle A101'],
            ['1ère Année-Génie Informatique', 'AP-102', 'T-001', 2, '08:30', '10:00', 'Lab Info-1'],
            ['1ère Année-Génie Informatique', 'AP-102', 'T-001', 4, '08:30', '10:00', 'Lab Info-1'],
            ['1ère Année-Génie Informatique', 'BD-103', 'T-001', 1, '10:15', '11:45', 'Lab Info-1'],
            ['1ère Année-Génie Informatique', 'BD-103', 'T-001', 5, '08:30', '10:00', 'Lab Info-1'],
            ['1ère Année-Génie Informatique', 'AP-301', 'T-006', 2, '10:15', '11:45', 'Salle A101'],
            ['1ère Année-Génie Informatique', 'AP-301', 'T-006', 4, '10:15', '11:45', 'Salle A101'],

            // ── 1ère Année – Génie Civil & BTP (GC-1) ───────────────────
            ['1ère Année-Génie Civil & BTP', 'MA-101', 'T-002', 1, '14:00', '15:30', 'Salle A102'],
            ['1ère Année-Génie Civil & BTP', 'MA-101', 'T-002', 3, '14:00', '15:30', 'Salle A102'],
            ['1ère Année-Génie Civil & BTP', 'GC-106', 'T-003', 2, '08:30', '10:00', 'Salle A102'],
            ['1ère Année-Génie Civil & BTP', 'GC-106', 'T-003', 4, '08:30', '10:00', 'Salle A102'],
            ['1ère Année-Génie Civil & BTP', 'TE-107', 'T-007', 2, '14:00', '15:30', 'Labo Physique'],
            ['1ère Année-Génie Civil & BTP', 'TE-107', 'T-007', 4, '14:00', '15:30', 'Labo Physique'],
            ['1ère Année-Génie Civil & BTP', 'AP-301', 'T-006', 3, '10:15', '11:45', 'Salle A102'],
            ['1ère Année-Génie Civil & BTP', 'AP-301', 'T-006', 5, '10:15', '11:45', 'Salle A102'],

            // ── 1ère Année – Génie Électrique & Énergie (GEE-1) ─────────
            ['1ère Année-Génie Électrique & Énergie', 'MA-101', 'T-002', 2, '08:30', '10:00', 'Salle A103'],
            ['1ère Année-Génie Électrique & Énergie', 'MA-101', 'T-002', 4, '14:00', '15:30', 'Salle A103'],
            ['1ère Année-Génie Électrique & Énergie', 'EC-108', 'T-007', 1, '08:30', '10:00', 'Labo Elec'],
            ['1ère Année-Génie Électrique & Énergie', 'EC-108', 'T-007', 3, '10:15', '11:45', 'Labo Elec'],
            ['1ère Année-Génie Électrique & Énergie', 'TE-107', 'T-007', 2, '10:15', '11:45', 'Labo Physique'],
            ['1ère Année-Génie Électrique & Énergie', 'TE-107', 'T-007', 4, '10:15', '11:45', 'Labo Physique'],
            ['1ère Année-Génie Électrique & Énergie', 'AP-301', 'T-006', 1, '10:15', '11:45', 'Salle A103'],
            ['1ère Année-Génie Électrique & Énergie', 'AP-301', 'T-006', 5, '08:30', '10:00', 'Salle A103'],

            // ── 2ème Année – Génie Informatique (GI-2) ──────────────────
            ['2ème Année-Génie Informatique', 'BD-103', 'T-001', 2, '14:00', '15:30', 'Lab Info-2'],
            ['2ème Année-Génie Informatique', 'BD-103', 'T-001', 4, '14:00', '15:30', 'Lab Info-2'],
            ['2ème Année-Génie Informatique', 'RS-104', 'T-008', 1, '08:30', '10:00', 'Lab Info-2'],
            ['2ème Année-Génie Informatique', 'RS-104', 'T-008', 3, '08:30', '10:00', 'Lab Info-2'],
            ['2ème Année-Génie Informatique', 'DW-105', 'T-008', 1, '10:15', '11:45', 'Lab Info-2'],
            ['2ème Année-Génie Informatique', 'DW-105', 'T-008', 3, '10:15', '11:45', 'Lab Info-2'],
            ['2ème Année-Génie Informatique', 'GP-303', 'T-008', 2, '08:30', '10:00', 'Salle B103'],
            ['2ème Année-Génie Informatique', 'GP-303', 'T-008', 4, '08:30', '10:00', 'Salle B103'],

            // ── 2ème Année – Management & Finance (MGT-2) ───────────────
            ['2ème Année-Management & Finance', 'MO-201', 'T-004', 1, '08:30', '10:00', 'Salle B101'],
            ['2ème Année-Management & Finance', 'MO-201', 'T-004', 3, '08:30', '10:00', 'Salle B101'],
            ['2ème Année-Management & Finance', 'CF-202', 'T-005', 1, '10:15', '11:45', 'Salle B101'],
            ['2ème Année-Management & Finance', 'CF-202', 'T-005', 3, '10:15', '11:45', 'Salle B101'],
            ['2ème Année-Management & Finance', 'MK-203', 'T-004', 2, '08:30', '10:00', 'Salle B101'],
            ['2ème Année-Management & Finance', 'MK-203', 'T-004', 4, '08:30', '10:00', 'Salle B101'],
            ['2ème Année-Management & Finance', 'EG-204', 'T-005', 2, '10:15', '11:45', 'Salle B101'],
            ['2ème Année-Management & Finance', 'EG-204', 'T-005', 4, '10:15', '11:45', 'Salle B101'],

            // ── 3ème Année – Génie Informatique (GI-3) ──────────────────
            ['3ème Année-Génie Informatique', 'CS-302', 'T-006', 1, '08:30', '10:00', 'Salle A104'],
            ['3ème Année-Génie Informatique', 'CS-302', 'T-006', 3, '08:30', '10:00', 'Salle A104'],
            ['3ème Année-Génie Informatique', 'DW-105', 'T-008', 1, '14:00', '15:30', 'Lab Info-3'],
            ['3ème Année-Génie Informatique', 'DW-105', 'T-008', 3, '14:00', '15:30', 'Lab Info-3'],
            ['3ème Année-Génie Informatique', 'RS-104', 'T-008', 2, '14:00', '15:30', 'Lab Info-3'],
            ['3ème Année-Génie Informatique', 'RS-104', 'T-008', 4, '14:00', '15:30', 'Lab Info-3'],
            ['3ème Année-Génie Informatique', 'GP-303', 'T-008', 4, '10:15', '11:45', 'Salle B103'],
            ['3ème Année-Génie Informatique', 'GP-303', 'T-008', 5, '08:30', '10:00', 'Salle B103'],

            // ── 3ème Année – Management & Finance (MGT-3) ───────────────
            ['3ème Année-Management & Finance', 'CF-202', 'T-005', 1, '14:00', '15:30', 'Salle B102'],
            ['3ème Année-Management & Finance', 'CF-202', 'T-005', 3, '14:00', '15:30', 'Salle B102'],
            ['3ème Année-Management & Finance', 'MK-203', 'T-004', 2, '14:00', '15:30', 'Salle B102'],
            ['3ème Année-Management & Finance', 'MK-203', 'T-004', 4, '14:00', '15:30', 'Salle B102'],
            ['3ème Année-Management & Finance', 'EG-204', 'T-005', 5, '08:30', '10:00', 'Salle B102'],
            ['3ème Année-Management & Finance', 'EG-204', 'T-005', 5, '10:15', '11:45', 'Salle B102'],
            ['3ème Année-Management & Finance', 'GP-303', 'T-008', 2, '10:15', '11:45', 'Salle B103'],
            ['3ème Année-Management & Finance', 'GP-303', 'T-008', 4, '15:45', '17:15', 'Salle B103'],
        ];

        foreach ($scheduleData as [$classKey, $subjectCode, $empId, $day, $start, $end, $room]) {
            Schedule::create([
                'school_id'        => 1,
                'academic_year_id' => $year2425->id,
                'class_id'         => $classes[$classKey]->id,
                'subject_id'       => $subjects[$subjectCode]->id,
                'teacher_id'       => $teacherUserId($empId),
                'day_of_week'      => $day,
                'start_time'       => $start . ':00',
                'end_time'         => $end . ':00',
                'room'             => $room,
            ]);
        }

        // ── 7. Students ──────────────────────────────────────────────────
        $studentData = [
            // GI-1 (10 students)
            ['Amine',    'Benali',      'male',   'amine.benali@esrmi.ma',       'GI1', '1ère Année-Génie Informatique',        '2006-03-14', 'ADM-2024-001'],
            ['Sara',     'El Amrani',   'female', 'sara.elamrani@esrmi.ma',      'GI1', '1ère Année-Génie Informatique',        '2005-07-22', 'ADM-2024-002'],
            ['Mehdi',    'Tazi',        'male',   'mehdi.tazi@esrmi.ma',         'GI1', '1ère Année-Génie Informatique',        '2006-01-05', 'ADM-2024-003'],
            ['Yasmine',  'Chraibi',     'female', 'yasmine.chraibi@esrmi.ma',    'GI1', '1ère Année-Génie Informatique',        '2005-11-30', 'ADM-2024-004'],
            ['Hamza',    'Ouazzani',    'male',   'hamza.ouazzani@esrmi.ma',     'GI1', '1ère Année-Génie Informatique',        '2006-05-18', 'ADM-2024-005'],
            ['Kaoutar',  'Zouiten',     'female', 'kaoutar.zouiten@esrmi.ma',    'GI1', '1ère Année-Génie Informatique',        '2006-02-11', 'ADM-2024-016'],
            ['Khalil',   'Berrada',     'male',   'khalil.berrada@esrmi.ma',     'GI1', '1ère Année-Génie Informatique',        '2005-09-25', 'ADM-2024-017'],
            ['Imane',    'Tahiri',      'female', 'imane.tahiri@esrmi.ma',       'GI1', '1ère Année-Génie Informatique',        '2006-06-03', 'ADM-2024-018'],
            ['Othmane',  'Mansouri',    'male',   'othmane.mansouri@esrmi.ma',   'GI1', '1ère Année-Génie Informatique',        '2005-12-18', 'ADM-2024-019'],
            ['Hiba',     'Rachidi',     'female', 'hiba.rachidi@esrmi.ma',       'GI1', '1ère Année-Génie Informatique',        '2006-04-29', 'ADM-2024-020'],
            // GC-1 (8 students)
            ['Youssef',  'El Idrissi',  'male',   'youssef.elidrissi@esrmi.ma',  'GC1', '1ère Année-Génie Civil & BTP',         '2005-09-10', 'ADM-2024-006'],
            ['Nadia',    'Lahlou',      'female', 'nadia.lahlou@esrmi.ma',       'GC1', '1ère Année-Génie Civil & BTP',         '2006-02-28', 'ADM-2024-007'],
            ['Omar',     'Bensouda',    'male',   'omar.bensouda@esrmi.ma',      'GC1', '1ère Année-Génie Civil & BTP',         '2005-12-01', 'ADM-2024-008'],
            ['Hajar',    'Senhaji',     'female', 'hajar.senhaji@esrmi.ma',      'GC1', '1ère Année-Génie Civil & BTP',         '2006-06-15', 'ADM-2024-009'],
            ['Anas',     'Boutaleb',    'male',   'anas.boutaleb@esrmi.ma',      'GC1', '1ère Année-Génie Civil & BTP',         '2005-10-22', 'ADM-2024-021'],
            ['Widad',    'El Fassi',    'female', 'widad.elfassi@esrmi.ma',      'GC1', '1ère Année-Génie Civil & BTP',         '2006-01-07', 'ADM-2024-022'],
            ['Noureddine','Cherkaoui',  'male',   'noureddine.cherkaoui@esrmi.ma','GC1','1ère Année-Génie Civil & BTP',         '2005-08-14', 'ADM-2024-023'],
            ['Ghizlane', 'Bennis',      'female', 'ghizlane.bennis@esrmi.ma',    'GC1', '1ère Année-Génie Civil & BTP',         '2006-03-30', 'ADM-2024-024'],
            // GEE-1 (8 students)
            ['Rayan',    'Moussaid',    'male',   'rayan.moussaid@esrmi.ma',     'GEE1','1ère Année-Génie Électrique & Énergie','2005-08-25', 'ADM-2024-010'],
            ['Zineb',    'Hammouti',    'female', 'zineb.hammouti@esrmi.ma',     'GEE1','1ère Année-Génie Électrique & Énergie','2006-04-11', 'ADM-2024-011'],
            ['Ilyas',    'Kettani',     'male',   'ilyas.kettani@esrmi.ma',      'GEE1','1ère Année-Génie Électrique & Énergie','2005-10-07', 'ADM-2024-012'],
            ['Sanaa',    'Bakkali',     'female', 'sanaa.bakkali@esrmi.ma',      'GEE1','1ère Année-Génie Électrique & Énergie','2006-01-20', 'ADM-2024-013'],
            ['Tariq',    'Sabir',       'male',   'tariq.sabir@esrmi.ma',        'GEE1','1ère Année-Génie Électrique & Énergie','2006-05-08', 'ADM-2024-025'],
            ['Loubna',   'Ziani',       'female', 'loubna.ziani@esrmi.ma',       'GEE1','1ère Année-Génie Électrique & Énergie','2005-11-17', 'ADM-2024-026'],
            ['Yassir',   'Fennich',     'male',   'yassir.fennich@esrmi.ma',     'GEE1','1ère Année-Génie Électrique & Énergie','2006-02-24', 'ADM-2024-027'],
            ['Manal',    'Benkirane',   'female', 'manal.benkirane@esrmi.ma',    'GEE1','1ère Année-Génie Électrique & Énergie','2005-07-13', 'ADM-2024-028'],
            // GI-2 (10 students)
            ['Soufiane', 'Benkirane',   'male',   'soufiane.benkirane@esrmi.ma', 'GI2', '2ème Année-Génie Informatique',        '2004-05-03', 'ADM-2023-001'],
            ['Meriem',   'Alaoui',      'female', 'meriem.alaoui@esrmi.ma',      'GI2', '2ème Année-Génie Informatique',        '2004-08-17', 'ADM-2023-002'],
            ['Adam',     'Bouhmidi',    'male',   'adam.bouhmidi@esrmi.ma',      'GI2', '2ème Année-Génie Informatique',        '2005-02-09', 'ADM-2023-003'],
            ['Houda',    'Lahlou',      'female', 'houda.lahlou@esrmi.ma',       'GI2', '2ème Année-Génie Informatique',        '2004-11-25', 'ADM-2023-004'],
            ['Zakaria',  'Tazi',        'male',   'zakaria.tazi@esrmi.ma',       'GI2', '2ème Année-Génie Informatique',        '2004-07-14', 'ADM-2023-005'],
            ['Fatima',   'Oulhaj',      'female', 'fatima.oulhaj@esrmi.ma',      'GI2', '2ème Année-Génie Informatique',        '2004-03-28', 'ADM-2023-011'],
            ['Ismail',   'Sekkouri',    'male',   'ismail.sekkouri@esrmi.ma',    'GI2', '2ème Année-Génie Informatique',        '2004-09-05', 'ADM-2023-012'],
            ['Nour',     'El Mouden',   'female', 'nour.elmouden@esrmi.ma',      'GI2', '2ème Année-Génie Informatique',        '2005-01-19', 'ADM-2023-013'],
            ['Ayoub',    'Kadiri',      'male',   'ayoub.kadiri@esrmi.ma',       'GI2', '2ème Année-Génie Informatique',        '2004-06-07', 'ADM-2023-014'],
            ['Chaimae',  'Belkadi',     'female', 'chaimae.belkadi@esrmi.ma',    'GI2', '2ème Année-Génie Informatique',        '2004-12-23', 'ADM-2023-015'],
            // MGT-2 (10 students)
            ['Bilal',    'El Amrani',   'male',   'bilal.elamrani@esrmi.ma',     'MGT2','2ème Année-Management & Finance',      '2004-03-22', 'ADM-2023-006'],
            ['Ghita',    'Benali',      'female', 'ghita.benali@esrmi.ma',       'MGT2','2ème Année-Management & Finance',      '2004-09-08', 'ADM-2023-007'],
            ['Taha',     'Senhaji',     'male',   'taha.senhaji@esrmi.ma',       'MGT2','2ème Année-Management & Finance',      '2005-01-16', 'ADM-2023-008'],
            ['Lamia',    'Ziani',       'female', 'lamia.ziani@esrmi.ma',        'MGT2','2ème Année-Management & Finance',      '2004-06-30', 'ADM-2023-009'],
            ['Saad',     'Ouazzani',    'male',   'saad.ouazzani@esrmi.ma',      'MGT2','2ème Année-Management & Finance',      '2004-12-04', 'ADM-2023-010'],
            ['Nassima',  'Amrani',      'female', 'nassima.amrani@esrmi.ma',     'MGT2','2ème Année-Management & Finance',      '2004-04-11', 'ADM-2023-016'],
            ['Youssef',  'Chaoui',      'male',   'youssef.chaoui@esrmi.ma',     'MGT2','2ème Année-Management & Finance',      '2004-10-27', 'ADM-2023-017'],
            ['Salma',    'Idrissi',     'female', 'salma.idrissi@esrmi.ma',      'MGT2','2ème Année-Management & Finance',      '2005-03-02', 'ADM-2023-018'],
            ['Mehdi',    'Alami',       'male',   'mehdi.alami@esrmi.ma',        'MGT2','2ème Année-Management & Finance',      '2004-07-18', 'ADM-2023-019'],
            ['Rim',      'Tazi',        'female', 'rim.tazi@esrmi.ma',           'MGT2','2ème Année-Management & Finance',      '2004-01-09', 'ADM-2023-020'],
            // GI-3 (8 students)
            ['Douaa',    'Chraibi',     'female', 'douaa.chraibi@esrmi.ma',      'GI3', '3ème Année-Génie Informatique',        '2003-04-19', 'ADM-2022-001'],
            ['Rania',    'Kettani',     'female', 'rania.kettani@esrmi.ma',      'GI3', '3ème Année-Génie Informatique',        '2003-09-02', 'ADM-2022-002'],
            ['Yassine',  'Bensouda',    'male',   'yassine.bensouda@esrmi.ma',   'GI3', '3ème Année-Génie Informatique',        '2002-11-12', 'ADM-2022-003'],
            ['Amira',    'El Idrissi',  'female', 'amira.elidrissi@esrmi.ma',    'GI3', '3ème Année-Génie Informatique',        '2003-07-28', 'ADM-2022-004'],
            ['Hamid',    'Bennis',      'male',   'hamid.bennis@esrmi.ma',       'GI3', '3ème Année-Génie Informatique',        '2002-12-05', 'ADM-2022-008'],
            ['Sofia',    'Lahlali',     'female', 'sofia.lahlali@esrmi.ma',      'GI3', '3ème Année-Génie Informatique',        '2003-05-21', 'ADM-2022-009'],
            ['Adil',     'Benali',      'male',   'adil.benali@esrmi.ma',        'GI3', '3ème Année-Génie Informatique',        '2002-09-14', 'ADM-2022-010'],
            ['Zineb',    'Cherkaoui',   'female', 'zineb.cherkaoui@esrmi.ma',    'GI3', '3ème Année-Génie Informatique',        '2003-01-30', 'ADM-2022-011'],
            // MGT-3 (8 students)
            ['Jalal',    'Moussaid',    'male',   'jalal.moussaid@esrmi.ma',     'MGT3','3ème Année-Management & Finance',      '2002-05-05', 'ADM-2022-005'],
            ['Rim',      'Hammouti',    'female', 'rim.hammouti@esrmi.ma',       'MGT3','3ème Année-Management & Finance',      '2003-02-14', 'ADM-2022-006'],
            ['Reda',     'Bakkali',     'male',   'reda.bakkali@esrmi.ma',       'MGT3','3ème Année-Management & Finance',      '2002-08-30', 'ADM-2022-007'],
            ['Hasnaa',   'El Mansouri', 'female', 'hasnaa.elmansouri@esrmi.ma',  'MGT3','3ème Année-Management & Finance',      '2002-10-17', 'ADM-2022-012'],
            ['Karim',    'Zouheir',     'male',   'karim.zouheir@esrmi.ma',      'MGT3','3ème Année-Management & Finance',      '2003-03-08', 'ADM-2022-013'],
            ['Nadia',    'Tahir',       'female', 'nadia.tahir@esrmi.ma',        'MGT3','3ème Année-Management & Finance',      '2002-07-25', 'ADM-2022-014'],
            ['Rachid',   'Fahmi',       'male',   'rachid.fahmi@esrmi.ma',       'MGT3','3ème Année-Management & Finance',      '2003-06-12', 'ADM-2022-015'],
            ['Samira',   'Bentaleb',    'female', 'samira.bentaleb@esrmi.ma',    'MGT3','3ème Année-Management & Finance',      '2002-11-28', 'ADM-2022-016'],
        ];

        $classKeyMap = [
            '1ère Année-Génie Informatique'         => '1ère Année-Génie Informatique',
            '1ère Année-Génie Civil & BTP'          => '1ère Année-Génie Civil & BTP',
            '1ère Année-Génie Électrique & Énergie' => '1ère Année-Génie Électrique & Énergie',
            '2ème Année-Génie Informatique'         => '2ème Année-Génie Informatique',
            '2ème Année-Management & Finance'       => '2ème Année-Management & Finance',
            '3ème Année-Génie Informatique'         => '3ème Année-Génie Informatique',
            '3ème Année-Management & Finance'       => '3ème Année-Management & Finance',
        ];

        $enrollDates = [
            'GI1' => '2024-09-09', 'GC1' => '2024-09-09', 'GEE1' => '2024-09-09',
            'GI2' => '2023-09-11', 'MGT2' => '2023-09-11',
            'GI3' => '2022-09-12', 'MGT3' => '2022-09-12',
        ];

        $students = [];
        $counter  = 1;
        foreach ($studentData as [$fn, $ln, $gender, $email, $group, $classKey, $dob, $admNo]) {
            $user = User::create([
                'school_id'  => 1,
                'first_name' => $fn,
                'last_name'  => $ln,
                'email'      => $email,
                'password'   => Hash::make('esrmi2025'),
                'role'       => 'student',
                'gender'     => $gender,
                'is_active'  => true,
            ]);
            $students[$admNo] = Student::create([
                'user_id'          => $user->id,
                'admission_number' => $admNo,
                'class_id'         => $classes[$classKeyMap[$classKey]]->id,
                'academic_year_id' => $year2425->id,
                'date_of_birth'    => $dob,
                'enrollment_date'  => $enrollDates[$group],
                'status'           => 'active',
            ]);
            $counter++;
        }

        // ── 8. Parents ───────────────────────────────────────────────────
        $parentData = [
            ['Hassan',   'Benali',     'male',   'h.benali.p@esrmi.ma',      'Ingénieur',       'father', ['ADM-2024-001']],
            ['Khadija',  'El Amrani',  'female', 'k.elamrani.p@esrmi.ma',    'Médecin',         'mother', ['ADM-2024-002']],
            ['Rachid',   'Tazi',       'male',   'r.tazi.p@esrmi.ma',        'Commerçant',      'father', ['ADM-2024-003', 'ADM-2023-005']],
            ['Amina',    'Chraibi',    'female', 'a.chraibi.p@esrmi.ma',     'Enseignante',     'mother', ['ADM-2024-004', 'ADM-2022-001']],
            ['Khalid',   'Ouazzani',   'male',   'k.ouazzani.p@esrmi.ma',    'Avocat',          'father', ['ADM-2024-005', 'ADM-2023-010']],
            ['Fatima',   'El Idrissi', 'female', 'f.elidrissi.p@esrmi.ma',   'Pharmacienne',    'mother', ['ADM-2024-006', 'ADM-2022-004']],
            ['Mostafa',  'Lahlou',     'male',   'm.lahlou.p@esrmi.ma',      'Architecte',      'father', ['ADM-2024-007', 'ADM-2023-004']],
            ['Said',     'Bensouda',   'male',   's.bensouda.p@esrmi.ma',    'Directeur',       'father', ['ADM-2024-008', 'ADM-2022-003']],
            ['Naima',    'Moussaid',   'female', 'n.moussaid.p@esrmi.ma',    'Infirmière',      'mother', ['ADM-2024-010', 'ADM-2022-005']],
            ['Jamal',    'Kettani',    'male',   'j.kettani.p@esrmi.ma',     'Expert-Comptable','father', ['ADM-2024-012', 'ADM-2022-002']],
            ['Souad',    'Benkirane',  'female', 's.benkirane.p@esrmi.ma',   'Gérante',         'mother', ['ADM-2023-001']],
            ['Karim',    'Bouhmidi',   'male',   'k.bouhmidi.p@esrmi.ma',    'Ingénieur',       'father', ['ADM-2023-003']],
            ['Brahim',   'El Amrani',  'male',   'b.elamrani.p@esrmi.ma',    'Fonctionnaire',   'father', ['ADM-2023-006']],
            ['Siham',    'Ziani',      'female', 's.ziani.p@esrmi.ma',       'Professeure',     'mother', ['ADM-2023-009']],
            ['Abderrahim','Bakkali',   'male',   'a.bakkali.p@esrmi.ma',     'Entrepreneur',    'guardian',['ADM-2024-013', 'ADM-2022-007']],
        ];

        foreach ($parentData as [$fn, $ln, $gender, $email, $occ, $rel, $childAdmNos]) {
            $user = User::create([
                'school_id'  => 1,
                'first_name' => $fn,
                'last_name'  => $ln,
                'email'      => $email,
                'password'   => Hash::make('esrmi2025'),
                'role'       => 'parent',
                'gender'     => $gender,
                'is_active'  => true,
            ]);
            $parent = ParentProfile::create([
                'user_id'               => $user->id,
                'occupation'            => $occ,
                'relation_to_student'   => $rel,
            ]);
            foreach ($childAdmNos as $admNo) {
                if (isset($students[$admNo])) {
                    DB::table('parent_student')->insert([
                        'parent_id'  => $parent->id,
                        'student_id' => $students[$admNo]->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // ── 9. Announcements ─────────────────────────────────────────────
        $adminUserId = User::where('role', 'admin')->first()->id;
        $announcements = [
            ['Bienvenue à l\'ESRMI – Rentrée 2024-2025',
             "Nous souhaitons la bienvenue à tous nos étudiants pour cette nouvelle année académique 2024-2025. L'ESRMI est heureuse de vous accueillir et vous souhaite une excellente année pleine de succès et d'apprentissage.",
             'success', true],
            ['Calendrier des Contrôles Continus – Semestre 1',
             "Le calendrier des contrôles continus du premier semestre est maintenant disponible. Les étudiants sont priés de consulter leur emploi du temps et de se préparer en conséquence. Tout retard ou absence devra être justifié.",
             'info', true],
            ['Rappel : Paiement des Frais de Scolarité – Semestre 1',
             "Nous rappelons à tous les étudiants que la date limite de paiement des frais de scolarité du premier semestre est fixée au 30 octobre 2024. Passé ce délai, des pénalités pourront être appliquées.",
             'warning', true],
            ['Forum des Entreprises ESRMI – Édition 2024',
             "L'ESRMI organise son Forum des Entreprises annuel le 15 novembre 2024. Plus de 40 entreprises partenaires seront présentes pour des opportunités de stage, d'alternance et d'emploi. Inscription obligatoire via le portail étudiant.",
             'success', true],
            ['Maintenance du Système Informatique – Samedi 16 Nov.',
             "Le système informatique (portail étudiant, bibliothèque numérique) sera indisponible le samedi 16 novembre 2024 de 8h à 18h pour maintenance. Merci de votre compréhension.",
             'warning', false],
        ];

        foreach ($announcements as [$title, $content, $type, $published]) {
            Announcement::create([
                'school_id'    => 1,
                'user_id'      => $adminUserId,
                'title'        => $title,
                'content'      => $content,
                'type'         => $type,
                'is_published' => $published,
            ]);
        }

        // ── 10. Exams ────────────────────────────────────────────────────
        $exams = [];
        foreach ($classSubjectMap as $classKey => $subjectCodes) {
            $class = $classes[$classKey];
            foreach ($subjectCodes as $code) {
                $subject = $subjects[$code];
                // CC1
                $exams[] = $cc1 = Exam::create([
                    'school_id'        => 1,
                    'class_room_id'    => $class->id,
                    'subject_id'       => $subject->id,
                    'academic_year_id' => $year2425->id,
                    'name'             => 'Contrôle Continu 1',
                    'exam_date'        => '2024-11-15',
                    'total_marks'      => 40,
                ]);
                // CC2
                $exams[] = $cc2 = Exam::create([
                    'school_id'        => 1,
                    'class_room_id'    => $class->id,
                    'subject_id'       => $subject->id,
                    'academic_year_id' => $year2425->id,
                    'name'             => 'Contrôle Continu 2',
                    'exam_date'        => '2025-01-17',
                    'total_marks'      => 40,
                ]);
                // Examen Final
                $exams[] = $final = Exam::create([
                    'school_id'        => 1,
                    'class_room_id'    => $class->id,
                    'subject_id'       => $subject->id,
                    'academic_year_id' => $year2425->id,
                    'name'             => 'Examen Final S1',
                    'exam_date'        => '2025-01-28',
                    'total_marks'      => 60,
                ]);
            }
        }

        // ── 11. Exam Results ─────────────────────────────────────────────
        foreach ($exams as $exam) {
            $classStudents = Student::where('class_id', $exam->class_room_id)->get();
            foreach ($classStudents as $student) {
                $total = $exam->total_marks;
                // Realistic distribution: mostly 60-90%, some outliers
                $pct = $this->realisticScore();
                $marks = (int) round(($pct / 100) * $total);
                $grade = $this->calcGrade($pct);
                ExamResult::create([
                    'exam_id'         => $exam->id,
                    'student_id'      => $student->id,
                    'marks_obtained'  => $marks,
                    'grade'           => $grade,
                    'remarks'         => $this->remarkForGrade($grade),
                ]);
            }
        }

        // ── 12. Fees ─────────────────────────────────────────────────────
        $feeTemplates = [
            ['Frais d\'Inscription 2024-2025', 3500,  '2024-09-30'],
            ['Frais de Scolarité – Semestre 1',14500, '2024-10-31'],
            ['Frais de Scolarité – Semestre 2',14500, '2025-03-15'],
        ];

        $allStudents = Student::all();
        foreach ($allStudents as $student) {
            foreach ($feeTemplates as $i => [$title, $amount, $dueDate]) {
                // Vary payment status realistically
                $rand = rand(1, 100);
                if ($i === 0) {
                    // Registration: mostly paid
                    $status = $rand <= 85 ? 'paid' : ($rand <= 95 ? 'partial' : 'unpaid');
                } elseif ($i === 1) {
                    // S1 tuition: mix
                    $status = $rand <= 65 ? 'paid' : ($rand <= 80 ? 'partial' : 'unpaid');
                } else {
                    // S2 tuition: mostly unpaid (future)
                    $status = $rand <= 20 ? 'paid' : ($rand <= 30 ? 'partial' : 'unpaid');
                }
                Fee::create([
                    'school_id'        => 1,
                    'student_id'       => $student->id,
                    'academic_year_id' => $year2425->id,
                    'title'            => $title,
                    'amount'           => $amount,
                    'due_date'         => $dueDate,
                    'paid_at'          => $status === 'paid' ? Carbon::parse($dueDate)->subDays(rand(2, 15)) : null,
                    'status'           => $status,
                ]);
            }
        }

        // ── 13. Attendance (Oct–Nov 2024, Mon–Fri) ───────────────────────
        $workdays = [];
        $start = Carbon::parse('2024-10-07');
        $end   = Carbon::parse('2024-11-29');
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if ($d->isWeekday()) $workdays[] = $d->toDateString();
        }

        foreach ($allStudents as $student) {
            foreach ($workdays as $date) {
                $rand = rand(1, 100);
                $status = match(true) {
                    $rand <= 78 => 'present',
                    $rand <= 88 => 'absent',
                    $rand <= 95 => 'late',
                    default     => 'excused',
                };
                Attendance::create([
                    'school_id'    => 1,
                    'student_id'   => $student->id,
                    'class_room_id'=> $student->class_id,
                    'date'         => $date,
                    'status'       => $status,
                    'note'         => $status === 'excused' ? 'Certificat médical fourni' : null,
                ]);
            }
        }

        $this->command->info('✅ ESRMI seeded successfully!');
        $this->command->info('   School   : ESRMI – Rabat');
        $this->command->info('   Teachers : ' . Teacher::count());
        $this->command->info('   Classes  : ' . ClassRoom::count());
        $this->command->info('   Students : ' . Student::count());
        $this->command->info('   Schedules: ' . Schedule::count());
        $this->command->info('   Exams    : ' . Exam::count());
        $this->command->info('   Results  : ' . ExamResult::count());
        $this->command->info('   Fees     : ' . Fee::count());
        $this->command->info('   Attend.  : ' . Attendance::count());
    }

    private function realisticScore(): float
    {
        // Bell-curve-ish: most students 55-85%, few very high/low
        $base = rand(50, 95);
        $noise = rand(-8, 8);
        return max(0, min(100, $base + $noise));
    }

    private function calcGrade(float $pct): string
    {
        return match(true) {
            $pct >= 95 => 'A+',
            $pct >= 90 => 'A',
            $pct >= 85 => 'B+',
            $pct >= 80 => 'B',
            $pct >= 75 => 'C+',
            $pct >= 70 => 'C',
            $pct >= 60 => 'D',
            default    => 'F',
        };
    }

    private function remarkForGrade(string $grade): ?string
    {
        return match($grade) {
            'A+', 'A' => 'Excellent travail, continuez ainsi.',
            'B+', 'B' => 'Bon travail, quelques points à améliorer.',
            'C+', 'C' => 'Résultats satisfaisants, des efforts supplémentaires sont nécessaires.',
            'D'       => 'Résultats insuffisants, travail à intensifier.',
            'F'       => 'Échec. Rattrapage requis.',
            default   => null,
        };
    }
}
