<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{

    public function index()
    {
        $categories=Category::paginate(2);
        return $this->successResponse(([
            'categories'=>CategoryResource::collection($categories),
            'links'=>CategoryResource::collection($categories)->response()->getData()->links,
            'meta'=>CategoryResource::collection($categories)->response()->getData()->meta
        ]));
    }


    public function store(Request $request)
    {

        $validator=Validator::make($request->all(), [
            'parent_id'=>'required|integer',
            'name'=>'required|string'
        ]);

        if ($validator->fails()){

            return $this->errorResponse($validator->messages(), 422);
        }


        DB::beginTransaction();

        $category=Category::create([
            'parent_id'=>$request->parent_id,
            'name'=>$request->name,
            'description'=>$request->description
        ]);

        DB::commit();


        return $this->successResponse(new CategoryResource($category),201);
    }

    public function show(Category $category)
    {
        return $this->successResponse(new CategoryResource($category),200);
    }


    public function update(Request $request, Category $category)
    {
        $validator=Validator::make($request->all(), [
            'parent_id'=>'required|integer',
            'name'=>'required|string'
        ]);

        if ($validator->fails()){

            return $this->errorResponse($validator->messages(), 422);
        }


        DB::beginTransaction();

        $category->update([
            'parent_id'=>$request->parent_id,
            'name'=>$request->name,
            'description'=>$request->description
        ]);

        DB::commit();


        return $this->successResponse(new CategoryResource($category),200);
    }


    public function destroy(Category $category)
    {
        DB::beginTransaction();
        $category->delete();
        DB::commit();
        return $this->successResponse(new CategoryResource($category),200);
    }
    public function children(Category $category){
        return $this->successResponse(new CategoryResource($category->load('children')),200);
    }
    public function parent(Category $category){
        return $this->successResponse(new CategoryResource($category->load('parent')),200);
    }
    public function products(Category $category){

        return $this->successResponse(new CategoryResource($category->load('products')),200);


    }
}
