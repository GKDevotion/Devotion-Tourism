<?php
  
namespace App\Http\Controllers\API;
  
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
  
class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message, $pagination=null, $status=200)
    {
        $response = [
            'success' => ( $status == 200 ) ? true : false,
            'data'    => $result,
            'pagination' => $pagination,
            'message' => $message,
        ];
  
        return response()->json($response, $status);
    }
  
    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
  
        if(!empty($errorMessages)){
            $response['data_error'] = $errorMessages;
        }
  
        return response()->json($response, $code);
    }
}