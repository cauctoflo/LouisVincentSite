<?php

namespace App\Modules\Internat\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Internat\Models\Eleve;
use Illuminate\Http\Request;

class EleveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eleves = Eleve::all();
        return view('Internat::eleve.index', compact('eleves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Internat::eleve.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Define validation rules here
        ]);

        Eleve::create($validated);

        return redirect()->route('Internat.eleve.index')
                         ->with('success', 'Eleve created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Eleve $eleve)
    {
        return view('Internat::eleve.show', compact('eleve'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Eleve $eleve)
    {
        return view('Internat::eleve.edit', compact('eleve'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Eleve $eleve)
    {
        $validated = $request->validate([
            // Define validation rules here
        ]);

        $eleve->update($validated);

        return redirect()->route('Internat.eleve.index')
                         ->with('success', 'Eleve updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Eleve $eleve)
    {
        $eleve->delete();

        return redirect()->route('Internat.eleve.index')
                         ->with('success', 'Eleve deleted successfully');
    }
}