<?php   

require_once "../errors.php";
require_once "../mysql-utils.php";
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
    $required = [];
    $optional = ["name", "author_name", "instructions", "prep_time",
                 "prep_time_max", "rating", "rating_max", "description",
                 "show_only", "sort_by", "using", "using_only",
                 "dietary_restriction"];

    require_params($required, $optional, $_GET);

    $mysqli = recipedb_connect();
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
