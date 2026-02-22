<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Village;
use App\Models\Taluka;

class VillageController extends Controller
{
    public function index(Taluka $taluka = null)
    {
        if ($taluka) {
            $villages = $taluka->villages;
        } else {
            $villages = Village::with('taluka')->get();
            $taluka = null;
        }
        return view('admin.villages.index', compact('taluka', 'villages'));
    }

    public function create(Taluka $taluka = null)
    {
        $talukas = Taluka::all();
        return view('admin.villages.create', compact('taluka', 'talukas'));
    }

    public function store(Request $request, Taluka $taluka = null)
    {
        $request->validate([
            'name' => 'required|max:255',
            'taluka_id' => 'required|integer|exists:talukas,id',
            'is_active' => 'boolean',
        ]);

        // Use the taluka_id from the form
        $taluka = Taluka::findOrFail($request->taluka_id);

        $taluka->villages()->create([
            'name' => $request->name,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        // If creating from standalone villages page, redirect to villages.index
        // If creating from taluka page, redirect to talukas.villages.index
        if ($request->route()->getName() === 'admin.villages.store') {
            return redirect()
                ->route('admin.villages.index')
                ->with('success', 'Village added successfully');
        }

        return redirect()
            ->route('admin.talukas.villages.index', $taluka)
            ->with('success', 'Village added successfully');
    }

    public function edit(Taluka $taluka, Village $village)
    {
        return view('admin.villages.edit', compact('taluka', 'village'));
    }

    public function update(Request $request, Taluka $taluka, Village $village)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $village->update([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('admin.talukas.villages.index', $taluka)
            ->with('success', 'Village updated successfully');
    }

    public function destroy(Taluka $taluka, Village $village)
    {
        $village->delete();

        return redirect()
            ->route('admin.talukas.villages.index', $taluka)
            ->with('success', 'Village deleted successfully');
    }

    public function getByTaluka(Taluka $taluka)
    {
        $villages = $taluka->villages()->where('is_active', true)->get(['id', 'name']);
        return response()->json($villages);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $talukaId = $request->get('taluka_id');

        $villages = Village::where('is_active', true);

        if ($talukaId) {
            $villages->where('taluka_id', $talukaId);
        }

        if ($query) {
            $villages->where('name', 'LIKE', '%' . $query . '%');
        }

        $results = $villages->limit(10)->get(['id', 'name']);
        return response()->json($results);
    }
}