<?php

    $arr = array(5, -2, 9, 1, -7, 4, 3);
    echo "Mảng ban đầu: ";
    print_r($arr);
    echo "<br>";
?>
<table border="1">
    <th>Index</th>
    <th>Value</th>
    <?php
        foreach($arr as $key => $value){
            echo "<tr><td> $key </td>
            <td> $value </td></tr>";
        }
    ?>

</table>