<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   
  
    public function index(): View
    {
         // Récupérer uniquement les chirps créés dans les 7 derniers jours
        $chirps = Chirp::with('user')
        ->where('created_at', '>=', now()->subDays(7))
        ->latest()
        ->get();

        // Passer les chirps filtrés à la vue
        return view('chirps.index', [
            'chirps' => $chirps,
        ]);

        dd($chirps);
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
   
    public function store(Request $request): RedirectResponse
    {

         // Vérifier si l'utilisateur a déjà 10 chirps
        if ($request->user()->chirps()->count() >= 10) {
            return redirect()->route('chirps.index')->withErrors(['message' => 'Vous avez atteint la limite de 10 chirps.']);
        }
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
 
        $request->user()->chirps()->create($validated);
 
        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp): View
    {
        Gate::authorize('update', $chirp);
 
        return view('chirps.edit', [
            'chirp' => $chirp,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        Gate::authorize('update', $chirp);
 
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
 
        $chirp->update($validated);
 
        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        Gate::authorize('delete', $chirp);
 
        $chirp->delete();
 
        return redirect(route('chirps.index'));
    }

    public function like(Request $request, Chirp $chirp)
{
    // Vérifier si l'utilisateur a déjà liké ce chirp
    if ($chirp->likes()->where('user_id', $request->user()->id)->exists()) {
        return response()->json(['message' => 'Vous avez déjà liké ce chirp.'], 400);
    }

    // Ajouter un like
    $chirp->likes()->create([
        'user_id' => $request->user()->id,
    ]);

    return response()->json(['message' => 'Chirp liké avec succès.']);
}


    

    
}
