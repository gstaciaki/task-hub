<?php

namespace Database\Populate;

use App\Models\User;

class UsersPopulate
{
    public static function populate()
    {
        $data = [
            'name' => 'root',
            'email' => 'root@example.com',
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
                'name' => 'Fulano ' . $i,
                'email' => 'fulano' . $i . '@example.com',
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
