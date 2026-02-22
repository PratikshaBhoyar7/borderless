<?php

namespace App\Http\Controllers\Admin;

use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StateController extends Controller
{

    public function index()
    {
        $states = State::with('country')->latest()->paginate(10);
        $countries = Country::active()->get();
        return view('admin.states.index', compact('states', 'countries'));
    }

    public function create()
    {
        $countries = Country::active()->get();
        return view('admin.states.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string',
            'code' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        State::create($validated);

        return redirect()->route('admin.states.index')
                       ->with('success', 'State created successfully');
    }

    public function edit(State $state)
    {
        $countries = Country::active()->get();
        return view('admin.states.edit', compact('state', 'countries'));
    }

    public function update(Request $request, State $state)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string',
            'code' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $state->update($validated);

        return redirect()->route('admin.states.index')
                       ->with('success', 'State updated successfully');
    }

    public function destroy(State $state)
    {
        if ($state->districts()->exists()) {
            return redirect()->route('admin.states.index')
                           ->with('error', 'Cannot delete state with existing districts');
        }

        $state->delete();

        return redirect()->route('admin.states.index')
                       ->with('success', 'State deleted successfully');
    }

    public function getByCountry(Country $country)
    {
        $states = $country->states()->active()->get(['id', 'name']);
        return response()->json($states);
    }
}
