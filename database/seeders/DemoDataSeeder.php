<?php

namespace Database\Seeders;

use App\Models\Parents;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\Student;
use App\Models\DivisionPlan;
use App\Models\StudyYear;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    /**
     * Algerian Demo Data for Schooly v2
     * Location: Annaba, Algeria
     */
    public function run(): void
    {
        // Create Study Years (use firstOrCreate to avoid duplicates)
        $studyYears = [
            StudyYear::firstOrCreate(['year' => '2023-2024']),
            StudyYear::firstOrCreate(['year' => '2024-2025']),
        ];
        $currentYear = $studyYears[1]; // 2024-2025

        // Create Study Classes (Algerian School System)
        $classNames = [
            // Primary School (ابتدائي)
            '1ère Année Primaire',
            '2ème Année Primaire',
            '3ème Année Primaire',
            '4ème Année Primaire',
            '5ème Année Primaire',
            // Middle School (متوسط)
            '1ère Année Moyenne',
            '2ème Année Moyenne',
            '3ème Année Moyenne',
            '4ème Année Moyenne',
            // High School (ثانوي)
            '1ère Année Secondaire',
            '2ème Année Secondaire',
            '3ème Année Secondaire - Sciences',
            '3ème Année Secondaire - Lettres',
        ];

        $classes = [];
        foreach ($classNames as $name) {
            $existing = DB::table('study_classes')->where('name', $name)->first();
            if ($existing) {
                $classes[] = $existing;
            } else {
                $id = DB::table('study_classes')->insertGetId([
                    'name' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $classes[] = (object)['id' => $id, 'name' => $name];
            }
        }

        // Create Payment Types (Common school fees in Algeria)
        $paymentTypeNames = [
            'Frais de Scolarité (رسوم التمدرس)',
            'Frais de Transport (النقل المدرسي)',
            'Frais de Cantine (المطعم المدرسي)',
            'Fournitures Scolaires (اللوازم المدرسية)',
            'Activités Parascolaires (الأنشطة اللاصفية)',
            'Assurance Scolaire (التأمين المدرسي)',
        ];
        
        $paymentTypes = [];
        foreach ($paymentTypeNames as $name) {
            $paymentTypes[] = PaymentType::firstOrCreate(['name' => $name]);
        }

        // Common Algerian Names
        $algerianFirstNames = [
            'male' => ['Mohamed', 'Ahmed', 'Youcef', 'Amine', 'Bilal', 'Karim', 'Omar', 'Hamza', 'Rayan', 'Ayoub', 'Zakaria', 'Nabil', 'Walid', 'Sofiane', 'Khaled'],
            'female' => ['Amina', 'Fatima', 'Nour', 'Sara', 'Lina', 'Yasmine', 'Meriem', 'Imene', 'Rania', 'Asma', 'Hadjer', 'Malek', 'Ikram', 'Chaima', 'Nesrine']
        ];
        
        $algerianLastNames = [
            'Benali', 'Boudiaf', 'Belhadj', 'Kaci', 'Mebarki', 'Cherif', 'Hamidi', 'Saidi', 'Mokrani', 'Zidane',
            'Bouzid', 'Brahimi', 'Messaoudi', 'Amrani', 'Sellami', 'Benaissa', 'Djelloul', 'Larbi', 'Ferhat', 'Taleb'
        ];

        $annabaAddresses = [
            'Rue Didouche Mourad, Centre-ville, Annaba',
            '23 Boulevard de la République, Annaba',
            'Cité des Martyrs, Sidi Amar, Annaba',
            'Rue Zighoud Youcef, Kouba, Annaba',
            'Avenue du 1er Novembre, El Bouni, Annaba',
            'Cité 500 Logements, Oued Forcha, Annaba',
            'Rue des Frères Mesbah, Annaba Centre',
            'Boulevard Ben Badis, La Colonne, Annaba',
            'Cité AADL, El Eulma, Annaba',
            'Rue Hocine Ait Ahmed, Seybouse, Annaba',
        ];

        $algerianCities = ['Annaba', 'Constantine', 'Alger', 'Oran', 'Sétif', 'Béjaïa', 'Skikda', 'Guelma', 'El Tarf'];

        // Get payment method
        $cashMethod = PaymentMethod::where('method_name', 'Cash')->first();
        if (!$cashMethod) {
            $cashMethod = PaymentMethod::first();
        }

        // Create division plans
        $trimesterPlan = DivisionPlan::firstOrCreate(
            ['name' => 'Trimestrial (3 parts)'],
            ['total_parts' => 3]
        );
        $annualPlan = DivisionPlan::firstOrCreate(
            ['name' => 'Annual (1 payment)'],
            ['total_parts' => 1]
        );
        $monthlyPlan = DivisionPlan::firstOrCreate(
            ['name' => 'Monthly (10 parts)'],
            ['total_parts' => 10]
        );

        // Create Parents and Students
        for ($i = 0; $i < 15; $i++) {
            $parentGender = rand(0, 1) ? 'male' : 'female';
            $parentFirstName = $algerianFirstNames[$parentGender][array_rand($algerianFirstNames[$parentGender])];
            $parentLastName = $algerianLastNames[array_rand($algerianLastNames)];
            $address = $annabaAddresses[array_rand($annabaAddresses)];
            $birthCity = $algerianCities[array_rand($algerianCities)];

            $parent = Parents::create([
                'first_name' => $parentFirstName,
                'last_name' => $parentLastName,
                'birth_date' => Carbon::now()->subYears(rand(35, 55))->subDays(rand(0, 365)),
                'birth_place' => $birthCity,
                'phone_number' => '+213 ' . rand(5, 7) . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                'address' => $address,
                'email' => strtolower(str_replace(' ', '', $parentFirstName) . '.' . str_replace(' ', '', $parentLastName) . rand(1,99)) . '@example.dz',
            ]);

            // Create 1-2 students per parent
            $numChildren = rand(1, 2);
            for ($j = 0; $j < $numChildren; $j++) {
                $studentGender = rand(0, 1) ? 'male' : 'female';
                $studentFirstName = $algerianFirstNames[$studentGender][array_rand($algerianFirstNames[$studentGender])];
                $class = $classes[array_rand($classes)];

                $student = Student::create([
                    'first_name' => $studentFirstName,
                    'last_name' => $parentLastName,
                    'birth_date' => Carbon::now()->subYears(rand(6, 18))->subDays(rand(0, 365)),
                    'birth_place' => $birthCity,
                    'address' => $address,
                    'parent_id' => $parent->id,
                    'study_year_id' => $currentYear->id,
                    'class_assigned_id' => $class->id,
                    'phone_number' => '+213 ' . rand(5, 7) . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                    'cassier_expiration' => Carbon::now()->addYear(),
                    'external' => rand(0, 10) < 2, // 20% external students
                ]);

                // Create payments for each student (Algerian pricing in DZD)
                if ($cashMethod) {
                    // Tuition fees - typically 3 trimesters
                    foreach ([1, 2, 3] as $trimester) {
                        $dueDate = match($trimester) {
                            1 => Carbon::create(2024, 9, 15),
                            2 => Carbon::create(2025, 1, 15),
                            3 => Carbon::create(2025, 4, 15),
                        };

                        $totalAmount = rand(15, 30) * 1000; // 15,000 - 30,000 DZD
                        $isPaid = $trimester < 3 ? rand(0, 10) > 2 : rand(0, 10) > 6;
                        $amountPaid = $isPaid ? $totalAmount : 0;

                        DB::table('payments')->insert([
                            'student_id' => $student->id,
                            'payment_type_id' => $paymentTypes[0]->id,
                            'division_plan_id' => $trimesterPlan->id,
                            'part_number' => $trimester,
                            'total_amount' => $totalAmount,
                            'amount_due' => $totalAmount - $amountPaid,
                            'amount_paid' => $amountPaid,
                            'year' => '2024-2025',
                            'study_year_id' => $currentYear->id,
                            'due_date' => $dueDate,
                            'payment_method_id' => $cashMethod->id,
                            'payment_method_text' => 'Cash',
                            'status' => $amountPaid >= $totalAmount ? 'paid' : ($amountPaid > 0 ? 'partial' : 'unpaid'),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    // Transport fees (70% of students)
                    if (rand(0, 10) > 3) {
                        $transportFee = rand(3, 6) * 1000;
                        $monthsPaid = rand(3, 8);
                        $totalTransport = $transportFee * 10;
                        $paidTransport = $transportFee * $monthsPaid;

                        DB::table('payments')->insert([
                            'student_id' => $student->id,
                            'payment_type_id' => $paymentTypes[1]->id,
                            'division_plan_id' => $monthlyPlan->id,
                            'part_number' => $monthsPaid,
                            'total_amount' => $totalTransport,
                            'amount_due' => $totalTransport - $paidTransport,
                            'amount_paid' => $paidTransport,
                            'year' => '2024-2025',
                            'study_year_id' => $currentYear->id,
                            'due_date' => Carbon::create(2024, 9, 1),
                            'payment_method_id' => $cashMethod->id,
                            'payment_method_text' => 'Cash',
                            'status' => $paidTransport >= $totalTransport ? 'paid' : ($paidTransport > 0 ? 'partial' : 'unpaid'),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    // Insurance (annual)
                    $insuranceFee = rand(1, 2) * 1000;
                    $isPaid = rand(0, 10) > 3;

                    DB::table('payments')->insert([
                        'student_id' => $student->id,
                        'payment_type_id' => $paymentTypes[5]->id,
                        'division_plan_id' => $annualPlan->id,
                        'part_number' => 1,
                        'total_amount' => $insuranceFee,
                        'amount_due' => $isPaid ? 0 : $insuranceFee,
                        'amount_paid' => $isPaid ? $insuranceFee : 0,
                        'year' => '2024-2025',
                        'study_year_id' => $currentYear->id,
                        'due_date' => Carbon::create(2024, 9, 30),
                        'payment_method_id' => $cashMethod->id,
                        'payment_method_text' => 'Cash',
                        'status' => $isPaid ? 'paid' : 'unpaid',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('✓ Created demo data:');
        $this->command->info('  - ' . StudyYear::count() . ' study years');
        $this->command->info('  - ' . DB::table('study_classes')->count() . ' classes');
        $this->command->info('  - ' . PaymentType::count() . ' payment types');
        $this->command->info('  - ' . Parents::count() . ' parents');
        $this->command->info('  - ' . Student::count() . ' students');
        $this->command->info('  - ' . DB::table('payments')->count() . ' payments');
    }
}
