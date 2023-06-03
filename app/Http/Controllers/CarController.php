<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Http\Requests\CarUpdateRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;


class CarController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        try {
            $modelId = $request->modelId;
            $brandName = $request->brandName;
            $cars = Car::orderBy('id', "desc");
            if ($modelId) {
                $cars->where('brand_id', $modelId);
            }

            if ($brandName) {
                $cars->where("model", $brandName);
            }

            $page = $request->input('page') ?: 1;
            $take = $request->input('count') ?: 4;
            $count = $cars->count();
            if ($page) {
                $skip = $take * ($page - 1);
                $cars = $cars->take($take)->skip($skip);
            } else {
                $cars = $cars->take($take)->skip(0);
            }
            return [
                'data' => CarResource::collection($cars->with('brand')->get()),
                'pagination' => [
                    'cars_pages' => ceil($count / $take),
                    'count' => $count
                ],
                'status' => true,
            ];
        } catch (\Exception $e) {
            return response()->json([
                'messages' => $e->getMessage(),
                'status' => false
            ], 500);
        }

    }

    /**
     * @param CarRequest $request
     * @return mixed
     */
    public function store(CarRequest $request)
    {
        $data = $request->validated();
        try {
            $car = Car::create($data);
            if ($request->hasFile('images')) {
                $images = $request->images;
                $imageData = [];
                foreach ($images as $image) {
                    $filename = pathinfo($image, PATHINFO_FILENAME);
                    $imageName = $filename . time() . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('/public/images', $imageName);
                    array_push($imageData, [
                        'name' => $imageName,
                        'car_id' => $car->id,
                    ]);
                }
                CarImage::insert($imageData);
            }
            return response()->json([
                'data' => new CarResource($car),
                'status' => true
            ], 201);
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
    public function show($id)
    {
        try {
            $car = Car::where('id', $id)->with('brand', 'images')->first();
            return response()->json([
                'data' => new CarResource($car),
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
     * @param CarUpdateRequest $request
     * @param $id
     * @return mixed
     */
    public function update(CarUpdateRequest $request, $id)
    {
        try {
            $car = Car::findOrFail($id);
            $data = $request->except('images');
            Storage::delete($car->images);
            if ($request->hasFile('images')) {
                $images = $request->images;
                $imageData = [];
                foreach ($images as $image) {
                    $filename = pathinfo($image, PATHINFO_FILENAME);
                    $imageName = $filename . time() . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('/public/images', $imageName);
                    array_push($imageData, [
                        'name' => $imageName,
                        'car_id' => $car->id,
                    ]);
                    CarImage::insert($imageData);
                }
            }

            $car->update($data);
            return response()->json([
                'data' => $car,
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
            $car = Car::findOrFail($id);
            $car->delete();
            return response()->json([
                'messages' => "Car Deleted successfully!",
                'status' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'messages' => $e->getMessage(),
                'status' => false
            ], 500);
        }
    }

    /**\
     * @param $id
     * @return JsonResponse
     */
    public function deleteCarImage($id)
    {
        try {
            $carImg = CarImage::findOrFail($id);
            $imageName = $carImg->name;
            $imagePath = public_path('storage/images/' . $imageName);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $carImg->delete();
            return response()->json([
                'messages' => "Car Image Deleted successfully!",
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

//  index(GET) ---> show the list
//  create(GET) ---> return create page
//  store(POST) ---> send data from create page
//  show (GET) -> show one item
//  edit(GET)  ---> get one item and show in edit page
//  update(PUT)  ---> send data from edit page
//  destroy(DELETE) --->  Destroy one item
