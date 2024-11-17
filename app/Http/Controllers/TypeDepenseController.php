<?php

namespace App\Http\Controllers;

use App\Models\TypeDepense;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
class TypeDepenseController extends Controller
{
    public function index()
    {
        $type_depenses = TypeDepense::all();
        return view('depenses.listTypeD', compact('type_depenses'));
    }
    public function exportPdf()
    {
        $type_depenses = TypeDepense::all();
        $pdf = Pdf::loadView('depenses.pdf_type', compact('type_depenses'));

        return $pdf->download('liste_Typedepenses.pdf');
    }
    public function create()
    {
        return view('depenses.TypeDepense');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Denomination' => 'required|string|max:100',
            'description' => 'nullable|string|max:100',
        ]);

        TypeDepense::create($validated);

        return redirect()->route('typesDepense.index')->with('success', 'Type de Depense ajoutée avec succès.');
    }

    public function show(TypeDepense $typeDepense)
    {
        return view('typesDepense.show', compact('typeDepense'));
    }

    public function edit(TypeDepense $typeDepense)
    {
        return view('typesDepense.edit', compact('typeDepense'));
    }

    public function update(Request $request,$id)
    {
        $validated = $request->validate([
            'Denomination' => 'required|string|max:100',
            'description' => 'nullable|string|max:100',
        ]);
        $typeDepense=TypeDepense::find($id);
        $typeDepense->update($validated);

        return response()->json(['success' => true, 'message' => 'Type de depense mise à jour avec succès']);
    }

    public function destroy($id)
    {
        $depense = TypeDepense::findOrFail($id);
        $depense->delete();

        return response()->json(['success' => true, 'message' => 'Type de depense supprimée avec succès.']);
    }
}

