<?php
include "../../config/base_url.php";
include "../../config/db.php";

if(isset($_POST["title"], $_POST["description"], $_POST["category_id"]) &&
strlen($_POST["title"]) > 0 &&
strlen($_POST["description"]) > 0 &&
intval($_POST["category_id"])
){
    $title = $_POST["title"];
    $cat_id = $_POST["category_id"];
    $desk = $_POST["description"];
    session_start();
    $author_id = $_SESSION["user_id"];

    if(isset($_FILES["image"], $_FILES["image"]["name"])){
        $ext =end(explode(".", $_FILES["image"]["name"]));
        $image_name = time().".".$ext;
        // 52654523594132.png сгенерированный код для названия картинки
        move_uploaded_file($_FILES["image"]["tmp_name"],"../../images/blogs/$image_name");
        $path = "/images/blogs/".$image_name;
        $prep = mysqli_prepare($con,
        "INSERT INTO blogs (title, description, category_id, author_id, img)
        VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($prep, "ssiis", $title, $desk, $cat_id, $author_id, $path);
        mysqli_stmt_execute($prep);
    }else{
        $prep = mysqli_prepare($con,
        "INSERT INTO blogs (title, description, category_id, author_id)
        VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($prep, "ssii", $title, $desk, $cat_id, $author_id);
        mysqli_stmt_execute($prep);
    
    }
    header("Location: $BASE_URL/profile.php?nickname=".$_SESSION["nickname"]);
}else{
    header("Location: $BASE_URL/newblog.php?error=6");
}
?>