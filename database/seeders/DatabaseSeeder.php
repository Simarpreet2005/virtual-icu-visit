<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1 555 0100',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $doctor1 = User::create([
            'name' => 'doctor1',
            'email' => 'doctor1@example.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'phone' => '+1 555 0101',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $doctor2 = User::create([
            'name' => 'doctor2',
            'email' => 'doctor2@example.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'phone' => '+1 555 0102',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $nurse1 = User::create([
            'name' => 'nurse1',
            'email' => 'nurse1@example.com',
            'password' => Hash::make('password'),
            'role' => 'nurse',
            'phone' => '+1 555 0103',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $nurse2 = User::create([
            'name' => 'nurse2',
            'email' => 'nurse2@example.com',
            'password' => Hash::make('password'),
            'role' => 'nurse',
            'phone' => '+1 555 0104',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $family1 = User::create([
            'name' => 'family1',
            'email' => 'family1@example.com',
            'password' => Hash::make('password'),
            'role' => 'family',
            'phone' => '+1 555 0105',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $family2 = User::create([
            'name' => 'family2',
            'email' => 'family2@example.com',
            'password' => Hash::make('password'),
            'role' => 'family',
            'phone' => '+1 555 0106',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $patient1 = Patient::create([
            'name' => 'patient1',
            'icu_ward' => 'Level 3 ICU — East Block',
            'bed_number' => 'E-12',
            'doctor_id' => $doctor1->id,
            'nurse_id' => $nurse1->id,
            'family_user_id' => $family1->id,
            'status' => 'critical',
            'emergency_contact' => 'Contact A +1 555 0200',
        ]);

        $patient2 = Patient::create([
            'name' => 'patient2',
            'icu_ward' => 'Cardiac ICU — Tower B',
            'bed_number' => 'C-04',
            'doctor_id' => $doctor2->id,
            'nurse_id' => $nurse2->id,
            'family_user_id' => $family1->id,
            'status' => 'improving',
            'emergency_contact' => 'Contact B +1 555 0201',
        ]);

        $patient3 = Patient::create([
            'name' => 'patient3',
            'icu_ward' => 'Neuro ICU — Ground Floor',
            'bed_number' => 'N-07',
            'doctor_id' => $doctor1->id,
            'nurse_id' => $nurse1->id,
            'family_user_id' => $family2->id,
            'status' => 'stable',
            'emergency_contact' => 'Contact C +1 555 0202',
        ]);

        $roomApproved = (string) Str::uuid();

        Appointment::create([
            'patient_id' => $patient1->id,
            'user_id' => $family1->id,
            'scheduled_at' => now()->addDay()->setTime(15, 0),
            'status' => 'approved',
            'room_id' => $roomApproved,
            'notes' => 'Brief evening visit; patient resting post-procedure.',
        ]);

        Appointment::create([
            'patient_id' => $patient2->id,
            'user_id' => $family1->id,
            'scheduled_at' => now()->addDays(2)->setTime(11, 30),
            'status' => 'pending',
            'notes' => 'Family would like an update after morning rounds.',
        ]);

        Appointment::create([
            'patient_id' => $patient3->id,
            'user_id' => $family2->id,
            'scheduled_at' => now()->addDays(3)->setTime(10, 0),
            'status' => 'pending',
            'notes' => 'Request to discuss mobility plan with treating team.',
        ]);

        Appointment::create([
            'patient_id' => $patient3->id,
            'user_id' => $family2->id,
            'scheduled_at' => now()->subDays(2)->setTime(16, 0),
            'status' => 'rejected',
            'notes' => 'Outside ICU quiet hours — alternate slot suggested.',
        ]);
    }
}
