<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bài 5</title>
</head>
<body>
<form method="post" action="">
    <?php session_start();

    if (isset($_POST['tao'])) {
        $array = [];
        $random = [];
        $a = isset($_POST['a']) ? ($_POST['a']) : '';

        for ($i = 1; $i <= $a; $i++) {
            $random = ['id' => $i, 'name' => RandomString($a), 'price' => RandomNumber($a), 'quantity' => RandomNumber($a), 'order' => RandomNumber($a)];
            array_push($array, $random);
        }

        if (count($array) > 0) {
            $_SESSION['arr'] = $array;
        }

        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Order</th>
                </tr>";
        foreach ($array as $value) {
            echo
                "<tr>
                    <td>" . $value['id'] . "</td>
                    <td>" . $value['name'] . "</td>
                    <td>" . $value['price'] . "</td>
                    <td>" . $value['quantity'] . "</td>
                    <td><input type='text' name='orderupp[order][]' value=" . $value['order'] . "></td>
                </tr>";
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

        for ($i = 1; $i < rand($k, $l); $i++) {
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

        for ($i = 0; $i < rand($k, $l); $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    function hoanvi($type, $sosanh, $j, $i, &$arr, $column)
    {
        if ($type) {
            if ($sosanh) {
                $tmp = $arr[$j];
                $arr[$j] = $arr[$i];
                $arr[$i] = $tmp;
            }
            if (($arr[$i][$column] == $arr[$j][$column]) && ($arr[$i]['id'] > $arr[$j]['id'])) {
                $tmp = $arr[$j];
                $arr[$j] = $arr[$i];
                $arr[$i] = $tmp;
            }
        }

        return $arr;
    }

    function change($type, $column, $arr)
    {
        $sophantu = count($arr);
        for ($i = 0; $i < ($sophantu - 1); $i++) {
            for ($j = $i + 1; $j < $sophantu; $j++) {
                hoanvi($type === 'potang', $arr[$i][$column] > $arr[$j][$column], $j, $i, $arr, $column);
                hoanvi($type === 'pogiam', $arr[$i][$column] < $arr[$j][$column], $j, $i, $arr, $column);
                hoanvi($type === 'totaltang', ($arr[$i][$column] * $arr[$i]['quantity']) > ($arr[$j][$column] * $arr[$j]['quantity']), $j, $i, $arr, $column);
                hoanvi($type === 'totalgiam', ($arr[$i][$column] * $arr[$i]['quantity']) < ($arr[$j][$column] * $arr[$j]['quantity']), $j, $i, $arr, $column);
            }
        }
        $_SESSION['arr'] = $arr;
        return $arr;
    }

    function hienthi($type, $column, $arr)
    {
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Order</th>
                </tr>";
        foreach (change($type, $column, $arr) as $value) {
           echo
               "<tr>
                    <td>" . $value['id'] . "</td>
                    <td>" . $value['name'] . "</td>
                    <td>" . $value['price'] . "</td>
                    <td>" . $value['quantity'] . "</td>
                    <td><input type='text' name='orderupp[order][]' value=" . $value['order'] . "></td>
               </tr>";
        }
    }

    if (isset($_POST['orderup'])) {
        $arr = $_SESSION['arr'];
        $orderup = $_POST['orderupp'];
        for ($i = 0; $i < count($arr); $i++) {
            $arr[$i]['order'] = $orderup['order'][$i];
        }
        hienthi('potang', 'order', $arr);
    }
    if (isset($_POST['orderdown'])) {
        $arr = $_SESSION['arr'];
        hienthi('pogiam', 'order', $arr);
    }

    if (isset($_POST['priceup'])) {
        $arr = $_SESSION['arr'];
        hienthi('potang', 'price', $arr);
    }

    if (isset($_POST['pricedown'])) {
        $arr = $_SESSION['arr'];
        hienthi('pogiam', 'price', $arr);
    }

    if (isset($_POST['totalup'])) {
        $arr = $_SESSION['arr'];
        hienthi('totaltang', 'price', $arr);
    }

    if (isset($_POST['totaldown'])) {
        $arr = $_SESSION['arr'];
        hienthi('totalgiam', 'price', $arr);
    }

    echo "</table>";
    ?>

    <input type="text" name="a" value="<?php echo isset($a) ? $a : '' ?>">
    <input type="submit" name="tao" value="submit"/>
    <input type="submit" name="orderup" value="order tăng"/>
    <input type="submit" name="orderdown" value="order giảm"/>
    <input type="submit" name="priceup" value="price tăng"/>
    <input type="submit" name="pricedown" value="price giảm"/>
    <input type="submit" name="totalup" value="tổng tăng"/>
    <input type="submit" name="totaldown" value="tổng giảm"/>
</form>
</body>
</html>

