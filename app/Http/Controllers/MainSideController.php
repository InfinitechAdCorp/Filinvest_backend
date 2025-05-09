<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Subscriber;

class MainSideController extends Controller
{
    public function unsubscribe($email)
    {
        $subscriber = Subscriber::where('email', $email)->first();
    
        if ($subscriber) {
            $subscriber->delete();
            // Redirect to your frontend unsubscribe confirmation page
            return redirect()->away('https://filinvest-main-frontend.vercel.app/unsubscribe');
        } else {
            // Optional: Redirect to a 'not found' page or still show unsubscribe confirmation
            return redirect()->away('https://filinvest-main-frontend.vercel.app/unsubscribe');
        }
    }
    
}
