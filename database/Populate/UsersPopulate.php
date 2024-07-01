<?php

namespace Database\Populate;

use App\Models\User;

class UsersPopulate
{
    public static function populate()
    {
        $namesMap = [
            1 => "Fulano ",
            2 => "Ciclano ",
            3 => "Beltrano ",
            4 => "Mano ",
        ];

        $data = [
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'created_at' => date_create()->format('Y-m-d H:i:s'),
            'is_admin' => true
        ];

        $user = new User($data);
        $user->save();

        $numberOfUsers = 5;

        for ($i = 1; $i < $numberOfUsers; $i++) {
            $data = [
                'name' => $namesMap[rand(1, 4)] . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => '123456',
                'password_confirmation' => '123456',
                'created_at' => date_create()->format('Y-m-d H:i:s'),
                'is_admin' => false
            ];

            $user = new User($data);
            $user->save();
        }


        echo "Users populated with $numberOfUsers registers\n";
    }
}
