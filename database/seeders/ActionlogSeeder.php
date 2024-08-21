<?php

namespace Database\Seeders;

use App\Models\Actionlog;
use App\Models\Asset;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActionlogSeeder extends Seeder
{
    public function run()
    {
        Actionlog::truncate();

        if (!Asset::count()) {
            $this->call(AssetSeeder::class);
        }

        if (!Location::count()) {
            $this->call(LocationSeeder::class);
        }

        $admin = User::where('permissions->superuser', '1')->first() ?? User::factory()->firstAdmin()->create();

        if (config('app.large_seeder')) {
            $records = [
                Actionlog::factory()->count(300_000)->assetCheckoutToUser()->make(['user_id' => $admin->id]),
                Actionlog::factory()->count(100_000)->assetCheckoutToLocation()->make(['user_id' => $admin->id]),
                Actionlog::factory()->count(20_000)->licenseCheckoutToUser()->make(['user_id' => $admin->id]),
            ];

            foreach ($records as $record) {
                $record->chunk(2000)->each(function ($records) {
                    Actionlog::insert($records->toArray());
                });
            }
        } else {
            Actionlog::factory()->count(300)->assetCheckoutToUser()->create(['user_id' => $admin->id]);
            Actionlog::factory()->count(100)->assetCheckoutToLocation()->create(['user_id' => $admin->id]);
            Actionlog::factory()->count(20)->licenseCheckoutToUser()->create(['user_id' => $admin->id]);
        }
    }
}
