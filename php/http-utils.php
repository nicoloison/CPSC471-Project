<?php

require_once "../errors.php";

function upload_image()
{
    $name = $_POST["name"] . "-" . $_POST["author_name"];

    if ($_FILES["image"]) {
        $path = "/srv/http/pics/" . $name . ".png";

        if (file_exists($path)) {
            unlink($path);
        }

        if ($_FILES["image"]["error"] != 0) {
            error("file upload error: " . $_FILES["image"]["error"]);
        }
        else if (!move_uploaded_file($_FILES["image"]["tmp_name"], $path)) {
            error("unable to upload image $name");
        }
    }
}

?>