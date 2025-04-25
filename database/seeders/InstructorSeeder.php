<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstructorSeeder extends Seeder
{
    public function run(): void
    {
        //BSIT
        //BSA
        //BSBA
        //Crim
        //Midwifery

        $instructors = [
            ['name' => 'Aguiflor, Ann Jubyl L.', 'department_id' => 2],
            ['name' => 'Panaligan, Jan Henry F.', 'department_id' => 5],
            ['name' => 'Cortas, Faustino', 'department_id' => 5],
            ['name' => 'Geronaga, Pinkie M.', 'department_id' => 5],
            ['name' => 'La Rosa, Marevic Q.', 'department_id' => 5],
            ['name' => 'Madrid, Dessa', 'department_id' => 2],
            ['name' => 'Gonzales, Eden R.', 'department_id' => 5],
            ['name' => 'Perlas, Sheika Anne C.', 'department_id' => 4],
            ['name' => 'Mambil, Dennis B.', 'department_id' => 4],
            ['name' => 'Pineda, Nelsie Grace D.', 'department_id' => 2],
            ['name' => 'Recuyata, Noriel', 'department_id' => 4],
            ['name' => 'Julao, Arturo', 'department_id' => 2],
            ['name' => 'Abalos, Mark Anthony M.', 'department_id' => 4],
            ['name' => 'Castillo, Richard P.', 'department_id' => 4],
            ['name' => 'Del Mundo, Danna Mary L.', 'department_id' => 1],
            ['name' => 'Aguilar, Jayward M.', 'department_id' => 3],
            ['name' => 'Lolong, Karen', 'department_id' => 2],
            ['name' => 'Geronaga, Jasmin G.', 'department_id' => 1],
            ['name' => 'Pagcaliwagan, Edward S.', 'department_id' => 2],
            ['name' => 'Maulit, Aba Mae', 'department_id' => 3],
            ['name' => 'Marasigan, Rodwin', 'department_id' => 4],
            ['name' => 'Lolong, Jenneta M.', 'department_id' => 5],
            ['name' => 'Del Mundo, Mieje Dianne L.', 'department_id' => 4],
            ['name' => 'Medina, Mia F.', 'department_id' => 4],
            ['name' => 'Fernando, Jayvie M.', 'department_id' => 4],
            ['name' => 'Balahadia, Ma. Carylle Anne', 'department_id' => 5],
            ['name' => 'Natal, Miguelito V.', 'department_id' => 1],
            ['name' => 'Lalaine Galicia', 'department_id' => 5],
            ['name' => 'Obregon, Imie', 'department_id' => 4],
            ['name' => 'Mangrobang, Victoria', 'department_id' => 3],
        ];

        $teachers = [];
        foreach ($instructors as $instructor) {
            $randomEmail = strtolower(Str::random(8)) . '@example.com';
            $teachers[] = [
                'name' => $instructor['name'],
                'email' => $randomEmail,
                'password' => Hash::make('12345578'),
                'phoneNumber' => 9123456789,
                'department_id' => $instructor['department_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Teacher::insert($teachers);
    }
}
