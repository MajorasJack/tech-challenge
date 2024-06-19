<?php

namespace Tests\Feature\Controllers;

use App\Client;
use App\Journal;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JournalsControllerTest extends TestCase
{
    use WithFaker;

    public function testThatItWillGetTheJournalsForAClient()
    {
        $client = factory(Client::class)->create();

        $journals = factory(
            Journal::class,
            $this->faker->numberBetween(1, 10),
        )
            ->create(['client_id' => $client->id]);

        $this->actingAs(factory(User::class)->create())
            ->get(route('journals.index', $client))
            ->assertOk()
            ->assertJson(
                $journals->map(function (Journal $journal) use ($client) {
                        return [
                            'id' => $journal->id,
                            'date' => $journal->date,
                            'text' => $journal->text,
                            'client_id' => $client->id,
                            'user_id' => $journal->user_id,
                            'created_at' => $journal->created_at->toISOString(),
                            'updated_at' => $journal->updated_at->toISOString(),
                        ];
                    })
                        ->toArray(),
            );
    }

    public function testThatItCanCreateAJournalAsExpected()
    {
        $date = $this->faker->date();
        $text = $this->faker->paragraph();

        $client = factory(Client::class)->create();

        $this->actingAs(factory(User::class)->create())
            ->post(route('journals.store', $client), [
                'date' => $date,
                'text' => $text,
            ])
            ->assertCreated()
            ->assertJson(['url' => route('clients.show', $client)]);

        $this->assertDatabaseHas(Journal::class, [
            'date' => $date,
            'text' => $text,
        ]);
    }

    public function testThatItCanDeleteAJournalAsExpected()
    {
        $journal = factory(Journal::class)->create();

        $this->actingAs(factory(User::class)->create())
            ->delete(route('journals.destroy', [$journal->client, $journal]))
            ->assertNoContent();

        $this->assertDatabaseCount(Journal::class, 0);
    }
}
