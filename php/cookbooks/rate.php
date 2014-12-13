<?php

require_once "../errors.php";
require_once "../mysql-utils.php";
require_once "../queries.php";
require_once "../parse.php";

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
    $required = ["username", "author_name", "cookbook_name", "rating"];
    $optional = [];
    require_params($required, $optional, $_GET);

    $mysqli = recipedb_connect();

    $params = parse_get($mysqli);
    $query = insert("user_cookbook_rating", $params);
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