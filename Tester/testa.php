<!DOCTYPE html>
<?php
require_once 'logindata.php';
try{$pdo = new PDO($attr, $user, $pass, $opts);} //Ett försök att skapa ett PDO gränssnitt med variabelvärden från logindata.php där värderna sparas
    //i våran variabel pdo.
    //Om inta databasen nås så skapar vi en fel hantering som ger oss en felmedelningsvärde samt meddelar användaren att systemet är nere(Detta är bortom användarens kapacitet att påverka)
catch(PDOExeption $e){throw new PDOException($e->getMessage(), (int)$e->getCode());}

echo "annatjobber";
echo Get_Movies($pdo);
echo "thabla bal";




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
        
        echo "<tr>";
        echo "<td>$titel</td>";
        echo "<td>$director</td>";
        echo "<td>$year</td>";
        echo "<td>$genre</td>";
        echo "<td>Edit</td>";
        echo "<input type='hidden' name='id' value='$id'>";
        echo "</tr>";
    } 
}
?>