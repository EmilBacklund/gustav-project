<?php
require_once 'logindata.php';
try{$pdo = new PDO($attr, $user, $pass, $opts);} //Ett försök att skapa ett PDO gränssnitt med variabelvärden från logindata.php där värderna sparas
    //i våran variabel pdo.
    //Om inta databasen nås så skapar vi en fel hantering som ger oss en felmedelningsvärde samt meddelar användaren att systemet är nere(Detta är bortom användarens kapacitet att påverka)
catch(PDOExeption $e){throw new PDOException($e->getMessage(), (int)$e->getCode());

if(isset($_POST['']))

if(isset($_POST['titel']) && isset($_POST['director']) && isset($_POST['year'] && isset($_POST['genre']))
{
    $spellcheck = $_POST['titel'] . $_POST['director'] . $_POST ['year'] . $_POST['genre'];
    if(!ctype_alnum($spellcheck)){ die();}
    else{
        if(strlen($_POST ['year']) != 4){die();}
        else{Add_Movie($_POST);}
    }
}
else
{
    die();
}
function Manage_String($pdo, $array)
{
    foreach ($array as $key => $string)
    {
        $string = $pdo->quote($string);
        $string = Fix_String($string);
        //return $string;
    }
    // $string = $pdo->quote($string);
    // $string = Fix_String($string);
    // return $string;
    //return $array;
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
function Get_Movies()
{
    $query = "SELECT id, titel, director, year, genre FROM movies, genre WHERE genre.genre_id=movies.genre_id";
    $result = $pdo->query($query);
    while($row = $result-fetch())
    {
        $id = htmlspecialchars($row['id']);
        return $row['titel'], $row['director'], $row['year'], $row['genre']; "<input type='hidden' "//Här behövs html för varje rad
    } 
}
function Add_Movie($input)
{
    $inputholder = array('titel'    => $input['titel'],
                        'director'  => $input['director'],
                        'year'      => $input['year'],
                        'genre'     => $input['genre']);
    $inputholder = Manage_String($pdo, $inputholder);
    $id = "";
    $stmt->prepare('INSERT INTO movies VALUES(?,?,?,?,?)');
    $stmt->bindParam(1, $id,                        PDO::PARAM_INT);
    $stmt->bindParam(2, $inputholder['titel'],      PDO::PARAM_STR, 128);
    $stmt->bindParam(3, $inputholder['director'],   PDO::PARAM_STR, 128);
    $stmt->bindParam(4, $inputholder['year'],       PDO::PARAM_STR, 4);
    $stmt->bindParam(5, $inputholder['genre'],      PDO::PARAM_INT);
    $result = $stmt->execute([$id,
                    $inputholder['titel'], 
                    $inputholder['director'],
                    $$inputholder['year'],
                    $inputholder['genre']]);
}
// function Get_Genres()
// {
//     $query = "SELECT * FROM genre";
//     $genreres = $pdo->query($query);
//     $row = $genreres->fetch();
//     $genre_id = $row['genre_id'];
//     $genre = $row['genre'];
// }
?>