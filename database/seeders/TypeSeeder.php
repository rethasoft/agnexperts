<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Type;
use App\Models\User;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first tenant user or create one for seeding
        $tenant = User::where('type', 'tenant')->first();
        
        if (!$tenant) {
            $tenant = User::create([
                'name' => 'AGN Experts',
                'email' => 'admin@agnexperts.be',
                'password' => bcrypt('password'),
                'type' => 'tenant',
            ]);
        }

        $types = [
            // EPC & Energie Certificaten
            [
                'name' => 'EPC Attest (Energieprestatiecertificaat)',
                'short_name' => 'EPC Attest',
                'price' => 150.00,
                'extra' => true,
                'extra_price' => 50.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => false,
                'sort_order' => 1
            ],
            [
                'name' => 'EPC+ Attest (Uitgebreid EPC)',
                'short_name' => 'EPC+ Attest',
                'price' => 200.00,
                'extra' => true,
                'extra_price' => 75.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => false,
                'sort_order' => 2
            ],

            // Asbest & Milieu
            [
                'name' => 'Asbestinventarisatie',
                'short_name' => 'Asbestinventarisatie',
                'price' => 300.00,
                'extra' => true,
                'extra_price' => 100.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => false,
                'sort_order' => 3
            ],
            [
                'name' => 'Asbestverwijdering & Sanering',
                'short_name' => 'Asbestverwijdering',
                'price' => 0.00,
                'extra' => false,
                'extra_price' => 0.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => true,
                'sort_order' => 4
            ],

            // Elektrische Keuringen
            [
                'name' => 'Elektrische Keuring',
                'short_name' => 'Elektrische Keuring',
                'price' => 120.00,
                'extra' => true,
                'extra_price' => 30.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => false,
                'sort_order' => 5
            ],
            [
                'name' => 'Elektrische Installatie Certificaat',
                'short_name' => 'Elektrisch Certificaat',
                'price' => 80.00,
                'extra' => false,
                'extra_price' => 0.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => false,
                'sort_order' => 6
            ],

            // Gas & Verwarming
            [
                'name' => 'Gasinstallatie Keuring',
                'short_name' => 'Gaskeuring',
                'price' => 100.00,
                'extra' => true,
                'extra_price' => 25.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => false,
                'sort_order' => 7
            ],
            [
                'name' => 'Verwarmingsketel Onderhoud',
                'short_name' => 'Ketel Onderhoud',
                'price' => 80.00,
                'extra' => false,
                'extra_price' => 0.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => false,
                'sort_order' => 8
            ],

            // Vastgoed & Bouw
            [
                'name' => 'Bouwtechnische Keuring',
                'short_name' => 'Bouwkeuring',
                'price' => 250.00,
                'extra' => true,
                'extra_price' => 100.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => false,
                'sort_order' => 9
            ],
            [
                'name' => 'Dakkeuring & Onderhoud',
                'short_name' => 'Dakkeuring',
                'price' => 180.00,
                'extra' => true,
                'extra_price' => 50.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => false,
                'sort_order' => 10
            ],

            // Milieu & Duurzaamheid
            [
                'name' => 'Milieuvergunning Advies',
                'short_name' => 'Milieuvergunning',
                'price' => 0.00,
                'extra' => false,
                'extra_price' => 0.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => true,
                'sort_order' => 11
            ],
            [
                'name' => 'Duurzaamheidsaudit',
                'short_name' => 'Duurzaamheidsaudit',
                'price' => 200.00,
                'extra' => true,
                'extra_price' => 75.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => false,
                'sort_order' => 12
            ],

            // Speciale Keuringen
            [
                'name' => 'Liftkeuring',
                'short_name' => 'Liftkeuring',
                'price' => 150.00,
                'extra' => false,
                'extra_price' => 0.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => false,
                'sort_order' => 13
            ],
            [
                'name' => 'Brandveiligheidskeuring',
                'short_name' => 'Brandveiligheid',
                'price' => 120.00,
                'extra' => true,
                'extra_price' => 40.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => false,
                'sort_order' => 14
            ],

            // Renovatie & Verbouwing
            [
                'name' => 'Renovatieadvies',
                'short_name' => 'Renovatieadvies',
                'price' => 0.00,
                'extra' => false,
                'extra_price' => 0.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => true,
                'sort_order' => 15
            ],
            [
                'name' => 'Bouwvergunning Begeleiding',
                'short_name' => 'Bouwvergunning',
                'price' => 0.00,
                'extra' => false,
                'extra_price' => 0.00,
                'regions' => ['brussel', 'vlaanderen'],
                'is_offerte' => true,
                'sort_order' => 16
            ]
        ];

        foreach ($types as $typeData) {
            Type::create([
                'name' => $typeData['name'],
                'short_name' => $typeData['short_name'],
                'price' => $typeData['price'],
                'extra' => $typeData['extra'],
                'extra_price' => $typeData['extra_price'],
                'regions' => $typeData['regions'],
                'is_offerte' => $typeData['is_offerte'],
                'sort_order' => $typeData['sort_order'],
                'category_id' => 0, // Ana kategoriler
                'tenant_id' => $tenant->id,
            ]);
        }

        $this->command->info('Types seeded successfully!');
    }
}
