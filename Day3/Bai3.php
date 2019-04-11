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
<?php session_start();

if (isset($_POST['tao'])) {
    $array = [];
    $random = [];
    $a = isset($_POST['a']) ? ($_POST['a']) : '';
    if ($a == '') {
        echo 'Bạn chưa nhập';
        die();
    }
    if (!is_numeric($a)) {
        echo 'nhập sai định dạng';
        die();
    }

    for ($i = 1; $i <= $a; $i++) {
        $random = [RandomNumber($a), RandomString($a)];
        $x = array_rand($random, 1);
        array_push($array, $random[$x]);
    }
    if (count($array) > 0) {
        var_dump($array);
    }
    $_SESSION['name'] = $array;
}

if (isset($_POST['chia'])) {
    $a = isset($_POST['a']) ? ($_POST['a']) : '';
    if (isset($_SESSION['name'])) {
        $arrayA = [];
        $arrayB = [];
        foreach ($_SESSION['name'] as $k) {
            if (is_numeric($k)) {
                array_push($arrayA, $k);
            } else {
                array_push($arrayB, $k);
            }
        }
        var_dump($arrayA);
        var_dump($arrayB);
    }
}
function RandomNumber($n)
{
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomNumber = '';
    for ($i = ($n / 4); $i <= (3 * $n / 4); $i++) {
        $randomNumber .= $characters[rand(0, $charactersLength - 1)];
    }

    return (int)$randomNumber;
}

function RandomString($m)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = ($m / 4); $i <= (3 * $m / 4); $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


?>

<h1>Bai 3</h1>
<form method="post" action="">
    <input type="text" name="a" value="<?php echo isset($a) ? $a : '' ?>">
    <input type="submit" name="tao" value="tạo mảng"/>
    <input type="submit" name="chia" value="chia mảng"/>
</form>
</body>
</html>