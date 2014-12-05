<?php

require "../errors.php";
require "../parse.php";

/**
 * Validate POSTed parameters
 */
function check_params($params)
{    
    foreach ($params as $p => $v) {
        if (!$v && $p != "picture" && $p != "rating") {
            error("missing parameter $p");
            exit();
        }
    }
}

/**
 * Fill $params and $dietary_restrictions with appropriate values
 * passed in via POST
 */
function fill_params(&$params, &$dietary_restrictions, $mysqli)
{
    foreach ($_POST as $param => $value) {
        if (array_key_exists($param, $params)) {
            $params[$param] = escape_value($mysqli, $param, $value);
        }
    }

    $params["picture"] = "pics/" . $params["name"];
}

/**
 * Create a query to insert recipe into database
 *
 * INSERT INTO recipe VALUES(
 *   '<name>', '<author_name>', '<instructions>', '<picture>',
 *   prep_time, portions, NULL, '<description>')
 */
function create_query($mysqli)
{
    $params = [
               "name" => null,
               "author_name" => null,
               "instructions" => null,
               "picture" => null,
               "prep_time" => null,
               "portions" => null,
               "rating" => "NULL",
               "description" => null,
               ];

    $dietary_restrictions = [];

    fill_params($params, $dietary_restrictions, $mysqli);
    check_params($params);

    $query = "INSERT INTO recipe VALUES (";

    reset($params);
    foreach ($params as $param => $value) {
        if ($param != "prep_time"
            && $param != "portions"
            && $param != "rating")
        {
            $value = "'$value'";
        }

        $query .= "$value,";
    }

    $query = substr($query, 0, -1);
    $query .= ")";

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
    $name = $_POST["name"];

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
        }
    }
}

/**
 * Main method
 */
function main()
{
    $mysqli = new mysqli("localhost", "mysql", "mysql", "recipedb");

    if ($mysqli->connect_errno) {
        error("database connection error " . $mysqli->error);
        exit();
    }

    $query = create_query($mysqli);
    $result = $mysqli->query($query);

    if ($result) {
        upload_image();
    }
    else {
        error("database insertion error " . $mysqli->error);
    }
}

main();

?>
