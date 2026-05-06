<?php

namespace Database\Seeders;

use App\Models\PolicyPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrivacyPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $policyPages = [
            ['name' => 'Privacy Policy', 'title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'created_at' => now()],
            ['name' => 'Terms & Conditions', 'title' => 'Terms & Conditions', 'slug' => 'terms-and-conditions', 'created_at' => now()],
            ['name' => 'Refund Policy', 'title' => 'Refund Policy', 'slug' => 'refund-policy', 'created_at' => now()],
        ];
        PolicyPage::insert($policyPages);
    }
}
