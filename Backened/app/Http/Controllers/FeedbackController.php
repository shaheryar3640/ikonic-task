<?php

namespace App\Http\Controllers;

use App\Models\{Feedback,Category};
use Illuminate\Http\Request;
use App\Services\IResponseCodes;
use Validator;
use Illuminate\Validation\Rule;

class FeedbackController extends Controller
{
    protected $_feedbackModel;
    protected $_categorykModel;
    public function __construct(Feedback $feedback,Category $category)
    {
        $this->_feedbackModel = $feedback;
        $this->_categorykModel = $category;
    }
    public function index()
    {
        $userId = auth()->user()->id;
        $feedback = $this->_feedbackModel->with('category')->orderBy('created_at', 'desc')->paginate(6);
        if(!$feedback->isEmpty()){
            $response = [
                'status' => 'success',
                'data' => $feedback,
            ];
        }
        else{
            $response = [
                'status' => false,
                'message' => 'feedback Not Found',
                'data' => []
            ];
        }
        return response()->json($response, IResponseCodes::SUCCESS);
    }
    public function show($id)
    {
        $categoryList = $this->_categorykModel->all();
        $userId = auth()->user()->id;
        $feedback =$this->_feedbackModel->with('category','user:id,name','comments.user:id,name','comments.replies.user:id,name','comments.replies.replies.user:id,name','comments.replies.replies.user:id,name')->find($id);
  
        if (is_null($feedback)) {
            return response()->json([
                'status' => false,
                'message' => 'feedback is not found!',
            ], IResponseCodes::NOT_FOUND);
        }
        if ($feedback->user_id !== $userId) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to access this feedback!',
            ], IResponseCodes::NOT_FOUND);
        }
        return response()->json([
            'status' => 'success',
            'feedback' => $feedback,
            'category' => $categoryList
        ], IResponseCodes::SUCCESS);
    }
    public function feedbackComment($id)
    {
        $userId = auth()->user()->id;
        $feedback =$this->_feedbackModel->with('category', 'user:id,name', 'comments')->find($id);
  
        if (is_null($feedback)) {
            return response()->json([
                'status' => false,
                'message' => 'feedback is not found!',
            ], IResponseCodes::NOT_FOUND);
        }
        return response()->json([
            'status' => 'success',
            'feedback' => $feedback,
        ], IResponseCodes::SUCCESS);
    }
    public function store(Request $request)
    {
        // return $request->all();
        $userId = auth()->user()->id;
        $validate = Validator::make($request->all(), [
            'title' => [
                'required',
                'string',
                'max:250',
                Rule::unique('feedback', 'title')->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                })
            ],
            'category_id' => 'required',
            'description' => 'required',
        ]);

        if($validate->fails()){  
            $response = [
                'status' => false,
                'message' => 'Validation Error!',
                'data' => $validate->errors(),
            ]; 
            return response()->json($response, IResponseCodes::Validator_error); 
        }

        
        $feedback =  $this->_feedbackModel->create([
            'title' => $request->title,
            'user_id' => $userId,
            'category_id' => $request->category_id,
            'description' => $request->description
        ]);
         $response = [
            'status' => 'success',
            'message' => 'feedback Added Successfully',
            'data' => $feedback,
         ];

        return response()->json($response, IResponseCodes::SUCCESS); 
    }
    public function update(Request $request)
{
    $userId = auth()->user()->id;

    $validate = Validator::make($request->all(), [
        'title' => [
            'required',
            'string',
            'max:250'
        ],
        'category_id' => 'required',
        'description' => 'required',
    ]);

    if ($validate->fails()) {  
        $response = [
            'status' => false,
            'message' => 'Validation Error!',
            'data' => $validate->errors(),
        ]; 
        return response()->json($response, IResponseCodes::Validator_error); 
    }

    $feedback = $this->_feedbackModel->where([
        'user_id' => $userId,
        'id' => $request->id
    ])->first();

    if ($feedback) {
        if (!empty($request->title)) {
            $feedback->title = $request->title;
        }
        if (!empty($request->description)) {
            $feedback->description = $request->description;
        }
        if (!empty($request->category_id)) {
            $feedback->category_id = $request->category_id;
        }
        $feedback->save();
        $statusCode = IResponseCodes::SUCCESS;
        $response = [
            'status' => 'success',
            'message' => 'feedback Updated Successfully',
            'data' => $feedback,
        ];
    } else {
        $statusCode = IResponseCodes::NOT_FOUND;
        $response = [
            'status' => false,
            'message' => 'feedback Not Found',
            'data' => [],
        ];
    }

    return response()->json($response, $statusCode); 
}


    public function destroy(Request $request)
    {
        $id = $request->id;
        $feedback =$this->_feedbackModel->find($id);
  
        if (is_null($feedback)) {
            return response()->json([
                'status' => false,
                'message' => 'feedback is not found!',
            ], IResponseCodes::NOT_FOUND);
        }

        $this->_feedbackModel->destroy($id);
            return response()->json([
                'status' => 'success',
                'message' => 'feedback is deleted successfully.'
            ], IResponseCodes::SUCCESS);
    }
    public function categories(){
        $categoryList = $this->_categorykModel->all();
        if(!$categoryList->isEmpty()){
            $response = [
                'status' => 'success',
                'data' => $categoryList,
            ];
        }
        else{
            $response = [
                'status' => false,
                'message' => 'categoryList Not Found',
                'data' => []
            ];
        }
        return response()->json($response, IResponseCodes::SUCCESS);
    }
}
