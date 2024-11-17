<?php

namespace App\Http\Controllers;

use App\Models\CategorieOeuf;
use Illuminate\Http\Request;

class CategorieOeufController extends Controller
{
    public function index()
    {
        $categoriesOeuf = CategorieOeuf::all();
        return view('TriOeuf.list', compact('categoriesOeuf'));
    }

    public function create()
    {
        return view('TriOeuf.addCategories');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Denomination' => 'required|string|max:100',
            'PrixUnitaire' => 'required|string|max:100',
            'PrixPlateaux' => 'required|string|max:100',
            'description' => 'string|max:100',
        ]);

        CategorieOeuf::create($request->all());
        return redirect()->route('categorieOeufs.index')->with('success', 'CategorieOeuf created successfully.');
    }

    public function show(CategorieOeuf $categorieOeuf)
    {
        return view('categorieOeufs.show', compact('categorieOeuf'));
    }

    public function edit(CategorieOeuf $categorieOeuf)
    {
        return view('categorieOeufs.edit', compact('categorieOeuf'));
    }

    public function update(Request $request, CategorieOeuf $categorieOeuf)
    {
        $request->validate([
            'Denomination' => 'required|string|max:100',
            'PrixUnitaire' => 'required|string|max:100',
            'PrixPlateaux' => 'required|string|max:100',
            'qteOeuf' => 'required|string|max:100',
            'qteEnplateaux' => 'required|string|max:100',
        ]);

        $categorieOeuf->update($request->all());
        return response()->json(['success' => true, 'message' => 'Categorie mise à jour avec succès']);
    }

    public function destroy($id)
    {
        $categorie = CategorieOeuf::findOrFail($id);
        $categorie->delete();

        return response()->json(['success' => true, 'message' => 'categorie supprimée avec succès.']);
    }
}
