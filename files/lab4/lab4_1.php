<pre><?php
$a = array();//mảng rỗng
$b = array(1, 3, 5); //mảng có 3 phần tử
/*
$b[0] = 1;
$b[1] = 3;
$b[2] = 5;
*/
$c = array("a"=>2, "b"=>4, "c"=>6);//mảng có 3 phần tử.Các index của mảng là chuỗi
/*
$c['a']= 2;
$c['b'] = 4;
$c['c'] = 6
*/

$na = Count($a);
$nb = Count($b);
$nc = Count($c);
echo "Mảng a có $na phần tử <br> ";
echo "Mảng b có $nb phần tử <br> ";
echo "Mảng c có $nc phần tử <br> ";
print_r($a);
var_dump($b);
print_r($c);
$a[] = 3;
$b[] = 7;
$c['d'] = 8;
echo "<hr> Sau khi thêm phần tử, nội dung các mảng  là :";
print_r($a);
print_r($b);
print_r($c);

$x = 1;
unset($a[$x]);
unset($b[$x]);
unset($c['a']);
echo "<hr> Sau khi xóa phần tử, nội dung các mảng  là :";
print_r($a);
print_r($b);
print_r($c);

$value = 2;
$key = 'b';
if (isset($c[$key])) $c[$key] += $value;
else $c[$key] = $value;
echo "<hr> Kết quả mảng c là:";
print_r($c);

?>
<form action="" method="POST">
<label for="val">Nhập giá trị cần tìm trong mảng</label>
<input type="text" name="val" />
<label for="arr">Nhập mảng</label>
<input type="text" name="arr" />
<button type="submit" name="submit">Gửi</button>
</form>

<?php
    if(isset($_POST['submit']))
     {
        $val = $_POST['val'];
        $arrName = $_POST['arr'];
        
        switch($arrName){
        case 'a': $arr = $a; break;
        case 'b': $arr = $b; break;
        case 'c': $arr = $c; break;
        default:
            echo "Tên mảng không hợp lệ!";
            exit;
        }
        
        if (in_array($val,$arr)){
            echo "Co $val trong mang $arrName";  
             $key = array_search($val, $arr); 
            unset($arr[$key]);
            // $arr[$key]="11";

            echo "<br> Mang sau khi xoa phan tu $val la: ";
            print_r($arr);
        }

        else {
            echo "Khong co $val trong mang $arrName";
        }
        
    
      
    }

?>