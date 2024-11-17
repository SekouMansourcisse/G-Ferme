<?php

namespace App\Http\Controllers;

use App\Models\Reproduction;
use Illuminate\Http\Request;

class ReproductionController extends Controller
{
    public function index()
    {
        $reproductions = Reproduction::all();
        return view('reproductions.index', compact('reproductions'));
    }

    public function create()
    {
        return view('reproductions.create');
    }

    public function store(Request $request)
    {
        Reproduction::create($request->all());
        return redirect()->route('reproductions.index')->with('success', 'Reproduction ajoutée.');
    }

    public function show(Reproduction $reproduction)
    {
        return view('reproductions.show', compact('reproduction'));
    }

    public function edit(Reproduction $reproduction)
    {
        return view('reproductions.edit', compact('reproduction'));
    }

    public function update(Request $request, Reproduction $reproduction)
    {
        $reproduction->update($request->all());
        return redirect()->route('reproductions.index')->with('success', 'Reproduction mise à jour.');
    }

    public function destroy(Reproduction $reproduction)
    {
        $reproduction->delete();
        return redirect()->route('reproductions.index')->with('success', 'Reproduction supprimée.');
    }
}

