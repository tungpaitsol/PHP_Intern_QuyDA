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
