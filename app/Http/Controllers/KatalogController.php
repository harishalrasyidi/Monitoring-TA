<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KotaHasArtefakModel;

class KatalogController extends Controller
{
    public function index()
    {
        $katalog = KotaHasArtefakModel::all();
        return view('katalog.katalog', compact('katalog'));
    }
}
