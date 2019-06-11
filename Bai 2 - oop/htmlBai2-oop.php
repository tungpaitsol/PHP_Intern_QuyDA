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

    private $create;
    private $firstName;
    private $lastName;
    private $email;
    private $passWord;
    private $conFirm;
    private $terms;
    private $termsPrivacy;
    private $register;
    private $signIn;
    private $logIn;

    public function __construct($txt)
    {
        $arr = [];
        $file = fopen($txt, "r");
        while (feof($file) == false) {
            $lg[] = fgets($file);
        }
        fclose($file);

        foreach ($lg as $key => $value) {
            $result = explode('="', $value);
            $outcome = explode('"', $result[1]);
            $arr[$result[0]] = $outcome[0];
        }

        $this->create = $arr['create'];
        $this->firstName = $arr['firstName'];
        $this->lastName = $arr['lastName'];
        $this->email = $arr['email'];
        $this->passWord = $arr['passWord'];
        $this->conFirm = $arr['conFirm'];
        $this->terms = $arr['terms'];
        $this->termsPrivacy = $arr['termsPrivacy'];
        $this->register = $arr['register'];
        $this->signIn = $arr['signIn'];
        $this->logIn = $arr['logIn'];
    }

    public function getCreate()
    {
        return $this->create;
    }

    public function getfirstName()
    {
        return $this->firstName;
    }

    public function getlastName()
    {
        return $this->lastName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassWord()
    {
        return $this->passWord;
    }

    public function getConFirm()
    {
        return $this->conFirm;
    }

    public function getTerms()
    {
        return $this->terms;
    }

    public function getTermsPrivacy()
    {
        return $this->termsPrivacy;
    }

    public function getRegister()
    {
        return $this->register;
    }

    public function getSignIn()
    {
        return $this->signIn;
    }

    public function getLogIn()
    {
        return $this->logIn;
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
        }
        else {
            echo $eng;
        }
    }

}

$languageVi = new Language("vi.txt");

    $vi_register = $languageVi->getRegister();
    $vi_create = $languageVi->getCreate();
    $vi_firstName = $languageVi->getfirstName();
    $vi_lastName = $languageVi->getlastName();
    $vi_email = $languageVi->getEmail();
    $vi_passWord = $languageVi->getPassWord();
    $vi_conFirm = $languageVi->getConFirm();
    $vi_terms = $languageVi->getTerms();
    $vi_termsPrivacy = $languageVi->getTermsPrivacy();
    $vi_signin = $languageVi->getSignIn();
    $vi_login = $languageVi->getLogIn();

$languageEng = new Language("eng.txt");

    $eng_register = $languageEng->getRegister();
    $eng_create = $languageEng->getCreate();
    $eng_firstName = $languageEng->getfirstName();
    $eng_lastName = $languageEng->getlastName();
    $eng_email = $languageEng->getEmail();
    $eng_passWord = $languageEng->getPassWord();
    $eng_conFirm = $languageEng->getConFirm();
    $eng_terms = $languageEng->getTerms();
    $eng_termsPrivacy = $languageEng->getTermsPrivacy();
    $eng_signin = $languageEng->getSignIn();
    $eng_login = $languageEng->getLogIn();

?>

<form method="post">
    <div class="container">
        <h1><?php Language::html($vi_register, $eng_register) ?></h1>
        <p><?php Language::html($vi_create, $eng_create) ?></p>
        <input type="text" placeholder="<?php Language::html($vi_firstName, $eng_firstName) ?>" name="firstname">
        <input type="text" placeholder="<?php Language::html($vi_lastName, $eng_lastName) ?>" name="lastname">
        <input type="text" placeholder="<?php Language::html($vi_email, $eng_email) ?>" name="email">
        <input type="password" placeholder="<?php Language::html($vi_passWord, $eng_passWord) ?>" name="password">
        <input type="password" placeholder="<?php Language::html($vi_conFirm, $eng_conFirm) ?>" name="confirm">
        <div class="terms">
            <p><?php Language::html($vi_terms, $eng_terms) ?></p>
            <p><a href="#"><?php Language::html($vi_termsPrivacy, $eng_termsPrivacy) ?></a></p>
        </div>
        <input type="submit" value="<?php Language::html($vi_register, $eng_register) ?>" class="registerbtn">
    </div>

    <div class="signin">
        <p><?php Language::html($vi_signin, $eng_signin) ?></p>
        <p><a href="#"><?php Language::html($vi_login, $eng_login) ?></a></p>
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
