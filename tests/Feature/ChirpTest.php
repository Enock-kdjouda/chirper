<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chirp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChirpTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
   //Exercice 1
    public function test_un_utilisateur_peut_creer_un_chirp()
    {
        // Simuler un utilisateur connecté
        $user = User::factory()->create();
        $this->actingAs($user);

        // Envoyer une requête POST pour créer un chirp
        $response = $this->post('/chirps', [
            'message' => 'Mon premier chirp !', // Doit correspondre à la validation dans le contrôleur
        ]);

        // Vérifier que le chirp a été ajouté à la base de données
        $response->assertStatus(302); // Vérifier que la redirection vers chirps.index a lieu
        $this->assertDatabaseHas('chirps', [
            'message' => 'Mon premier chirp !', // Champ correct
            'user_id' => $user->id,
        ]);
    }

    // Exercice 2
    public function test_un_chirp_ne_peut_pas_avoir_un_contenu_vide()
    {
    $utilisateur = User::factory()->create();
    $this->actingAs($utilisateur);
    $reponse = $this->post('/chirps', [

    'message' => '',
    ]);
    $reponse->assertSessionHasErrors(['message']);
    }
    public function test_un_chirp_ne_peut_pas_depasse_255_caracteres()
    {
    $utilisateur = User::factory()->create();
    $this->actingAs($utilisateur);
    $reponse = $this->post('/chirps', [
    'message' => str_repeat('a', 256)
    ]);
    $reponse->assertSessionHasErrors(['message']);
    }

    // Exercice 3

    public function test_les_chirps_sont_affiches_sur_la_page_d_accueil(): void
{
    // Créer un utilisateur et le connecter
    $user = User::factory()->create();
    $this->actingAs($user);

    // Créer des chirps
    $chirps = Chirp::factory()->count(3)->create();

    // Faire une requête GET sur la page d'accueil
    $response = $this->get('/chirps');

    // Vérifier que chaque message est affiché sur la page
    foreach ($chirps as $chirp) {
        $response->assertSee($chirp->message, false);
    }
}

// Exercice 4

public function test_un_utilisateur_peut_modifier_son_chirp()
    {
    $utilisateur = User::factory()->create();
    $chirp = Chirp::factory()->create(['user_id' => $utilisateur->id]);
    $this->actingAs($utilisateur);
    $reponse = $this->put("/chirps/{$chirp->id}", [
    'message' => 'Chirp modifié'
    ]);

    $reponse->assertStatus(302);
    // Vérifie si le chirp existe dans la base de donnée.
    $this->assertDatabaseHas('chirps', [
    'id' => $chirp->id,
    'message' => 'Chirp modifié',
    ]);
    }


}
