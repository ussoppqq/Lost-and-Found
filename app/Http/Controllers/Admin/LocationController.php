<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = auth()->user()->company_id;
        $locations = Location::where('company_id', $companyId)
                            ->orderBy('area')
                            ->orderBy('name')
                            ->paginate(20);
        return view('livewire.admin.locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('livewire.admin.locations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'area' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        $companyId = auth()->user()->company_id;

        Location::create([
            'location_id' => Str::uuid(),
            'company_id' => $companyId,
            'area' => $validated['area'],
            'name' => $validated['name'],
        ]);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $companyId = auth()->user()->company_id;
        $location = Location::where('company_id', $companyId)->findOrFail($id);
        return view('livewire.admin.locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $companyId = auth()->user()->company_id;
        $location = Location::where('company_id', $companyId)->findOrFail($id);
        return view('livewire.admin.locations.edit', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $companyId = auth()->user()->company_id;
        $location = Location::where('company_id', $companyId)->findOrFail($id);

        $validated = $request->validate([
            'area' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        $location->update([
            'area' => $validated['area'],
            'name' => $validated['name'],
        ]);

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $companyId = auth()->user()->company_id;
        $location = Location::where('company_id', $companyId)->findOrFail($id);
        $location->delete();

        return redirect()->route('admin.locations.index')
            ->with('success', 'Location deleted successfully.');
    }
}
