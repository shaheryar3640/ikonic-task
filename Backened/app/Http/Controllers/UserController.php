<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\IResponseCodes;

class UserController extends Controller
{
    
    protected $_userModel;


    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->_userModel = $user;
    }
    public function register(Request $request)
     {
        $validate = Validator::make($request->all(),[
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);
        if($validate->fails()){  
            $response = [
                'status' => false,
                'message' => 'Validation Error!',
                'data' => $validate->errors(),
            ]; 
            return response()->json($response, IResponseCodes::Validator_error); 
        }
        $user = $this->_userModel->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        if($user){
            $statusCode = IResponseCodes::SUCCESS;
            $response = [
                'status' => 'success',
                'message' => 'Successfully created user!',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ];
        }
        else{
            $statusCode = IResponseCodes::SUCCESS;
            $response = [
                'status' => false,
                'message' => 'Provide proper details',
            ];
        }
        return response()->json($response, $statusCode);
        
    }

    public function login(Request $request)
     {
        $validate = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        if($validate->fails()){  
            $response = [
                'status' => false,
                'message' => 'Validation Error!',
                'data' => $validate->errors(),
            ]; 
            return response()->json($response, IResponseCodes::Validator_error); 
        }
        if(!Auth::attempt($request->only(['email', 'password']))){
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], IResponseCodes::BAD_REQUEST);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => 'success',
            'message' => 'User Logged In Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], IResponseCodes::SUCCESS);
        
    }
    public function user(Request $request){
        $user = $request->user();
        if ($user) {
            return response()->json(['user' => $user], IResponseCodes::SUCCESS);
        } else {
            return response()->json(['message' => 'User not authenticated'], IResponseCodes::UNAUTHENTICATED);
        }
    }
    public function logout(Request $request){
        // $user = $request->user()->currentAccessToken()->delete();
        $user = $request->user()->tokens()->delete();
        return response()->json(['message'=> 'User Successfully Logout '],IResponseCodes::SUCCESS);
    }
}
