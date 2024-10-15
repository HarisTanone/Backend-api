<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Validator;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return response()->json(['data' => $users]);
    }

    public function show($id)
    {
        $user = $this->userService->findUserById($id);
        return response()->json(['data' => $user]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:4|max:100',
            'username' => 'required|string|min:4|max:100|unique:users',
            'password' => 'required|string|min:8|max:100',
            'confirm_password' => 'required|string|min:8|max:100|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid Input',
                'validations' => $validator->errors()
            ], 400);
        }

        $this->userService->createUser($request->all());

        return response()->json(['message' => 'Username berhasil disimpan']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:4|max:100',
            'username' => 'required|string|min:4|max:100|unique:users,username,'.$id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid Input',
                'validations' => $validator->errors()
            ], 400);
        }

        $this->userService->updateUser($request->all(), $id);

        return response()->json(['message' => 'Username berhasil diperbarui']);
    }

    public function updatePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|max:100',
            'confirm_password' => 'required|string|min:8|max:100|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid Input',
                'validations' => $validator->errors()
            ], 400);
        }

        $this->userService->updatePassword($request->all(), $id);

        return response()->json(['message' => 'Password berhasil diperbarui']);
    }

    public function destroy(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'confirm_password' => 'required|string|min:8|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid Input',
                'validations' => $validator->errors()
            ], 400);
        }

        try {
            $this->userService->deleteUser($id, $request->confirm_password);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return response()->json(['message' => 'User berhasil dihapus']);
    }
}
