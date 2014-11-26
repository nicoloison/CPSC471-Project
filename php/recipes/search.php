<?php   

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
               ];

$modifiers = [
              "show_only",
              "sort_by",
              "dietary_restriction",
              ];

function using_clause($operator, $ingredient)
{
    return $operator . " EXISTS (SELECT * FROM recipe_ingredient WHERE ("
        . "name = recipe_name AND ingredient_name = '" . $ingredient . "'))";
}

/*
 * WHERE NOT EXISTS (SELECT * FROM recipe_ingredient
 *                   WHERE name = recipe_name
 *                   AND (ingredient_name <> ingredient1
 *                        AND ingredient_name <> ingredient2
 *                        AND ingredient_name <> ingredient3
 *                   ))
 */
function using_only_clause($operator, $ingredients)
{
    echo "using_only:".implode(" ", $ingredients);
    $clause = $operator . " NOT EXISTS (SELECT * FROM recipe_ingredient WHERE "
        . "name = recipe_name AND ";

    while (count($ingredients) > 1) {
        $clause .= "ingredient_name <> '" . array_shift($ingredients) . "' AND ";
    }

    $clause .= "ingredient_name <> '" . $ingredients[0] . "') ";
    echo "using_only clause():" . $clause;
    return $clause;
}

function dietary_restriction_clause()
{

}

function less_clause($operator, $field, $value)
{
    return $operator . " " . $field . "<" . $value;
}

function greater_clause($operator, $field, $value)
{
    return $operator . " " . $field . ">" . $value;
}

function equals_clause($operator, $field, $value)
{
    return $operator . " " . $field . "=" . $value;
}

function like_clause($operator, $field, $value)
{
    return $operator . " " . $field
        . " LIKE CONCAT ('%', '" . $value . "', '%')";
}

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

function limit_clause()
{
    $clause = "";

    if (array_key_exists("show_only", $_GET)) {
        $clause = " LIMIT " . $_GET["show_only"];
    }

    return $clause;
}

function where_clause($attributes)
{
    $operator = " WHERE ";
    $clause = "";

    foreach ($_GET as $param => $value) {
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
            else {
                $clause .= like_clause($operator, $param, $value);
            }

            $operator = " AND ";
        }
    }

    return $clause;
}

function create_query($attributes)
{
    $query = "SELECT * FROM recipe";
    $query .= where_clause($attributes);
    $query .= order_by_clause();
    $query .= limit_clause();

    return $query;
}

function filter_results($results, $modifiers)
{
    $show_only = NULL;
    $dietary_restriction = [];
    
    foreach ($_GET as $param => $value) {
        if ($param == "show_only") {
            $show_only = $value;
        }
    }

    return $results;
}

$mysqli = new mysqli("localhost", "mysql", "mysql", "recipedb");

if ($mysqli->connect_errno) {
    echo "Error connecting to database:";
    echo $mysqli->connect_errno;
    exit();
}

$query = create_query($attributes);
$result = $mysqli->query($query);
$recipes = filter_results($result, $modifiers);

echo "ran query: " . $query;

if ($recipes) {
    while ($recipe = $recipes->fetch_assoc()) {
        printf(json_encode($recipe));
    }
}
else {
    printf("No rows retrieved!\n");
}

?>
