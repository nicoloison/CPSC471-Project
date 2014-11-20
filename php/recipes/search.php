<?php
$mysqli = new mysqli("localhost", "sql", "sql-pwd", "recipedb");

if ($mysqli->connect_errno) {
    echo "Error connecting to database:";
    echo $mysqli->connect_errno;
    exit();
}

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
