<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class ProfileController extends Controller
{
    public function show(): void
    {
        $user = $this->current_user;

        $this->render('profile/show', compact('user'));
    }

    public function updateAvatar(Request $request): void
    {
        $image = $_FILES['user_avatar'];

        $this->current_user->avatar()->update($image);
        $user = $this->current_user;

        $this->render('profile/show', compact('user'));
    }

    public function index(): void
    {
        $users = User::all();

        $this->render('profile/index', compact('users'));
    }
}
