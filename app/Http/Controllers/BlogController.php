<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class BlogController extends Controller
{

    private $path;
    private $imageUploadService;

    public function __construct()
    {
        $this->path = 'app.tenant.blog.';
        $this->imageUploadService = new ImageUploadService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::all();
        return view($this->path . 'list', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view($this->path . 'add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->data;
            if ($request->hasFile('image')) {
                $data['image'] = $this->imageUploadService->upload($request->image, $data['title']);
            }

            $blog = Blog::create($data);
            if ($blog) {
                return redirect()->route('blog.index')->with('success', 'Blog succesvol aangemaakt');
            }
            return redirect()->back()->withErrors('Blog niet aangemaakt');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        return view($this->path . 'show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        return view($this->path . 'edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        try {
            $data = $request->data;
            if ($request->hasFile('image')) {
                $data['image'] = $this->imageUploadService->upload($request->image, $data['title']);
            }
            $update = $blog->update($data);
            if ($update) {
                return redirect()->route('blog.index')->with('success', 'Blog succesvol bijgewerkt');
            }
            return redirect()->back()->withErrors('Blog niet bijgewerkt');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        try {
            $blog->delete();
            return redirect()->route('blog.index')->with('success', 'Blog succesvol verwijderd');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
