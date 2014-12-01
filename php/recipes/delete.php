<?php

require "../errors.php";
require "../escape.php";

/**
 * Create query to delete from table based on parameters
 *
 * DELETE FROM table
 * WHERE <parameter1> = <value1>
 * AND <parameter2> = <value2>
 * ...
 */
function delete_query($table, $parameters, $mysqli)
{
    $query = "DELETE FROM $table WHERE ";

    foreach ($parameters as $p => $v) {
        $query .= "$p = '$v' AND ";
    }

    $query = substr($query, 0, -5);

    return $query;
}

/**
 * Remove an entry from the specified table in mysqli based on
 * parameters
 */
function delete_from($mysqli, $table, $parameters)
{
    $query = delete_query($table, $parameters, $mysqli);
    $result = $mysqli->query($query);

    if (!$result) {
        error("unable to complete query $query");
        exit();
    }
}

/**
 * Escape parameters required by $parameters found in $_GET
 *
 * Param $mysqli: mysqli connection used to escape strings
 * Param $parameters: array containing required keys, to be filled
 *   with values from $_GET
 */
function escape_parameters($mysqli, &$parameters)
{
    foreach ($parameters as $p => $v) {
        if (!array_key_exists($p, $_GET)) {
            error("missing parameter $p");
            exit();
        }

        $parameters[$p] = escape_value($mysqli, $p, $_GET[$p]);
    }
}

/**
 * Main method
 */
function main()
{
    $parameters = ["recipe_name" => null,
                   "author_name" => null];

    $mysqli = new mysqli("localhost", "mysql", "mysql", "recipedb");

    if ($mysqli->connect_errno) {
        error("database connection error" . $mysqli->error);
        exit();
    }

    escape_parameters($mysqli, $parameters);

    /* Cookbooks and recipes tables have different names for these parameters */
    $cookbook_parameters = ["recipe_name" => $parameters["recipe_name"],
                            "recipe_author" => $parameters["author_name"]];
    $recipe_parameters = ["name" => $parameters["recipe_name"],
                          "author_name" => $parameters["author_name"]];


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
