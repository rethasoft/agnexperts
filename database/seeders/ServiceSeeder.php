<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
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

        $services = [
            // EPC & Energie Certificaten
            [
                'name' => 'EPC Attest (Energieprestatiecertificaat)',
                'short_description' => 'Professioneel EPC attest voor woningen en gebouwen in heel België',
                'description' => 'Wij verzorgen complete EPC attesten voor alle types vastgoed. Onze gecertificeerde experts zorgen voor een snelle en nauwkeurige beoordeling van de energieprestatie van uw eigendom. Geschikt voor verkoop, verhuur en renovatieprojecten.',
                'image' => 'images/services/1739217772_epc-certificaat.jpg',
                'regions' => ['brussel', 'vlaanderen']
            ],
            [
                'name' => 'EPC+ Attest (Uitgebreid EPC)',
                'short_description' => 'Uitgebreid energieprestatiecertificaat met renovatieadvies',
                'description' => 'Een uitgebreid EPC attest met gedetailleerd renovatieadvies. Perfect voor eigenaars die hun woning willen verbeteren en de energie-efficiëntie willen verhogen. Inclusief concrete aanbevelingen en kostenramingen.',
                'image' => 'images/services/1739217857_energieadvies.jpg',
                'regions' => ['brussel', 'vlaanderen']
            ],

            // Asbest & Milieu
            [
                'name' => 'Asbestinventarisatie',
                'short_description' => 'Professionele asbestinventarisatie voor veilige renovatie',
                'description' => 'Uitgebreide asbestinventarisatie volgens de nieuwste wetgeving. Onze gecertificeerde experts identificeren alle asbesthoudende materialen en stellen een veilig renovatieplan op. Verplicht voor renovaties en sloopwerken.',
                'image' => 'images/services/1739217945_asbestattest.jpg',
                'regions' => ['brussel', 'vlaanderen']
            ],
            [
                'name' => 'Asbestverwijdering & Sanering',
                'short_description' => 'Veilige verwijdering en sanering van asbesthoudende materialen',
                'description' => 'Professionele asbestverwijdering door gecertificeerde specialisten. Wij zorgen voor een veilige en volledige verwijdering van asbesthoudende materialen volgens de strengste veiligheidsnormen.',
                'image' => 'images/services/1739035287_asb.png',
                'regions' => ['brussel', 'vlaanderen']
            ],

            // Elektrische Keuringen
            [
                'name' => 'Elektrische Keuring',
                'short_description' => 'Periodieke elektrische keuring voor woningen en bedrijven',
                'description' => 'Uitgebreide elektrische keuring door erkende elektriciens. Controle van alle elektrische installaties, zekeringkasten en aarding. Verplicht voor verkoop en verhuur van woningen.',
                'image' => 'images/services/1739218029_elektriciteitskeuring.jpg',
                'regions' => ['brussel', 'vlaanderen']
            ],
            [
                'name' => 'Elektrische Installatie Certificaat',
                'short_description' => 'Certificaat voor nieuwe elektrische installaties',
                'description' => 'Certificering van nieuwe elektrische installaties volgens de nieuwste normen. Verplicht voor alle nieuwe installaties en belangrijke wijzigingen aan bestaande installaties.',
                'image' => 'images/services/1739035321_ek.png',
                'regions' => ['brussel', 'vlaanderen']
            ],

            // Gas & Verwarming
            [
                'name' => 'Gasinstallatie Keuring',
                'short_description' => 'Veiligheidskeuring van gasinstallaties en verwarmingsketels',
                'description' => 'Periodieke keuring van gasinstallaties en verwarmingsketels door erkende technici. Controle van veiligheid, efficiëntie en naleving van de geldende normen. Verplicht voor verkoop en verhuur.',
                'image' => 'images/services/1739218100_gaskeuring.jpg',
                'regions' => ['brussel', 'vlaanderen']
            ],
            [
                'name' => 'Verwarmingsketel Onderhoud',
                'short_description' => 'Professioneel onderhoud van verwarmingsketels',
                'description' => 'Regelmatig onderhoud van uw verwarmingsketel voor optimale prestaties en veiligheid. Onze technici zorgen voor een efficiënte en betrouwbare verwarming.',
                'image' => 'images/services/1739218100_gaskeuring.jpg',
                'regions' => ['brussel', 'vlaanderen']
            ],

            // Vastgoed & Bouw
            [
                'name' => 'Bouwtechnische Keuring',
                'short_description' => 'Uitgebreide technische keuring van vastgoed',
                'description' => 'Complete bouwtechnische keuring van woningen en gebouwen. Controle van fundering, dak, muren, ramen en alle technische installaties. Essentieel voor aankoop en verkoop van vastgoed.',
                'image' => 'images/services/1739217772_epc-certificaat.jpg',
                'regions' => ['brussel', 'vlaanderen']
            ],
            [
                'name' => 'Dakkeuring & Onderhoud',
                'short_description' => 'Professionele dakkeuring en onderhoudsdiensten',
                'description' => 'Uitgebreide dakkeuring en onderhoud door ervaren dakwerkers. Controle van dakbedekking, goten, isolatie en ventilatie. Preventief onderhoud voorkomt dure reparaties.',
                'image' => 'images/services/1739217772_epc-certificaat.jpg',
                'regions' => ['brussel', 'vlaanderen']
            ],

            // Milieu & Duurzaamheid
            [
                'name' => 'Milieuvergunning Advies',
                'short_description' => 'Advies en begeleiding bij milieuvergunningen',
                'description' => 'Professioneel advies bij het aanvragen van milieuvergunningen. Wij helpen u door het complexe proces en zorgen voor een snelle en correcte aanvraag.',
                'image' => 'images/services/1739218181_ventilatieverslag.jpg',
                'regions' => ['brussel', 'vlaanderen']
            ],
            [
                'name' => 'Duurzaamheidsaudit',
                'short_description' => 'Audit van duurzaamheid en energie-efficiëntie',
                'description' => 'Uitgebreide duurzaamheidsaudit van uw vastgoed. Analyse van energieverbruik, watergebruik en milieuprestaties. Concrete aanbevelingen voor verbetering.',
                'image' => 'images/services/1739217857_energieadvies.jpg',
                'regions' => ['brussel', 'vlaanderen']
            ],

            // Speciale Keuringen
            [
                'name' => 'Liftkeuring',
                'short_description' => 'Periodieke keuring van liften en hefwerktuigen',
                'description' => 'Verplichte periodieke keuring van liften en hefwerktuigen door erkende keuringsinstanties. Controle van veiligheid en naleving van de geldende normen.',
                'image' => 'images/services/1739218029_elektriciteitskeuring.jpg',
                'regions' => ['brussel', 'vlaanderen']
            ],
            [
                'name' => 'Brandveiligheidskeuring',
                'short_description' => 'Keuring van brandveiligheid en evacuatievoorzieningen',
                'description' => 'Uitgebreide brandveiligheidskeuring van gebouwen en woningen. Controle van rookmelders, brandblussers, evacuatiewegen en brandveiligheidsvoorzieningen.',
                'image' => 'images/services/1739218181_ventilatieverslag.jpg',
                'regions' => ['brussel', 'vlaanderen']
            ],

            // Renovatie & Verbouwing
            [
                'name' => 'Renovatieadvies',
                'short_description' => 'Professioneel advies voor renovatieprojecten',
                'description' => 'Uitgebreid advies voor uw renovatieproject. Van planning tot uitvoering, wij begeleiden u door het hele proces en zorgen voor optimale resultaten.',
                'image' => 'images/services/1739217857_energieadvies.jpg',
                'regions' => ['brussel', 'vlaanderen']
            ],
            [
                'name' => 'Bouwvergunning Begeleiding',
                'short_description' => 'Begeleiding bij bouwvergunningen en stedenbouwkundige procedures',
                'description' => 'Professionele begeleiding bij het aanvragen van bouwvergunningen. Wij helpen u door de complexe procedures en zorgen voor een snelle goedkeuring.',
                'image' => 'images/services/1739217772_epc-certificaat.jpg',
                'regions' => ['brussel', 'vlaanderen']
            ]
        ];

        foreach ($services as $serviceData) {
            Service::create([
                'name' => $serviceData['name'],
                'slug' => Str::slug($serviceData['name']),
                'short_description' => $serviceData['short_description'],
                'description' => $serviceData['description'],
                'image' => $serviceData['image'],
                'regions' => $serviceData['regions'],
                'tenant_id' => $tenant->id,
            ]);
        }

        $this->command->info('Services seeded successfully!');
    }
}
