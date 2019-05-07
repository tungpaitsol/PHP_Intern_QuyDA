<?php
session_start();

if (isset($_POST['tao'])) {
    $_SESSION['luu'] = 0;
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
}

if (isset($_POST['luu'])) {
    $arr = $_SESSION['arr'];
    $orderup = $_POST['orders'];

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
    $_SESSION['luu'] = 1;
}

if (isset($_POST['orderup']) && $_SESSION['luu'] == 1) {
    $arr = $_SESSION['arr'];
    $orderup = $_POST['orders'];

    for ($i = 0; $i < count($arr); $i++) {
        $arr[$i]['order'] = $orderup['order'][$i];
    }

    $_SESSION['arr'] = change($arr);
}

?>
    <form method="POST" role="form">
        <?php
        if (isset($_SESSION['arr'])) {
            ?>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Order</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($_SESSION['arr'] as $itemt) { ?>
                    <tr>
                        <td><?php echo $itemt['id'] ?></td>
                        <td><?php echo $itemt['name'] ?></td>
                        <td><?php echo $itemt['price'] ?></td>
                        <td><?php echo $itemt['quantity'] ?></td>
                        <td>
                            <input type='text' name='orders[order][]' value="<?php echo $itemt['order'] ?>">
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
        <input type="text" name="a" value="<?php echo isset($a) ? $a : '' ?>">
        <input type="submit" name="tao" value="submit"/>
        <input type="submit" name="luu" value="luu"/>
        <input type='submit' name='orderup' value='order tăng'/>
    </form>
<?php

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

    return $arr;
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


?>