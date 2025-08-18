<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Models\Gallery;
use App\Models\Merchandise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdmindController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
  $recordEvents = Events::all()->where('user_id', Auth::id());
   $recordMerchandise = Merchandise::all()->where('user_id', Auth::id());

  $eventsCounts = Events::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as events'))
            ->where('user_id', Auth::id())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get()
            ->keyBy('date');

        $merchandiseCounts = Merchandise::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as merchandise'))
            ->where('user_id', Auth::id())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get()
            ->keyBy('date');

        $galleryCounts = Gallery::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as gallery'))
            ->where('user_id', Auth::id())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get()
            ->keyBy('date');

        // Gabungkan data events dan merchandise berdasarkan tanggal
        $allDates = collect($eventsCounts->keys())->merge($merchandiseCounts->keys())->merge($galleryCounts->keys())->unique();
        
        $counts = $allDates->map(function ($date) use ($eventsCounts, $merchandiseCounts, $galleryCounts) {
            return [
                'date' => $date,
                'events' => $eventsCounts->get($date)->events ?? 0,
                'merchandise' => $merchandiseCounts->get($date)->merchandise ?? 0,
                'gallery' => $galleryCounts->get($date)->gallery ?? 0,
            ];
        })->values();




$statusCount = $recordEvents->groupBy('status')->map(function ($group) {
    return $group->count();   });
$StatusMerchandiseCount = $recordMerchandise->groupBy('status')->map(function ($group) {
    return $group->count();   });

  $totalEvents = Events::where('user_id', Auth::id())->count();


  $totalMerchandise = Merchandise::where('user_id', Auth::id())->count();
  $totalGallery = Gallery::where('user_id', Auth::id())->count();
  $terjualMerchandise = Merchandise::where('user_id', Auth::id())->where('status', 'terjual')->count();

          return Inertia::render('dashboard/index',[
                'reports' => [
                    'totalEvents' => $totalEvents,

                    'totalMerchandise' => $totalMerchandise,
                    'totalGallery' => $totalGallery,
                    'totalMerchandiseTerjual' => $terjualMerchandise,
   
                    'EventsstatusCount' => $statusCount,
                    'StatusMerchandiseCount' => $StatusMerchandiseCount,
                    'countsByDate' => $counts,
                ],
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
