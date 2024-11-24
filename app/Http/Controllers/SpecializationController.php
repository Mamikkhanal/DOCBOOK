<?php

namespace App\Http\Controllers;

use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specializations = Specialization::all();
        return view("specialization.index", compact("specializations"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("specialization.create");
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|unique:specializations,name',
        ]);

        Specialization::create([
            'name' => $request->name,
        ]);

        return redirect()->route('specialization.index')->with('success', 'Specialization added successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Specialization $specialization)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specialization $specialization)
    {
        return view('specialization.edit', compact('specialization'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specialization $specialization)
    {
        $specialization->name = $request->name;
        $specialization->save();
        return redirect()->route('specialization.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialization $specialization)
    {
        $specialization->delete();
        return redirect()->route('specialization.index');
    }
}
