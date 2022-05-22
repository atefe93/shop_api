<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BrandController extends ApiController
{

    public function index()
    {
        $brands=Brand::paginate(2);
        return $this->successResponse(([
            'brands'=>BrandResource::collection($brands),
            'links'=>BrandResource::collection($brands)->response()->getData()->links,
            'meta'=>BrandResource::collection($brands)->response()->getData()->meta
        ]));


    }


    public function store(Request $request)
    {

        $validator=Validator::make($request->all(), [
            'name'=>'required',
            'display_name'=>'required|unique:brands'
        ]);

        if ($validator->fails()){

            return $this->errorResponse($validator->messages(), 422);
        }


        DB::beginTransaction();

        $brand=Brand::create([
            'name'=>$request->name,
            'display_name'=>$request->display_name
        ]);

        DB::commit();


        return $this->successResponse(new BrandResource($brand),201);
    }


    public function show(Brand $brand)
    {
        return $this->successResponse(new BrandResource($brand),200);
    }


    public function update(Request $request, Brand $brand)
    {

        $validator=Validator::make($request->all(), [
            'name'=>'required',
            'display_name'=>'required|unique:brands'
        ]);

        if ($validator->fails()){

            return $this->errorResponse($validator->messages(), 422);
        }


        DB::beginTransaction();

        $brand->update([
            'name'=>$request->name,
            'display_name'=>$request->display_name
        ]);

        DB::commit();


        return $this->successResponse(new BrandResource($brand),200);
    }


    public function destroy(Brand $brand)
    {
        DB::beginTransaction();
        $brand->delete();
        DB::commit();

        return $this->successResponse(new BrandResource($brand),200);
    }
    public function products(Brand $brand){

        return $this->successResponse(new BrandResource($brand->load('products')),200);


    }
}
