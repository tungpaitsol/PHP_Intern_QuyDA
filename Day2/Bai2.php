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
$error = [];
$push = [];

if (isset($_POST['button'])) {
    $a = isset($_POST['a']) ? ($_POST['a']) : '';

    if ($a == '') {
        $error[] = 'Bạn chưa nhập';
    }
    if (count($error) > 0) {
        echo $error[0];
        die();
    }
    $arrayBig = explode(",",$a);

    foreach ($arrayBig as $t) {
        $arraySmall = explode("-",$t);

        if(!isset($arraySmall[0]) || !is_numeric($arraySmall[0])){
            $error[] = ' không đúng định dạng';
        }
        if(!isset($arraySmall[1]) || !is_numeric($arraySmall[1])){
            $error[] = ' không đúng định dạng';
        }
        if (count($error) > 0) {
            echo $error[0];
            die();
        }
        $number0 = (int)$arraySmall[0];
        $number1 = (int)$arraySmall[1];

        for($i = $number0; $i <= $number1; $i ++) {
            if (isPrimeNumber ( $i )) {
                array_push($push, $i);
            }
        }
    }
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
if(count($push) > 0){
    echo "Các số nguyên tố cần tìm là : ";
    foreach ($push as $item){
        echo ($item . " ");
    }
}
if (isset($_POST['button'])) {
    if (count($push) == 0) {
        echo 'không có số nguyên tố thỏa mãn';
    }
}
?>

</body>
</html>