<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Uploadable;

use App\Models\Property as Model;

class PropertyController extends Controller
{
    use Uploadable;

    public $model = "Property";
    public $relations = ["offerings.property"];
    public $directory = "properties";

    public $rules = [
        'name' => 'required|max:255',
        'type' => 'required|max:255',
        'location' => 'required|max:255',
        "map" => 'nullable|url',
        'minimum_price' => 'required|decimal:0,2',
        'maximum_price' => 'required|decimal:0,2',
        'minimum_area' => 'required|decimal:0,2',
        'maximum_area' => 'required|decimal:0,2',
        'status' => 'required|max:255',
        'description' => 'required',
        'logo' => 'required|file',
        'images' => 'required|array',
        'amenities' => 'required|array',
    ];

    public function getAll()
    {
        $records = Model::with($this->relations)->get();
        $response = ['message' => "Fetched Properties", 'records' => $records];
        $code = 200;
        return response()->json($response, $code);
    }

    public function get($id)
    {
        $record = Model::with($this->relations)->where('id', $id)->first();
        if ($record) {
            $response = ['message' => "Fetched $this->model", 'record' => $record];
            $code = 200;
        } else {
            $response = ['message' => "$this->model Not Found"];
            $code = 404;
        }
        return response()->json($response, $code);
    }

    public function create(Request $request)
    {
        $validated = $request->validate($this->rules);

        $key = 'logo';
        if ($request[$key]) {
            $validated[$key] = $this->upload("$this->directory/logos", $request[$key]);
        }

        $key = 'images';
        if ($request[$key]) {
            $images = [];
            foreach ($request[$key] as $image) {
                array_push($images, $this->upload("$this->directory/images", $image));
            }
            $validated[$key] = json_encode($images);
        }

        $key = 'amenities';
        if ($request[$key]) {
            $validated[$key] = json_encode($validated[$key]);
        }

        $record = Model::create($validated);

        $response = [
            'message' => "Created $this->model",
            'record' => $record,
        ];
        $code = 201;
        return response()->json($response, $code);
    }

    public function update(Request $request)
    {
        $this->rules['id'] = 'required|exists:properties,id';
        $this->rules['logo'] = 'nullable|file';
        $this->rules['images'] = 'nullable|array';
        $this->rules['amenities'] = 'nullable|array';
        $validated = $request->validate($this->rules);

        $record = Model::find($validated['id']);

        $key = 'logo';
        if ($request[$key]) {
            $validated[$key] = $this->upload("$this->directory/logos", $request[$key]);
        }

        $key = 'images';
        if ($request[$key]) {
            $images = [];
            foreach ($request[$key] as $image) {
                array_push($images, $this->upload("$this->directory/images", $image));
            }
            $validated[$key] = json_encode($images);
        }

        $key = 'amenities';
        if ($request[$key]) {
            $validated[$key] = json_encode($validated[$key]);
        }

        $record->update($validated);

        $response = ['message' => "Updated $this->model", 'record' => $record];
        $code = 200;
        return response()->json($response, $code);
    }

    public function delete($id)
    {
        $record = Model::find($id);
        if ($record) {
            $record->delete();
            $response = ['message' => "Deleted $this->model"];
            $code = 200;
        } else {
            $response = ['message' => "$this->model Not Found"];
            $code = 404;
        }
        return response()->json($response, $code);
    }

    public function setIsPublished(Request $request)
    {
        $rules = [
            'id' => 'required|exists:properties,id',
            'isPublished' => 'required|boolean',
        ];
        $validated = $request->validate($rules);

        $record = Model::find($validated['id']);
        $record->update($validated);

        $response = ['message' => "Updated Published Status of $this->model", 'record' => $record];
        $code = 200;
        return response()->json($response, $code);
    }

    public function setIsFeatured(Request $request)
    {
        $rules = [
            'id' => 'required|exists:properties,id',
            'isFeatured' => 'required|boolean',
        ];
        $validated = $request->validate($rules);

        $record = Model::find($validated['id']);
        $record->update($validated);

        $response = ['message' => "Updated Featured Status of $this->model", 'record' => $record];
        $code = 200;
        return response()->json($response, $code);
    }
}
