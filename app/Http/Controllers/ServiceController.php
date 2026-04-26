<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('id', 'desc')->paginate(10);
        return view('service.index', compact('services'));
    }

    public function create()
    {
        return view('service.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_service' => 'required|string|max:255',
            'harga' => 'nullable|numeric|min:0',
        ]);

        Service::create($request->all());

        return redirect()->route('service.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('service.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'nama_service' => 'required|string|max:255',
            'harga' => 'nullable|numeric|min:0',
        ]);

        $service->update($request->all());

        return redirect()->route('service.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('service.index')->with('success', 'Service deleted successfully.');
    }
}
