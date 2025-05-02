<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MahasiswaModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = MahasiswaModel::all();
        return response()->json($mahasiswa);
    }

    public function show($id)
    {
        $mahasiswa = MahasiswaModel::find($id);
        if ($mahasiswa) {
            return response()->json($mahasiswa);
        } else {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|unique:tbl_mahasiswa',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tbl_mahasiswa',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $mahasiswa = MahasiswaModel::create($request->all());

        return response()->json($mahasiswa, 201);
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = MahasiswaModel::find($id);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nim' => 'required|unique:tbl_mahasiswa,nim,' . $mahasiswa->id . ',id',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:tbl_mahasiswa,email,' . $mahasiswa->id . ',id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $mahasiswa->update($request->only(['nim', 'nama', 'email']));

        return response()->json($mahasiswa);
    }

    public function destroy($id)
    {
        $mahasiswa = MahasiswaModel::find($id);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        }

        $mahasiswa->delete();

        return response()->json(['message' => 'Mahasiswa deleted']);
    }
}
