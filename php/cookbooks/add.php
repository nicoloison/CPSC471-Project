<?php

require_once "../errors.php";
require_once "../mysql-utils.php";
require_once "../parse.php";
require_once "../queries.php";

function create_query($params)
{
    $values = [
               quote($params["cookbook_name"]),
               quote($params["cookbook_author"]),
               quote($params["recipe_name"]),
               quote($params["recipe_author"]),
               ];

    return insert("cookbook_recipe", $values);
}

function main()
{
    $required = ["cookbook_name", "cookbook_author",
                 "recipe_name", "recipe_author"];
    $optional = [];

    require_params($required, $optional, $_GET);

    $mysqli = recipedb_connect();
    $params = parse_get($mysqli);
    $query = create_query($params);
    
    if (!$mysqli->query($query)) {
        recipedb_error("database update error", $mysqli);
        exit();
    }

    success();
}

main();

?>