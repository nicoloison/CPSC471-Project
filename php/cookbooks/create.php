<?php

require_once "../errors.php";
require_once "../http-utils.php";
require_once "../mysql-utils.php";
require_once "../parse.php";
require_once "../queries.php";

function main()
{
    $required = ["name", "author_name", "description"];
    $optional = ["image"];

    require_params($required, $optional, $_POST);

    $mysqli = recipedb_connect();
    $params = parse_post($mysqli);

    $quoted = [quote($params["name"]),
               quote($params["author_name"]),
               quote($params["description"]),
               null,
               quote("pics/" . $params["name"] . "-" . $params["author_name"])];

    $query = insert("cookbook", $quoted);
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
