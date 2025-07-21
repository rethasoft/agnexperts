<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Blog;

class AppController extends Controller
{

    protected $path;

    public function __construct($path = 'frontend.pages.')
    {
        $this->path = $path;
    }
    public function home()
    {
        return view($this->path . 'home');
    }
    public function about()
    {
        return view($this->path . 'about');
    }
    public function serviceDetail($slug)
    {
        $service = Service::where('slug', $slug)->first();
        if ($service) {
            $services = Service::all();
            return view($this->path . 'service_detail', compact('service', 'services'));
        }
        return redirect()->route('frontend.home');
    }
    public function services()
    {
        $services = Service::all();
        return view($this->path . 'services', compact('services'));
    }
    public function blog()
    {
        $blogs = Blog::all();
        return view($this->path . 'blog', compact('blogs'));
    }
    public function blogDetail($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        if ($blog) {
            $blogs = Blog::all();
            return view($this->path . 'blog_detail', compact('blog', 'blogs'));
        }
        return redirect()->route('frontend.blog');
    }
    public function pricing()
    {
        return view($this->path . 'pricing');
    }
    public function contact()
    {
        return view($this->path . 'contact');
    }
    public function order()
    {
        return view($this->path . 'home');
    }
}
