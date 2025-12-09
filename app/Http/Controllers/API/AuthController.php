<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = Admin::create($input);
        $success['token'] =  $user->createToken('DevotionAuth')->plainTextToken;
        $success['name'] =  $user->name;
        $success['id'] =  $user->id;
        
        return $this->sendResponse($success, 'User register successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if( Auth::attempt( ['email' => $request->email, 'password' => $request->password] ) ){ 
        // $res = Auth::guard('admin')->attempt( ['email' => $request->email, 'password' => $request->password] );
        // if ( Auth::guard('admin')->attempt( ['email' => $request->email, 'password' => $request->password] ) ) {
            // $user = admin::where( ['email' => $request->email ] )->first(); 
            $user = Auth::user();
            $success['token'] =  $user->createToken('DevotionAuth')->plainTextToken; 
            $success['id'] =  $user->id;
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
}