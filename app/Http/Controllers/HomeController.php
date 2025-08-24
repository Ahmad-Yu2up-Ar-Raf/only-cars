<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Events;
use Inertia\Inertia;

class HomeController extends Controller
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
        $query = Events::paginate($perPage, ['*'], 'page', $page);

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


       $events = $query;

       $events->through(function($item) {
            return [
                ...$item->toArray(),
                'cover_image' => $item->cover_image ? url($item->cover_image) : null
            ];
        });
        return Inertia::render('welcome', [
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
