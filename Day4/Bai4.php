<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bài 4</title>
</head>
<body>
<?php session_start();
echo "<table><tr><th>ID</th><th>Name</th><th>Price</th><th>Quantity</th><th>Order</th></tr>";

if (isset($_POST['tao'])) {
    $array = [];
    $random = [];
    $a = isset($_POST['a']) ? ($_POST['a']) : '';

    if ($a == '') {
        echo 'Bạn chưa nhập';
        die();
    }
    if (!ctype_digit($a)) {
        echo 'nhập sai định dạng';
        die();
    }

    if ($a < 0 || $a == 0) {
        echo 'chỉ nhận giá trị lớn hơn 0';
        die();
    }
    for ($i = 1; $i <= $a; $i++) {
        $random = ['id' => $i, 'name' => RandomString($a), 'price' => RandomNumber($a), 'quantity' => RandomNumber($a), 'order' => RandomNumber($a)];
        array_push($array, $random);
    }

    if (count($array) > 0) {
        $_SESSION['arr'] = $array;
    }
// echo :
    foreach ($array as $value) {
        echo "<tr><td>" . $value['id'] . "</td><td>" . $value['name'] . "</td><td>" . $value['price'] . "</td><td>" . $value['quantity'] . "</td><td>" . $value['order'] . "</td></tr>";
    }

}

function RandomNumber($n)
{
    $k = floor($n / 4);
    $l = floor(3 * $n / 4);
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomNumber = '';
    $randomNumber[0] = $characters[rand(1, $charactersLength - 1)];

    for ($i = 1; $i < rand($k,$l); $i++) {
        $randomNumber .= $characters[rand(0, $charactersLength - 1)];
    }

    return (int)$randomNumber;
}

function RandomString($m)
{
    $k = floor($m / 4);
    $l = floor(3 * $m / 4);

    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < rand($k,$l); $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

function change($type, $column) {
    $sophantu = count($_SESSION['arr']);
    for ($i = 0; $i < ($sophantu - 1); $i++) {
        for ($j = $i + 1; $j < $sophantu; $j++) {
            if ($type === 'potang') {
                if ($_SESSION['arr'][$i][$column] > $_SESSION['arr'][$j][$column]) {
                    $tmp = $_SESSION['arr'][$j];
                    $_SESSION['arr'][$j] = $_SESSION['arr'][$i];
                    $_SESSION['arr'][$i] = $tmp;
                }
            } elseif ($type === 'pogiam') {
                if ($_SESSION['arr'][$i][$column] < $_SESSION['arr'][$j][$column]) {
                    $tmp = $_SESSION['arr'][$j];
                    $_SESSION['arr'][$j] = $_SESSION['arr'][$i];
                    $_SESSION['arr'][$i] = $tmp;
                }
            } elseif ($type === 'totaltang'){
                if (($_SESSION['arr'][$i][$column] * $_SESSION['arr'][$i]['quantity']) > ($_SESSION['arr'][$j][$column] * $_SESSION['arr'][$j]['quantity'])) {
                    $tmp = $_SESSION['arr'][$j];
                    $_SESSION['arr'][$j] = $_SESSION['arr'][$i];
                    $_SESSION['arr'][$i] = $tmp;
                }
            } else {
                if (($_SESSION['arr'][$i][$column] * $_SESSION['arr'][$i]['quantity']) < ($_SESSION['arr'][$j][$column] * $_SESSION['arr'][$j]['quantity'])) {
                    $tmp = $_SESSION['arr'][$j];
                    $_SESSION['arr'][$j] = $_SESSION['arr'][$i];
                    $_SESSION['arr'][$i] = $tmp;
                }
            }
        }
    }

    return $_SESSION['arr'];
}

if (isset($_POST['priceup'])) {
    foreach (change('potang', 'price') as $value) {
        echo "<tr><td>".$value['id']."</td><td>".$value['name']."</td><td>".$value['price']."</td><td>".$value['quantity']."</td><td>".$value['order']."</td></tr>";
    }
}

if (isset($_POST['pricedown'])) {
    foreach (change('pogiam', 'price') as $value) {
        echo "<tr><td>".$value['id']."</td><td>".$value['name']."</td><td>".$value['price']."</td><td>".$value['quantity']."</td><td>".$value['order']."</td></tr>";
    }
}

if (isset($_POST['orderup'])) {
    foreach (change('potang', 'order') as $value) {
        echo "<tr><td>".$value['id']."</td><td>".$value['name']."</td><td>".$value['price']."</td><td>".$value['quantity']."</td><td>".$value['order']."</td></tr>";
    }
}

if (isset($_POST['orderdown'])) {
    foreach (change('pogiam', 'order') as $value) {
        echo "<tr><td>".$value['id']."</td><td>".$value['name']."</td><td>".$value['price']."</td><td>".$value['quantity']."</td><td>".$value['order']."</td></tr>";
    }
}

if (isset($_POST['totalup'])) {
    foreach (change('totaltang', 'price') as $value) {
        echo "<tr><td>".$value['id']."</td><td>".$value['name']."</td><td>".$value['price']."</td><td>".$value['quantity']."</td><td>".$value['order']."</td></tr>";
    }
}

if (isset($_POST['totaldown'])) {
    foreach (change('totalgiam', 'price') as $value) {
        echo "<tr><td>".$value['id']."</td><td>".$value['name']."</td><td>".$value['price']."</td><td>".$value['quantity']."</td><td>".$value['order']."</td></tr>";
    }
}

echo "</table>";

?>
<form method="post" action="">
    <input type="text" name="a" value="<?php echo isset($a) ? $a : '' ?>">
    <input type="submit" name="tao" value="submit"/>
    <input type="submit" name="priceup" value="price tăng"/>
    <input type="submit" name="pricedown" value="price giảm"/>
    <input type="submit" name="orderup" value="order tăng"/>
    <input type="submit" name="orderdown" value="order giảm"/>
    <input type="submit" name="totalup" value="tổng tăng"/>
    <input type="submit" name="totaldown" value="tổng giảm"/>
</form>
</body>
</html>