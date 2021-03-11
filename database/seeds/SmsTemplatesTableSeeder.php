<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmsTemplatesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table("sms_templates")->insert([
            'template_type' => 'verification_code',
            'template_subject' => 'Verification code',
            'default_content' =>
                'Your verification code is {verification_code}'
        ]);
    }
}