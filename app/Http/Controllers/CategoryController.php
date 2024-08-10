<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct()
    {
        if (!Gate::allows('admin') && !Gate::allows('super-admin') && !Gate::allows('HoD')) {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        $categories = Cache::remember('categories', now()->addMinutes(60), function () {
            return Category::with(['requirements', 'submissions'])->get();;
        });

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
                foreach ($validatedData['requirements'] as $index => $requirement) {
                    $filePath = null;
                    if (isset($requirement['file'])) {
                        $file = $requirement['file'];
                        $fileName = time() . '_' . $category->slug . '_persyaratan_' . $index + 1 . '.' . $file->getClientOriginalExtension();
                        $filePath = $file->storeAs('public/file/requirements', $fileName);
                    }
                    $category->requirements()->create([
                        'name' => $requirement['name'],
                        'file_path' => $filePath ? Storage::url($filePath) : null,
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
    public function show(Category $kategori): View
    {
        $submissions = $kategori->submissions()->with('student')->get();
        return view('dashboard.category.show', compact('kategori', 'submissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $kategori)
    {
        return view('dashboard.category.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $kategori)
    {
        $rulesValidated = [
            'requirements' => ['sometimes', 'array'],
            'requirements.*.name' => ['required', 'string', 'max:255'],
            'requirements.*.file' => ['sometimes', 'file', 'mimes:pdf,doc,docx'],
        ];

        if ($request->name != $kategori->name) {
            $rulesValidated['name'] = ['required', 'string', 'min:2', 'max:255', 'unique:categories'];
        }

        $validatedData = $request->validate($rulesValidated);

        $categoryName = !empty($validatedData['name']) ? $validatedData['name'] : $kategori->name;

        DB::beginTransaction();

        try {
            if ($request->name != $kategori->name) {
                $kategori->name = $categoryName;
                $kategori->slug = generateSlug($kategori, $categoryName);
                $kategori->save();
            }

            // Delete existing requirements
            foreach ($kategori->requirements as $requirement) {
                if ($requirement->file_path) {
                    Storage::delete($requirement->file_path);
                }
                $requirement->delete();
            }

            // Create new requirements
            if (isset($validatedData['requirements'])) {
                foreach ($validatedData['requirements'] as $index => $requirement) {
                    $filePath = null;
                    if (isset($requirement['file'])) {
                        $fileName = time() . '_' . str_replace(' ', '_', $kategori->name) . '_persyaratan_' . $index . '.' . $requirement['file']->getClientOriginalExtension();
                        $filePath = $requirement['file']->storeAs('public/file/requirements', $fileName);
                    }
                    $kategori->requirements()->create([
                        'name' => $requirement['name'],
                        'file_path' => $filePath,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('dashboard.category.index')->with('toast_success', 'Kategori updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast_error', 'Failed to update Kategori. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $kategori)
    {
        DB::beginTransaction();

        try {
            foreach ($kategori->requirements as $requirement) {
                if ($requirement->file_path) {
                    Storage::delete($requirement->file_path);
                }
                $requirement->delete();
            }

            $kategori->delete();

            DB::commit();
            return redirect()->route('dashboard.category.index')->with('toast_success', 'Kategori deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast_error', 'Failed to delete Kategori. Please try again.');
        }
    }
}
