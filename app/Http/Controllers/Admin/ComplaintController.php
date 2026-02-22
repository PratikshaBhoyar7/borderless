<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        $complaints = Complaint::latest()->paginate(10);
        return view('admin.complaints.index', compact('complaints'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.complaints.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'complaint' => 'required|string|max:5000',
        ]);

        Complaint::create($validated);

        return redirect()->route('admin.complaints.index')->with('success', 'Complaint created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Complaint $complaint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    $complaint = Complaint::findOrFail($id);
    return view('admin.complaints.edit', compact('complaint'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'complaint' => 'required|string'
    ]);

    $complaint = Complaint::findOrFail($id);
    $complaint->update([
        'complaint' => $request->complaint
    ]);

    return redirect()
        ->route('admin.complaints.index')
        ->with('success', 'Complaint updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
{
    try {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();

        return redirect()
            ->route('admin.complaints.index')
            ->with('success', 'Complaint deleted successfully.');
    } catch (\Exception $e) {
        return redirect()
            ->route('admin.complaints.index')
            ->with('error', 'Something went wrong while deleting.');
    }
}
}
