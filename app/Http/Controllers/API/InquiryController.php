<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Uploadable;

use App\Models\Inquiry as Model;

class InquiryController extends Controller
{
    use Uploadable;

    public $model = "Inquiry";
    public $relations = ["property"];
    public $directory = "";

    public $rules = [
        'property_id' => 'required|exists:properties,id',
        'first_name' => 'required|max:255',
        'last_name' => 'required|max:255',
        'mobile' => 'required|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required',
    ];

    public function getAll()
    {
        $records = Model::with($this->relations)->get();
        $response = ['message' => "Fetched Inquiries", 'records' => $records];
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
        $this->rules['id'] = 'required|exists:inquiries,id';
        $validated = $request->validate($this->rules);

        $record = Model::find($validated['id']);
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
}
