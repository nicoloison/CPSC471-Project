<?php   

require_once "../errors.php";
require_once "../parse.php";
require_once "../queries.php";

/**
 * Create the master query
 */
function create_query($attributes, $mysqli)
{
    $query = select("recipe");
    $query .= where($attributes);

    if ($attributes["sort_by"] != null) {
        $query .= order_by($attributes["sort_by"]);
    }

    if ($attributes["show_only"] != null) {
        $query .= limit($attributes["show_only"]);
    }

    /* $query = "SELECT * FROM recipe"; */
    /* $query .= where_clause($attributes, $mysqli); */
    /* $query .= order_by_clause(); */
    /* $query .= limit_clause(); */

    return $query;
}

/**
 * Main method
 */
function main() {
    /* Connect to database with mysql user, password mysql */
    $mysqli = new mysqli("localhost", "mysql", "mysql", "recipedb");

    if ($mysqli->connect_errno) {
        error("database connection error");
        exit();
    }

    $attributes = parse_get($mysqli);
    $query = create_query($attributes, $mysqli);
    $recipes = $mysqli->query($query);

    if ($recipes) {
        while ($recipe = $recipes->fetch_assoc()) {
            printf(json_encode($recipe));
        }
    }
}

main();

?>
