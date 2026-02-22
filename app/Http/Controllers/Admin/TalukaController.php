<?php

namespace App\Http\Controllers\Admin;

use App\Models\Taluka;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TalukaController extends Controller
{

    public function index()
    {
        $talukas = Taluka::with('district')->latest()->paginate(10);
        $districts = District::active()->get();
        return view('admin.talukas.index', compact('talukas', 'districts'));
    }

    public function create()
    {
        $districts = District::active()->get();
        return view('admin.talukas.create', compact('districts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string',
            'is_active' => 'boolean',
        ]);

        Taluka::create($validated);

        return redirect()->route('admin.talukas.index')
                       ->with('success', 'Taluka created successfully');
    }

    public function edit(Taluka $taluka)
    {
        $districts = District::active()->get();
        return view('admin.talukas.edit', compact('taluka', 'districts'));
    }

    public function update(Request $request, Taluka $taluka)
    {
        $validated = $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $taluka->update($validated);

        return redirect()->route('admin.talukas.index')
                       ->with('success', 'Taluka updated successfully');
    }

    public function destroy(Taluka $taluka)
    {
        if ($taluka->district()->exists()) {
            return redirect()->route('admin.talukas.index')
                           ->with('error', 'Cannot delete taluka with existing patients');
        }

        $taluka->delete();

        return redirect()->route('admin.talukas.index')
                       ->with('success', 'Taluka deleted successfully');
    }

    public function getByDistrict(District $district)
    {
        $talukas = $district->talukas()->active()->get(['id', 'name']);
        return response()->json($talukas);
    }
}
