
<?php
require_once 'logindata.php';
try{$pdo = new PDO($attr, $user, $pass, $opts);} //Ett försök att skapa ett PDO gränssnitt med variabelvärden från logindata.php där värderna sparas
    //i våran variabel pdo.
    //Om inta databasen nås så skapar vi en fel hantering som ger oss en felmedelningsvärde samt meddelar användaren att systemet är nere(Detta är bortom användarens kapacitet att påverka)
catch(PDOExeption $e){throw new PDOException($e->getMessage(), (int)$e->getCode());

start_session();
if ($_SESSION['validate'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']))
{
    Destroy_Sessiondata();
    Redirect('main.php');
    die();
}
else
{
    $id = $_SESSION['id'];
    $titel = $_SESSION['titel'];
    $director = $_SESSION['director'];
    $year = $_SESSION['year'];
    $genre = $_SESSION['genre'];
    
    if(isset($_POST['titel']) && isset($_POST['director']) && isset($_POST['year']) && isset($_POST['genre']))
    {
        $spellcheck = $_POST['titel'] . $_POST['director'] . $_POST ['year'] . $_POST['genre'];
        if(!ctype_alnum($spellcheck))
        { 
            $_POST = null;
            die();
        }
        else
        {
            if(strlen($_POST ['year']) != 4)
            {
                die();
            }
            else
            {
                $addmovie = Update_Movie($pdo, $_POST, $id);
                Destroy_Sessiondata();
                Redirect('main.php');
            }
        }
    }
    if(isset($_POST['delete'])
    {
        Delete_Movie($pdo, $id);
        Destroy_Sessiondata();
        Redirect('main.php');
    }
}
// Destroy_Sessiondata();
function Update_Movie($pdo, $input, $id)
{
    $inputholder = array('titel'    => $input['titel'],
                        'director'  => $input['director'],
                        'year'      => $input['year'],
                        'genre'     => $input['genre']
                        'id'        => $id);
    $inputholder = Manage_Array($pdo, $inputholder);
    $stmt->prepare('UPDATE movies SET titel=?, director=?, year=?, genre_id=? WHERE id=?');
    $stmt->bindParam(5, $di,                        PDO::PARAM_INT);
    $stmt->bindParam(1, $inputholder['titel'],      PDO::PARAM_STR, 128);
    $stmt->bindParam(2, $inputholder['director'],   PDO::PARAM_STR, 128);
    $stmt->bindParam(3, $inputholder['year'],       PDO::PARAM_STR, 4);
    $stmt->bindParam(4, $inputholder['genre'],      PDO::PARAM_INT);
    $result = $stmt->execute([$id,
                            $inputholder['titel'], 
                            $inputholder['director'],
                            $inputholder['year'],
                            $inputholder['genre']]);
    
}
function Delete_Movie($pdo, $input)
{
    $holder = Manage_String($pdo, $input);
    $query = "DELETE FROM movies WHERE id=$holder";
    $result = $pdo->query($query);
}
function Manage_Array($pdo, $array)
{
    foreach ($array as $key => $string)
    {
        $string = $pdo->quote($string);
        $string = Fix_String($string);
    }
    return $array;
}
function Manage_String($pdo, $string)
{
    $string = $pdo->quote($string);
    $string = Fix_String($string);
    return $string;
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
function Destroy_Sessiondata()
{
    //förvandlar sessionens alla nycklar till en array
    $_SESSION = array();
    //Avslutar kakan genom att ge den en livslängd till ett datum som redan passerat oavsett vad
    setcookie(session_name(), '', time() - 2592000, '/');
    //Förstör alla värden i våran session array
    session_destroy(); 
}
function Redirect($path) 
    {
        //stannar programmet och tillåter bara headers att fortsätta i koden
        //samtidigt som en buffrar all kod som hittils körts
        ob_start();
        //Skrickar programmet vidare till en annan fil
        header('Location: ' . $path);
        //stänger av buffringen 
        ob_end_flush();
        //dödar programmet
        die();
    }
?>