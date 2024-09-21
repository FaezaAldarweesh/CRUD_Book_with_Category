<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Request;

class CategoryService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all categories with filter on active
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Categories(){
        try {
            $category = Category::with('books')->get();
            return $category;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetche categories', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new category
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an error
     */
    public function create_Category($data) {
        try {
            $category = new Category;
            $category->name = $data['name'];
            $category->description = $data['description'];
           
            $category->save(); 
    
            return $category; 
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with create category', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update category alraedy exist
     * @param  $data
     * @param  Category $category
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_category($data,Category $category){
        try {  
            $category->name = $data['name'] ?? $category->name;
            $category->description = $data['description'] ?? $category->description;

            $category->save();  

            return $category;
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view category', 400);}
    }
    //========================================================================================================================
    /**
     * method to show category alraedy exist
     * @param  $category_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_Category($category_id) {
        try {    
            $category = Category::find($category_id);
            if(!$category){
                throw new \Exception('category not found');
            }
            return $category;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with update category', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete category alraedy exist
     * @param  Category $category
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_category($category_id)
    {
        try {  
            $category = Category::find($category_id);
            if(!$category){
                throw new \Exception('category not found');
            }
            //get all books that related with this category to soft delete it 
            $books = Book::where('category_id',$category_id)->get();

            $category->delete();
            foreach($books as $book){
                $book->delete();
            }

            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting category', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete categories
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_category()
    {
        try {  
            $categories = Category::onlyTrashed()->get();
            return $categories;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view trashed category', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete category alraedy exist
     * @param   $category_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_category($category_id)
    {
        try {
            $category = Category::withTrashed()->find($category_id);
            if(!$category){
                throw new \Exception('category not found');
            }
            //get all books that related with this category to restore it 
            $books = Book::withTrashed()->where('category_id',$category_id)->get();

            foreach($books as $book){
                $book->restore();
            }

            return $category->restore();

        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with restore category', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on category that soft deleted before
     * @param   $category_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_category($category_id)
    {   
        try {
            $category = Category::onlyTrashed()->find($category_id);
            if(!$category){
                throw new \Exception('category not found');
            }
             //get all books that related with this category to force delete it 
             $books = Book::onlyTrashed()->where('category_id',$category_id)->get();

             foreach($books as $book){
                 $book->forceDelete();
             }

            return $category->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting category', 400);}
    }
    //========================================================================================================================

}
