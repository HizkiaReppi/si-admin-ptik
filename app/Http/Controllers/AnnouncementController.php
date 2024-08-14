<?php

namespace App\Http\Controllers;

use App\Helpers\SlugHelper;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        $adminOrHeadOfDepartmentRole = ['admin', 'HoD', 'super-admin'];
        $isAdminOrHeadOfDepartment = in_array(auth()->user()->role, $adminOrHeadOfDepartmentRole);
        
        if (!$isAdminOrHeadOfDepartment) {
            $announcements = Announcement::with('user')->latest()->take(10)->get();
            return view('dashboard.announcements.index', compact('announcements'));
        } else {
            if ($request->ajax()) {
                $model = Announcement::with('user')->latest();

                return DataTables::of($model)
                    ->addIndexColumn()
                    ->addColumn('title', function ($row) {
                        return $row->title;
                    })
                    ->addColumn('created_at', function ($row) {
                        return $row->created_at->diffForHumans();
                    })
                    ->addColumn('user', function ($row) {
                        return $row->user->name;
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item"
                                        href="' . route('dashboard.announcements.show', $row->slug) . '">
                                        <i class="bx bxs-user-detail me-1"></i> Detail
                                    </a>
                                    <a class="dropdown-item"
                                        href="' . route('dashboard.announcements.edit', $row->slug) . '">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </a>
                                    <a class="dropdown-item"
                                        href="' . route('dashboard.announcements.destroy', $row->slug) . '"
                                        data-confirm-delete="true">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </a>
                                </div>
                            </div>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('dashboard.announcements.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.announcements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required'],
        ]);

        DB::beginTransaction();

        try {
            $announcement = new Announcement();
            $announcement->title = $validatedData['title'];
            $announcement->slug = SlugHelper::generateSlug($announcement, $validatedData['title']);
            $announcement->content = $validatedData['content'];
            $announcement->user_id = auth()->user()->id;
            $announcement->save();

            DB::commit();
            return redirect()->route('dashboard.announcements.index')->with('toast_success', 'Announcement created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create Announcement. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $pengumuman): View
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        $announcement = $pengumuman->load('user');
        return view('dashboard.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $pengumuman): View
    {
        return view('dashboard.announcements.edit', compact('pengumuman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $pengumuman): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required'],
        ]);

        DB::beginTransaction();

        try {
            $pengumuman->title = $validatedData['title'];
            $pengumuman->slug = SlugHelper::generateSlug($pengumuman, $validatedData['title']);
            $pengumuman->content = $validatedData['content'];
            $pengumuman->save();

            DB::commit();
            return redirect()->route('dashboard.announcements.index')->with('toast_success', 'Announcement updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update Announcement. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $pengumuman): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $pengumuman->delete();
            DB::commit();
            return redirect()->route('dashboard.announcements.index')->with('toast_success', 'Announcement deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete Announcement. Please try again.');
        }
    }
}
