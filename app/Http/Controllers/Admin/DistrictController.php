<?php

namespace App\Http\Controllers\Admin;

use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DistrictController extends Controller
{

    public function index()
    {
        $districts = District::with('state')->latest()->paginate(10);
        $states = State::active()->get();
        return view('admin.districts.index', compact('districts', 'states'));
    }

    public function create()
    {
        $states = State::active()->get();
        return view('admin.districts.create', compact('states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string',
            'is_active' => 'boolean',
        ]);

        District::create($validated);

        return redirect()->route('admin.districts.index')
                       ->with('success', 'District created successfully');
    }

    public function edit(District $district)
    {
        $states = State::active()->get();
        return view('admin.districts.edit', compact('district', 'states'));
    }

    public function update(Request $request, District $district)
    {
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $district->update($validated);

        return redirect()->route('admin.districts.index')
                       ->with('success', 'District updated successfully');
    }

    public function destroy(District $district)
    {
        if ($district->talukas()->exists()) {
            return redirect()->route('admin.districts.index')
                           ->with('error', 'Cannot delete district with existing talukas');
        }

        $district->delete();

        return redirect()->route('admin.districts.index')
                       ->with('success', 'District deleted successfully');
    }

    public function getByState(State $state)
    {
        $districts = $state->districts()->active()->get(['id', 'name']);
        return response()->json($districts);
    }
}
