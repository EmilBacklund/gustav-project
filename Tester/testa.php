<!DOCTYPE html>
<?php
require_once 'logindata.php';
try{$pdo = new PDO($attr, $user, $pass, $opts);} //Ett försök att skapa ett PDO gränssnitt med variabelvärden från logindata.php där värderna sparas
    //i våran variabel pdo.
    //Om inta databasen nås så skapar vi en fel hantering som ger oss en felmedelningsvärde samt meddelar användaren att systemet är nere(Detta är bortom användarens kapacitet att påverka)
catch(PDOExeption $e){throw new PDOException($e->getMessage(), (int)$e->getCode());}


$testet = array('titel'     => 'entitel',
                'director'  => 'endirector',
                'year'      => 'year',
                'genre'     => '2');


Add_Movie($pdo, $testet);




function Add_Movie($pdo, $input)
{

    $test = array('titel'   => $input['titel'],
                'director'  => $input['director'],
                'year'      => $input['year'],
                'genre'     => $input['genre']);
    $test = Manage_String($pdo, $test);



    foreach ($test as $item)
    {
        echo $item . '<br>';
    }

    // $test = array('titel'    => $input['titel'],
    //                     'director'  => $input['director'],
    //                     'year'      => $input['year'],
    //                     'genre'     => $input['genre']);asd




    // $test = Manage_String($pdo, $test);
    $id = '';
    $stmt = $pdo->prepare('INSERT INTO movies VALUES(?,?,?,?,?)');
    $stmt->bindParam(1, $id,                        PDO::PARAM_INT);
    $stmt->bindParam(2, $test['titel'],      PDO::PARAM_STR, 128);
    $stmt->bindParam(3, $test['director'],   PDO::PARAM_STR, 128);
    $stmt->bindParam(4, $test['year'],       PDO::PARAM_STR, 4);
    $stmt->bindParam(5, $test['genre'],      PDO::PARAM_INT);
    $result = $stmt->execute([$id,
                    $test['titel'], 
                    $test['director'],
                    $test['year'],
                    $test['genre']]);
}
function Manage_String($pdo, $array)
{
    foreach ($array as $key => $string)
    {
        $string = $pdo->quote($string);
        $string = Fix_String($string);
    }
    return $array;
}
function Fix_String($string)
{
    if (get_magic_quotes_gpc())
    {
        $string = stripslashes($string);
    }
    $string = strip_tags($string);
    $string = htmlentities($string);
    return $string;
}

?>