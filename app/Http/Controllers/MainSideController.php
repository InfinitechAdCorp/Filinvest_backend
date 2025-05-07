<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Subscriber;

class MainSideController extends Controller
{
    public function unsubscribe($email)
    {
        $record = Subscriber::where('email', $email);
        if ($record) {
            $record->delete();
            $response = ['message' => "Deleted Subscriber"];
            $code = 200;
        } else {
            $response = ['message' => "Subscriber Not Found"];
            $code = 404;
        }
        return response()->json($response, $code);
    }
}
