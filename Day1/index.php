<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Giai Phuong Trinh Bac 2</title>
</head>
<body>
<?php
$kq = '';
$error = [];
$e = [];
if (isset($_POST['button'])) {
    $a = isset($_POST['a']) ? ($_POST['a']) : '';
    $b = isset($_POST['b']) ? ($_POST['b']) : '';
    $c = isset($_POST['c']) ? ($_POST['c']) : '';

    if (empty($a) && $a != 0) {
        $error[] = 'Bạn chưa nhập a';
    }

    if (empty($b) && $b != 0) {
        $error[] = 'Bạn chưa nhập b';
    }
    if (empty($c) && $c != 0) {
        $error[] = 'Bạn chưa nhập c';
    }
    if (!is_numeric($a)) {
        $e[] = 'a không đúng định dạng';
    }
    if (!is_numeric($b)) {
        $e[] = 'b không đúng định dạng';
    }
    if (!is_numeric($c)) {
        $e[] = 'c không đúng định dạng';
    }

    if ($error != []) {
       echo $error[0];
       die();
    }
    if ($e != []) {
        echo $e[0];
        die();
    }

    $delta = ($b * $b) - (4 * $a * $c);
    if ($a != 0) {
        if ($delta < 0) {
            $kq = 'PT vo nghiem';
        } else if ($delta == 0) {
            $kq = 'PT co nghiem kep la : x1 = x2 = ' . (-$b / (2 * $a));
        } else {
            $kq = 'PT co 2 nghiem phan biet la : x1 = ' . ((-$b + sqrt($delta)) / 2 * $a);
            $kq .= ',x2 = ' . ((-$b - sqrt($delta)) / 2 * $a);
        }
    } else {
        if ($b == 0) {
            if ($c == 0) {
                $kq = 'Vo so nghiem';
            }
            if ($c != 0) {
                $kq = 'Vo nghiem';
            }
        } else {
            $kq = 'x= ' . -($c / $b);
        }
    }
}
?>

<h1>Phuong Trinh Bac 2</h1>
<form method="post" action="">
    <input type="text" name="a" value="<?php echo isset($a) ? $a : '' ?>">x<sup>2</sup>+ <input type="text" name="b" value="<?php echo isset($b) ? $b : '' ?>">x + <input type="text" name="c" value="<?php echo isset($c) ? $c : '' ?>"> = 0
    <input type="submit" name="button" value="ket qua"/>
</form>
<?php
echo $kq;
?>

</body>
</html>