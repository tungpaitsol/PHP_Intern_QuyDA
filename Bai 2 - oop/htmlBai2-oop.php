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
            box-sizing: border-box;
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
session_start();

class Language
{
    private $code;
    private $name;

    public function __construct($code)
    {
        $this->code = $code;
        $file = fopen('Language_folder/' . $code . '.txt', "r");
        while (feof($file) == false) {
            $lang[] = fgets($file);
        }
        fclose($file);

        foreach ($lang as $key => $value) {
            $result = explode('=', $value);
            $outcome = str_replace(['"', "\n"], '', $result[1]);
            $this->name[$result[0]] = $outcome;
        }
    }

    public function getValue($infor)
    {
        return $this->name[$infor];
    }

}

$l = 'eng';
if (isset($_GET['select'])) {
    $l = $_GET['select'];
    $_SESSION['select'] = $_GET['select'];
}
$lang = new Language($l);

?>

<form method="get">
    <div class="container">
        <h1><?php echo $lang->getValue('register') ?></h1>
        <p><?php echo $lang->getValue('create') ?></p>
        <input type="text" placeholder="<?php echo $lang->getValue('firstName') ?>">
        <input type="text" placeholder="<?php echo $lang->getValue('lastName') ?>">
        <input type="text" placeholder="<?php echo $lang->getValue('email') ?>">
        <input type="password" placeholder="<?php echo $lang->getValue('passWord') ?>">
        <input type="password" placeholder="<?php echo $lang->getValue('conFirm') ?>">
        <div class="terms">
            <p><?php $lang->getValue('terms') ?></p>
            <p><a href="#"><?php $lang->getValue('termsPrivacy') ?></a></p>
        </div>
        <input type="submit" value="<?php echo $lang->getValue('register') ?>" class="registerbtn">
    </div>

    <div class="signin">
        <p><?php echo $lang->getValue('signIn') ?></p>
        <p><a href="#"><?php echo $lang->getValue('logIn') ?></a></p>
    </div>

    <div>
        <select name="select">
            <option value="vi" <?php if (isset($_GET['select']) && $_GET['select'] == 'vi') {
                echo 'selected';
            } ?>>Tiếng Việt
            </option>
            <option value="eng" <?php if (isset($_GET['select']) && $_GET['select'] == 'eng') {
                echo 'selected';
            } ?>>English
            </option>
        </select>
        <input type="submit" name="input" value="submit">
    </div>
</form>

</body>
</html>
