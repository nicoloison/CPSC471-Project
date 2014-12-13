<?php

require_once "../errors.php";
require_once "../http-utils.php";
require_once "../mysql-utils.php";
require_once "../parse.php";
require_once "../queries.php";

/**
 * Main method
 */
function main()
{
    $required = ["name", "author_name", "instructions",
                 "prep_time", "portions", "description"];
    $optional = ["dietary_restriction", "image"];

    require_params($required, $optional, $_POST);

    $mysqli = recipedb_connect();
    $params = parse_post($mysqli);

    $quoted = [quote($params["name"]),
               quote($params["author_name"]),
               quote("pics/" . $params["name"] . "-" . $params["author_name"]),
               quote($params["instructions"]),
               $params["prep_time"],
               $params["portions"],
               null,
               quote($params["description"])];

    $query = insert("recipe", $quoted);
    $result = $mysqli->query($query);

    if ($result) {
        upload_image();
        success();
    }
    else {
        recipedb_error("database insertion error", $mysqli);
    }
}

main();

?>
