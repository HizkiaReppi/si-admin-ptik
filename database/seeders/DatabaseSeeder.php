<?php

namespace Database\Seeders;

use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use App\Helpers\GenerationHelper;
use App\Models\Category;
use App\Models\Submission;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'id' => Str::uuid(),
            'name' => env('SUPER_ADMIN_NAME'),
            'username' => env('SUPER_ADMIN_USERNAME'),
            'email' => env('SUPER_ADMIN_EMAIL'),
            'role' => 'super-admin',
            'password' => bcrypt(env('SUPER_ADMIN_PASSWORD')),
        ]);

        User::factory()->create([
            'id' => Str::uuid(),
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('admin'),
        ]);

        // Seeder untuk 10 data user dan dosen
        for ($i = 1; $i <= 5; $i++) {
            $gender = rand(1, 2);

            $uuid = Str::uuid();
            $nip = GenerationHelper::generateNIP($i, $gender);
            $nidn = GenerationHelper::generateNIDN();

            $user = User::create([
                'id' => $uuid,
                'name' => Factory::create()->name($gender == 1 ? 'male' : 'female'),
                'username' => $nidn,
                'email' => $nidn . '@unima.ac.id',
                'password' => bcrypt($nidn),
                'role' => 'lecturer',
            ]);

            Lecturer::create([
                'id' => Factory::create()->uuid(),
                'user_id' => $uuid,
                'nip' => $nip,
                'nidn' => $nidn,
                'front_degree' => 'Prof.',
                'back_degree' => 'Ph.D',
                'position' => 'Kaprodi',
                'rank' => 'Lektor Kepala',
                'phone_number' => '08' . sprintf('%09d', $i),
            ]);
        }

        $lecturers = Lecturer::all()->shuffle();
        $year = rand(2018, 2024);

        for ($i = 1; $i <= 999; $i++) {
            $gender = rand(0, 1);
            $nim = substr($year, -2) . sprintf('%03d', $i);
            $uuid = Str::uuid();
            $user = User::create([
                'id' => $uuid,
                'name' => Factory::create()->name($gender == 1 ? 'male' : 'female'),
                'email' => $nim . '@unima.ac.id',
                'username' => $nim,
                'password' => bcrypt($nim),
                'role' => 'student',
            ]);

            $supervisor_1 = $lecturers->random()->getAttributes();
            $supervisor_2 = $lecturers->random()->getAttributes();

            Student::create([
                'id' => Factory::create()->uuid(),
                'user_id' => $uuid,
                'lecturer_id_1' => $supervisor_1['id'],
                'lecturer_id_2' => $supervisor_2['id'],
                'nim' => $nim,
                'batch' => rand(2018, 2024),
                'concentration' => $this->randomKonsentrasi(),
            ]);
        }

    $categories = [
        'Surat Keterangan Aktif Kuliah',
        'Surat Izin Penelitian',
        'Surat Cuti Kuliah',
        'Surat Rekomendasi',
        'Surat Pengajuan Beasiswa',
        'Surat Keterangan Seminar Proposal',
        'Surat Keterangan Seminar Hasil Penelitian',
        'Surat Keterangan Ujian Komprehensif',
        'Berita Acara Konversi Nilai',
        'Surat Keterangan Tugas Akhir',
    ];

    foreach ($categories as $category) {
        Category::create([
            'name' => $category,
            'slug' => Str::slug($category)
        ]);
    }

    $categories = Category::all();

        // Ambil beberapa mahasiswa sebagai contoh (pastikan tabel students sudah ada datanya)
        $students = Student::all();

        $statuses = ['submitted', 'pending', 'proses_kajur', 'proses_dekan', 'done', 'rejected', 'canceled', 'expired'];

        foreach ($students as $student) {
            foreach (range(1, 5) as $index) {
                Submission::create([
                    'id' => Str::uuid(),
                    'student_id' => $student->id,
                    'category_id' => rand(1,9),
                    'status' => $statuses[rand(0, 7)],
                    'note' => null,
                    'file_result' => null,
                    'created_at' => Carbon::createFromFormat('Y-m-d H:i:s',
                               now()->subMonths(rand(0, 7))->subDays(rand(0, 30))->format('Y-m-d H:i:s')),
                'updated_at' => now()
                ]);
            }
        }
    // }

    // /**
    //  * Generate random konsentrasi.
    //  *
    //  * @return string
    //  */
    }
    private function randomKonsentrasi()
    {
        $konsentrasiOptions = ['RPL', 'TKJ', 'Multimedia'];
        return collect($konsentrasiOptions)->random();
    }
}

