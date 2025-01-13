<?php

namespace Tests;

use App\Models\Armors;
use App\Models\Sets;
use App\Models\Skills;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $adminUser;
    protected $skills;
    protected $armors;
    protected $set;

    public function setUp(): void
    {
        parent::setup();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');
        Artisan::call('passport:keys');
        Artisan::call('passport:client', [
            '--name' => 'ClientTest',
            '--no-interaction' => true,
            '--personal' => true,
        ]);
        $this->user = User::factory()->asHunter()->create();
        $this->adminUser = User::factory()->asAdmin()->create();
        $this->armors = Armors::factory()->count(5)->create();
        $this->skills = Skills::factory()->count(5)->create();

        foreach($this->armors as $armor) {
            $armor->skills()->attach($this->skills[0]->id, ['level' => 1]);
        }
        $armorsWithSkills = $this->armors->pluck('id')->toArray();

        DB::transaction(function() use($armorsWithSkills){
            $this->set = Sets::create([
                'name' => 'Alatreon Set'
            ]);
            foreach($armorsWithSkills as $armors){
                DB::table('sets_have_armors')->insert([
                    'id_sets' => $this->set->id,
                    'id_armors' => $armors,
                ]);
            }
        });

        $this->skills = Skills::create([
            'name' => 'defense bonus',
            'effect' => json_encode(["1" => "defense + 5",
             "2" =>"defense +10"])
        ]);
    }
}
