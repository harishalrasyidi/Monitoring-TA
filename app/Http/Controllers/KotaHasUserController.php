<?php

// App\Http\Controllers\KotaHasUserController.php
namespace App\Http\Controllers;

use App\Models\KotaHasUserModel;
use App\Models\KotaModel;
use App\Models\User;
use Illuminate\Http\Request;

class KotaHasUserController extends Controller
{
    public function index()
    {
        $kotaHasUsers = KotaHasUserModel::with(['kota', 'user'])->get();
        return view('kota_has_user.index', compact('kotaHasUsers'));
    }

    public function create()
    {
        $kotas = KotaModel::all();
        $users = User::all();
        return view('kota_has_user.create', compact('kotas', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kota' => 'required|exists:tbl_kota,id_kota',
            'id_user' => 'required|exists:tbl_user,id_user',
        ]);

        KotaHasUserModel::create($request->all());

        return redirect()->route('kota_has_user.index')->with('success', 'KotaModel-User association created successfully.');
    }

    public function edit($id)
    {
        $kotaHasUser = KotaHasUserModel::findOrFail($id);
        $kotas = KotaModel::all();
        $users = User::all();
        return view('kota_has_user.edit', compact('kotaHasUser', 'kotas', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kota' => 'required|exists:tbl_kota,id_kota',
            'id_user' => 'required|exists:tbl_user,id_user',
        ]);

        $kotaHasUser = KotaHasUserModel::findOrFail($id);
        $kotaHasUser->update($request->all());

        return redirect()->route('kota_has_user.index')->with('success', 'Kota-User association updated successfully.');
    }

    public function destroy($id)
    {
        $kotaHasUser = KotaHasUserModel::findOrFail($id);
        $kotaHasUser->delete();

        return redirect()->route('kota_has_user.index')->with('success', 'Kota-User association deleted successfully.');
    }
}
