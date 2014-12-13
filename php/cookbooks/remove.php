<?php

require_once "../errors.php";
require_once "../mysql-utils.php";
require_once "../parse.php";
require_once "../queries.php";

function main()
{
    $required = ["cookbook_name", "cookbook_author", "recipe_name", "recipe_author"];
    $optional = [];

    require_params($required, $optional, $_GET);

    $mysqli = recipedb_connect();
    $params = parse_get($mysqli);
    $query = delete("cookbook_recipe") . where($params);

    echo "running query $query\n";
    if (!$mysqli->query($query)) {
        error("database update error "
              . $mysqli->connect_errno . " " . $mysqli->error);
        exit();
    }
    else if ($mysqli->affected_rows == 0) {
        error("item not found in database");
        exit();
    }

    success();
}

main();

?>