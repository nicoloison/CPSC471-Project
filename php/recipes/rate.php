<?php

require "../errors.php";
require "../escape.php";

/**
 * Validate GET parameters
 */
function check_params()
{
    $params = ["username", "recipe_name", "author_name", "rating"];

    foreach ($params as $p) {
        if (!array_key_exists($p, $_GET)) {
            error("missing parameter $p");
            exit();
        }
    }
}

/**
 * Create a query to update an entry already in the database
 *
 * UPDATE user_recipe_ratings SET rating = <rating>
 *   WHERE user_name = '<username>'
 *   AND recipe_name = '<recipe_name>'
 *   AND author_name = '<author_name>'
 */
function update_query($params)
{
    $query = "UPDATE user_recipe_ratings SET rating = " . $params["rating"]
        . " WHERE user_name = '" . $params["username"]
        . "' AND recipe_name = '" . $params["recipe_name"]
        . "' AND author_name = '" . $params["author_name"] . "'";
    return $query;
}

/**
 * Create a query to add a new rating entry to the database
 * This query will return a false result if the entry already exists
 * (or for any reason an SQL query can normally return errors).
 *
 * INSERT INTO user_recipe_ratings VALUES('<username>','<recipe_name',
 *                                        '<author_name>',<rating>)
 */
function insert_query($params)
{
    $query = "INSERT INTO user_recipe_ratings VALUES ('"
        . $params["username"] . "','" . $params["recipe_name"] . "','"
        . $params["author_name"] . "','" . $params["rating"] . "')";
    return $query;
}

/**
 * Escape all parameters to be used in query strings
 * Param $mysqli: mysqli connection to use for string escaping
 */
function escape_params($mysqli)
{
    $params = [];

    foreach ($_GET as $param => $value) {
        $params[$param] = escape_value($mysqli, $param, $value);
    }

    return $params;
}

/**
 * Main method
 */
function main()
{
    check_params();

    $mysqli = new mysqli("localhost", "mysql", "mysql", "recipedb");

    if ($mysqli->connect_errno) {
        error("database connection error");
        exit();
    }

    $params = escape_params($mysqli);


    $query = insert_query($params);
    $result = $mysqli->query($query);

    if ($result) {
        success();
    }
    else {
        $query = update_query();
        $result = $mysqli->query($query);
        
        if ($result) {
            success();
        }
        else {
            error("database update error" . $mysqli->error);
        }
    }
}

main();

?>
