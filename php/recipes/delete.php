<?php

require_once "../errors.php";
require_once "../mysql-utils.php";
require_once "../parse.php";
require_once "../queries.php";

/**
 * Main method
 */
function main()
{
    $required = ["recipe_name", "author_name"];
    $optional = [];

    require_params($required, $optional, $_GET);
    
    $mysqli = recipedb_connect();
    $attributes = parse_get($mysqli);
    /* Cookbooks and recipes tables have different names for these parameters */

    $cookbook_parameters = ["recipe_name" => $attributes["recipe_name"],
                            "recipe_author" => $attributes["author_name"]];
    $recipe_parameters = ["name" => $attributes["recipe_name"],
                          "author_name" => $attributes["author_name"]];


    /* Delete from all necessary tables in order */
    /* Note: these will exit without return if a database error is encountered
     * and print an error */
    delete_from($mysqli, "user_recipe_ratings", $parameters);
    delete_from($mysqli, "recipe_ingredient", $parameters);
    delete_from($mysqli, "cookbook_recipe", $cookbook_parameters);
    delete_from($mysqli, "recipe", $recipe_parameters);

    if ($mysqli->affected_rows == 0) {
        error("element not found in database");
    }
    else {
        success();
    }
}

main();

?>
