<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diagnosis;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $diagnoses = Diagnosis::latest()->paginate(10);
        return view('admin.diagnoses.index', compact('diagnoses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.diagnoses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Diagnosis::create($validated);

        return redirect()
            ->route('admin.diagnoses.index')
            ->with('success', 'Diagnosis created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Diagnosis $diagnosis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $diagnosis = Diagnosis::findOrFail($id);
        return view('admin.diagnoses.edit', compact('diagnosis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $diagnosis = Diagnosis::findOrFail($id);
        $diagnosis->update([
            'title' => $request->title
        ]);

        return redirect()
            ->route('admin.diagnoses.index')
            ->with('success', 'Diagnosis updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $diagnosis = Diagnosis::findOrFail($id);
            $diagnosis->delete();

            return redirect()
                ->route('admin.diagnoses.index')
                ->with('success', 'Diagnosis deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.diagnoses.index')
                ->with('error', 'Something went wrong while deleting.');
        }
    }
}