<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Table::all();

        return response()->json(['tables' => $tables]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $table = Table::create([
            'table_number' => 0
        ]);

        return response()->json($table, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $table = Table::findOrFail($id);
            return response()->json(['table' => $table]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Table not found'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        $table->delete();

        return response()->json(['message' => 'Table deleted successfuly']);
    }


    /**
     * Change completion_status
     */
    public function tableFinished(Table $table)
    {
        $table->update(['status' => 1]);

        return response()->json(['message' => 'Table Finished Successfully']);
    }
}
