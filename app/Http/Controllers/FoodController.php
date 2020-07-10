<?php

namespace App\Http\Controllers;

use App\Food;
use App\PhotoFood;
use Illuminate\Http\Request;
use Exception;
use JWTAuth;
use DB;

class FoodController extends RespondController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = $this->user();
            $foods = Food::where('user_id', $user->id)->with('photos')->paginate();
            // $foods = $user->food->paginate();
            $counts = $this->countData($foods);
            return $this->sendResponse(true, "get all foods", 200, ['foods' => $foods, 'counts' => $counts, 'token' => $user]);
        } catch (Exception $e) {
            return $this->sendResponse(false, "error get all foods", 500, $e);
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
        $food = Food::create([
            'user_id' => $this->user()->id,
            'warung_id' => $request->warung_id,
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'price' => $request->price
        ]); 
        $image = $this->uploadImage($request, $this->user()->id,  $food->id);
        return $this->sendResponse(true, "create food", 201, $food);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function show(Food $food)
    {
        $result = $food->with('photos')->get();
        return $this->sendResponse(true, "show food", 200, $result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function edit(Food $food)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Food $food)
    {

        $food->update($request->all());
        return $this->sendResponse(true, "update food", 200, $food);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function destroy(Food $food)
    {
        // $food = Food::findOrFail($id);
        $food->delete();
        return $this->sendResponse(true, "delete food", 204, $food);
    }

    public function filter(Request $request) 
    {
        if($request->query('name') && $request->query('description') && $request->query('status'))
        {
            $name = strtolower($request->query('name'));
            $description = strtolower($request->query('description'));
            $status = strtolower($request->query('status'));
            $match = [['name', 'like', '%'.$name.'%'], ['description', 'like', '%'.$description.'%'], ['status', '=', $status]];
            $result = Food::where($match)->get();
        }
        else if($request->query('name'))
        {
            $value = strtolower($request->query('name'));
            $result = Food::where('name', $value)->orWhere('name', 'like', '%'.$value.'%')->get();
        }
        else if($request->query('description'))
        {
            $value = strtolower($request->query('description'));
            $result = Food::where('description', $value)->orWhere('description', 'like', '%'.$value.'%')->get();            
        }
        else if($request->query('status'))
        {
            $value = strtolower($request->query('status'));
            $result = Food::where('status', $value)->get();            
        }
        else 
        {
            return $this->index();
        }
        return $this->sendResponse(true, "count food", 200, $result);
    }

    public function count(Request $request)
    {
        if($request->query('status'))
        {
            $result = Food::where('status', $request->query('status'))->get();
        }
        else 
        {
            $result = Food::all();
        }
        $count = count($result);
        return $this->sendResponse(true, "count food", 200, ['count' => $count]);
    }

    public function user() {
        $token = JWTAuth::getToken();
        // $user = JWTAuth::getPayload($token)->toArray();
        $user = JWTAuth::toUser($token);
        return $user;
    }

    // public function uploadImage(Request $request, $user_id, $food_id)
    // {
    //     $image = $request->file('image');
    //     $image_storage = 'upload';
    //     $image_name = 'img_'.time();
    //     $image->move($image_storage, $image_name);
    //     $photo = PhotoFood::create([
    //         'user_id' => $user_id,
    //         'food_id' => $food_id,
    //         'path' => 'public/upload/'.$image_name.'.'.$image->getClientOriginalExtension(),
    //     ]);
    //     return $image_name;
    // }
    public function uploadImage(Request $request, $user_id, $food_id)
    {
        $image = $request->file('image');
        $image_storage = 'upload';
        $image_name = 'img_'.(microtime(true)*10000).'.'.$image->getClientOriginalExtension();
        $image->move($image_storage, $image_name);
        $photo = PhotoFood::create([
            'user_id' => $user_id,
            'food_id' => $food_id,
            'path' => $image_name,
        ]);
        return $image_name;
    }    
}
