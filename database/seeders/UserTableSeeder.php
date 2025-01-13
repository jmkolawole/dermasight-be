<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createUserIfNotExist('kolawole', 'jmkolawole@gmail.com', 'Jimoh', 'Kolawole', '');
        $this->createUserIfNotExist('doe', 'johndoe@gmail.com', 'John', 'Doe', '');
    }

    private function createUserIfNotExist($username, $email, $firstName, $lastName, $profileImage)
    {
        User::firstOrCreate(
            ['email' => $email],
            [
                'username' => $username,
                'password' => bcrypt('Pa$$w0rd'),
                'status' => 1,
                'firstname' => $firstName,
                'lastname' => $lastName,
                'profile_image' => $profileImage,
            ]
        );
    }
}
