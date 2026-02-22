<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    public function __construct()
    {
        // $this->middleware('admin');
    }

    public function index()
    {
        $countries = Country::latest()->paginate(10);
        return view('admin.countries.index', compact('countries'));
    }

    public function create()
    {
        return view('admin.countries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:countries',
            'code' => 'required|string|size:2|unique:countries',
            'phone_code' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Country::create($validated);

        return redirect()->route('admin.countries.index')
                       ->with('success', 'Country created successfully');
    }

    public function edit(Country $country)
    {
        return view('admin.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:countries,name,' . $country->id,
            'code' => 'required|string|size:2|unique:countries,code,' . $country->id,
            'phone_code' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $country->update($validated);

        return redirect()->route('admin.countries.index')
                       ->with('success', 'Country updated successfully');
    }

    public function destroy(Country $country)
    {
        if ($country->states()->exists()) {
            return redirect()->route('admin.countries.index')
                           ->with('error', 'Cannot delete country with existing states');
        }

        $country->delete();

        return redirect()->route('admin.countries.index')
                       ->with('success', 'Country deleted successfully');
    }
}
