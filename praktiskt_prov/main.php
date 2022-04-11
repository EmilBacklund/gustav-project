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
  if(isset($_POST['movieid']) && isset($_POST['moviecheck']))
  {
    session_start();
    $_SESSION['title']    = $_POST['movietitle'];
    $_SESSION['id']       = $_POST['movieid'];
    $_SESSION['director'] = $_POST['moviedirector'];
    $_SESSION['year']     = $_POST['movieyear'];
    $_SESSION['genre']    = $_POST['moviegenre'];
    $_SESSION['validate'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
    Redirect('edit.php');
  }
  
  if(isset($_POST['title']) && isset($_POST['director']) && isset($_POST['year']) && isset($_POST['genre'])
    && strlen($_POST['title']) >= 1 && strlen($_POST['director']) >= 1 && strlen($_POST ['year']) === 4 && is_numeric($_POST['year']))
  {
    if(duplicates($pdo, $_POST['title']) == true)
    {
      Add_Movie($pdo, $_POST);
    }
  }
  if(isset($_SESSION)){Destroy_Sessiondata();}
?>
<body>
<div class='media_container'>
  <div class='media-block add-movies'>
    <h1>Add Movies</h1>
    <form action='' method='post' id='form'>
      <div class='media_container-inner'>
        <div class='media_movies'>
          <div class='movie-edit'>
            <div>
              <p class='movie-edit-text'>Title</p>
              <input type='text' name='title' placeholder='' />
              <?php
                if(isset($_POST['title']) && strlen($_POST['title']) < 1)
                {
                    echo "<div class='form-error' id='titleError'>
                    Please enter a Titel </div>";
                }
                ?>
            </div>
            <div>
              <p class='movie-edit-text'>Year</p>
              <input type='text' name='year' placeholder='YYYY' />
              <?php
                if(isset($_POST['year']) && strlen($_POST['year']) < 4)
                {
                    echo "<div class='form-error' id='titleError'>
                    Please enter a valid year 'YYYY' </div>";
                }
                ?>
            </div>
            <div>
              <p class='movie-edit-text'>Director</p>
              <input type='text' name='director' placeholder='' />
              <?php
                if(isset($_POST['director']) && strlen($_POST['director']) < 1)
                {
                    echo "<div class='form-error' id='titleError'>
                    Please enter a Director </div>";
                }
                ?>
            </div>
          </div>
          <div class='radio-container'>
            <p class='movie-edit-text'>Genres</p>
            <label for='action' class='radio'>
              <input
                type='radio'
                name='genre'
                id='action'
                value='1'
                class='radio__input'
              />
              <div class='radio__radio'></div>
              Action
            </label>
            <label for='comedy' class='radio'>
              <input
                type='radio'
                name='genre'
                id='comedy'
                value='2'
                class='radio__input'
              />
              <div class='radio__radio'></div>
              Comedy
            </label>
            <label for='drama' class='radio'>
              <input
                type='radio'
                name='genre'
                id='drama'
                value='3'
                class='radio__input'
              />
              <div class='radio__radio'></div>
              Drama
            </label>
            <label for='fantasy' class='radio'>
              <input
                type='radio'
                name='genre'
                id='fantasy'
                value='4'
                class='radio__input'
              />
              <div class='radio__radio'></div>
              Fantasy
            </label>
            <label for='scifi' class='radio'>
              <input
                type='radio'
                name='genre'
                id='scifi'
                value='5'
                class='radio__input'
              />
              <div class='radio__radio'></div>
              Science Fiction
            </label>
            <label for='thriller' class='radio'>
              <input
                type='radio'
                name='genre'
                id='thriller'
                value='6'
                checked="checked"
                class='radio__input'
              />
              <div class='radio__radio'></div>
              Thriller
            </label>
          </div>
        </div>
        <div class='confirm-container'>
          <input type='submit' class='confirm-btn' value='Add Movie' > 
          <div class='invisible-object'></div>
        </div>
      </div>
    </form>
  </div>
  <form action='' method='post'>
  <div class='media-block media_library'>
    <h1>Media Library</h1>
    <div class='media_container-inner'>
      <div>
        <div class='media_header-static'>
          <div class='static media-title'>Title</div>
          <div class='static media-director'>Director</div>
          <div class='static media-year'>Year</div>
          <div class='static media-genre'>Genre</div>
          <div class='static media-update'>Edit</div>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src='../js/confirm-btn.js'></script>
</body>
</html>
<?php
if(isset($_POST['sort']))
{
  $sort = $_POST['sort'];
  get_sortedmovies($pdo, $sort);
}
elseif(isset($_POST['search']))
{
  $search = $_POST['search'];
  get_search($pdo, $search);
}
else {
  Get_Movies($pdo);
}
  ?>
<div class="media-footer-container">
<div class="media-footer-outer">
<div class="media-footer-inner"></div>
</div>
</div>
<?php
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
  function Get_Movies($pdo)
  {
      $query = 'SELECT id, title, director, year, genre FROM movies, genre WHERE genre.genre_id=movies.genre_id';
      $result = $pdo->query($query);
      while ($row = $result->fetch())
      {
        printmovies($row['id'], $row['title'], $row['director'], $row['year'], $row['genre']);
      } 
  }
  function Add_Movie($pdo, $input)
  {
      $inputholder = array('title'    => $input['title'],
                          'director'  => $input['director'],
                          'year'      => $input['year'],
                          'genre'     => $input['genre']);
      $inputholder = Manage_Array($pdo, $inputholder);
      $id = '';
      $stmt = $pdo->prepare('INSERT INTO movies VALUES(?,?,?,?,?)');
      $stmt->bindParam(1, $id,                        PDO::PARAM_INT);
      $stmt->bindParam(2, $inputholder['title'],      PDO::PARAM_STR, 128);
      $stmt->bindParam(3, $inputholder['director'],   PDO::PARAM_STR, 128);
      $stmt->bindParam(4, $inputholder['year'],       PDO::PARAM_STR, 4);
      $stmt->bindParam(5, $inputholder['genre'],      PDO::PARAM_INT);
      $result = $stmt->execute([$id,
                      $inputholder['title'], 
                      $inputholder['director'],
                      $inputholder['year'],
                      $inputholder['genre']]);
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
  function Destroy_Sessiondata()
  {
      //förvandlar sessionens alla nycklar till en array
      $_SESSION = array();
      //Avslutar kakan genom att ge den en livslängd till ett datum som redan passerat oavsett vad
      setcookie(session_name(), '', time() - 2592000, '/');
      //Förstör alla värden i våran session array
      session_destroy(); 
  }
  function duplicates($pdo, $check)
  {
    $holder = Manage_String($pdo, $check);
    $stmt = $pdo->prepare('SELECT title FROM movies WHERE title=?');
    $stmt->bindParam(1, $holder, PDO::PARAM_STR, 128);
    $stmt->execute([$holder]);
    $result = $stmt;
    if($result->rowCount())
    {
      return false;
    }
  }
  function get_sortedmovies($pdo, $order)
  {
      $query = "SELECT id, title, director, year, genre FROM movies, genre WHERE genre.genre_id=movies.genre_id ORDER BY '$order'";
      $result = $pdo->query($query);
      while ($row = $result->fetch())
      {
        printmovies($row['id'], $row['title'], $row['director'], $row['year'], $row['genre']);
      } 
  }
  function get_search($pdo, $search)
  {
      $holder = Manage_String($pdo, $search);
      $stmt = $pdo->prepare("SELECT id, title, director, year, genre FROM movies, genre WHERE genre.genre_id=movies.genre_id WHERE MATCH(title, director, genre) AGAINST(?)");
      $stmt->bindParam(1, $holder, PDO::PARAM_STR, 128);
      $stmt->execute([$holder]);
      $result = $stmt;
      while ($row = $result->fetch())
      {
        printmovies($row['id'], $row['title'], $row['director'], $row['year'], $row['genre']);
      } 
  }
  function printmovies($id, $title, $director, $year, $genre)
  {
    $id = htmlspecialchars($id);
    $title = htmlspecialchars($title);
    $director = htmlspecialchars($director);
    $year = htmlspecialchars($year);
    $genre = htmlspecialchars($genre);
   echo <<<_END
           <div class="block-post-container">
             <div class="post-container">
               <div class="library-block">
                 <div class="media-title">$title</div>
                 <div class="media-director">$director</div>
                 <div class="media-year">$year</div>
                 <div class="media-genre">$genre</div>
                 <form action='' method='post'>
                 <input type='hidden' name='movieid' value='$id'>
                 <input type='hidden' name='movietitle' value='$title'>
                 <input type='hidden' name='moviedirector' value='$director'>
                 <input type='hidden' name='movieyear' value='$year'>
                 <input type='hidden' name='moviegenre' value='$genre'>
                 <input type='hidden' name='moviecheck' value='check'>
                 <input class="library-block-edit edit-image" type='submit' value='Edit'></form>
               </div>
             </div>
           </div>     
         _END;
  }
?>