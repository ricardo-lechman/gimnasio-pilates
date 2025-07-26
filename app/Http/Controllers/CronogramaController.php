<?php

namespace App\Http\Controllers;

abstract class Controller
{



namespace App\Http\Controllers;

use App\Models\Cronograma;
use Illuminate\Http\Request;

class CronogramaController extends Controller
{
    public function index()
    {
        return Cronograma::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
        return Cronograma::create($request->all());
    }
}


}