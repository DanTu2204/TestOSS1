<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php 
    $arr = array();
    $r = array("id" => 1, "name" => "Product1");
    $arr[] = $r;
    $r = array("id" => 2, "name" => "Product2");
    $arr[] = $r;
    $r = array("id" => 3, "name" => "Product3");
    $arr[] = $r;
    $r = array("id" => 4, "name" => "Product4");
    $arr[] = $r;

    foreach($arr as $r){
        echo '<a href="2.php?id=' . $r["id"] . '">' . $r["name"] . '</a><br>';
    }
?>
</body>
</html>
