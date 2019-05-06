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
<form method="post">
    <?php session_start();

    if (isset($_POST['tao'])) {
        $array = [];
        $random = [];
        $a = isset($_POST['a']) ? ($_POST['a']) : '';

        for ($i = 1; $i <= $a; $i++) {
            $random = ['id' => $i, 'name' => RandomNumber($a), 'price' => RandomNumber($a), 'quantity' => RandomNumber($a), 'order' => RandomNumber($a)];
            array_push($array, $random);
        }

        if (count($array) > 0) {
            $_SESSION['arr'] = $array;

        }

        echo "<table><tr><th>ID</th><th>Name</th><th>Price</th><th>Quantity</th><th>Order</th></tr>";
        foreach ($array as $value) {
            echo "<tr><td>" . $value['id'] . "</td><td>" . $value['name'] . "</td><td>" . $value['price'] . "</td><td>" . $value['quantity'] . "</td><td><input type='text' name='orderupp[order][]' value=" . $value['order'] . "></td></tr>";
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

    function change($arr)
    {
        $sophantu = count($arr);
        for ($i = 0; $i < ($sophantu - 1); $i++) {
            for ($j = $i + 1; $j < $sophantu; $j++) {
                if ($arr[$i]['order'] > $arr[$j]['order']) {
                    $tmp = $arr[$j];
                    $arr[$j] = $arr[$i];
                    $arr[$i] = $tmp;
                }
                if (($arr[$i]['order'] == $arr[$j]['order']) && ($arr[$i]['id'] > $arr[$j]['id'])) {
                    $tmp = $arr[$j];
                    $arr[$j] = $arr[$i];
                    $arr[$i] = $tmp;
                }
            }
        }
        $_SESSION['arr'] = $arr;
        return $arr;
    }

    if (isset($_POST['orderup'])) {
        $arr = $_SESSION['arr'];
        $orderup = $_POST['orderupp'];

        for ($i = 0; $i < count($arr); $i++) {
            $arr[$i]['order'] = $orderup['order'][$i];
        }

        echo "<table><tr><th>ID</th><th>Name</th><th>Price</th><th>Quantity</th><th>Order</th></tr>";
        foreach (change($arr) as $value) {
            echo "<tr><td>" . $value['id'] . "</td><td>" . $value['name'] . "</td><td>" . $value['price'] . "</td><td>" . $value['quantity'] . "</td><td><input type='text' name='orderupp[order][]' value=" . $value['order'] . "></td></tr>";
        }
    }

    if (isset($_POST['luu'])) {
        $arr = $_SESSION['arr'];
        $orderup = $_POST['orderupp'];

        for ($i = 0; $i < count($arr); $i++) {
            $arr[$i]['order'] = $orderup['order'][$i];
        }
        $sophantu = count($arr);
        for ($i = 0; $i < ($sophantu - 1); $i++) {
            for ($j = $i + 1; $j < $sophantu; $j++) {
                if ($arr[$i]['id'] > $arr[$j]['id']) {
                    $tmp = $arr[$j];
                    $arr[$j] = $arr[$i];
                    $arr[$i] = $tmp;
                }
            }
        }
        $_SESSION['arr'] = $arr;

        echo "<table><tr><th>ID</th><th>Name</th><th>Price</th><th>Quantity</th><th>Order</th></tr>";
        foreach ($arr as $value) {
            echo "<tr><td>" . $value['id'] . "</td><td>" . $value['name'] . "</td><td>" . $value['price'] . "</td><td>" . $value['quantity'] . "</td><td><input type='text' name='orderupp[order][]' value=" . $value['order'] . "></td></tr>";
        }

        echo "<input type='submit' name='orderup' value='order tăng'/>";
    }

    echo "</table>";
    ?>

    <input type="text" name="a" value="<?php echo isset($a) ? $a : '' ?>">
    <input type="submit" name="tao" value="submit"/>
    <input type="submit" name="luu" value="luu"/>
<!--    <input type='submit' name='orderup' value='order tăng'/>-->
</form>
</body>
</html>

