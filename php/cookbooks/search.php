<?php

require_once "../errors.php";
require_once "../mysql-utils.php";
require_once "../parse.php";
require_once "../queries.php";

function create_query($attributes) {
    $query = select("cookbook") . where($attributes);

    if ($attributes["sort_by"] != null) {
        $query .= order_by($attributes["sort_by"]);
    }

    if ($attributes["show_only"] != null) {
        $query .= limit($attributes["show_only"]);
    }

    return $query;
}

function main() {
    $required = [];
    $optional = ["name", "author_name", "rating", "rating_max",
                 "show_only", "sort_by", "dietary_restriction"];

    require_params($required, $optional);

    $mysqli = recipedb_connect();
    $attributes = parse_get($mysqli);
    $query = create_query($attributes);
    $result = $mysqli->query($query);

    if (!$result) {
        error("no rows returned");
        exit();
    }

    while ($cookbook = $result->fetch_assoc()) {
        printf(json_encode($cookbook));
    }
}

main();

?>
