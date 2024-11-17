<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use Illuminate\Http\Request;

class EntrepriseController extends Controller
{
    public function index()
    {
        $entreprises = Entreprise::all();
        return view('entreprise.list', compact('entreprises'));
    }

    public function create()
    {
        return view('entreprise.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'adresse' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        Entreprise::create($validated);

        return redirect()->route('entreprises.index');
    }

    public function edit(Entreprise $entreprise)
    {
        return view('entreprises.edit', compact('entreprise'));
    }

    public function update(Request $request, Entreprise $entreprise)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'adresse' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $entreprise->update($validated);

        return redirect()->route('entreprises.index');
    }

    public function destroy(Entreprise $entreprise)
    {
        $entreprise->delete();

        return redirect()->route('entreprises.index');
    }
}
