<?php

class ResponseService
{
    public static function response(int $status, $data)
    {
        header("Content-Type: application/json");  //cleaner, it will be used in every controller when calling the class & the function
        http_response_code($status);
        return json_encode([
            "status" => $status,
            "data" => $data
        ]);
    }
}
