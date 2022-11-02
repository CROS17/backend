<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
	use RefreshDatabase;
	/** @test*/
	function test_can_get_all_books()
	{
		$book = Book::factory(4)->create();
		$this->getJson(route('books.index'))
			->assertJsonFragment([
				'title' => $book[0]->title
			])->assertJsonFragment([
				'title' => $book[1]->title
			]);
	}

	function test_can_get_one_book()
	{
		$book = Book::factory()->create();
		$this->getJson(route('books.show',$book))
			->assertJsonFragment([
				'title' => $book->title
			]);
	}

	function test_can_create_books()
	{

		$this->postJson(route('books.store'),
			[])->assertJsonValidationErrorFor('title');

		$this->postJson(route('books.store'),[
				'title' => 'My Libro'
			])->assertJsonFragment([
				'title' => 'My Libro'
			]);

		$this->assertDatabaseHas('books',[
				'title' => 'My Libro'
			]);
	}

	function test_can_update_books()
	{

		$book = Book::factory()->create();
		
		$this->patchJson(route('books.update',$book),
			[])->assertJsonValidationErrorFor('title');
		$this->patchJson(route('books.update',$book),[
			'title' => 'My Libro'
		])->assertJsonFragment([
			'title' => 'My Libro'
		]);

		$this->assertDatabaseHas('books',[
			'title' => 'My Libro'
		]);
	}

	function test_can_delete_books()
	{
		$book = Book::factory()->create();
		$this->deleteJson(route('books.update',$book))->assertNoContent();
		$this->assertDatabaseCount('books',0);

	}
}
