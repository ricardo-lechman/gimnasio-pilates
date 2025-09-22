<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cronograma;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
