<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bài 2</title>
</head>
<body>
<?php
//$prime = [];
if (isset($_POST['button'])) {
    $prime = [];
    $a = isset($_POST['a']) ? ($_POST['a']) : '';

    if ($a == '') {
        echo  'Bạn chưa nhập';
        die();
    }

    $array = explode(",",$a);

    foreach ($array as $t) {
        $arrayChild = explode("-",$t);
        if((!isset($arrayChild[0]) || !is_numeric($arrayChild[0])) || (!isset($arrayChild[1]) || !is_numeric($arrayChild[1]))){
            echo ' không đúng định dạng';
            die();
        }

        $x = (int)$arrayChild[0];
        $y = (int)$arrayChild[1];

        if($x >= $y){
            echo 'số trước phải nhỏ hơn số sau';
            die();
        }
        for($i = $x; $i <= $y; $i ++) {
            if (isPrimeNumber ( $i )) {
                array_push($prime, $i);
            }
//            else{
//               array_push($prime, null);
//            }
        }
    }
    if(count($prime) > 0){
        echo "Các số nguyên tố cần tìm là : ";
        foreach ($prime as $item){
            echo ($item . " ");
        }
    }
    if (count($prime) == 0){
        echo 'không có số thỏa mãn';
    };
}

function isPrimeNumber($n) {
    // so nguyen n < 2 khong phai la so nguyen to
    if ($n < 2) {
        return false;
    }
    // check so nguyen to khi n >= 2
    $squareRoot = sqrt ( $n );
    for($i = 2; $i <= $squareRoot; $i ++) {
        if ($n % $i == 0) {
            return false;
        }
    }
    return true;
}
?>

<h1>Bai 2</h1>
<form method="post" action="">
    <input type="text" name="a" value="<?php echo isset($a) ? $a : '' ?>">
    <input type="submit" name="button" value="ket qua"/>
</form>
<?php
//if(count($prime) > 0){
//    echo "Các số nguyên tố cần tìm là : ";
//    foreach ($prime as $item){
//            echo ($item . " ");
//    }
//}

?>

</body>
</html>