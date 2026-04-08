<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();
        $user->update($request->validate([
            'name' => 'required|string|max:255',
        ]));
        return response()->json($user);
    }
}
