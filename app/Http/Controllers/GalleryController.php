<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGallery;
use App\Http\Requests\UpdateGallery;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');
           $page = $request->input('page', 1);

        $status = $request->input('status');
        $query = Gallery::where('user_id', Auth::id());

     if ($search) {
            $query->where(function($q) use ($search) {
                $searchLower = strtolower($search);
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereRaw('LOWER(plat_nomor) LIKE ?', ["%{$searchLower}%"]);
            });
        }

        // FIX: Multiple status filter
        if ($status) {
            if (is_array($status)) {
                $query->whereIn('status', $status);
            } else if (is_string($status)) {
                $statusArray = explode(',', $status);
                $query->whereIn('status', $statusArray);
            }
        }


       $gallery = $query->paginate($perPage, ['*'], 'page', $page);


        return Inertia::render('dashboard/gallery', [
            'gallery' => $gallery->items() ?? [],
         'filters' => [
                'search' => $search ?? '',
           
                'status' => $status ?? [],
            ],
            'pagination' => [
                'data' => $gallery->toArray(),
                'total' => $gallery->total(),
                'currentPage' => $gallery->currentPage(),
                'perPage' => $gallery->perPage(),
                'lastPage' => $gallery->lastPage(),
            ],
            'flash' => [
                'success' => session('success'),
                'error' => session('error')
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGallery $request)
    {
        try {
            // Observer akan handle file upload segallerya otomatis
            $gallery = Gallery::create([
                ...$request->validated(),
                'user_id' => Auth::id()
            ]);

            $fileCount = count($gallery->files ?? []);
            $message = $fileCount > 0 
                ? "Gallery berhasil ditambahkan dengan {$fileCount} file."
                : "Gallery berhasil ditambahkan.";

            return redirect()->route('dashboard.gallery.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Gallery creation error: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGallery $request, Gallery $gallery)
    {
        try {
            
            $gallery->update($request->validated());

            $fileCount = count($gallery->files ?? []);
            $message = request()->hasFile('files') || request()->has('files')
                ? "Gallery berhasil diupdate dengan {$fileCount} file."
                : "Gallery berhasil diupdate.";

            return redirect()->route('dashboard.gallery.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Gallery update error: ' . $e->getMessage());
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return redirect()->route('dashboard.gallery.index')
                ->with('error', 'Tidak ada mobil yang dipilih untuk dihapus.');
        }

        // Validasi apakah semua ID milik user yang sedang login
        $mobils = Gallery::whereIn('id', $ids)->where('user_id', Auth::id())->get();
        if ($mobils->count() !== count($ids)) {
            return redirect()->route('dashboard.gallery.index')
                ->with('error', 'Unauthorized access atau mobil tidak ditemukan.');
        }

        try {
            DB::beginTransaction();
            
            // SOLUSI: Delete satu per satu agar Observer terpicu
            foreach ($mobils as $mobil) {
                $mobil->delete(); // Ini akan trigger observer gallery
            }
            
            DB::commit();

            $deletedCount = $mobils->count();
            return redirect()->route('dashboard.gallery.index')
                ->with('success', "{$deletedCount} Gallery berhasil dihapus beserta semua file terkait.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gallery deletion error: ' . $e->getMessage());
            return redirect()->route('dashboard.gallery.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

}
