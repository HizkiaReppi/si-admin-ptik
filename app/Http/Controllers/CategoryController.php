<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct()
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Category::with('requirements')->get();

        return view('dashboard.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255', 'unique:categories'],
            'requirements' => ['sometimes', 'array'],
            'requirements.*.name' => ['required', 'string', 'max:255'],
            'requirements.*.file' => ['sometimes', 'file', 'mimes:pdf,doc,docx'],
        ]);

        DB::beginTransaction();

        try {
            $category = new Category();
            $category->name = $validatedData['name'];
            $category->slug = generateSlug($category, $validatedData['name']);
            $category->save();

            if (isset($validatedData['requirements'])) {
                foreach ($validatedData['requirements'] as $requirement) {
                    $filePath = null;
                    if (isset($requirement['file'])) {
                        $filePath = $requirement['file']->store('requirements');
                    }
                    $category->requirements()->create([
                        'name' => $requirement['name'],
                        'file_path' => $filePath,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('dashboard.category.index')->with('toast_success', 'Kategori added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Failed to add Kategori. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
