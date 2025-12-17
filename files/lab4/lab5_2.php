<?php

    $arr= array();
    $r = array("id"=> "sp1", "name"=> "Sản phẩm 1 ");
    $arr[] = $r;
    $r = array("id"=> "sp2", "name"=> "Sản phẩm 2 ");
    $arr[] = $r;
    $r = array("id"=> "sp3", "name"=> "Sản phẩm 3 ");
    $arr[] = $r;

    echo "Mảng ban đầu: <br>";
    print_r($arr);
    echo "<br>";
    
?>

<table border="1">
    <tr>
        <td>Stt</td>
        <td>Mã Sản Phẩm</td>
        <td>Tên Sản Phẩm</td>
    </tr>
    <?php 
        $i=0;
        foreach($arr as $value){
            $i++;
            echo "<tr>
                <td>$i</td>
                <td>{$value['id']}</td>
                <td>{$value['name']}</td>
                </tr>";
        }
    ?>
</table>