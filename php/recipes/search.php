<?php   
function add_param(&$query, &$operator, $attribute, $value)
{
    $query .= $operator;
    if ($operator == " WHERE ") {
        $operator = " AND ";
    }

    $query .= $attribute;
    $query .= " = ";
    $query .= $value;
    $query .= " ";
}

function build_query()
{
    $ret = "SELECT * FROM recipe";
    $operator = " WHERE ";

    if (isset($_GET['author_name'])) {
        add_param($ret, $operator, 'author_name', $_GET['author_name']);
    }   
}

$mysqli = new mysqli("localhost", "sql", "sql-pwd", "recipedb");

if ($mysqli->connect_errno) {
    echo "Error connecting to database:";
    echo $mysqli->connect_errno;
    exit();
}

$query = "SELECT * FROM recipe";

$recipes = $mysqli->query("SELECT * FROM recipe");

if ($recipes) {
    while ($recipe = $recipes->fetch_assoc()) {
        printf("Recipe:<br>");
        printf(json_encode($recipe));
        printf("<br>");
    }
}
else {
    printf("No rows retrieved!\n");
}
?>
