<?php

require_once "../errors.php";
require_once "../mysql-utils.php";
require_once "../parse.php";
require_once "../queries.php";

function create_query($params)
{
    $cookbook_params = ["c.name" => quote($params["name"]),
                        "c.author_name" => quote($params["author_name"])];
    $recipe_params = ["cr.recipe_name" => "r.name",
                      "cr.cookbook_name" => "c.name"];

    $query = select("recipe as r") . exists("WHERE",
                 select("cookbook as c") . where_equal($cookbook_params) . exists("AND",
                     select("cookbook_recipe as cr") . where_equal($recipe_params)));

    return $query;
}

function main()
{
    $required = ["name", "author_name"];
    $optional = [];

    require_params($required, $optional, $_GET);

    $mysqli = recipedb_connect();
    $params = parse_get($mysqli);

    $query = create_query($params);
    
    $result = $mysqli->query($query);

    echo "ran query $query\n";
    
    if (!$result) {
        error("database query error " . $mysqli->errno . " " . $mysqli->error);
        exit();
    }

    while($recipe = $result->fetch_assoc()) {
        printf(json_encode($recipe));
    }
}

main();

?>