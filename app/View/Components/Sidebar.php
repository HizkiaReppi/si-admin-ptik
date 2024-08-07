<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class Sidebar extends Component
{
    public $categories;

    public function __construct()
    {
        $this->categories = Cache::remember('categories', now()->addMinutes(60), function () {
            return Category::all();
        });
    }

    public function render()
    {
        return view('components.sidebar', [
            'categories' => $this->categories
        ]);
    }
}
