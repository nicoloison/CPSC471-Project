<?php

require_once "../errors.php";
require_once "../mysql-utils.php";
require_once "../parse.php";
require_once "../queries.php";

function main()
{
    $required = ["name", "author_name"];
    $optional = [];

    require_params($required, $optional, $_GET);

    $mysqli = recipedb_connect();
    $parameters = parse_get($mysqli);
    $recipe_parameters = ["cookbook_name" => $parameters["name"],
                          "cookbook_author" => $parameters["author_name"]];

    delete_from($mysqli, "cookbook_recipe", $recipe_parameters);
    delete_from($mysqli, "cookbook", $parameters);

    if ($mysqli->affected_rows == 0) {
        error("element not found in database");
    }
    else {
        success();
    }
}

main();

?>