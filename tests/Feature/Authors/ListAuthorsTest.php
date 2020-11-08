<?php

namespace Tests\Feature\Authors;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

class ListAuthorsTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function can_fetch_single_author()
    {
        $author =  factory(User::class)->create();

        $response = $this->jsonApi()->get(route('api.v1.authors.read', $author))->assertSee($author->name);

        $this->assertTrue(Str::isUuid($response->json('data.id')), "The authors 'id' must be Uuid");
    }

    /** @test */
    public function can_fetch_all_authors()
    {
        $authors =  factory(User::class)->times(3)->create();

        $this->jsonApi()->get(route('api.v1.authors.index'))->assertSee($authors[0]->name);
    }
}
