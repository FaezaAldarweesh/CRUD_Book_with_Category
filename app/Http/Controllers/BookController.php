<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\BookService;
use App\Http\Resources\BookResources;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\Store_Book_Request;
use App\Http\Requests\Update_Book_Request;

class BookController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $bookservices;
    /**
     * construct to inject Book Services 
     * @param BookService $bookservices
     */
    public function __construct(BookService $bookservices)
    {
        $this->bookservices = $bookservices;
    }
    //===========================================================================================================================
    /**
     * method to view all books with filter on active book status and category. 
     * @param   Request $request
     * @return /Illuminate\Http\JsonResponse
     * BookResources to customize the return responses.
     */
    public function index(Request $request)
    {  
        $books = $this->bookservices->get_all_Books($request->input('is_active'),$request->input('category_id'));
        return $this->success_Response(BookResources::collection($books), "All books fetched successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new book
     * @param   Store_Book_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Book_Request $request)
    {
        $book = $this->bookservices->create_Book($request->validated());
        return $this->success_Response(new BookResources($book), "Book created successfully.", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show Book alraedy exist
     * @param  $book_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($book_id)
    {
        $book = $this->bookservices->view_book($book_id);

        // In case error messages are returned from the services section 
        if ($book instanceof \Illuminate\Http\JsonResponse) {
            return $book;
        }
            return $this->success_Response(new BookResources($book), "Book viewed successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to update book alraedy exist
     * @param  Update_Book_Request $request
     * @param  Book $book
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Book_Request $request, Book $book)
    {
        $book = $this->bookservices->update_Book($request->validated(), $book);
        return $this->success_Response(new BookResources($book), "Book updated successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete book alraedy exist
     * @param  $book_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($book_id)
    {
        $book = $this->bookservices->delete_book($book_id);

        // In case error messages are returned from the services section 
        if ($book instanceof \Illuminate\Http\JsonResponse) {
            return $book;
        }
            return $this->success_Response(null, "book soft deleted successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft delete books
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_book()
    {
        $books = $this->bookservices->all_trashed_book();
        return $this->success_Response(BookResources::collection($books), "All trashed books fetched successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft delete book alraedy exist
     * @param   $book_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($book_id)
    {
        $delete = $this->bookservices->restore_book($book_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "book restored successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on book that soft deleted before
     * @param   $book_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($book_id)
    {
        $delete = $this->bookservices->forceDelete_book($book_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "book force deleted successfully", 200);
    }  
    //========================================================================================================================
}
