<?php

require_once "errors.php";

function escape_value($mysqli, $param, $value)
{
    if ($param == "rating"
        || $param == "prep_time"
        || $param == "portions"
        || $param == "using")
    {
        return $value;
    }

    if ($param == "using"
        || $param == "using_only"
        || $param == "dietary_restriction")
    {
        $values = [];

        foreach ($value as $v) {
            array_push($values, $mysqli->real_escape_string($v));
        }

        return $values;
    }
    
    return $mysqli->real_escape_string($value);
}

function escape_all_values(&$params, $mysqli)
{
    foreach ($params as $p => $v) {
        $params[$p] = escape_value($mysqli, $p, $v);
    }
}

function require_params($required)
{
    foreach ($required as $r) {
        if ($_GET[$r] == null) {
            error("missing parameter $r");
            exit();
        }
    }
}

function parse_get($mysqli)
{
    $attributes = ["name" => null,
                   "author_name" => null,
                   "instructions" => null,
                   "prep_time" => null,
                   "prep_time_max" => null,
                   "rating" => null,
                   "rating_max" => null,
                   "description" => null,
                   "using" => null,
                   "using_only" => null,
                   "dietary_restriction" => null,
                   "recipe_name" => null,
                   "username" => null,
                   "cookbook_name" => null,
                   "show_only" => null,
                   "sort_by" => null,
                   ];

    foreach ($_GET as $param => $value) {
        if (array_key_exists($param, $attributes)) {
            $attributes[$param] = escape_value($mysqli, $param, $value);
        }
        else {
            error("unknown parameter $param\n");
        }
    }

    return $attributes;
}

?>