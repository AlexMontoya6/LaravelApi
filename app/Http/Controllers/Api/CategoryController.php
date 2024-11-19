<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Symfony\Component\HttpFoundation\Response;

#[Group('Categories', description: 'Manage categories')]
class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    #[Endpoint('Get all categories')]
    public function index()
    {
        abort_if(!auth()->user()->tokenCan('categories-list'), 403);

        return CategoryResource::collection(Category::all());
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

