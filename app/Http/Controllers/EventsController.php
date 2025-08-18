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
use Illuminate\Support\Facades\Storage;
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
                  ->orWhereRaw('LOWER(location) LIKE ?', ["%{$searchLower}%"]);
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


       $events = $query->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);

       $events->through(function($item) {
            return [
                ...$item->toArray(),
                'cover_image' => $item->cover_image ? url($item->cover_image) : null
            ];
        });
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
                    $cover_imagePath = null;
    if (request()->hasFile('cover_image')) {
            $file = request()->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');
            $cover_imagePath = 'storage/' . $path;
        }
            $events = Events::create([
                ...$request->validated(),
                   'cover_image' => $cover_imagePath,
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
    public function update(UpdateEvents $request, Events $event)
    {
        try {
          


    $updateData = [
             'title' => $request['title'],
  
           
            'deskripsi' => $request['deskripsi'],
            'location' => $request['location'],
            'status' => $request['status'],
            'visibility' => $request['visibility'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],

            'capacity' => $request['capacity'],
            
             // Validasi untuk struktur files yang kompleks dengan base64
            'files' =>  $request['files'],
           
        ];
            

 if (request()->hasFile('cover_image')) {
            Log::info('New cover_image file detected');
            
            // Hapus gambar lama jika ada
            if ( $event->cover_image && Storage::disk('public')->exists(str_replace('storage/', '',  $event->cover_image))) {
                Storage::disk('public')->delete(str_replace('storage/', '',  $event->cover_image));
                Log::info('Old cover_image deleted: ' .  $event->cover_image);
            }
            
            // Upload gambar baru
            $file = request()->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');
            $updateData['cover_image'] = 'storage/' . $path;
            
            Log::info('New cover_image uploaded: ' . $updateData['cover_image']);
        } else {
            Log::info('No new cover_image file, keeping existing cover_image');
            // Jika tidak ada file baru, pertahankan gambar yang sudah ada
            // Tidak perlu menambahkan 'cover_image' ke updateData agar tidak mengganti dengan null
        }

            $event->update($updateData);

            $fileCount = count($event->files ?? []);
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
                ->with('error', 'Tidak ada event yang dipilih untuk dihapus.');
        }

        // Validasi apakah semua ID milik user yang sedang login
        $events = Events::whereIn('id', $ids)->where('user_id', Auth::id())->get();
        if ($events->count() !== count($ids)) {
            return redirect()->route('dashboard.events.index')
                ->with('error', 'Unauthorized access atau event tidak ditemukan.');
        }

        try {
            DB::beginTransaction();
              
            // SOLUSI: Delete satu per satu agar Observer terpicu
            foreach ($events as $event) {
                $event->delete(); // Ini akan trigger observer events
            }
            
            DB::commit();

            $deletedCount = $events->count();
            return redirect()->route('dashboard.events.index')
                ->with('success', "{$deletedCount} Events berhasil dihapus beserta semua file terkait.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Events deletion error: ' . $e->getMessage());
            return redirect()->route('dashboard.events.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function statusUpdate(Request $request)
    {
        
        $ids = $request->input('ids');
        $value = $request->input('value');
        $colum = $request->input('colum');

        if (empty($ids)) {
            return redirect()->route('dashboard.events.index')
                ->with('error', 'Tidak ada event yang dipilih untuk dihapus.');
        }

        // Validasi apakah semua ID milik user yang sedang login
        $events = Events::whereIn('id', $ids)->where('user_id', Auth::id())->get();
        if ($events->count() !== count($ids)) {
            return redirect()->route('dashboard.events.index')
                ->with('error', 'Unauthorized access atau event tidak ditemukan.');
        }

        try {
            DB::beginTransaction();
              
             foreach ($events as $event) {
                $event->update([$colum => $value]);
            }

   

            
            
            DB::commit();
            

            $deletedCount = $events->count();
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
