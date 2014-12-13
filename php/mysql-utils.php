<?php

require_once "errors.php";

function recipedb_error($message, $mysqli)
{
    error($message . " " . $mysqli->errno . " " . $mysqli->error);
}

function recipedb_connect()
{
    $mysqli = new mysqli("localhost", "mysql", "mysql", "recipedb");
    if ($mysqli->connect_errno) {
        recipedb_error("database connection error", $mysqli);
        exit();
    }

    return $mysqli;
}

function delete_from($mysqli, $table, $parameters)
{
    foreach ($parameters as $p => $v) {
        $parameters[$p] = quote($v);
    }

    $query = delete($table) . where_equal($parameters);
    $result = $mysqli->query($query);

    if (!$result) {
        error("unable to complete query $query");
        exit();
    }
}

?>