<?php

/**
 * Validate POSTed parameters
 */
function check_params($params)
{    
    foreach ($params as $p => $v) {
        if (!$v && $p != "picture" && $p != "rating") {
            printf(error("missing parameter $p"));
            exit();
        }
    }
}

/**
 * Fill $params and $dietary_restrictions with appropriate values
 * passed in via POST
 */
function fill_params(&$params, &$dietary_restrictions)
{
    foreach ($_POST as $param => $value) {
        if (array_key_exists($param, $params)) {
            $params[$param] = $value;
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
function create_query()
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

    fill_params($params, $dietary_restrictions);
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
 * JSON-formatted success message
 */
function success()
{
    $ret = ["result" => "success", "error" => ""];
    return json_encode($ret);
}

/**
 * JSON-formatted error message
 */
function error($message)
{
    $ret = ["result" => "failure", "error" => $message];
    return json_encode($ret);
}

/**
 * Retrieve and store uploaded image if it exists
 */
function upload_image()
{
    echo "uploading image...\n";
    $name = $_POST["name"];

    if ($_FILES["image"]) {
        $path = "/srv/http/pics/" . $name . ".png";
        echo "uploading image $path\n";
        echo "server filename: " . $_FILES["image"]["tmp_name"] . "\n";

        if (file_exists($path)) {
            return error("file already exists: $path");
        }
        if ($_FILES["image"]["error"] != 0) {
            return error("file upload error: " . $_FILES["image"]["error"]);
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $path)) {
            return error("unable to upload image $name");
        }

        return success();
    }
}

/**
 * Main method
 */
function main()
{
    $mysqli = new mysqli("localhost", "mysql", "mysql", "recipedb");

    if ($mysqli->connect_errno) {
        printf(error("database connection error " . $mysqli->connect_errno));
        exit();
    }

    $query = create_query();
    $result = $mysqli->query($query);

    if ($result) {
        printf(upload_image());
    }
    else {
        printf(error("database insertion error"));
    }
}

main();

?>
