<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/radio.css" />
    <link rel="stylesheet" href="../css/smileyface.css" />
    <link rel="stylesheet" href="../css/edit-movie.css" />
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
    $movietitel = $movie['title'];
    $moviedirector = $movie['director'];
    $movieyear = $movie['year'];
    $moviegenre = $movie['genre'];
    ?>
    <body>
    <div class="media_container">
      <div class="media-block add-movies">
          <?php
        echo "<h1>Edit Movie '$movietitel'</h1>";
        ?>
         <div class="media_container-inner">
           <form method='post' action=''>
          <div class="media_movies">
            <div class="movie-edit">
              <div>
                <p class="movie-edit-text">Title</p>
                <?php
                    echo "<input id='title' type='text' name='title' value='$movietitel' />";
                ?>
                <?php
                if(isset($_POST['title']) && strlen($_POST['title']) < 1)
                {
                    echo "<div class='form-error' id='titleError'>
                    Please enter a Titel </div>";
                }
                ?>
              </div>
              <div>
                <p class="movie-edit-text">Year</p>
                <?php
                echo "<input class='validate-year' onkeypress='validateYear(event)' id='year' type='text' name='year' value='$movieyear' />"
                ?>
                <?php
                if(isset($_POST['year']) && strlen($_POST['year']) < 4)
                {
                    echo "<div class='form-error' id='titleError'>
                    Please enter a valid year 'YYYY' </div>";
                }
                ?>
              </div>
              <div>
                <p class="movie-edit-text">Director</p>
                <?php
                echo "<input id='director' type='text' name='director' value='$moviedirector' />";
                ?>
                <?php
                if(isset($_POST['director']) && strlen($_POST['director']) < 1)
                {
                    echo "<div class='form-error' id='titleError'>
                    Please enter a Director </div>";
                }
                ?>
              </div>
            </div>
            <div class="radio-container">
              <div class="face-container">
                <div class="face">
                  <div class="eyes">
                    <div class="eye"></div>
                    <div class="eye"></div>
                  </div>
                </div>
              </div>
              <p class="movie-edit-text">Genres</p>
              <?php
              check_radio($moviegenre);
              ?>
            </div>
          </div>
          <div class="confirm-container">
            <input value="Apply" type="submit" class="apply-btn" />
            </form>
            <form id="deleteConfirm" action="">
              <input
                data-modal-target="#modal"
                value="Delete"
                type="submit"
                class="delete-btn"
              />
             </form>
            </div>
            <div class="modal" id="modal">
              <div data-close-button class="close-button">&times</div>
              <div class="modal-body">
                <div class="modal-inner-container">
                  <div class="delete-img-container">
                    <img class="delete-img" src="../images/delete.png" alt="" />
                  </div>
                  <div class="modal_inform-text">Are you sure?</div>
                  <div class="modal_text">
                    Do you really want to delete this movie? This process cannot
                    be undone
                  </div>
                  <div class="modal__delete-cancel">
                    <div data-close-button class="modal__cancel-button">
                      Cancel
                    </div>
                    <form action="" method="post">
                      <input
                        class="modal__delete-button"
                        value="Delete"
                        name="delete"
                        type="submit"
                      />
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div id="overlay"></div>
          </div>
        </div>
      </div>
    </div>
    <script src="../js/delete-movie.js"></script>
    <script src="../js/smileyface.js"></script>
    <script src='../js/validate-search.js'></script>

  </body>
  </html>
    <?php
    function Update_Movie($pdo, $input, $id)
    {
    $inputholder = array('title'    => $input['title'],
                        'director'  => $input['director'],
                        'year'      => $input['year'],
                        'genre'     => $input['genre'],
                        'id'        => $id);
    $inputholder = Manage_Array($pdo, $inputholder);
    $stmt = $pdo->prepare('UPDATE movies SET title=?, director=?, year=?, genre_id=? WHERE id=?');
    $stmt->bindParam(1, $inputholder['title'],      PDO::PARAM_STR, 128);
    $stmt->bindParam(2, $inputholder['director'],   PDO::PARAM_STR, 128);
    $stmt->bindParam(3, $inputholder['year'],       PDO::PARAM_STR, 4);
    $stmt->bindParam(4, $inputholder['genre'],      PDO::PARAM_INT);
    $stmt->bindParam(5, $id,                        PDO::PARAM_INT);
    $result = $stmt->execute([$inputholder['title'], 
                            $inputholder['director'],
                            $inputholder['year'],
                            $inputholder['genre'],
                            $id]);
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
    function check_radio($radio)
    {
        if($radio === "Action")
        {
            echo <<<_END
                <label for="action" class="radio">
                <input type="radio" name="genre" id="action" class="radio__input" value="1" checked="checked" />
                <div class="radio__radio"></div>
                Action
              </label>
              <label for="comedy" class="radio">
                <input type="radio" name="genre" id="comedy" class="radio__input" value="2" />
                <div class="radio__radio"></div>
                Comedy
              </label>
              <label for="drama" class="radio">
                <input type="radio" name="genre" id="drama" class="radio__input" value="3" />
                <div class="radio__radio"></div>
                Drama
              </label>
              <label for="fantasy" class="radio">
                <input type="radio" name="genre" id="fantasy" class="radio__input" value="4" />
                <div class="radio__radio"></div>
                Fantasy
              </label>
              <label for="scifi" class="radio">
                <input type="radio" name="genre" id="scifi" class="radio__input" value="5" />
                <div class="radio__radio"></div>
                Science Fiction
              </label>
              <label for="thriller" class="radio">
                <input type="radio" name="genre" id="thriller" class="radio__input" value="6" />
                <div class="radio__radio"></div>
                Thriller
              </label>
            _END;
        }
        if($radio === "Comedy")
        {
            echo <<<_END
                <label for="action" class="radio">
                  <input type="radio" name="genre" id="action" class="radio__input" value="1" />
                  <div class="radio__radio"></div>
                  Action
                 </label>
                 <label for="comedy" class="radio">
                   <input type="radio" name="genre" id="comedy" class="radio__input" value="2" checked="checked" />
                   <div class="radio__radio"></div>
                  Comedy
                 </label>
                <label for="drama" class="radio">
                  <input type="radio" name="genre" id="drama" class="radio__input" value="3" />
                  <div class="radio__radio"></div>
                  Drama
                 </label>
                 <label for="fantasy" class="radio">
                   <input type="radio" name="genre" id="fantasy" class="radio__input" value="4" />
                  <div class="radio__radio"></div>
                   Fantasy
                 </label>
                 <label for="scifi" class="radio">
                    <input type="radio" name="genre" id="scifi" class="radio__input" value="5" />
                 <div class="radio__radio"></div>
                 Science Fiction
                </label>
                <label for="thriller" class="radio">
                 <input type="radio" name="genre" id="thriller" class="radio__input" value="6" />
                  <div class="radio__radio"></div>
                  Thriller
                 </label>
            _END;
        }
        if($radio === "Drama")
        {
            echo <<<_END
                <label for="action" class="radio">
                    <input type="radio" name="genre" id="action" class="radio__input" value="1" />
                    <div class="radio__radio"></div>
                    Action
               </label>
                  <label for="comedy" class="radio">
                    <input type="radio" name="genre" id="comedy" class="radio__input" value="2" />
                    <div class="radio__radio"></div>
                 Comedy
               </label>
               <label for="drama" class="radio">
                 <input type="radio" name="genre" id="drama" class="radio__input" value="3" checked="checked" />
                 <div class="radio__radio"></div>
                 Drama
               </label>
               <label for="fantasy" class="radio">
                 <input type="radio" name="genre" id="fantasy" class="radio__input" value="4" />
                 <div class="radio__radio"></div>
                 Fantasy
               </label>
               <label for="scifi" class="radio">
                 <input type="radio" name="genre" id="scifi" class="radio__input" value="5" />
                 <div class="radio__radio"></div>
                 Science Fiction
               </label>
               <label for="thriller" class="radio">
                 <input type="radio" name="genre" id="thriller" class="radio__input" value="6" />
                 <div class="radio__radio"></div>
                 Thriller
               </label>
            _END;
        }
        if($radio === "Fantasy")
        {
            echo <<<_END
             <label for="action" class="radio">
                    <input type="radio" name="genre" id="action" class="radio__input" value="1" />
                 <div class="radio__radio"></div>
                 Action
               </label>
               <label for="comedy" class="radio">
                 <input type="radio" name="genre" id="comedy" class="radio__input" value="2" />
                 <div class="radio__radio"></div>
                 Comedy
               </label>
               <label for="drama" class="radio">
                 <input type="radio" name="genre" id="drama" class="radio__input" value="3" />
                  <div class="radio__radio"></div>
                    Drama
                  </label>
                  <label for="fantasy" class="radio">
                    <input type="radio" name="genre" id="fantasy" class="radio__input" value="4" checked="checked" />
                    <div class="radio__radio"></div>
                    Fantasy
                  </label>
               <label for="scifi" class="radio">
                    <input type="radio" name="genre" id="scifi" class="radio__input" value="5" />
                 <div class="radio__radio"></div>
                    Science Fiction
                  </label>
                  <label for="thriller" class="radio">
                    <input type="radio" name="genre" id="thriller" class="radio__input" value="6" />
                 <div class="radio__radio"></div>
                    Thriller
                  </label>
            _END;
        }
        if($radio === "Sci-fi")
        {
            echo <<<_END
                <label for="action" class="radio">
                    <input type="radio" name="genre" id="action" class="radio__input" value="1" />
                    <div class="radio__radio"></div>
                 Action
               </label>
               <label for="comedy" class="radio">
                 <input type="radio" name="genre" id="comedy" class="radio__input" value="2" />
                 <div class="radio__radio"></div>
                 Comedy
               </label>
               <label for="drama" class="radio">
                 <input type="radio" name="genre" id="drama" class="radio__input" value="3" />
                 <div class="radio__radio"></div>
                 Drama
               </label>
               <label for="fantasy" class="radio">
                 <input type="radio" name="genre" id="fantasy" class="radio__input" value="4" />
                 <div class="radio__radio"></div>
                 Fantasy
               </label>
               <label for="scifi" class="radio">
                 <input type="radio" name="genre" id="scifi" class="radio__input" value="5" checked="checked" />
                 <div class="radio__radio"></div>
                 Science Fiction
               </label>
               <label for="thriller" class="radio">
                 <input type="radio" name="genre" id="thriller" class="radio__input" value="6" />
                 <div class="radio__radio"></div>
                 Thriller
               </label>
            _END;
        }
        if($radio === "Thriller")
        {
            echo <<<_END
             <label for="action" class="radio">
                 <input type="radio" name="genre" id="action" class="radio__input" value="1" />
                 <div class="radio__radio"></div>
                  Action
                </label>
                <label for="comedy" class="radio">
                  <input type="radio" name="genre" id="comedy" class="radio__input" value="2" />
                  <div class="radio__radio"></div>
                  Comedy
                </label>
                <label for="drama" class="radio">
                  <input type="radio" name="genre" id="drama" class="radio__input" value="3" />
                  <div class="radio__radio"></div>
                  Drama
                </label>
                <label for="fantasy" class="radio">
                  <input type="radio" name="genre" id="fantasy" class="radio__input" value="4" />
                  <div class="radio__radio"></div>
                  Fantasy
                </label>
                <label for="scifi" class="radio">
                  <input type="radio" name="genre" id="scifi" class="radio__input" value="5" />
                   <div class="radio__radio"></div>
                  Science Fiction
                </label>
                <label for="thriller" class="radio">
                  <input type="radio" name="genre" id="thriller" class="radio__input" value="6" checked="checked" />
                  <div class="radio__radio"></div>
                  Thriller
                </label>
            _END;
        }
    }
?>