<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Symfony\Component\HttpFoundation\Response;

#[Group('Categories', description: 'Manage categories')]
class CategoryController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Get all categories",
     *     description="Retrieve a list of all categories.",
     *     operationId="getCategories",
     *     tags={"Categories"},
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CategoryResource"))
     *     ),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */

     public function index()
     {
         abort_if(!auth()->user()->tokenCan('categories-list'), 403);

         return CategoryResource::class::collection(Cache::remember('categories', 60*60*24, function () {
             return Category::all();
         }));
     }

    /**
     * Display a category.
     */
    #[Endpoint('Show category', description: 'Get a category by ID')]
    public function show(Category $category)
    {
        abort_if(!auth()->user()->tokenCan('categories-show'), 403);

        return new CategoryResource($category);
    }

    /**
     * Store a newly created category in storage.
     */
    #[Endpoint('Create a category')]
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->all();
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $name = Str::uuid() . '.' . $file->extension();
            $file->storeAs('categories', $name, 'public');
            $data['photo'] = $name;
        }

        $category = Category::create($data);

        return new CategoryResource($category);
    }

    /**
     * Get all categories.
     */
    #[Endpoint('Get all categories')]
    public function list()
    {
        return CategoryResource::collection(Category::all());
    }

    /**
     * Update the specified category in storage.
     */
    #[Endpoint('Update a category')]
    public function update(Category $category, StoreCategoryRequest $request)
    {
        $category->update($request->all());

        return new CategoryResource($category);
    }

    /**
     * Remove the specified category from storage.
     */
    #[Endpoint('Delete a category')]
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->noContent();
    }
}



