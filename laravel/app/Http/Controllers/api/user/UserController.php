<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class UserController extends Controller
{
    /**
     * Get own user id, name, email, and active tokens
     */
    public function index()
    {
        $user = Auth::user();
        $tokens = $user->token();
        return response()->json(['success' => ['user' => $user, 'tokens' => $tokens]], 200);
    }

    /**
     * Return error when no token provided
     */
    public function forbidden()
    {
        return response()->json(["error" => Config::get('constants.http_error.e403')], 403);
    }

    /**
     * Forward query to external Yelp API, limiting results to 5
     *
     * @param Request $request URL encoded search term and location
     */
    public function yelpExternal(Request $request)
    {
        $params = $request->validate([
            "term" => "required|string",
            "location" => "required|string",
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('YELP_API_KEY'),
        ])->get('https://api.yelp.com/v3/businesses/search', [
            'term' => $params['term'],
            'location' => $params['location'],
            'limit' => 5,
        ]);

        return response($response);
    }
}
