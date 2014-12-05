<?php

function compare($operator, $column, $comparison, $value)
{
    return "$operator $column $comparison $value ";
}

function less($operator, $column, $value)
{
    if (strpos($column, "_max") !== false) {
        $column = substr($column, 0, -4);
    }

    return compare($operator, $column, "<", $value);
}

function greater($operator, $column, $value)
{
    return compare($operator, $column, ">", $value);
}

function equal($operator, $column, $value)
{
    return compare($operator, $column, "=", $value);
}

function not_equal($operator, $column, $value)
{
    return compare($operator, $column, "<>", $value);
}

function like($operator, $field, $value)
{
    return $operator . " " . $field
        . " LIKE CONCAT ('%', '" . $value . "', '%') ";
}

function where_field($operator, $field, $value)
{
    $functions = ["name" => "like",
                  "author_name" => "like",
                  "instructions" => "like",
                  "prep_time" => "greater",
                  "prep_time_max" => "less",
                  "rating" => "greater",
                  "rating_max" => "less",
                  "description" => "like",
                  "using" => "using",
                  "using_only" => "using_only",
                  "dietary_restriction" => "dietary_restriction"
                  ];

    if (array_key_exists($field, $functions)) {
        return call_user_func($functions[$field], $operator, $field, $value);
    }
}

function where($attributes)
{
    $operator = "WHERE";
    $clause = "";

    foreach ($attributes as $field => $value) {
        if ($value) {
            $clause .= where_field($operator, $field, $value);
            $operator = "AND";
        }
    }

    return $clause;
}

function select($table)
{
    return "SELECT * FROM $table ";
}

function limit($number)
{
    return "LIMIT $number ";
}

function order_by($field)
{
    if ($field[0] == '-') {
        $field = substr($field, 1);
        $field .= "DESC ";
    }

    return "ORDER BY $field ";
}

function exists($operator, $clause)
{
    return "$operator EXISTS ($clause) ";
}

function not_exists($operator, $clause)
{
    return "$operator NOT EXISTS ($clause) ";
}

function update($table, $field, $value)
{
    return "UPDATE $table SET $field = $value ";
}

function insert($table, $values)
{
    $query = "INSERT INTO $table VALUES (";

    foreach ($values as $value)
    {
        $query .= "$value,";
    }

    $query = substr($query, 0, -1);
    $query .= ")";

    return $query;
}

function using($operator, $dummy, $ingredients)
{
    $clause = "";

    foreach ($ingredients as $i) {
        $clause .= exists($operator,
                          select("recipe_ingredient")
                          . equal("WHERE", "name", "recipe_name")
                          . equal("AND", "ingredient_name", "'$i'"));
        $operator = "OR";
    }

    return $clause;
}

function using_only($operator, $dummy, $ingredients)
{
    $clause = not_exists($operator,
                         select("recipe_ingredient")
                         . equal("WHERE", "name", "recipe_name"));

    foreach ($ingredients as $i) {
        $clause .= not_equal("AND", "ingredient_name", "'$i'");
    }

    $clause .= ")";

    return $clause;
}

function dietary_restriction($operator, $dummy, $restrictions)
{
    $clause = "";

    foreach ($restrictions as $r) {
        $clause .= not_exists($operator,
                              select("recipe_ingredient")
                              . equal("WHERE", "name", "recipe_name")
                              . exists("AND",
                                       select("dietary_restrictions")
                                       . equal("WHERE",
                                               "ingredient_name",
                                               "restriction_ingredient")
                                       . equal("AND",
                                               "restriction_name",
                                               "'$r'")));
        $operator = "AND";
    }

    return $clause;
}

?>