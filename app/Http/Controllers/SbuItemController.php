<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SbuItem;

class SbuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = SbuItem::orderByRaw("CASE WHEN kategori_biaya = 'Penginapan' THEN 0 ELSE 1 END")
                ->orderBy('id', 'asc')
                ->paginate(10);        
        
        return view('admin.sbu-item', compact('items'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
