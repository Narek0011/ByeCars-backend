<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModelCarRequest;
use App\Http\Resources\ModelCarResource;
use App\Models\ModelCar;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

//ToDo Fix messages/ cods / responses
class ModelCarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        try{
            $models = ModelCar::orderBy('id', "desc");
            $page = $request->input('page') ?: 1;
            $take = $request->input('count') ?: 4;
            $count = $models->count();
            if ($page) {
                $skip = $take * ($page - 1);
                $models = $models->take($take)->skip($skip);
            } else {
                $models = $models->take($take)->skip(0);
            }

            return [
                'data' => ModelCarResource::collection($models->get()),
                'pagination' => [
                    'cars_pages' => ceil($count / $take),
                    'count' => $count
                ],
                'status' => true
            ];
        }catch(\Exception $e){
            return response()->json([
                'messages' => $e->getMessage(),
                'status' => false
            ], 500);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ModelCarRequest $request
     * @return Response
     */
    public function store(ModelCarRequest $request)
    {
        $data = $request->validated();
        try {
            $model = ModelCar::create([
                'name' => $data['name'],
                'brand_id' => $data['brand_id'],
            ]);
            return response()->json([
                "data" => new ModelCarResource($model),
                'status' => true
            ],200) ;
        } catch (\Exception $e) {
            return response()->json([
                'messages' => $e->getMessage(),
                'status' => false
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ModelCarRequest $request
     * @param $id
     * @return ModelCarResource
     */
    public function update(ModelCarRequest $request, $id)
    {
        try {
            $modelData = ModelCar::findOrFail($id);
            $modelData->update(['name' => $request->name]);
            return new ModelCarResource($modelData);
        } catch (\Exception $e) {
            return response()->json([
                'messages' => $e->getMessage(),
                'status' => false
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = ModelCar::findOrFail($id);
            $model->delete();
            return response()->json([
                'messages' => "Model Deleted successfully!",
                'status' => true,
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'messages' => $e->getMessage(),
                'status' => false
            ], 500);
        }
    }

    /**
     * @param $id
     * @return ResponseFactory|Response
     */
    public function getBrandsById($id)
    {
        try {
            $models = ModelCar::where("brand_id", $id)->get();
            return response()->json([
                'data' => $models,
                'status' => true,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'messages' => $e->getMessage(),
                'status' => false
            ], 500);
        }
    }
}
