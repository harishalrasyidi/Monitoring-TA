<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DosenModel;
use Illuminate\Http\Request;


class DosenController extends Controller
{
    public function index()
    {
        $dosen = DosenModel::all();
        return response()->json($dosen);
    }

    public function show($id)
    {
        $dosen = DosenModel::find($id);
        if ($dosen) {
            return response()->json($dosen);
        } else {
            return response()->json(['message' => 'Dosen not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:tbl_dosen',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tbl_dosen',
        ]);

        if ($request->fails()) {
            return response()->json($request->errors(), 400);
        }

        $dosen = DosenModel::create($request->all());

        return response()->json($dosen, 201);
    }

    public function update(Request $request, $id)
    {
        $dosen = DosenModel::find($id);
        if (!$dosen) {
            return response()->json(['message' => 'Dosen not found'], 404);
        }

        $request->validate([
            'nip' => 'required|unique:tbl_dosen,nip,'.$dosen->id.',id',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tbl_dosen,email,'.$dosen->id.',id',
        ]);

        if ($request->fails()) {
            return response()->json($request->errors(), 400);
        }

        $dosen->update($request->all());

        return response()->json($dosen);
    }

    public function destroy($id)
    {
        $dosen = DosenModel::find($id);
        if (!$dosen) {
            return response()->json(['message' => 'Dosen not found'], 404);
        }

        $dosen->delete();

        return response()->json(['message' => 'Dosen deleted']);
    }
}
