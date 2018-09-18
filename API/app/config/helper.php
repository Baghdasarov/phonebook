<?php

function dd(...$args) {
    var_dump($args);
    die(1);
}

function json_response($data, $message = null, $code = 200)
{
    header_remove();
    http_response_code($code);
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header('Access-Control-Allow-Origin: *');
    header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
    header('Content-Type: application/json');
    $status = array(
        200 => '200 OK',
        400 => '400 Bad Request',
        404 => '404 Not found',
        500 => '500 Internal Server Error'
    );
    header('Status: '.$status[$code]);
    return json_encode(array(
        'data' => $data,
        'status' => $code < 300,
        'message' => $message
    ));
}