<?php

namespace App\Http\Controllers\Admin;

use App\Models\CampaignType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CampaignTypeController extends Controller
{
    public function index()
    {
        $campaignTypes = CampaignType::withTrashed()
                                     ->latest()
                                     ->paginate(20);
        return view('admin.campaign-types.index', compact('campaignTypes'));
    }

    public function create()
    {
        return view('admin.campaign-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:campaign_types,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        CampaignType::create($validated);

        return redirect()->route('admin.campaign-types.index')
                       ->with('success', 'Campaign Type created successfully');
    }

    public function edit(CampaignType $campaignType)
    {
        return view('admin.campaign-types.edit', compact('campaignType'));
    }

    public function update(Request $request, CampaignType $campaignType)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:campaign_types,name,' . $campaignType->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $campaignType->update($validated);

        return redirect()->route('admin.campaign-types.index')
                       ->with('success', 'Campaign Type updated successfully');
    }

    public function destroy(CampaignType $campaignType)
    {
        // Prevent deletion of core campaign types
        $protectedCampaignTypes = [
            'OPD',
            'Swatch bharat',
            'Special HC. Beneficary',
            'Awareness camp',
        ];

        if (in_array($campaignType->name, $protectedCampaignTypes)) {
            return redirect()->route('admin.campaign-types.index')
                           ->with('error', 'This is a protected campaign type and cannot be deleted.');
        }

        $campaignType->delete();

        return redirect()->route('admin.campaign-types.index')
                       ->with('success', 'Campaign Type deleted successfully');
    }

    public function restore($id)
    {
        $campaignType = CampaignType::withTrashed()->findOrFail($id);
        $campaignType->restore();

        return redirect()->route('admin.campaign-types.index')
                       ->with('success', 'Campaign Type restored successfully');
    }
}
