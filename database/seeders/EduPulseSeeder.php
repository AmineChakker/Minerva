<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Admin;
use App\Models\ClassRoom;
use App\Models\ParentProfile;
use App\Models\School;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EduPulseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. School ────────────────────────────────────────────
        $school = School::create([
            'name'              => 'EduPulse Academy',
            'slug'              => 'edupulse-academy',
            'email'             => 'info@edupulse.edu',
            'phone'             => '+1-555-0100',
            'address'           => '123 Education Blvd',
            'city'              => 'Springfield',
            'country'           => 'US',
            'subscription_plan' => 'pro',
            'subscription_expires_at' => now()->addYear(),
            'is_active'         => true,
        ]);

        // ─── 2. Academic Year ─────────────────────────────────────
        $academicYear = AcademicYear::create([
            'school_id'  => $school->id,
            'name'       => '2025-2026',
            'start_date' => '2025-09-01',
            'end_date'   => '2026-06-30',
            'is_current' => true,
        ]);

        // ─── 3. Super Admin ───────────────────────────────────────
        $adminUser = User::create([
            'school_id'  => $school->id,
            'first_name' => 'Super',
            'last_name'  => 'Admin',
            'email'      => 'admin@edupulse.edu',
            'phone'      => '+1-555-0001',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'gender'     => 'male',
            'is_active'  => true,
        ]);

        Admin::create([
            'user_id'     => $adminUser->id,
            'employee_id' => 'ADMIN-001',
            'department'  => 'Administration',
            'hire_date'   => '2020-01-01',
        ]);

        // ─── 4. Subjects ──────────────────────────────────────────
        $subjects = collect([
            ['name' => 'Mathematics',        'code' => 'MATH101', 'description' => 'Core mathematics curriculum covering algebra, geometry, and calculus.'],
            ['name' => 'English Language',   'code' => 'ENG101',  'description' => 'Reading comprehension, grammar, writing, and literature.'],
            ['name' => 'Science',            'code' => 'SCI101',  'description' => 'Introduction to physics, chemistry, and biology.'],
            ['name' => 'Social Studies',     'code' => 'SOC101',  'description' => 'History, geography, civics, and economics.'],
            ['name' => 'Physical Education', 'code' => 'PE101',   'description' => 'Sports, fitness, health education.'],
            ['name' => 'Computer Science',   'code' => 'CS101',   'description' => 'Programming fundamentals, digital literacy, and algorithms.'],
        ])->map(fn ($s) => Subject::create([...$s, 'school_id' => $school->id]));

        // ─── 5. Teachers ──────────────────────────────────────────
        $teacherData = [
            ['first_name' => 'Alice',   'last_name' => 'Johnson', 'email' => 'alice@edupulse.edu',   'gender' => 'female', 'emp_id' => 'TCH-001', 'spec' => 'Mathematics',     'exp' => 8,  'qual' => 'M.Ed Mathematics'],
            ['first_name' => 'Robert',  'last_name' => 'Williams', 'email' => 'robert@edupulse.edu', 'gender' => 'male',   'emp_id' => 'TCH-002', 'spec' => 'Science',         'exp' => 5,  'qual' => 'B.Sc Physics'],
            ['first_name' => 'Samantha','last_name' => 'Brown',    'email' => 'samantha@edupulse.edu','gender' => 'female', 'emp_id' => 'TCH-003', 'spec' => 'English Language', 'exp' => 10, 'qual' => 'M.A. English Literature'],
        ];

        $teachers = [];
        foreach ($teacherData as $i => $data) {
            $u = User::create([
                'school_id'  => $school->id,
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'phone'      => '+1-555-01' . str_pad($i + 10, 2, '0', STR_PAD_LEFT),
                'password'   => Hash::make('password'),
                'role'       => 'teacher',
                'gender'     => $data['gender'],
                'is_active'  => true,
            ]);

            $teachers[] = Teacher::create([
                'user_id'          => $u->id,
                'employee_id'      => $data['emp_id'],
                'hire_date'        => now()->subYears($data['exp'])->format('Y-m-d'),
                'qualification'    => $data['qual'],
                'experience_years' => $data['exp'],
                'specialization'   => $data['spec'],
                'salary'           => 45000 + ($i * 5000),
                'status'           => 'active',
            ]);
        }

        // ─── 6. Classes ───────────────────────────────────────────
        $classData = [
            ['name' => 'Grade 7', 'section' => 'A', 'capacity' => 30, 'teacher_idx' => 0],
            ['name' => 'Grade 8', 'section' => 'A', 'capacity' => 30, 'teacher_idx' => 1],
            ['name' => 'Grade 9', 'section' => 'A', 'capacity' => 35, 'teacher_idx' => 2],
        ];

        $classes = [];
        foreach ($classData as $data) {
            $classes[] = ClassRoom::create([
                'school_id'        => $school->id,
                'academic_year_id' => $academicYear->id,
                'name'             => $data['name'],
                'section'          => $data['section'],
                'capacity'         => $data['capacity'],
                'class_teacher_id' => $teachers[$data['teacher_idx']]->user_id,
            ]);
        }

        // ─── 7. Students ──────────────────────────────────────────
        $studentNames = [
            ['Ethan',   'Taylor',    'male',   '001'],
            ['Olivia',  'Martinez',  'female', '002'],
            ['Liam',    'Anderson',  'male',   '003'],
            ['Emma',    'Thompson',  'female', '004'],
            ['Noah',    'Garcia',    'male',   '005'],
            ['Ava',     'Robinson',  'female', '006'],
            ['Sophia',  'Lewis',     'female', '007'],
            ['James',   'Walker',    'male',   '008'],
            ['Isabella','Hall',      'female', '009'],
            ['Mason',   'Allen',     'male',   '010'],
        ];

        $students = [];
        foreach ($studentNames as $i => $s) {
            $classObj = $classes[$i % 3];
            $u = User::create([
                'school_id'  => $school->id,
                'first_name' => $s[0],
                'last_name'  => $s[1],
                'email'      => strtolower($s[0]) . '.' . strtolower($s[1]) . '@student.edupulse.edu',
                'phone'      => '+1-555-02' . str_pad($i + 10, 2, '0', STR_PAD_LEFT),
                'password'   => Hash::make('password'),
                'role'       => 'student',
                'gender'     => $s[2],
                'is_active'  => true,
            ]);

            $students[] = Student::create([
                'user_id'          => $u->id,
                'admission_number' => 'EP-2025-' . $s[3],
                'class_id'         => $classObj->id,
                'academic_year_id' => $academicYear->id,
                'date_of_birth'    => now()->subYears(12 + ($i % 4))->format('Y-m-d'),
                'blood_group'      => ['A+', 'B+', 'O+', 'AB+'][$i % 4],
                'nationality'      => 'American',
                'address'          => ($i + 100) . ' Student Lane, Springfield',
                'emergency_contact_name'  => 'Parent of ' . $s[0],
                'emergency_contact_phone' => '+1-555-09' . str_pad($i + 10, 2, '0', STR_PAD_LEFT),
                'enrollment_date'  => '2025-09-01',
                'status'           => 'active',
            ]);
        }

        // ─── 8. Parents ───────────────────────────────────────────
        $parentData = [
            ['first_name' => 'David',    'last_name' => 'Taylor',    'email' => 'david.taylor@email.com',   'occ' => 'Engineer',       'rel' => 'father', 'children' => [0, 1]],
            ['first_name' => 'Sarah',    'last_name' => 'Martinez',  'email' => 'sarah.martinez@email.com', 'occ' => 'Nurse',          'rel' => 'mother', 'children' => [1, 2]],
            ['first_name' => 'Michael',  'last_name' => 'Anderson',  'email' => 'michael.anderson@email.com','occ' => 'Accountant',    'rel' => 'father', 'children' => [2, 3]],
            ['first_name' => 'Jennifer', 'last_name' => 'Thompson',  'email' => 'jennifer.t@email.com',     'occ' => 'Teacher',        'rel' => 'mother', 'children' => [3, 4]],
            ['first_name' => 'William',  'last_name' => 'Garcia',    'email' => 'william.garcia@email.com', 'occ' => 'Businessman',    'rel' => 'father', 'children' => [4, 5]],
        ];

        foreach ($parentData as $i => $data) {
            $u = User::create([
                'school_id'  => $school->id,
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'phone'      => '+1-555-03' . str_pad($i + 10, 2, '0', STR_PAD_LEFT),
                'password'   => Hash::make('password'),
                'role'       => 'parent',
                'is_active'  => true,
            ]);

            $parent = ParentProfile::create([
                'user_id'             => $u->id,
                'occupation'          => $data['occ'],
                'relation_to_student' => $data['rel'],
            ]);

            foreach ($data['children'] as $childIdx) {
                $parent->students()->attach($students[$childIdx]->id);
            }
        }

        $this->command->info('✅ EduPulse seeded successfully!');
        $this->command->info('');
        $this->command->info('  School:  EduPulse Academy');
        $this->command->info('  Admin:   admin@edupulse.edu  / password');
        $this->command->info('  Teacher: alice@edupulse.edu  / password');
        $this->command->info('  Student: ethan.taylor@student.edupulse.edu / password');
        $this->command->info('  Parent:  david.taylor@email.com / password');
    }
}
