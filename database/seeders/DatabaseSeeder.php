<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\ToolReturn;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@ukk.local'],
            [
                'name' => 'Admin UKK',
                'role' => 'admin',
                'phone' => '081234567890',
                'password' => Hash::make('password123'),
            ]
        );

        $petugas = User::updateOrCreate(
            ['email' => 'petugas@ukk.local'],
            [
                'name' => 'Petugas UKK',
                'role' => 'petugas',
                'phone' => '081234567891',
                'password' => Hash::make('password123'),
            ]
        );

        $peminjam = User::updateOrCreate(
            ['email' => 'peminjam@ukk.local'],
            [
                'name' => 'Peminjam UKK',
                'role' => 'peminjam',
                'phone' => '081234567892',
                'password' => Hash::make('password123'),
            ]
        );

        $categories = collect([
            ['name' => 'Multimedia', 'description' => 'Peralatan presentasi dan dokumentasi'],
            ['name' => 'Jaringan', 'description' => 'Peralatan jaringan komputer'],
            ['name' => 'Elektronika', 'description' => 'Peralatan praktikum elektronika'],
        ])->map(fn (array $category) => Category::updateOrCreate(['name' => $category['name']], $category));

        $toolsData = [
            ['code' => 'ALT-001', 'name' => 'Laptop Core i5', 'category' => 'Multimedia', 'total_stock' => 10, 'available_stock' => 9, 'condition' => 'baik', 'location' => 'Lab 1', 'status' => 'active', 'description' => 'Laptop untuk praktikum'],
            ['code' => 'ALT-002', 'name' => 'Proyektor Epson', 'category' => 'Multimedia', 'total_stock' => 5, 'available_stock' => 5, 'condition' => 'baik', 'location' => 'Ruang Meeting', 'status' => 'active', 'description' => 'Proyektor presentasi'],
            ['code' => 'ALT-003', 'name' => 'Router Mikrotik', 'category' => 'Jaringan', 'total_stock' => 6, 'available_stock' => 6, 'condition' => 'baik', 'location' => 'Lab Jaringan', 'status' => 'active', 'description' => 'Router training'],
            ['code' => 'ALT-004', 'name' => 'Multimeter Digital', 'category' => 'Elektronika', 'total_stock' => 12, 'available_stock' => 12, 'condition' => 'baik', 'location' => 'Lab Elektro', 'status' => 'active', 'description' => 'Alat ukur tegangan/arus'],
        ];

        foreach ($toolsData as $toolData) {
            $category = $categories->firstWhere('name', $toolData['category']);
            Tool::updateOrCreate(
                ['code' => $toolData['code']],
                [
                    'name' => $toolData['name'],
                    'category_id' => $category?->id,
                    'total_stock' => $toolData['total_stock'],
                    'available_stock' => $toolData['available_stock'],
                    'condition' => $toolData['condition'],
                    'location' => $toolData['location'],
                    'status' => $toolData['status'],
                    'description' => $toolData['description'],
                ]
            );
        }

        $laptop = Tool::where('code', 'ALT-001')->first();
        $router = Tool::where('code', 'ALT-003')->first();

        if ($laptop && $router) {
            Loan::updateOrCreate(
                ['code' => 'LOAN-SEED-001'],
                [
                    'user_id' => $peminjam->id,
                    'tool_id' => $laptop->id,
                    'approved_by' => $petugas->id,
                    'loan_date' => now()->subDays(2)->toDateString(),
                    'due_date' => now()->addDays(2)->toDateString(),
                    'qty' => 1,
                    'purpose' => 'Presentasi tugas pra ujikom',
                    'status' => Loan::STATUS_BORROWED,
                ]
            );

            $returnedLoan = Loan::updateOrCreate(
                ['code' => 'LOAN-SEED-002'],
                [
                    'user_id' => $peminjam->id,
                    'tool_id' => $router->id,
                    'approved_by' => $petugas->id,
                    'returned_verified_by' => $admin->id,
                    'loan_date' => now()->subDays(10)->toDateString(),
                    'due_date' => now()->subDays(6)->toDateString(),
                    'return_date' => now()->subDays(5)->toDateString(),
                    'qty' => 1,
                    'purpose' => 'Praktikum routing',
                    'status' => Loan::STATUS_RETURNED,
                    'return_note' => 'Dikembalikan dalam kondisi baik',
                ]
            );

            ToolReturn::updateOrCreate(
                ['loan_id' => $returnedLoan->id],
                [
                    'requested_return_date' => now()->subDays(5)->toDateString(),
                    'received_date' => now()->subDays(5)->toDateString(),
                    'status' => 'verified',
                    'condition_after_return' => 'baik',
                    'fine' => 0,
                    'note' => 'Selesai',
                    'processed_by' => $admin->id,
                ]
            );
        }
    }
}
