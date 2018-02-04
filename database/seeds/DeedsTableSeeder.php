<?php

use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;

class DeedsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('deeds')->insert([
            [
                "id"=> (string) Uuid::generate(4),
                "user_id"=> "fddfbfdb-a000-46d2-b10d-693160f521bb",
                "title"=> "Shalat Sunnah Wudhu",
                "description"=> "Shalat sunnah setelah wudhu",
                "public"=> true,
            ],
            [
                "id"=> (string) Uuid::generate(4),
                "user_id"=> "fddfbfdb-a000-46d2-b10d-693160f521bb",
                "title"=> "Shalat Sunnah Rawatib",
                "description"=> "Shalat sunnah setelah shalat fardhu",
                "public"=> true,
            ],
            [
                "id"=> (string) Uuid::generate(4),
                "user_id"=> "7239a85c-931e-4a8b-8f71-fbeec729a404",
                "title"=> "Puasa Daud",
                "description"=> "sehari puasa sehari tidak puasa",
                "public"=> false,
            ],
            [
                "id"=> (string) Uuid::generate(4),
                "user_id"=> "7239a85c-931e-4a8b-8f71-fbeec729a404",
                "title"=> "Puasa Syawal",
                "description"=> "6 hari du bulan syaway",
                "public"=> false,
            ],
        ]);
    }
}
