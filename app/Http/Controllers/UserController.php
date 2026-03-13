<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $users = User::paginate(10);

        return response()->json($users,200);
    }

    public function store(Request $request)
    {
        if(auth()->user()->role !== 'admin'){
            return response()->json([
                'message' => 'Acesso não autorizado'
            ],403);
        }

        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|string'
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return response()->json([
            'message' => 'Usuário criado',
            'data' => $user
        ],201);
    }


    public function show(User $user)
    {
        return response()->json([
            'data' => $user
        ],200);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email',
            'role' => 'sometimes|string'
        ]);

        $user->update($data);

        return response()->json([
            'data' => $user
        ]);
    }

    public function destroy(User $user)
    {
        if(auth()->user()->role !== 'admin'){
            return response()->json([
                'message' => 'Acesso não autorizado'
            ],403);
        }

        $user->delete();

        return response()->json([
            'message' => 'Usuário removido'
        ],200);
    }
}