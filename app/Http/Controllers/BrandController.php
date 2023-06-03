<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BrandController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        try {
            $brand = Brand::orderBy('id', "desc");
            $page = $request->input('page') ?: 1;
            $take = $request->input('count') ?: 4;
            $count = $brand->count();
            if ($page) {
                $skip = $take * ($page - 1);
                $brand = $brand->take($take)->skip($skip);
            } else {
                $brand = $brand->take($take)->skip(0);
            }
            return [
                'data' => BrandResource::collection($brand->get()),
                'pagination' => [
                    'cars_pages' => ceil($count / $take),
                    'count' => $count,
                ],
                'status' => true
            ];
        } catch (\Exception $e) {
            return response()->json([
                'messages' => $e->getMessage(),
                'status' => false
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param BrandRequest $request
     * @return Response
     */
    public function store(BrandRequest $request)
    {
        try {
            $brandData = $request->validated();
            $brand = Brand::create($brandData);
            return response()->json([
                'data' => $brand,
                'status' => true,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'messages' => $e->getMessage(),
                'status' => false
            ], 500);
        }
    }

    /**
     * @param BrandRequest $request
     * @param $id
     * @return BrandResource
     */
    public function update(BrandRequest $request, $id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->update(['name' => $request->name]);
            return response()->json([
                'data' => new BrandResource($brand),
                'status' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'messages' => $e->getMessage(),
                'status' => false
            ], 500);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->delete();
            return response()->json([
                'message' => "Brand Deleted successfully!",
                'status' => true,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'messages' => $e->getMessage(),
                'status' => false
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function getAllBrands()
    {
        try {
            $brands = Brand::all();
            return response()->json([
                'data' => BrandResource::collection($brands),
                'status' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'messages' => $e->getMessage(),
                'status' => false
            ], 500);
        }
    }
}
