<?php

namespace Tests\Feature;

use App\Models\User;
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
}
