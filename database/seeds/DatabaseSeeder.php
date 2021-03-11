<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
           EmailTemplatesTableSeeder::class,
           SmsTemplatesTableSeeder::class,
           RoleTableSeeder::class,
           UsersTableSeeder::class,
           SettingsTableSeeder::class,
           PaymentMethodsTableSeeder::class,
           ServicePolicyTableSeeder::class,
           ContactInformationTableSeeder::class,
        ]);
    }
}
