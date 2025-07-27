<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


   public function switch(Request $request, Team $team): RedirectResponse
    {
        $user = FacadesAuth::user();
        
        // Pastikan user adalah member dari team yang dipilih
        if (!$user->teams()->where('team_id', $team->id)->exists()) {
            return redirect()->back()->withErrors([
                'team' => 'You are not a member of this team.'
            ]);
        }
        
        // Update current team user
        $user->update([
            'current_team_id' => $team->id
        ]);
        
        return redirect()->back()->with('success', 'Team switched successfully!');
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
