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
      <table style='width: 100%'>
        <tr>
          <th>Title</th>
          <th>Director</th>
          <th>Year</th>
          <th>Genre</th>
          <th>Update</th>
        </tr>
        <tr>
          <td>Title</td>
          <td>Director</td>
          <td>Year</td>
          <td>Genre</td>
          <td>Edit</td>
        </tr>
      </table>
    </div>
  </div>
</div>
<script src='js/confirm-btn.js'></script>
</body>
?>