<?php

require_once "../errors.php";
require_once "../mysql-utils.php";
require_once "../queries.php";
require_once "../parse.php";

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
    $optional = [];
    require_params($required, $optional, $_GET);
    
    $mysqli = recipedb_connect();

    $params = parse_get($mysqli);
    $sorted = [quote($params["username"]),
               quote($params["recipe_name"]),
               quote($params["author_name"]),
               $params["rating"]];

    $query = insert("user_recipe_ratings", $sorted);
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
