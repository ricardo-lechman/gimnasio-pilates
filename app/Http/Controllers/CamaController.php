<?php

namespace App\Http\Controllers;

abstract class Controller
{

namespace App\Http\Controllers;

use App\Models\Cama;
use Illuminate\Http\Request;

class CamaController extends Controller
{
    public function index()
    {
        return Cama::all();
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        return Cama::create($request->only('nombre'));
    }

    public function destroy(Cama $Cama)
    {
        $Cama->delete();
        return response()->noContent();
    }
}

}