<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Request;

class BookService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all books with filter on active book status and category.
     * @param   Request $request
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Books($is_active,$category){
        try {
            $book = Book::filter($is_active,$category)->with('category')->get();
            return $book;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetche books', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new book
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an error
     */
    public function create_Book($data) {
        try {
            $book = new Book;
            $book->title = $data['title'];
            $book->author = $data['author'];
            $book->published_at = $data['published_at'];  
            $book->is_active = $data['is_active'] ?? 1;
            $book->category_id = $data['category_id'];
           
            $book->save(); 
    
            return $book; 
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with create book', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update book alraedy exist
     * @param  $data
     * @param  Book $book
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_book($data,Book $book){
        try {  
            $book->title = $data['title'] ?? $book->title;
            $book->author = $data['author'] ?? $book->author;
            $book->published_at = $data['published_at'] ?? $book->published_at;  
            $book->is_active = $data['is_active'] ?? $book->is_active;
            $book->category_id = $data['category_id'];

            $book->save();  

            return $book;
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view book', 400);}
    }
    //========================================================================================================================
    /**
     * method to show book alraedy exist
     * @param  $book_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_Book($book_id) {
        try {    
            $book = Book::find($book_id);
            if(!$book){
                throw new \Exception('book not found');
            }
            return $book;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with update book', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete book alraedy exist
     * @param  Book $book
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_book($book_id)
    {
        try {  
            $book = Book::find($book_id);
            if(!$book){
                throw new \Exception('book not found');
            }
            $book->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting book', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete books
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_book()
    {
        try {  
            $books = Book::onlyTrashed()->get();
            return $books;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view trashed book', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete book alraedy exist
     * @param   $book_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_book($book_id)
    {
        try {
            $book = Book::withTrashed()->find($book_id);
            if(!$book){
                throw new \Exception('book not found');
            }
            return $book->restore();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with restore book', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on book that soft deleted before
     * @param   $book_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_book($book_id)
    {   
        try {
            $book = Book::onlyTrashed()->find($book_id);
            if(!$book){
                throw new \Exception('book not found');
            }
            return $book->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting book', 400);}
    }
    //========================================================================================================================

}
