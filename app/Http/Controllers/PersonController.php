<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;

class PersonController extends Controller
{
    public function index()
    {
        $personas = Person::all();
        return response()->json($personas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'avatar' => 'required|string', //no recibe file, espera la base64
        ]);

        Person::create([
            'name' => $request->name,
            'avatar' => $request->avatar,
        ]);

        return response()->json(['message' => 'Persona creada con éxito']);
    }
}
