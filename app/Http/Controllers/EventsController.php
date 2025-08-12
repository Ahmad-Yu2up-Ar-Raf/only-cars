<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEvents;
use App\Http\Requests\UpdateEvents;
use App\Models\Events;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EventsController extends Controller
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
        $query = Events::where('user_id', FacadesAuth::id());

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


       $events = $query->paginate($perPage, ['*'], 'page', $page);


        return Inertia::render('dashboard/events', [
            'events' => $events->items() ?? [],
         'filters' => [
                'search' => $search ?? '',
             
                'status' => $status ?? [],
            ],
            'pagination' => [
                'data' => $events->toArray(),
                'total' => $events->total(),
                'currentPage' => $events->currentPage(),
                'perPage' => $events->perPage(),
                'lastPage' => $events->lastPage(),
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
    public function store(StoreEvents $request)
    {
        try {
            // Observer akan handle file upload seeventsa otomatis
            $events = Events::create([
                ...$request->validated(),
                'user_id' => Auth::id()
            ]);

            $fileCount = count($events->files ?? []);
            $message = $fileCount > 0 
                ? "Events berhasil ditambahkan dengan {$fileCount} file."
                : "Events berhasil ditambahkan.";

            return redirect()->route('dashboard.events.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Events creation error: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Events $events)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Events $events)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEvents $request, Events $events)
    {
        try {
            
            $events->update($request->validated());

            $fileCount = count($events->files ?? []);
            $message = request()->hasFile('files') || request()->has('files')
                ? "Events berhasil diupdate dengan {$fileCount} file."
                : "Events berhasil diupdate.";

            return redirect()->route('dashboard.events.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Events update error: ' . $e->getMessage());
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
            return redirect()->route('dashboard.events.index')
                ->with('error', 'Tidak ada mobil yang dipilih untuk dihapus.');
        }

        // Validasi apakah semua ID milik user yang sedang login
        $mobils = Events::whereIn('id', $ids)->where('user_id', Auth::id())->get();
        if ($mobils->count() !== count($ids)) {
            return redirect()->route('dashboard.events.index')
                ->with('error', 'Unauthorized access atau mobil tidak ditemukan.');
        }

        try {
            DB::beginTransaction();
            
            // SOLUSI: Delete satu per satu agar Observer terpicu
            foreach ($mobils as $mobil) {
                $mobil->delete(); // Ini akan trigger observer events
            }
            
            DB::commit();

            $deletedCount = $mobils->count();
            return redirect()->route('dashboard.events.index')
                ->with('success', "{$deletedCount} Events berhasil dihapus beserta semua file terkait.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Events deletion error: ' . $e->getMessage());
            return redirect()->route('dashboard.events.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
