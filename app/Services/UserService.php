<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAllUsers()
    {
        return User::select('username', 'name', 'created_by', 'created_at', 'updated_by', 'updated_at')->get();
    }

    public function findUserById($id)
    {
        return User::select('username', 'name', 'created_by', 'created_at', 'updated_by', 'updated_at')
            ->findOrFail($id);
    }

    public function createUser($data)
    {
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);
    }

    public function updateUser($data, $id)
    {
        $user = User::findOrFail($id);
        return $user->update([
            'name' => $data['name'],
            'username' => $data['username'],
            'updated_by' => auth()->id(),
        ]);
    }

    public function updatePassword($data, $id)
    {
        $user = User::findOrFail($id);
        return $user->update([
            'password' => Hash::make($data['password']),
            'updated_by' => auth()->id(),
        ]);
    }

    public function deleteUser($id, $confirmPassword)
    {
        $user = User::findOrFail($id);

        if (!Hash::check($confirmPassword, $user->password)) {
            throw new \Exception('Password konfirmasi salah');
        }

        $user->delete();
    }
}
