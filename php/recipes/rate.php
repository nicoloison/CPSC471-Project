<?php

require "../errors.php";
require "../queries.php";
require "../parse.php";

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
    $query = update("user_recipe_ratings", "rating", $params["rating"]);
    unset($params["rating"]);

    $query .= where($params);
    return $query;
}

/**
 * Main method
 */
function main()
{
    $required = ["username", "author_name", "recipe_name", "rating"];
    require_params($required);

    $mysqli = new mysqli("localhost", "mysql", "mysql", "recipedb");
    if ($mysqli->connect_errno) {
        error("database connection error");
        exit();
    }

    $params = parse_get($mysqli);
    $query = insert("user_recipe_rating", $params);
    $result = $mysqli->query($query);

    if ($result) {
        success();
    }
    else {
        $query = update_query($params);
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
