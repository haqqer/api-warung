<?php

namespace App\Http\Controllers;

use App\Warung;
use App\PhotoWarung;
use App\Comment;
use Illuminate\Http\Request;
use Exception;
use JWTAuth;
use DB;

class WarungController extends RespondController
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
            $warungs = Warung::where('user_id', $user->id)->with('photos', 'foods', 'foods.photos', 'comments')->paginate();
            // $warungs = $user->warung->paginate();
            $counts = $this->countData($warungs);
            return $this->sendResponse(true, "get all warungs", 200, ['warungs' => $warungs, 'counts' => $counts, 'token' => $user]);
        } catch (Exception $e) {
            return $this->sendResponse(false, "error get all warungs", 500, $e);
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
        $warung = Warung::create([
            'user_id' => $this->user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'status' => $request->status,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]); 
        $image = $this->uploadImage($request, $this->user()->id,  $warung->id);
        return $this->sendResponse(true, "create warung", 201, $warung);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Warung  $warung
     * @return \Illuminate\Http\Response
     */
    public function show(Warung $warung)
    {
        $data= Warung::where('id', $warung->id)->with('photos', 'foods', 'foods.photos', 'comments')->first();
        $data->average = $data->comments()->avg('score');
        return $this->sendResponse(true, "show warung", 200, $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Warung  $warung
     * @return \Illuminate\Http\Response
     */
    public function edit(Warung $warung)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Warung  $warung
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warung $warung)
    {

        $warung->update($request->all());
        return $this->sendResponse(true, "update warung", 200, $warung);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Warung  $warung
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warung $warung)
    {
        // $warung = Warung::findOrFail($id);
        $warung->delete();
        return $this->sendResponse(true, "delete warung", 204, $warung);
    }

    public function filter(Request $request) 
    {
        if($request->query('name') && $request->query('description') && $request->query('status'))
        {
            $name = strtolower($request->query('name'));
            $description = strtolower($request->query('description'));
            $status = strtolower($request->query('status'));
            $match = [['name', 'like', '%'.$name.'%'], ['description', 'like', '%'.$description.'%'], ['status', '=', $status]];
            $result = Warung::where($match)->get();
        }
        else if($request->query('name'))
        {
            $value = strtolower($request->query('name'));
            $result = Warung::where('name', $value)->orWhere('name', 'like', '%'.$value.'%')->get();
        }
        else if($request->query('description'))
        {
            $value = strtolower($request->query('description'));
            $result = Warung::where('description', $value)->orWhere('description', 'like', '%'.$value.'%')->get();            
        }
        else if($request->query('status'))
        {
            $value = strtolower($request->query('status'));
            $result = Warung::where('status', $value)->get();            
        }
        else 
        {
            $result = Warung::with('photos', 'foods', 'foods.photos', 'comments')->paginate();
        }
        return $this->sendResponse(true, "count warung", 200, $result);
    }

    public function count(Request $request)
    {
        if($request->query('status'))
        {
            $result = Warung::where('status', $request->query('status'))->get();
        }
        else 
        {
            $result = Warung::all();
        }
        $count = count($result);
        return $this->sendResponse(true, "count warung", 200, ['count' => $count]);
    }

    public function user() {
        $token = JWTAuth::getToken();
        // $user = JWTAuth::getPayload($token)->toArray();
        $user = JWTAuth::toUser($token);
        return $user;
    }

    public function uploadImage(Request $request, $user_id, $warung_id)
    {
        $image = $request->file('image');
        $image_storage = 'upload';
        $image_name = 'img_'.(microtime(true)*10000).'.'.$image->getClientOriginalExtension();
        $image->move($image_storage, $image_name);
        $photo = PhotoWarung::create([
            'user_id' => $user_id,
            'warung_id' => $warung_id,
            'path' => $image_name,
        ]);
        return $image_name;
    }
}
