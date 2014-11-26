<?php   

$attributes = [
               "name",
               "author_name",
               "rating",
               ];

$modifiers = [
              "show_only",
              "dietary_restriction"
              ];

function order_query($query)
{
    $clause = "";
    foreach ($_GET as $param => $value) {
        if ($param == "sort_by") {
            $clause = " ORDER BY " . $value;
        }
    }

    return $query . $clause;
}

function create_query($attributes)
{
    $query = "SELECT * FROM recipe";
    $clause = " WHERE ";

    foreach ($_GET as $param => $value) {
        if (in_array($param, $attributes)) {
            if ($param != "rating") {
                $value = "\"" . $value . "\"";
            }
            
            $query .= $clause . $param . "=" . $value;
            $clause = " AND ";
        }
    }

    return order_query($query);
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

echo "creating query...<br>";
$query = create_query($attributes);
echo "created query: ";
echo $query;
echo "<br>";
$result = $mysqli->query($query);
echo "ran query!<br>";
$recipes = filter_results($result, $modifiers);
echo "filtered results!<br>";

if ($recipes) {
    while ($recipe = $recipes->fetch_assoc()) {
        printf(json_encode($recipe));
    }
}
else {
    printf("No rows retrieved!\n");
}

?>
