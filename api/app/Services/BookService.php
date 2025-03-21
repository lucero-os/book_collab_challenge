<?php

namespace App\Services;

use App\Exceptions\CustomErrorException;
use App\Models\Book;
use App\Models\BookSection;

class BookService
{
    public function createSection(array $data)
    {
        // Check book exists
        $book = Book::find($data['bookId']);
        if(!$book) throw new CustomErrorException('Book not found');

        // Check user has permissions to create section
        $loggedUser = auth()->user();
        if(!$loggedUser->hasBookPermission($book->id, 'create_book_section')) throw new CustomErrorException('Action not available');

        $bookSection = new BookSection();
        $bookSection->name = $data['name'];
        $bookSection->book_id = $book->id;

        if(isset($data['parentId'])){
            // Check book exists
            $parentSection = BookSection::find($data['parentId']);
            if(!$parentSection) throw new CustomErrorException('Parent section not found');

            $bookSection->parent_id = $parentSection->id;
        }
        $bookSection->content = ''; // initially empty
        $bookSection->save();
    }
}
