<?php


namespace core;


class Response
{
    public static function json($data = [])
    {
        header("Content-Type: application/json;charset=utf-8");
        echo json_encode($data);
    }
}
