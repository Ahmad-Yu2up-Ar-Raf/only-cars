<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MerchandisePageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 5);
        $search = $request->input('search');
           $page = $request->input('page', 1);

        $status = $request->input('status');
  

        $query = Merchandise::paginate($perPage, ['*'], 'page', $page);

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
   
   
          $merchandise = $query;







       $merchandise->through(function($item) {
               return [
                   ...$item->toArray(),
                   'image' => $item->image ? url($item->image) : null
               ];
           });


 


    
        return Inertia::render('merchandise', [
           
            'Merch' => $merchandise->items() ?? [],
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
