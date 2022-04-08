<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='UTF-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <title>Document</title>
    <link rel='stylesheet' href='../css/style.css' />
    <link rel='stylesheet' href='../css/radio.css' />
    <link rel='preconnect' href='https://fonts.googleapis.com' />
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin />
    <link
      href='https://fonts.googleapis.com/css2?family=Exo:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap'
      rel='stylesheet'
    />
  </head>
<?php
require_once 'logindata.php';
try{$pdo = new PDO($attr, $user, $pass, $opts);} //Ett försök att skapa ett PDO gränssnitt med variabelvärden från logindata.php där värderna sparas
    //i våran variabel pdo.
    //Om inta databasen nås så skapar vi en fel hantering som ger oss en felmedelningsvärde samt meddelar användaren att systemet är nere(Detta är bortom användarens kapacitet att påverka)
catch(PDOExeption $e){throw new PDOException($e->getMessage(), (int)$e->getCode());}

// if(is) asd

if(isset($_POST['titel']) && isset($_POST['director']) && isset($_POST['year']) && isset($_POST['genre']))
{
    $spellcheck = $_POST['titel'] . $_POST['director'] . $_POST ['year'] . $_POST['genre'];
    if(!ctype_alnum($spellcheck))
    { 
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
            $addmovie = Add_Movie($pdo, $_POST);
        }
    }
}
else{echo 'hhhhhhhhhhhhh';}
echo <<<_END
<body>
<div class='media_container'>
  <div class='media-block add-movies'>
    <h1>Add Movies</h1>
    <form action=''>
      <div class='media_container-inner'>
        <div class='media_movies'>
          <div class='movie-edit'>
            <div>
              <p class='movie-edit-text'>Title</p>
              <input type='text' name='title' placeholder='' />
            </div>
            <div>
              <p class='movie-edit-text'>Year</p>
              <input type='text' name='year' placeholder='' />
            </div>
            <div>
              <p class='movie-edit-text'>Director</p>
              <input type='text' name='director' placeholder='' />
            </div>
          </div>
          <div class='radio-container'>
            <p class='movie-edit-text'>Genres</p>
            <label for='drama' class='radio'>
              <input
                type='radio'
                name='myRadioField'
                id='drama'
                class='radio__input'
              />
              <div class='radio__radio'></div>
              Drama
            </label>
            <label for='thriller' class='radio'>
              <input
                type='radio'
                name='myRadioField'
                id='thriller'
                class='radio__input'
              />
              <div class='radio__radio'></div>
              Thriller
            </label>
            <label for='action' class='radio'>
              <input
                type='radio'
                name='myRadioField'
                id='action'
                class='radio__input'
              />
              <div class='radio__radio'></div>
              Action
            </label>
            <label for='comedy' class='radio'>
              <input
                type='radio'
                name='myRadioField'
                id='comedy'
                class='radio__input'
              />
              <div class='radio__radio'></div>
              Comedy
            </label>
            <label for='scifi' class='radio'>
              <input
                type='radio'
                name='myRadioField'
                id='scifi'
                class='radio__input'
              />
              <div class='radio__radio'></div>
              Science Fiction
            </label>
            <label for='romance' class='radio'>
              <input
                type='radio'
                name='myRadioField'
                id='romance'
                class='radio__input'
              />
              <div class='radio__radio'></div>
              Romance
            </label>
          </div>
        </div>
        <div class='confirm-container'>
          <div class='confirm-btn'>Confirm</div>
          <div class='invisible-object'></div>
        </div>
      </div>
    </form>
  </div>
  <div class='media-block media_library'>
    <h1>Media Library</h1>
    <div class='media_container-inner'>
      <div>
        <div class='media_header-static'>
          <div class='static media-title'>Title</div>
          <div class='static media-director'>Director</div>
          <div class='static media-year'>Year</div>
          <div class='static media-genre'>Genre</div>
          <div class='static media-update'>Update</div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src='../js/confirm-btn.js'></script>
</body>
</html>
_END;
Get_Movies($pdo);
echo <<<_END

<div class="media-footer-container">
<div class="media-footer-outer">
<div class="media-footer-inner"></div>
</div>
</div>

_END;
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
function Get_Movies($pdo)
{
    $query = 'SELECT id, titel, director, year, genre FROM movies, genre WHERE genre.genre_id=movies.genre_id';
    $result = $pdo->query($query);
    while ($row = $result->fetch())
    {
        $id = htmlspecialchars($row['id']);
        $titel = htmlspecialchars($row['titel']);
        $director = htmlspecialchars($row['director']);
        $year = htmlspecialchars($row['year']);
        $genre = htmlspecialchars($row['genre']);
        echo <<<_END
        
                <div class="block-post-container">
                     <div class="post-container">
                         <div class="library-block">
                                <div class="media-title">$titel</div>
                                <div class="media-director">$director</div>
                                <div class="media-year">$year</div>
                                <div class="media-genre">$genre</div>
                                <div class="media-edit">Edit</div>
                                <input type='hidden' name='id' value='$id'>
                                         </div>
                     </div>
                </div>
                     
            
    _END;
    } 
}
function Add_Movie($pdo, $input)
{
    $inputholder = array('titel'    => $input['titel'],
                        'director'  => $input['director'],
                        'year'      => $input['year'],
                        'genre'     => $input['genre']);
    $inputholder = Manage_String($pdo, $inputholder);
    $id = '';
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
//     $query = 'SELECT * FROM genre';
//     $genreres = $pdo->query($query);
//     $row = $genreres->fetch();
//     $genre_id = $row['genre_id'];
//     $genre = $row['genre'];
// }
?>