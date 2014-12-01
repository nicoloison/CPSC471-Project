<?php

function escape_value($mysqli, $param, $value)
{
    if ($param != "rating"
        && $param != "prep_time"
        && $param != "portions")
    {
        return $mysqli->real_escape_string($value);
    }

    return $value;
}

?>