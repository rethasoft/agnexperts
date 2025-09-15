<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{

    private $path;

    public function __construct()
    {
        $this->path = 'app.tenant.service.';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all();
        return view($this->path . 'list', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view($this->path . 'add');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->data;
            
            // Handle regions checkbox data
            $regions = [];
            if ($request->has('regions')) {
                $regions = $request->regions;
            }
            $data['regions'] = $regions;
            
            if ($request->hasFile('image')) {
                $data['image'] = $this->handleImageUpload($request->file('image'), $data['name']);
            }
            $service = Service::create($data);
            if ($service) {
                return redirect()->route('service.index')->with('success', 'Dienst toegevoegd');
            }
            return redirect()->back()->with('error', 'Er is een fout opgetreden');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        try {
            return view($this->path . 'edit', compact('service'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        try {
            $data = $request->data;
            
            // Handle regions checkbox data
            $regions = [];
            if ($request->has('regions')) {
                $regions = $request->regions;
            }
            $data['regions'] = $regions;
            
            if ($request->hasFile('image')) {
                $data['image'] = $this->handleImageUpload($request->file('image'), $data['name']);
            }
            $service->update($data);
            return redirect()->route('service.index')->with('success', 'Dienst bijgewerkt');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        try {
            $service->delete();
            return redirect()->route('service.index')->with('success', 'Dienst verwijderd');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get services by region
     */
    public function getServicesByRegion($region)
    {
        try {
            $services = Service::whereJsonContains('regions', $region)->get();
            return response()->json($services);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle image upload and return the image path
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $serviceName
     * @return string
     */
    private function handleImageUpload($image, $serviceName)
    {
        $imageName = time() . '_' . Str::slug($serviceName) . '.' . $image->getClientOriginalExtension();

        // Create directory if it doesn't exist
        $path = public_path('images/services');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // Move the image to public/images/services
        $image->move($path, $imageName);

        return 'images/services/' . $imageName;
    }
}
