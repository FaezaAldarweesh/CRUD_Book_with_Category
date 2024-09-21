<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\CategoryResources;
use App\Http\Requests\Store_Category_Request;
use App\Http\Requests\Update_Category_Request;

class CategoryController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $categoryservices;
    /**
     * construct to inject Category Service
     * @param CategoryService $categoryservices
     */
    public function __construct(CategoryService $categoryservices)
    {
        $this->categoryservices = $categoryservices;
    }
    //===========================================================================================================================
    /**
     * method to view all categories
     * @return /Illuminate\Http\JsonResponse
     * CategoryResources to customize the return responses.
     */
    public function index()
    {  
        $categories = $this->categoryservices->get_all_Categories();
        return $this->success_Response(CategoryResources::collection($categories), "All categories fetched successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new category
     * @param   Store_Category_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Category_Request $request)
    {
        $category = $this->categoryservices->create_Category($request->validated());
        return $this->success_Response(new CategoryResources($category), "category created successfully.", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show category alraedy exist
     * @param  $category_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($category_id)
    {
        $category = $this->categoryservices->view_category($category_id);

        // In case error messages are returned from the services section 
        if ($category instanceof \Illuminate\Http\JsonResponse) {
            return $category;
        }
            return $this->success_Response(new CategoryResources($category), "category viewed successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to update category alraedy exist
     * @param  Update_Category_Request $request
     * @param  Category $category
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Category_Request $request, Category $category)
    {
        $category = $this->categoryservices->update_Category($request->validated(), $category);
        return $this->success_Response(new CategoryResources($category), "category updated successfully", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete category alraedy exist
     * @param  $category_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($category_id)
    {
        $category = $this->categoryservices->delete_category($category_id);

        // In case error messages are returned from the services section 
        if ($category instanceof \Illuminate\Http\JsonResponse) {
            return $category;
        }
            return $this->success_Response(null, "category soft deleted successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft delete categories
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_category()
    {
        $categories = $this->categoryservices->all_trashed_category();
        return $this->success_Response(CategoryResources::collection($categories), "All trashed categories fetched successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft delete category alraedy exist
     * @param   $category_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($category_id)
    {
        $delete = $this->categoryservices->restore_category($category_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "category restored successfully", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on category that soft deleted before
     * @param   $category_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($category_id)
    {
        $delete = $this->categoryservices->forceDelete_category($category_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "category force deleted successfully", 200);
    }  
    //========================================================================================================================
}
