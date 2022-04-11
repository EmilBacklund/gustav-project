<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/radio.css" />
    <link rel="stylesheet" href="css/edit-movie.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Exo:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
  </head>
<?php
    require_once 'logindata.php';
    try{$pdo = new PDO($attr, $user, $pass, $opts);} //Ett försök att skapa ett PDO gränssnitt med variabelvärden från logindata.php där värderna sparas
    //i våran variabel pdo.
    //Om inta databasen nås så skapar vi en fel hantering som ger oss en felmedelningsvärde samt meddelar användaren att systemet är nere(Detta är bortom användarens kapacitet att påverka)
    catch(PDOExeption $e){throw new PDOException($e->getMessage(), (int)$e->getCode());}

    session_start();
    if ($_SESSION['validate'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']))
    {
        Destroy_Sessiondata();
        Redirect('main.php');
        die();
    }
    //Om inte sessionen har värdet i nyckeln 'initiated' genererar vi ett nytt session id
    //För att undvika att våra användare blir hänvisad av tredje part när de är inloggade
    //för en skadlig länk som potentiellt sett skulle kunna kapa deras session.
    //Fölt av att vi sätter ett värde under nyckeln 'initiated' till ett vilket gör att
    //denna kod inte körs igen när den inte behövs.
    if(!isset($_SESSION['initiated']))
    {
        session_regenerate_id();
        $_SESSION['initiated'] = 1;
    }
    //Om sessionen med nyckeln 'count' inte finns sätts värdet av den till 0.
    //och om den finns ökar dess värde med ett för att undvika att undvika att en kapare
    //kan fixera sessionen och använda den. Nu kommer sessionen att ha ett annat värde.
    if(!isset($_SESSION['count'])) {$_SESSION['count'] = 0;}
    else{++$_SESSION['count'];}

    $movie = array('id'         => $_SESSION['id'],
                    'title'     => $_SESSION['title'],
                    'director'  => $_SESSION['director'],
                    'year'      => $_SESSION['year'],
                    'genre'     => $_SESSION['genre']);
    
    if(isset($_POST['title']) && isset($_POST['director']) && isset($_POST['year']) && isset($_POST['genre'])
    && strlen($_POST['title']) >= 1 && strlen($_POST['director']) >= 1 && strlen($_POST ['year']) === 4 && is_numeric($_POST['year']))
    {
        Update_Movie($pdo, $_POST, $movie['id']);
        Destroy_Sessiondata();
        Redirect('main.php');
    }
    if(isset($_POST['delete']))
    {
        Delete_Movie($pdo, $movie['id']);
        Destroy_Sessiondata();
        Redirect('main.php');
    }
    echo $_SESSION['title'];
    Destroy_Sessiondata();
    ?>



    <?php
    function Update_Movie($pdo, $input, $id)
    {
    $inputholder = array('title'    => $input['title'],
                        'director'  => $input['director'],
                        'year'      => $input['year'],
                        'genre'     => $input['genre'],
                        'id'        => $id);
    $inputholder = Manage_Array($pdo, $inputholder);
    $stmt->prepare('UPDATE movies SET title=?, director=?, year=?, genre_id=? WHERE id=?');
    $stmt->bindParam(5, $di,                        PDO::PARAM_INT);
    $stmt->bindParam(1, $inputholder['title'],      PDO::PARAM_STR, 128);
    $stmt->bindParam(2, $inputholder['director'],   PDO::PARAM_STR, 128);
    $stmt->bindParam(3, $inputholder['year'],       PDO::PARAM_STR, 4);
    $stmt->bindParam(4, $inputholder['genre'],      PDO::PARAM_INT);
    $result = $stmt->execute([$id,
                            $inputholder['title'], 
                            $inputholder['director'],
                            $inputholder['year'],
                            $inputholder['genre']]);
    
    }
    function Delete_Movie($pdo, $input)
    {
        $holder = Manage_String($pdo, $input);
        $query = "DELETE FROM movies WHERE id='$holder'";
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