<?php


    function jsonResponse($menssage, $status = 201, $data = []){
        return response()->json([
            'message'=> $menssage,
            'data' =>$data,
        ], $status);
    }


if(!function_exists('jsonError')){
    function jsonError($menssage, $status = 201){
        return response()->json([
            'message'=> $menssage,
        ], $status);
    }
}


