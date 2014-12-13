<?php

require_once "../errors.php";
require_once "../mysql-utils.php";
require_once "../parse.php";
require_once "../queries.php";

function create_query($mysqli)
{
    $query = insert("recipe", $params);
    
    return $query;
}

/**
 * Retrieve and store uploaded image if it exists
 * Removes any image already present since we use a database primary key for
 *   the image name - so if there's something already present it's leftover
 *   from another recipe with the same name (or from a reupload with a
 *   different picture)
 */
function upload_image()
{
    $name = $_POST["name"] . "-" . $_POST["author_name"];
    
    if ($_FILES["image"]) {
        $path = "/srv/http/pics/" . $name . ".png";

        if (file_exists($path)) {
            unlink($path);
        }

        if ($_FILES["image"]["error"] != 0) {
            error("file upload error: " . $_FILES["image"]["error"]);
        }
        else if (!move_uploaded_file($_FILES["image"]["tmp_name"], $path)) {
            error("unable to upload image $name");
        }
        else {
            success();
            exit();
        }
    }
}

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
