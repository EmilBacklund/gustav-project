<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP crud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/bootstrap-changes.css">
</head>
<body>
    <?php require_once 'process.php'; ?>
    <div>
        <form class="container" action="process.php" method="POST">
            <div class="col-sm-6 center-block">
                <label>Genre</label>
                <input class="form-control" type="text" name="genre" value="Select Genre">
            </div>
            <div class="col-sm-6 center-block">
                <label>Movie</label>
                <input class="form-control" type="text" name="movies" value="Select Movie">
            </div>
            <div class="col-sm-6 center-block">
                <button class="btn btn-primary" type="submit" name="save">Save</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>