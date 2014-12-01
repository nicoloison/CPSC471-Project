<?php

/**
 * JSON-formatted success message
 */
function success()
{
    $ret = ["result" => "success", "error" => ""];
    printf(json_encode($ret));
}

/**
 * JSON-formatted error message
 */
function error($message)
{
    $ret = ["result" => "failure", "error" => $message];
    printf(json_encode($ret));
}

?>