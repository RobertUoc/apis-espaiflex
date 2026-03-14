<?php

namespace App\Http\Controllers;

use App\Models\Complement;
use Illuminate\Http\Request;

class ComplementController extends Controller
{
    // GET /api/complements
    public function index(Request $request)
    {
        if ($request->has('id')) {
            return Complement::findOrFail($request->id);
        }

        return Complement::all();
    }

    // POST /api/complements
    public function store(Request $request)
    {
        $validated = $request->validate([
            'descripcio' => 'required|string|max:200',
            'preu'       => 'required|numeric',
            'actiu'      => 'required|string|max:2',
        ]);

        $complement = Complement::create($validated);

        return response()->json([
            'success' => true,
            'id' => $complement->id
        ], 201);
    }

    // PUT /api/complements/{id}
    public function update(Request $request, $id)
    {
        $complement = Complement::findOrFail($id);

        $validated = $request->validate([
            'descripcio' => 'required|string|max:200',
            'preu'       => 'required|numeric',
            'actiu'      => 'required|string|max:2',
        ]);

        $complement->update($validated);

        return response()->json([
            'success' => true
        ]);
    }
}