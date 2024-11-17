<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operation;

class OperationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $operation = Operation::insertOperation($data);
        return response()->json($operation);
    }

    public function show($id)
    {
        $operation = Operation::getOperation($id);
        return response()->json($operation);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $operation = Operation::updateOperation($id, $data);
        return response()->json($operation);
    }

    public function destroy($id)
    {
        $success = Operation::deleteOperation($id);
        return response()->json(['success' => $success]);
    }
}
