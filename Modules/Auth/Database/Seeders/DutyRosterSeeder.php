<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Entities\dutyRoster;

class DutyRosterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        dutyRoster::factory()
        ->count(30)
        ->create();

        // $this->call("OthersTableSeeder");
    }
}
