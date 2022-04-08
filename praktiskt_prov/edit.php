
<?php
require_once 'logindata.php';
try{$pdo = new PDO($attr, $user, $pass, $opts);} //Ett försök att skapa ett PDO gränssnitt med variabelvärden från logindata.php där värderna sparas
    //i våran variabel pdo.
    //Om inta databasen nås så skapar vi en fel hantering som ger oss en felmedelningsvärde samt meddelar användaren att systemet är nere(Detta är bortom användarens kapacitet att påverka)
catch(PDOExeption $e){throw new PDOException($e->getMessage(), (int)$e->getCode());

// if()s

function Update_Movie($pdo, $input)
{
    $inputholder = array('titel'    => $input['titel'],
                        'director'  => $input['director'],
                        'year'      => $input['year'],
                        'genre'     => $input['genre']
                        'id'        => $input['id']);
    $inputholder = Manage_String($pdo, $inputholder);
    $stmt->prepare('UPDATE movies SET titel=?, director=?, year=?, genre_id=? WHERE id=?');
    $stmt->bindParam(5, $inputholder['id'],         PDO::PARAM_INT);
    $stmt->bindParam(1, $inputholder['titel'],      PDO::PARAM_STR, 128);
    $stmt->bindParam(2, $inputholder['director'],   PDO::PARAM_STR, 128);
    $stmt->bindParam(3, $inputholder['year'],       PDO::PARAM_STR, 4);
    $stmt->bindParam(4, $inputholder['genre'],      PDO::PARAM_INT);
    $result = $stmt->execute([$inputholder['id'],
                            $inputholder['titel'], 
                            $inputholder['director'],
                            $inputholder['year'],
                            $inputholder['genre']]);
    
}
function Delete_Movie($input)
{
    $query = "DELETE FROM movies WHERE id=$input";
    $result = $pdo->query($query);
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