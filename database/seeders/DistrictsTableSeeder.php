<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Region;

class DistrictsTableSeeder extends Seeder
{
    public function run()
    {
        // Find the Songwe region by name, assuming it's already seeded
        $region = Region::where('name', 'Songwe')->first();

        // If Songwe region exists and has no districts added
        if ($region && $region->districts()->count() == 0) {
            $districts = ['Vwawa', 'Mbozi', 'Momba', 'Ileje', 'Songwe'];

            foreach ($districts as $districtName) {
                DB::table('districts')->insert([
                    'region_id' => $region->id,
                    'name' => $districtName
                ]);
            }
        }
    }
}

