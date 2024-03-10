<?php

namespace App\Traits;

trait HttpResponses{
    protected function success($data,$message=null,$code=200){
        return response()->json([
            'status' => 'request was successful. ',
            'message' => $message,
            'data' => $data
        ],$code);
    }

    protected function error($data,$message=null,$code){
        return response()->json([
            'status' => 'Error has occurred ... ',
            'message' => $message,
            'data' => $data
        ],$code);
    }

    protected function responseError($message=null,$code){
        return response()->json([
            'message' => $message,
        ],$code);
    }

    protected function responseSuccess($message=null,$code=200){
        return response()->json([
            'message' => $message,
        ],$code);
    }
}
