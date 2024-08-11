<?php

namespace App\Http\Controllers;

use App\Helpers\SlugHelper;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $announcements = Cache::rememberForever('announcements', function () {
            return Announcement::with('user')->latest()->get();
        });

        return view('dashboard.announcements.index', compact('announcements'));
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
    public function store(Request $request)
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
            dd($e);
            return redirect()->back()->with('error', 'Failed to create Announcement. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $pengumuman): View
    {
        $announcement = $pengumuman->load('user');
        return view('dashboard.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        //
    }
}
