<?php

namespace Database\Seeders;

use App\Models\Actionlog;
use App\Models\Asset;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ActionlogSeeder extends Seeder
{
    public function run()
    {
        dump('seeder invoked');
        if (! Asset::count()) {
            $this->call(AssetSeeder::class);
        }

        if (! Location::count()) {
            $this->call(LocationSeeder::class);
        }

        $admin = User::where('permissions->superuser', '1')->first() ?? User::factory()->firstAdmin()->create();

        dump('start assets checkout to user');
        Actionlog::factory()
            ->count(300)
            ->assetCheckoutToUser()
            ->create(['user_id' => $admin->id]);
        dump('end assets checkout to user');

        dump('start asset checkout to location');
        Actionlog::factory()
            ->count(100)
            ->assetCheckoutToLocation()
            ->create(['user_id' => $admin->id]);
        dump('end asset checkout to location');

        //Actionlog::factory()
        //    ->count(20)
        //    ->licenseCheckoutToUser()
        //    ->create(['user_id' => $admin->id]);
    }
}
