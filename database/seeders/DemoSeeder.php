<?php

namespace Database\Seeders;

use App\Models\Subsidiary;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------------------------------------
        // 1. George Steuart Group — Official Subsidiaries
        //    Source: https://georgesteuart.lk/
        // -------------------------------------------------------
        $subsidiariesData = [
            [
                'code' => 'GSH',
                'azure_id' => 'az-dept-george-steuart-health',
                'name' => 'George Steuart Health',
                'description' => 'Pharmaceuticals, Medical Equipment & Healthcare distribution.',
                'contact_email' => 'health@georgesteuart.com',
                'status' => 'active',
            ],
            [
                'code' => 'GST',
                'azure_id' => 'az-dept-george-steuart-teas',
                'name' => 'George Steuart Teas',
                'description' => 'Tea Exporting, Value-Added Tea Manufacturing & Heladiv brand.',
                'contact_email' => 'teas@georgesteuart.com',
                'status' => 'active',
            ],
            [
                'code' => 'GSC',
                'azure_id' => 'az-dept-george-steuart-consumer',
                'name' => 'George Steuart Consumer',
                'description' => 'Fast-Moving Consumer Goods (FMCG), Personal Care & Home Care.',
                'contact_email' => 'consumer@georgesteuart.com',
                'status' => 'active',
            ],
            [
                'code' => 'GSTA',
                'azure_id' => 'az-dept-george-steuart-travel-aviation',
                'name' => 'George Steuart Travel & Aviation',
                'description' => 'Comprehensive Business Travel, Leisure Tourism & Aviation Services.',
                'contact_email' => 'travel@georgesteuart.com',
                'status' => 'active',
            ],
            [
                'code' => 'GSS',
                'azure_id' => 'az-dept-george-steuart-solutions',
                'name' => 'George Steuart Solutions',
                'description' => 'Industrial Solutions, Enterprise Infrastructure & Technology.',
                'contact_email' => 'solutions@georgesteuart.com',
                'status' => 'active',
            ],
            [
                'code' => 'GSF',
                'azure_id' => 'az-dept-george-steuart-financial-services',
                'name' => 'George Steuart Financial Services',
                'description' => 'Insurance Broking, Investment Management & Advisory.',
                'contact_email' => 'financial@georgesteuart.com',
                'status' => 'active',
            ],
            [
                'code' => 'GSL',
                'azure_id' => 'az-dept-george-steuart-leisure',
                'name' => 'George Steuart Leisure',
                'description' => 'Hotels, Resorts & Hospitality Management (Citrus).',
                'contact_email' => 'leisure@georgesteuart.com',
                'status' => 'active',
            ],

            [
                'code' => 'GSREC',
                'azure_id' => 'az-dept-george-steuart-recruitment',
                'name' => 'George Steuart Recruitment',
                'description' => 'Professional Talent Sourcing & Executive Recruitment.',
                'contact_email' => 'recruitment@georgesteuart.com',
                'status' => 'active',
            ],
            [
                'code' => 'GSOPT',
                'azure_id' => 'az-dept-george-steuart-optimize',
                'name' => 'George Steuart Optimize',
                'description' => 'Digital Transformation, Process Optimization & Analytics.',
                'contact_email' => 'optimize@georgesteuart.com',
                'status' => 'active',
            ],
        ];

        foreach ($subsidiariesData as $data) {
            Subsidiary::updateOrCreate(['code' => $data['code']], $data);
        }

        // -------------------------------------------------------
        // 2. Super Administrator Account
        //    This is the only pre-seeded user.
        //    All real users will log in via Microsoft Azure SSO.
        // -------------------------------------------------------
        $gsoptSub = Subsidiary::where('code', 'GSOPT')->first() ?? Subsidiary::first();

        $admin = User::firstOrCreate(
            ['email' => 'superadmin@georgesteuart.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('Password@123'),
                'phone_number' => '+94 11 234 5678',
                'subsidiary_id' => $gsoptSub?->id,
                'is_active' => true,
                'must_change_password' => false,
                'email_verified_at' => now(),
            ]
        );

        if (!$admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }

        $this->command->info('✅ Subsidiaries seeded: ' . count($subsidiariesData));
        $this->command->info('✅ Super Admin ready: superadmin@georgesteuart.com / Password@123');
        $this->command->info('ℹ️  All other users will register via Microsoft Azure SSO login.');
    }
}
