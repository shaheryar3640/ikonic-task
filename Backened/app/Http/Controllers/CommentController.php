<?php

namespace App\Http\Controllers;

use App\Models\{Comment,User};
use Illuminate\Http\Request;
use App\Services\IResponseCodes;
use Validator;

class CommentController extends Controller
{
    protected $_commentModel;
    protected $_userModel;
    public function __construct(Comment $comment, User $user)
    {
        $this->_commentModel = $comment;
        $this->_userModel= $user;
    }
    public function allUser(){
        $allUser = $this->_userModel->all();
        if(!$allUser->isEmpty()){
            $response = [
                'status' => 'success',
                'data' => $allUser,
            ];
        }
        else{
            $response = [
                'status' => false,
                'message' => 'User Not Found',
                'data' => []
            ];
        }
        return response()->json($response, IResponseCodes::SUCCESS);
    }
    public function store(Request $request)
    {
        // return $request->all();
        $userId = auth()->user()->id;
        $validate = Validator::make($request->all(), [
            'feedback_id' => 'required',
            'comment' => 'required',
        ]);

        if($validate->fails()){  
            $response = [
                'status' => false,
                'message' => 'Validation Error!',
                'data' => $validate->errors(),
            ]; 
            return response()->json($response, IResponseCodes::Validator_error); 
        }

        
        $feedback =  $this->_commentModel->create([
            'comment' => $request->comment,
            'user_id' => $userId,
            'feedback_id' => $request->feedback_id
        ]);
         $response = [
            'status' => 'success',
            'message' => 'Comment Added Successfully',
            'data' => $feedback,
         ];

        return response()->json($response, IResponseCodes::SUCCESS); 
    }

    public function newComment(Request $request)
    {
        // return $request->all();
        // return $request->all();
        $userId = auth()->user()->id;
        $validate = Validator::make($request->all(), [
            'feedback_id' => 'required',
            'comment' => 'required',
        ]);

        if($validate->fails()){  
            $response = [
                'status' => false,
                'message' => 'Validation Error!',
                'data' => $validate->errors(),
            ]; 
            return response()->json($response, IResponseCodes::Validator_error); 
        }

        
        $feedback =  $this->_commentModel->create([
            'comment' => $request->comment,
            'user_id' => $userId,
            'feedback_id' => $request->feedback_id,
            'parent_id' => $request->parent_id
        ]);
         $response = [
            'status' => 'success',
            'message' => 'Comment Added Successfully',
            'data' => $feedback,
         ];

        return response()->json($response, IResponseCodes::SUCCESS); 
    }
}
