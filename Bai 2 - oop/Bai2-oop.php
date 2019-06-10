<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bài 2 - oop</title>
    <style>
        * {
            box-sizing: border-box
        }

        input[type=text], input[type=password] {
            width: 100%;
            padding: 15px;
            margin: 0 0 10px 0;
            display: inline-block;
            border: none;
            background: #f1f1f1;
        }

        .registerbtn {
            background-color: #4CAF50;
            color: white;
            padding: 16px 20px;
            margin: 8px 0;
            border: none;
            width: 100%;
        }

        .signin {
            background-color: #f1f1f1;
            text-align: center;
            padding: 1px;
            display: flex;

        }

        .terms {

            display: flex;

        }
    </style>
</head>
<body>
<?php

class Language
{

    public function split_string($txt)
    {
        $arr = [];
        $file = fopen($txt, "r");
        while (feof($file) == false) {
            $lg[] = fgets($file);
        }
        fclose($file);

        foreach ($lg as $key => $value) {
            $result = explode('=', $value);
            $arr[$result[0]] = $result[1];
        }
        return $arr;
    }

    public static function html($vi, $eng)
    {
        if (isset($_POST['select'])) {
            if ($_POST['select'] == '1') {
                echo $vi;
            }
            if ($_POST['select'] == '2') {
                echo $eng;
            }
        } else {
            echo $eng;
        }
    }

}

$languageVi = new Language();
$vi = $languageVi->split_string("vi.txt");

$languageEng = new Language();
$eng = $languageEng->split_string("eng.txt");

//$arrVi = [];
//$fileVi = fopen("vi.txt", "r");
//while(feof($fileVi ) == false) {
//    $vi[] = fgets($fileVi);
//}
//fclose($fileVi);
//
//foreach ($vi as $key => $value){
//    $result = explode('=',$value);
//    $arrVi[$result[0]] = $result[1];
//}
//
//$arrEng = [];
//$fileEng = fopen("eng.txt", "r");
//while(feof($fileEng) == false) {
//    $eng[] = fgets($fileEng);
//}
//fclose($fileEng);
//
//foreach ($eng as $key => $value){
//    $result = explode('=',$value);
//    $arrEng[$result[0]] = $result[1];
//}

?>

<form method="post">

    <div class="container">
        <h1><?php Language::html($vi['register'], $eng['register']) ?></h1>
        <p><?php Language::html($vi['create'], $eng['create']) ?></p>
        <input type="text" placeholder="<?php Language::html($vi['firstName'], $eng['firstName']) ?>" name="firstname">
        <input type="text" placeholder="<?php Language::html($vi['lastName'], $eng['lastName']) ?>" name="lastname">
        <input type="text" placeholder="<?php Language::html($vi['email'], $eng['email']) ?>" name="email">
        <input type="password" placeholder="<?php Language::html($vi['passWord'], $eng['passWord']) ?>" name="password">
        <input type="password" placeholder="<?php Language::html($vi['conFirm'], $eng['conFirm']) ?>" name="confirm">
        <div class="terms">
            <p><?php Language::html($vi['terms'], $eng['terms']) ?></p>
            <p><a href="#"><?php Language::html($vi['termsPrivacy'], $eng['termsPrivacy']) ?></a></p>
        </div>
        <input type="submit" value="<?php Language::html($vi['register'], $eng['register']) ?>" class="registerbtn">
    </div>

    <div class="signin">
        <p><?php Language::html($vi['signIn'], $eng['signIn']) ?></p>
        <p><a href="#"><?php Language::html($vi['logIn'], $eng['logIn']) ?></a></p>
    </div>

    <div>
        <select name="select">
            <option value="1" <?php if (isset($_POST['select'])) {
                if ($_POST['select'] == '1') {
                    echo 'selected';
                }
            } ?>>Tiếng Việt
            </option>
            <option value="2" <?php if (isset($_POST['select'])) {
                if ($_POST['select'] == '2') {
                    echo 'selected';
                }
            } ?>>English
            </option>
        </select>
        <input type="submit" name="input" value="submit">
    </div>
</form>

</body>
</html>
