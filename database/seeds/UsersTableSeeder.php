<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
            [
                'id'=> 'fddfbfdb-a000-46d2-b10d-693160f521bb',
                'email'=> 'cemal.tester1@rifanmfauzi.com',
                'password' => '$2y$10$KpvUFDS7/qdAJf/GBwdhqO6AG5oL238BHap/EOQw8FlgeyToEDpEy',
                'role' => 1,
                'register_date'=> new \DateTime,
                'status' => 1,
                'address'=> 'Jalan Anomali no 90',
                'register_ip'=> '192.168.10.1',
                'biography'=> 'Iam Cemal Tester1',
                'verified' => true,
                'avatar'=> 'https://openclipart.org/image/2400px/svg_to_png/177482/ProfilePlaceholderSuit.png',
                'name'=> 'Cemal Tester 1',
                'phone'=> '093839434',
                'activation_date'=> new \DateTime,
                'updated_at'=> new \DateTime,
                'created_at'=> new \DateTime,
            ],
            [
                'id'=> '7239a85c-931e-4a8b-8f71-fbeec729a404',
                'email'=> 'cemal.tester2@rifanmfauzi.com',
                'password' => '$2y$10$KpvUFDS7/qdAJf/GBwdhqO6AG5oL238BHap/EOQw8FlgeyToEDpEy',
                'role' => 2,
                'register_date'=> new \DateTime,
                'status' => 1,
                'address'=> '187 Kingsford Street',
                'register_ip'=> '192.168.10.1',
                'biography'=> 'Iam Cemal Tester2',
                'verified' => true,
                'avatar'=> 'https://openclipart.org/image/2400px/svg_to_png/177482/ProfilePlaceholderSuit.png',
                'name'=> 'Cemal Tester 2',
                'phone'=> '39537384939',
                'activation_date'=> new \DateTime,
                'updated_at'=> new \DateTime,
                'created_at'=> new \DateTime,
            ],
            [
                'id'=> '392209bb-c668-4889-ac85-eac8c724df7e',
                'email'=> 'cemal.tester3@rifanmfauzi.com',
                'password' => '$2y$10$KpvUFDS7/qdAJf/GBwdhqO6AG5oL238BHap/EOQw8FlgeyToEDpEy',
                'role' => 2,
                'register_date'=> new \DateTime,
                'status' => 1,
                'address'=> '187 Kingsford Street',
                'register_ip'=> '192.168.10.1',
                'biography'=> 'Iam Cemal Tester3',
                'verified' => true,
                'avatar'=> 'https://openclipart.org/image/2400px/svg_to_png/177482/ProfilePlaceholderSuit.png',
                'name'=> 'Cemal Tester 3',
                'phone'=> '39537384939',
                'activation_date'=> new \DateTime,
                'updated_at'=> new \DateTime,
                'created_at'=> new \DateTime,
            ],
            [
                'id'=> '7239a85c-931e-4a8b-8f71-fbeec729a405',
                'email'=> 'dontuse1@rifanmfauzi.com',
                'password' => '$2y$10$KpvUFDS7/qdAJf/GBwdhqO6AG5oL238BHap/EOQw8FlgeyToEDpEy',
                'role' => 2,
                'register_date'=> new \DateTime,
                'status' => 1,
                'address'=> 'Dont use me',
                'register_ip'=> 'Dont use me',
                'biography'=> 'Dont use me',
                'verified' => true,
                'avatar'=> 'https://openclipart.org/image/2400px/svg_to_png/177482/ProfilePlaceholderSuit.png',
                'name'=> 'Dont use me',
                'phone'=> '39537384939',
                'activation_date'=> new \DateTime,
                'updated_at'=> new \DateTime,
                'created_at'=> new \DateTime,
            ],
        ]);
    }
}
