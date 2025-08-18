<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMerch;
use App\Http\Requests\UpdateMerch;
use App\Models\Merchandise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MerchandiseController extends Controller
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
        $query = Merchandise::where('user_id', Auth::id());

     if ($search) {
            $query->where(function($q) use ($search) {
                $searchLower = strtolower($search);
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereRaw('LOWER(deskripsi) LIKE ?', ["%{$searchLower}%"]);
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


       $merchandise = $query->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);
    $merchandise->through(function($item) {
            return [
                ...$item->toArray(),
                'image' => $item->image ? url($item->image) : null
            ];
        });

        return Inertia::render('dashboard/merchandise', [
            'Merchandise' => $merchandise->items() ?? [],
         'filters' => [
                'search' => $search ?? '',
           
                'status' => $status ?? [],
            ],
            'pagination' => [
                'data' => $merchandise->toArray(),
                'total' => $merchandise->total(),
                'currentPage' => $merchandise->currentPage(),
                'perPage' => $merchandise->perPage(),
                'lastPage' => $merchandise->lastPage(),
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
    public function store(StoreMerch $request)
    {
        try {

            
                    $imagePath = null;
    if (request()->hasFile('image')) {
            $file = request()->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/', $filename, 'public');
            $imagePath = 'storage/' . $path;
        }
            // Observer akan handle file upload semerchandisea otomatis
            $merchandise = Merchandise::create([
                ...$request->validated(),
                'user_id' => Auth::id(),
                    'image' => $imagePath,
            ]);

            $fileCount = count($merchandise->files ?? []);
            $message = $fileCount > 0 
                ? "Merchandise berhasil ditambahkan dengan {$fileCount} file."
                : "Merchandise berhasil ditambahkan.";

            return redirect()->route('dashboard.merchandise.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Merchandise creation error: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Merchandise $merchandise)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Merchandise $merchandise)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMerch $request, Merchandise $merchandise)
    {
        try {
           
                $updateData = [
             'name' => $request['name'],
  
           
            'deskripsi' => $request['deskripsi'],
            'price' => $request['price'],
            'status' => $request['status'],
            'visibility' => $request['visibility'],
            'quantity' => $request['quantity'],
        
             // Validasi untuk struktur files yang kompleks dengan base64
            'files' =>  $request['files'],
           
        ];

 if (request()->hasFile('image')) {
            Log::info('New image file detected');
            
            // Hapus gambar lama jika ada
            if ( $merchandise->image && Storage::disk('public')->exists(str_replace('storage/', '',  $merchandise->image))) {
                Storage::disk('public')->delete(str_replace('storage/', '',  $merchandise->image));
                Log::info('Old image deleted: ' .  $merchandise->image);
            }
            
            // Upload gambar baru
            $file = request()->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');
            $updateData['image'] = 'storage/' . $path;
            
            Log::info('New image uploaded: ' . $updateData['image']);
        } else {
            Log::info('No new image file, keeping existing image');
            // Jika tidak ada file baru, pertahankan gambar yang sudah ada
            // Tidak perlu menambahkan 'image' ke updateData agar tidak mengganti dengan null
        }






            $merchandise->update($updateData);

            $fileCount = count($merchandise->files ?? []);
            $message = request()->hasFile('files') || request()->has('files')
                ? "Merchandise berhasil diupdate dengan {$fileCount} file."
                : "Merchandise berhasil diupdate.";

            return redirect()->route('dashboard.merchandise.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Merchandise update error: ' . $e->getMessage());
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
            return redirect()->route('dashboard.merchandise.index')
                ->with('error', 'Tidak ada mobil yang dipilih untuk dihapus.');
        }

        // Validasi apakah semua ID milik user yang sedang login
        $merchandise = Merchandise::whereIn('id', $ids)->where('user_id', Auth::id())->get();
        if ($merchandise->count() !== count($ids)) {
            return redirect()->route('dashboard.merchandise.index')
                ->with('error', 'Unauthorized access atau mobil tidak ditemukan.');
        }

        try {
            DB::beginTransaction();
            
            // SOLUSI: Delete satu per satu agar Observer terpicu
            foreach ($merchandise as $merch) {
                $merch->delete(); // Ini akan trigger observer merchandise
            }
            
            DB::commit();

            $deletedCount = $merchandise->count();
            return redirect()->route('dashboard.merchandise.index')
                ->with('success', "{$deletedCount} Merchandise berhasil dihapus beserta semua file terkait.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Merchandise deletion error: ' . $e->getMessage());
            return redirect()->route('dashboard.merchandise.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function statusUpdate(Request $request)
    {
        
        $ids = $request->input('ids');
        $value = $request->input('value');
        $colum = $request->input('colum');

        if (empty($ids)) {
            return redirect()->route('dashboard.merchandise.index')
                ->with('error', 'Tidak ada event yang dipilih untuk dihapus.');
        }

        // Validasi apakah semua ID milik user yang sedang login
        $merchandise = Merchandise::whereIn('id', $ids)->where('user_id', Auth::id())->get();
        if ($merchandise->count() !== count($ids)) {
            return redirect()->route('dashboard.merchandise.index')
                ->with('error', 'Unauthorized access atau event tidak ditemukan.');
        }

        try {
            DB::beginTransaction();
              
             foreach ($merchandise as $merch) {
                $merch->update([$colum => $value]);
            }

   

            
            
            DB::commit();
            

            $deletedCount = $merchandise->count();
            return redirect()->route('dashboard.merchandise.index')
                ->with('success', "{$deletedCount} Merchandise berhasil dihapus beserta semua file terkait.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Merchandise deletion error: ' . $e->getMessage());
            return redirect()->route('dashboard.merchandise.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
    
}
