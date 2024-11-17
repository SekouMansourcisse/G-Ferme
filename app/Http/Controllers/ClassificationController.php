<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use Illuminate\Http\Request;

class ClassificationController extends Controller
{
    public function index()
    {
        $classifications = Classification::all();
        return view('TriOeuf.classification', compact('classifications'));
    }

    public function create()
    {
        return view('classifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'OeufTotal' => 'required|string|max:100',
            'qteCasse' => 'required|string|max:100',
            'qte_nonCommerciale' => 'required|string|max:100',
            'Total_a_categoriser' => 'required|string|max:100',
            'date' => 'required|string|max:100',
            'bande_id' => 'required|integer',
        ]);

        Classification::create($request->all());
        return redirect()->route('classifications.index')->with('success', 'Classification created successfully.');
    }

    public function show(Classification $classification)
    {
        return view('classifications.show', compact('classification'));
    }

    public function edit(Classification $classification)
    {
        return view('classifications.edit', compact('classification'));
    }

    public function update(Request $request, $id)
    {
        $classification = Classification::findOrFail($id);
        $classification->qteCasse = $request->input('qte_casse');
        $classification->qte_nonCommerciale = $request->input('qte_non_commerciale');
        $classification->Total_a_categoriser = $classification->OeufTotal - ($classification->qteCasse + $classification->qte_nonCommerciale);
        $classification->save();

        return response()->json(['success' => true, 'message' => 'Classification mis à jour avec succès']);
    }



    public function destroy(Classification $classification)
    {
        $classification->delete();
        return redirect()->route('classifications.index')->with('success', 'Classification deleted successfully.');
    }
}
