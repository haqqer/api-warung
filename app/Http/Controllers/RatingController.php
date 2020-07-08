<?php

namespace App\Http\Controllers;

use App\Rating;
use Illuminate\Http\Request;
use Exception;
use JWTAuth;
use DB;

class RatingController extends RespondController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $token = JWTAuth::getToken();
            // $user = JWTAuth::getPayload($token)->toArray();
            $user = JWTAuth::toUser($token);
            // print_r($user);
            $ratings = Rating::with('posts')->paginate();
            $counts = $this->countData($ratings);
            return $this->sendResponse(true, "get all ratings", 200, ['ratings' => $ratings, 'counts' => $counts, 'token' => $user]);
        } catch (Exception $e) {
            return $this->sendResponse(false, "error get all ratings", 500, $e);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rating = Rating::create($request->all());
        return $this->sendResponse(true, "create rating", 201, $rating);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function show(Rating $rating)
    {
        return $this->sendResponse(true, "show rating", 200, $rating);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function edit(Rating $rating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rating $rating)
    {
        $rating->update($request->all());
        return $this->sendResponse(true, "update rating", 200, $rating);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rating $rating)
    {
        // $rating = Rating::findOrFail($id);
        $rating->delete();
        return $this->sendResponse(true, "delete rating", 204, $rating);
    }

    public function count(Request $request)
    {
        if($request->query('status'))
        {
            $result = Rating::where('status', $request->query('status'))->get();
        }
        else 
        {
            $result = Rating::all();
        }
        $count = count($result);
        return $this->sendResponse(true, "count rating", 200, ['count' => $count]);
    }
}
