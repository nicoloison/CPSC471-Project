<?php

/**
 * Validate GET parameters
 */
function check_params()
{
    $params = ["username", "recipe_name", "author_name", "rating"];

    foreach ($params as $p) {
        if (!array_key_exists($p, $_GET)) {
            printf(error("missing parameter $p"));
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
function update_query()
{
    $query = "UPDATE user_recipe_ratings SET rating = " . $_GET["rating"]
        . " WHERE user_name = '" . $_GET["username"]
        . "' AND recipe_name = '" . $_GET["recipe_name"]
        . "' AND author_name = '" . $_GET["author_name"] . "'";
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
function insert_query()
{
    $query = "INSERT INTO user_recipe_ratings VALUES ('"
        . $_GET["username"] . "','" . $_GET["recipe_name"] . "','"
        . $_GET["author_name"] . "','" . $_GET["rating"] . "')";
    return $query;
}

/**
 * JSON formatted success message
 */
function success()
{
    $ret = ["result" => "success", "error" => ""];
    return json_encode($ret);
}

/**
 * JSON formatted error message
 */
function error($message)
{
    $ret = ["result" => "failure", "error" => $message];
    return json_encode($ret);
}

/**
 * Main method
 */
function main()
{
    $mysqli = new mysqli("localhost", "mysql", "mysql", "recipedb");

    if ($mysqli->connect_errno) {
        printf(error("database connection error"));
        exit();
    }

    $query = insert_query();
    $result = $mysqli->query($query);

    if ($result) {
        printf(success());
    }
    else {
        $query = update_query();
        $result = $mysqli->query($query);
        
        if ($result) {
            printf(success());
        }
        else {
            printf(error("database update error"));
        }
    }
}

main();

?>
