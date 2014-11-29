<?php   

/**
 * Create clause equivalent to using parameter
 *
 * WHERE EXISTS (
 *   SELECT * FROM recipe_ingredient
 *   WHERE name = recipe_name AND ingredient_name = 'ingredient1')
 * AND EXISTS (
 *   SELECT * ...
 *   WHERE ...)
 */
function using_clause($operator, $ingredients)
{
    $clause = $operator . " EXISTS (";

    while (count($ingredients) > 1) {
        $clause .= "SELECT * FROM recipe_ingredient WHERE "
            . "name = recipe_name AND ingredient_name = '"
            . array_shift($ingredients) . "') AND EXISTS (";
    }

    $clause .= "SELECT * FROM recipe_ingredient WHERE "
        . "name = recipe_name AND ingredient_name = '"
        . $ingredients[0] . "')";

    return $clause;
}

/**
 * Create clause equivalent to using_only parameter
 *
 * WHERE NOT EXISTS (SELECT * FROM recipe_ingredient
 *                   WHERE name = recipe_name
 *                   AND (ingredient_name <> ingredient1
 *                        AND ingredient_name <> ingredient2
 *                        AND ingredient_name <> ingredient3
 *                   ))
 */
function using_only_clause($operator, $ingredients)
{
    $clause = $operator . " NOT EXISTS (SELECT * FROM recipe_ingredient WHERE "
        . "name = recipe_name AND ";

    while (count($ingredients) > 1) {
        $clause .= "ingredient_name <> '" . array_shift($ingredients) . "' AND ";
    }

    $clause .= "ingredient_name <> '" . $ingredients[0] . "') ";

    return $clause;
}

/**
 * Create clause equivalent to dietary_restriction parameter
 *
 * WHERE NOT EXISTS (
 *   SELECT * FROM recipe_ingredient AS ri
 *   WHERE name = ri.recipe_name AND EXISTS (
 *     SELECT * FROM dietary_restrictions AS dr
 *     WHERE dr.ingredient = ri.ingredient_name
 *     AND dr.name = "restriction"))
 */
function dietary_restriction_clause($operator, $restriction)
{
    $clause = $operator . " NOT EXISTS (SELECT * FROM recipe_ingredient AS ri "
        . "WHERE name = ri.recipe_name AND EXISTS "
        . "(SELECT * FROM dietary_restrictions AS dr WHERE "
        . "dr.ingredient = ri.ingredient_name AND "
        . "dr.name = \"" . $restriction . "\")) ";
    return $clause;
}

/**
 * Create clause equivalent to [rating/prep_time]_max
 *
 * WHERE rating < value
 */
function less_clause($operator, $field, $value)
{
    return $operator . " " . $field . "<" . $value;
}

/**
 * Create clause equivalent to [rating/prep_time]
 *
 * WHERE rating > value
 */
function greater_clause($operator, $field, $value)
{
    return $operator . " " . $field . ">" . $value;
}

/**
 * Create clause that performs free text search on $field for $value
 *
 * WHERE field LIKE CONCAT ('%', 'value', '%')
 */
function like_clause($operator, $field, $value)
{
    return $operator . " " . $field
        . " LIKE CONCAT ('%', '" . $value . "', '%')";
}

/**
 * Create clause equivalent to sort_by parameter
 *
 * ORDER BY value [DESC]
 */
function order_by_clause()
{
    $clause = "";

    foreach ($_GET as $param => $value) {
        if ($param == "sort_by") {
            $desc = false;

            if ($value[0] == '-') {
                $desc = true;
                $value = substr($value, 1);                
            }

            $clause = " ORDER BY " . $value;

            if ($desc) {
                $clause .= " DESC";
            }

            break;
        }
    }

    return $clause;
}

/**
 * Create clause equivalent to show_only parameter
 *
 * LIMIT value
 */
function limit_clause()
{
    $clause = "";

    if (array_key_exists("show_only", $_GET)) {
        $clause = " LIMIT " . $_GET["show_only"];
    }

    return $clause;
}

/**
 * Create overall WHERE clause by building from smaller clauses
 *
 * Param $attributes: attributes to accept. Will treat some specially,
 *  any without special casing are handled via freetext search for
 *  $value on $param in the database.
 */
function where_clause($attributes)
{
    $operator = " WHERE ";
    $clause = "";

    foreach ($_GET as $param => $value) {
        if ($param != "using" && $param != "using_only") {
            $value = html_entity_decode($value);
        }
        
        if (in_array($param, $attributes)) {
            if ($param == "rating" || $param == "prep_time") {
                $clause .= greater_clause($operator, $param, $value);
            }
            else if (strpos($param, "_max") !== false) {
                $param = substr($param, 0, -4);
                $clause .= less_clause($operator, $param, $value);
            }
            else if ($param == "using") {
                $clause .= using_clause($operator, $value);
            }
            else if ($param == "using_only") {
                $clause .= using_only_clause($operator, $value);
            }
            else if ($param == "dietary_restriction") {
                echo "restriction: " . $value . "<br>";
                $clause .= dietary_restriction_clause($operator, $value);
            }
            else {
                $clause .= like_clause($operator, $param, $value);
            }

            $operator = " AND ";
        }
    }

    return $clause;
}

/**
 * Create the master query
 */
function create_query($attributes)
{
    $query = "SELECT * FROM recipe";
    $query .= where_clause($attributes);
    $query .= order_by_clause();
    $query .= limit_clause();

    return $query;
}

/**
 * Main method
 */
function main() {
    /* Accepted GET parameter attributes */
    $attributes = [
                   "name",
                   "author_name",
                   "instructions",
                   "prep_time",
                   "prep_time_max",
                   "rating",
                   "rating_max",
                   "description",
                   "using",
                   "using_only",
                   "dietary_restriction"
                   ];

    /* Connect to database with mysql user, password mysql */
    $mysqli = new mysqli("localhost", "mysql", "mysql", "recipedb");

    if ($mysqli->connect_errno) {
        $error = ["result" => "failure",
                  "error" => htmlentities("database connection error "
                                          . $mysqli->connect_errno)];
        printf(json_encode($error));
        exit();
    }

    $query = create_query($attributes);
    $recipes = $mysqli->query($query);

    if ($recipes) {
        while ($recipe = $recipes->fetch_assoc()) {
            printf(json_encode($recipe));
        }
    }
}

main();

?>
