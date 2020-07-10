<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Exception;
use JWTAuth;
use DB;

class CommentController extends RespondController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if($request->query('warung_id')) {
                $comments = Comment::where('warung_id', $request->query('warung_id'))->paginate();    
            }
            else 
            {
                $comments = Comment::paginate();
            }
            return $this->sendResponse(true, "get all comments", 200, $comments);
        } catch (Exception $e) {
            return $this->sendResponse(false, "error get all comments", 500, $e);
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
        $comment = Comment::create([
            'user_id' => $this->user()->id,
            'warung_id' => $request->warung_id,
            'comment' => $request->comment,
            'status' => $request->status, 
            'score' => $request->score
        ]); 
        return $this->sendResponse(true, "create comment", 201, $comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return $this->sendResponse(true, "show comment", 200, $comment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $comment->update($request->all());
        return $this->sendResponse(true, "update comment", 200, $comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */

    public function user() {
        $token = JWTAuth::getToken();
        // $user = JWTAuth::getPayload($token)->toArray();
        $user = JWTAuth::toUser($token);
        return $user;
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return $this->sendResponse(true, "delete comment", 204, $comment);
    }
}
