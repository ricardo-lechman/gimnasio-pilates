<?php

namespace App\Http\Controllers;

abstract class Controller
{

namespace App\Http\Controllers;

use App\Models\Bed;
use Illuminate\Http\Request;

class BedController extends Controller
{
    public function index()
    {
        return Bed::all();
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        return Bed::create($request->only('nombre'));
    }

    public function destroy(Bed $bed)
    {
        $bed->delete();
        return response()->noContent();
    }
}

}