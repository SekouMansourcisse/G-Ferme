<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Traitement;
use Illuminate\Http\Request;

class TraitementController extends Controller
{
    public function index()
    {
        $traitements = Traitement::all();
        return view('Traitement.index', compact('traitements'));
    }
    public function addEvent(Request $request) {
        $request->validate([
            'date' => 'required|date',
            'denomination' => 'required|string',
            'description' => 'required|string'
        ]);

        $event = new Event;
        $event->event_name = $request->denomination;
        $event->description = $request->description;
        $event->event_start_date = $request->date;
        $event->event_end_date = $request->date;
        $event->save();

        return response()->json(['status' => true, 'msg' => 'Event saved successfully']);
    }


    public function getEvents()
    {
        $events = Event::all();
        $data_arr = [];

        foreach ($events as $key => $event) {
            $data_arr[$key]['event_id'] = $event->event_id;
            $data_arr[$key]['title'] = $event->event_name;
            $data_arr[$key]['start'] = date("Y-m-d", strtotime($event->event_start_date));
            $data_arr[$key]['end'] = date("Y-m-d", strtotime($event->event_end_date));
            $data_arr[$key]['color'] = '#'.substr(uniqid(), -6);
            $data_arr[$key]['url'] = '#';
        }

        if (!empty($data_arr)) {
            return response()->json([
                'status' => true,
                'msg' => 'Events retrieved successfully!',
                'data' => $data_arr
            ]);
        } else {
            return response()->json([
                'status' => false,
                'msg' => 'No events found.'
            ]);
        }
    }
    public function create()
    {
        return view('Traitement.add');
    }

    public function store(Request $request)
    {

        if($request->input('type')=="vache"){
                    // Création d'une nouvelle vache
        $traitement = new Traitement([
            'vache_id' => $request->input('vache_id'),
            'denomination' => $request->input('denomination'),
            'description' => $request->input('description'),
            'date' => $request->input('date'),
            'Produit' => $request->input('Produit'),
            'qte_utilise' => $request->input('qte_utilise'),
            'etat' => 1,
        ]);
        }else{
                    // Création d'une nouvelle vache
        $traitement = new Traitement([
            'bande_id' => $request->get('bande_id'),
            'denomination' => $request->input('denomination'),
            'description' => $request->input('description'),
            'date' => $request->input('date'),
            'Produit' => $request->input('Produit'),
            'qte_utilise' => $request->input('qte_utilise'),
            'etat' => 1
        ]);
        }

        $traitement->save();

                // Redirection avec un message de succès
                session()->flash('active_tab', 'soin');
        return redirect()->back()->with('success', 'Traitement ajoutée avec succès !');
    }

    public function show($id)
    {
        $traitement = Traitement::find($id);
        return view('traitements.show', compact('traitement'));
    }
    public function valider(Request $request)
    {
        $id=$request->input('traitement_id');
        $traitement = Traitement::find($id);
        $traitement->etat=$request->input('etat');
        $traitement->save();
        return redirect()->back()->with('success', 'Modification effectuée avec succès !');

    }

    public function edit($id)
    {
        $traitement = Traitement::find($id);
        return view('traitements.edit', compact('traitement'));
    }

    public function update(Request $request, $id)
    {
        $traitement = Traitement::find($id);
        $traitement->update($request->all());
        return response()->json(['success' => true, 'message' => 'traitement mise à jour avec succès']);
    }

    public function destroy($id)
    {
        $traitement = Traitement::find($id);
        $traitement->delete();
        return response()->json(['success' => true, 'message' => 'soin supprimée avec succès.']);
    }
}

