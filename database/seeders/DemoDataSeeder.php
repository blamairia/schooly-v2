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
     * Comprehensive Algerian Demo Data for Schooly v2
     * Location: Annaba, Algeria
     * Target: 500+ students, 3000+ payments
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting comprehensive demo data generation...');
        
        // Create Study Years
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
            '2ème Année Secondaire - Sciences',
            '2ème Année Secondaire - Lettres',
            '3ème Année Secondaire - Sciences Expérimentales',
            '3ème Année Secondaire - Mathématiques',
            '3ème Année Secondaire - Gestion et Économie',
            '3ème Année Secondaire - Lettres et Philosophie',
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

        // Expanded Algerian Names
        $algerianFirstNames = [
            'male' => [
                'Mohamed', 'Ahmed', 'Youcef', 'Amine', 'Bilal', 'Karim', 'Omar', 'Hamza', 'Rayan', 'Ayoub',
                'Zakaria', 'Nabil', 'Walid', 'Sofiane', 'Khaled', 'Mehdi', 'Samir', 'Tarek', 'Farid', 'Hicham',
                'Adel', 'Fares', 'Nassim', 'Sami', 'Rachid', 'Mourad', 'Kamel', 'Hakim', 'Djamel', 'Salim',
                'Abdelkader', 'Abderrahmane', 'Abdelaziz', 'Abdallah', 'Mustapha', 'Redouane', 'Yazid', 'Nadir',
                'Anis', 'Ilyes', 'Adam', 'Younes', 'Ismail', 'Ibrahim', 'Malik', 'Samy', 'Wassim', 'Anes'
            ],
            'female' => [
                'Amina', 'Fatima', 'Nour', 'Sara', 'Lina', 'Yasmine', 'Meriem', 'Imene', 'Rania', 'Asma',
                'Hadjer', 'Malek', 'Ikram', 'Chaima', 'Nesrine', 'Khadija', 'Samia', 'Leila', 'Naima', 'Houria',
                'Souad', 'Malika', 'Karima', 'Farida', 'Zohra', 'Aicha', 'Hafsa', 'Salma', 'Dounia', 'Siham',
                'Hanane', 'Wafa', 'Sabrina', 'Lamia', 'Soraya', 'Dalila', 'Nabila', 'Rachida', 'Latifa', 'Zahia',
                'Meryem', 'Nadia', 'Soumia', 'Lynda', 'Selma', 'Aya', 'Melissa', 'Dina'
            ]
        ];
        
        $algerianLastNames = [
            'Benali', 'Boudiaf', 'Belhadj', 'Kaci', 'Mebarki', 'Cherif', 'Hamidi', 'Saidi', 'Mokrani', 'Zidane',
            'Bouzid', 'Brahimi', 'Messaoudi', 'Amrani', 'Sellami', 'Benaissa', 'Djelloul', 'Larbi', 'Ferhat', 'Taleb',
            'Benabdallah', 'Bouazza', 'Benamara', 'Bensalem', 'Benyoucef', 'Boumediene', 'Chaoui', 'Djaballah', 'Ghazi',
            'Hadj', 'Khelifi', 'Mansouri', 'Meziane', 'Ouali', 'Rahmani', 'Slimani', 'Touati', 'Yahiaoui', 'Zouaoui',
            'Abdi', 'Abed', 'Amar', 'Azzouz', 'Bachir', 'Belaidi', 'Belmadi', 'Benkhaled', 'Bensaid', 'Bensalah',
            'Boukhari', 'Boukhemis', 'Boulahia', 'Boussaid', 'Chelli', 'Dahmani', 'Derradji', 'Ferhani', 'Ghanem',
            'Guerroudj', 'Haddad', 'Hammoudi', 'Haroun', 'Khellaf', 'Lahouel', 'Madani', 'Mahmoudi', 'Mekki', 'Nouri'
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
            'Cité 20 Août, Annaba',
            'Boulevard de l\'ALN, Annaba',
            'Rue Larbi Ben M\'hidi, Annaba',
            'Cité Boukhadra, Annaba',
            'Avenue Habib Bourguiba, Annaba',
            'Rue Mohamed Khemisti, Annaba',
            'Cité des Fonctionnaires, Annaba',
            'Boulevard Che Guevara, Annaba',
            'Rue Emir Abdelkader, Annaba',
            'Cité El Hadjar, Annaba',
        ];

        $algerianCities = [
            'Annaba', 'Constantine', 'Alger', 'Oran', 'Sétif', 'Béjaïa', 'Skikda', 'Guelma', 
            'El Tarf', 'Batna', 'Tizi Ouzou', 'Blida', 'Tlemcen', 'Biskra', 'Mostaganem'
        ];

        // Get payment methods
        $paymentMethods = PaymentMethod::all();
        if ($paymentMethods->isEmpty()) {
            $this->command->error('No payment methods found. Run PaymentMethodsSeeder first.');
            return;
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

        $this->command->info('📊 Creating 500+ students and 3000+ payments...');
        
        $targetStudents = 550;
        $studentsCreated = 0;
        $paymentsCreated = 0;

        // Create Parents and Students
        for ($i = 0; $i < 300; $i++) {
            if ($studentsCreated >= $targetStudents) break;

            $parentGender = rand(0, 1) ? 'male' : 'female';
            $parentFirstName = $algerianFirstNames[$parentGender][array_rand($algerianFirstNames[$parentGender])];
            $parentLastName = $algerianLastNames[array_rand($algerianLastNames)];
            $address = $annabaAddresses[array_rand($annabaAddresses)];
            $birthCity = $algerianCities[array_rand($algerianCities)];

            $parent = Parents::create([
                'first_name' => $parentFirstName,
                'last_name' => $parentLastName,
                'birth_date' => Carbon::now()->subYears(rand(32, 58))->subDays(rand(0, 365)),
                'birth_place' => $birthCity,
                'phone_number' => '+213 ' . rand(5, 7) . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                'address' => $address,
                'email' => strtolower(str_replace(' ', '', $parentFirstName) . '.' . str_replace(' ', '', $parentLastName) . rand(1, 999)) . '@example.dz',
            ]);

            // Create 1-3 students per parent (weighted towards 2)
            $numChildren = rand(1, 10) <= 7 ? 2 : (rand(0, 1) ? 1 : 3);
            for ($j = 0; $j < $numChildren; $j++) {
                if ($studentsCreated >= $targetStudents) break;

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

                $studentsCreated++;

                // Create payments for each student
                $paymentMethod = $paymentMethods->random();

                // 1. Tuition fees - 3 trimesters (always created)
                foreach ([1, 2, 3] as $trimester) {
                    $dueDate = match($trimester) {
                        1 => Carbon::create(2024, 9, 15)->addDays(rand(-5, 10)),
                        2 => Carbon::create(2025, 1, 15)->addDays(rand(-5, 10)),
                        3 => Carbon::create(2025, 4, 15)->addDays(rand(-5, 10)),
                    };

                    $totalAmount = rand(18, 35) * 1000; // 18,000 - 35,000 DZD
                    
                    // Payment probability: 1st trimester 95%, 2nd 80%, 3rd 50%
                    $paymentProb = match($trimester) {
                        1 => 95,
                        2 => 80,
                        3 => 50,
                    };
                    
                    $isPaid = rand(1, 100) <= $paymentProb;
                    $isPartial = !$isPaid && rand(1, 100) <= 30;
                    
                    $amountPaid = $isPaid ? $totalAmount : ($isPartial ? rand(5, 15) * 1000 : 0);

                    // Randomize creation date between September and now
                    $createdAt = $trimester == 1 
                        ? Carbon::create(2024, 9, rand(1, 30))
                        : ($trimester == 2 
                            ? Carbon::create(2025, rand(1, 2), rand(1, 28))
                            : Carbon::now()->subDays(rand(0, 30)));

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
                        'payment_method_id' => $paymentMethod->id,
                        'payment_method_text' => $paymentMethod->method_name,
                        'status' => $amountPaid >= $totalAmount ? 'paid' : ($amountPaid > 0 ? 'partial' : 'unpaid'),
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                    $paymentsCreated++;
                }

                // 2. Transport fees (75% of students)
                if (rand(0, 100) <= 75) {
                    $transportFee = rand(3, 7) * 1000; // per month
                    $monthsPaid = rand(2, 9);
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
                        'payment_method_id' => $paymentMethod->id,
                        'payment_method_text' => $paymentMethod->method_name,
                        'status' => $paidTransport >= $totalTransport ? 'paid' : ($paidTransport > 0 ? 'partial' : 'unpaid'),
                        'created_at' => Carbon::create(2024, 9, rand(1, 30)),
                        'updated_at' => Carbon::create(2024, 9, rand(1, 30)),
                    ]);
                    $paymentsCreated++;
                }

                // 3. Cafeteria fees (60% of students)
                if (rand(0, 100) <= 60) {
                    $cafeteriaFee = rand(2, 5) * 1000; // per month
                    $monthsPaid = rand(3, 9);
                    $totalCafeteria = $cafeteriaFee * 10;
                    $paidCafeteria = $cafeteriaFee * $monthsPaid;

                    DB::table('payments')->insert([
                        'student_id' => $student->id,
                        'payment_type_id' => $paymentTypes[2]->id,
                        'division_plan_id' => $monthlyPlan->id,
                        'part_number' => $monthsPaid,
                        'total_amount' => $totalCafeteria,
                        'amount_due' => $totalCafeteria - $paidCafeteria,
                        'amount_paid' => $paidCafeteria,
                        'year' => '2024-2025',
                        'study_year_id' => $currentYear->id,
                        'due_date' => Carbon::create(2024, 9, 1),
                        'payment_method_id' => $paymentMethod->id,
                        'payment_method_text' => $paymentMethod->method_name,
                        'status' => $paidCafeteria >= $totalCafeteria ? 'paid' : ($paidCafeteria > 0 ? 'partial' : 'unpaid'),
                        'created_at' => Carbon::create(2024, 9, rand(1, 30)),
                        'updated_at' => Carbon::create(2024, 9, rand(1, 30)),
                    ]);
                    $paymentsCreated++;
                }

                // 4. School supplies (annual, 85% of students)
                if (rand(0, 100) <= 85) {
                    $suppliesFee = rand(8, 15) * 1000;
                    $isPaid = rand(0, 100) <= 70;

                    DB::table('payments')->insert([
                        'student_id' => $student->id,
                        'payment_type_id' => $paymentTypes[3]->id,
                        'division_plan_id' => $annualPlan->id,
                        'part_number' => 1,
                        'total_amount' => $suppliesFee,
                        'amount_due' => $isPaid ? 0 : $suppliesFee,
                        'amount_paid' => $isPaid ? $suppliesFee : 0,
                        'year' => '2024-2025',
                        'study_year_id' => $currentYear->id,
                        'due_date' => Carbon::create(2024, 9, 10),
                        'payment_method_id' => $paymentMethod->id,
                        'payment_method_text' => $paymentMethod->method_name,
                        'status' => $isPaid ? 'paid' : 'unpaid',
                        'created_at' => Carbon::create(2024, 9, rand(1, 20)),
                        'updated_at' => Carbon::create(2024, 9, rand(1, 20)),
                    ]);
                    $paymentsCreated++;
                }

                // 5. Extracurricular activities (40% of students)
                if (rand(0, 100) <= 40) {
                    $activitiesFee = rand(3, 8) * 1000;
                    $isPaid = rand(0, 100) <= 60;

                    DB::table('payments')->insert([
                        'student_id' => $student->id,
                        'payment_type_id' => $paymentTypes[4]->id,
                        'division_plan_id' => $annualPlan->id,
                        'part_number' => 1,
                        'total_amount' => $activitiesFee,
                        'amount_due' => $isPaid ? 0 : $activitiesFee,
                        'amount_paid' => $isPaid ? $activitiesFee : 0,
                        'year' => '2024-2025',
                        'study_year_id' => $currentYear->id,
                        'due_date' => Carbon::create(2024, 10, 1),
                        'payment_method_id' => $paymentMethod->id,
                        'payment_method_text' => $paymentMethod->method_name,
                        'status' => $isPaid ? 'paid' : 'unpaid',
                        'created_at' => Carbon::create(2024, rand(9, 10), rand(1, 30)),
                        'updated_at' => Carbon::create(2024, rand(9, 10), rand(1, 30)),
                    ]);
                    $paymentsCreated++;
                }

                // 6. Insurance (annual, 90% of students)
                if (rand(0, 100) <= 90) {
                    $insuranceFee = rand(1, 3) * 1000;
                    $isPaid = rand(0, 100) <= 85;

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
                        'payment_method_id' => $paymentMethod->id,
                        'payment_method_text' => $paymentMethod->method_name,
                        'status' => $isPaid ? 'paid' : 'unpaid',
                        'created_at' => Carbon::create(2024, 9, rand(1, 30)),
                        'updated_at' => Carbon::create(2024, 9, rand(1, 30)),
                    ]);
                    $paymentsCreated++;
                }
            }

            // Progress indicator
            if ($i % 50 == 0) {
                $this->command->info("  Progress: {$studentsCreated} students, {$paymentsCreated} payments created...");
            }
        }

        $this->command->info('');
        $this->command->info('✅ Demo data generation complete!');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('📊 Summary:');
        $this->command->info('  📚 Study Years: ' . StudyYear::count());
        $this->command->info('  🏫 Classes: ' . DB::table('study_classes')->count());
        $this->command->info('  💰 Payment Types: ' . PaymentType::count());
        $this->command->info('  👨‍👩‍👧‍👦 Parents: ' . Parents::count());
        $this->command->info('  🎓 Students: ' . Student::count());
        $this->command->info('  💵 Payments: ' . DB::table('payments')->count());
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}
