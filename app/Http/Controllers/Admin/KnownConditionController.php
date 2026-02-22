<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnownCondition;
use Illuminate\Http\Request;

class KnownConditionController extends Controller
{
    public function index()
    {
        $knownConditions = KnownCondition::latest()->paginate(10);
        return view('admin.known_conditions.index', compact('knownConditions'));
    }

    public function create()
    {
        return view('admin.known_conditions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        KnownCondition::create($request->only('title'));

        return redirect()
            ->route('admin.known-conditions.index')
            ->with('success', 'Known condition created successfully.');
    }

    public function edit(KnownCondition $knownCondition)
    {
        return view('admin.known_conditions.edit', compact('knownCondition'));
    }

    public function update(Request $request, KnownCondition $knownCondition)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $knownCondition->update($request->only('title'));

        return redirect()
            ->route('admin.known-conditions.index')
            ->with('success', 'Known condition updated successfully.');
    }

    public function destroy(KnownCondition $knownCondition)
    {
        $knownCondition->delete();

        return redirect()
            ->route('admin.known-conditions.index')
            ->with('success', 'Known condition deleted successfully.');
    }
}