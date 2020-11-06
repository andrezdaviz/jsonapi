<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SortArticleTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function in_can_sort_articles_title_asc()
    {
        factory(Article::class)->create(['title' => 'C Title']);
        factory(Article::class)->create(['title' => 'A Title']);
        factory(Article::class)->create(['title' => 'B Title']);

        $url = route('api.v1.articles.index', ['sort' => 'title']);



        $this->jsonApi()->get($url)->assertSeeInOrder([
            'A Title',
            'B Title',
            'C Title',
        ]);
    }

    /** @test */
    public function in_can_sort_articles_title_desc()
    {
        factory(Article::class)->create(['title' => 'C Title']);
        factory(Article::class)->create(['title' => 'A Title']);
        factory(Article::class)->create(['title' => 'B Title']);

        $url = route('api.v1.articles.index', ['sort' => '-title']);



        $this->jsonApi()->get($url)->assertSeeInOrder([
            'C Title',
            'B Title',
            'A Title',
        ]);
    }

    /** @test */
    public function in_can_sort_articles_title_and_content()
    {
        factory(Article::class)->create([
            'title' => 'C Title',
            'content' => 'D Content'
        ]);
        factory(Article::class)->create([
            'title' => 'A Title',
            'content' => 'B Content'
        ]);
        factory(Article::class)->create([
            'title' => 'B Title',
            'content' => 'C Content'
        ]);

        $url = route('api.v1.articles.index', ['sort' => 'title,-content']);



        $this->jsonApi()->get($url)->assertSeeInOrder([
            'A Title',
            'B Title',
            'C Title',
        ]);

        $url = route('api.v1.articles.index', ['sort' => '-content,title']);

        $this->jsonApi()->get($url)->assertSeeInOrder([

            'D Content',
            'C Content',
            'B Content',
        ]);
    }

    /** @test */
    public function in_canot_sort_articles_by_unknown_fields()
    {
        factory(Article::class)->times(3)->create();


        $url = route('api.v1.articles.index', 'sort=unknown');

        $this->jsonApi()->get($url)->assertStatus(400);
    }
}
