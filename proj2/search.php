<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>CS143 Project 2</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/project2.css" rel="stylesheet">

      <ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="./index.php">Home</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="./search.php?searchInput=soumya+uppuganti">Search</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="./show_actor.php">Show Actor Info</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="./show_movie.php">Show Movie Info</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" href="./input.php">Input</a>
  </li>
</ul>

<body>

  <div class= "col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h2>
      Search page:
    </h2>
    <p class="lead">A page that lets users search for an actor/actress/movie through a keyword search interface</p>
  </div>


  <div class="container">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="GET">
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main form-group">
          <label for="search">Enter in the actor/actress/movie name:</label>
          <input type="text" class="form-control" placeholder="Enter name or title" name="searchInput">
          <br>
          </br>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
  </div>

<?php
  
  $db = new mysqli("localhost", "cs143", "", "cs143");

  if ($db->connect_error) {
    die("Database connection failed: " . $db->connect_error);
  }

  $names = explode(' ', $_GET["searchInput"]);


  //ACTORS
  $addedActors = "";
  for ($i = 0; $i < count($names); $i++) {
    if ($i > 0){
      $addedActors = $addedActors . " AND ";
      // echo $names[$i];
    }
    $addedActors = $addedActors . "(first LIKE \"%" . $names[$i] . "%\" OR last LIKE \"%" . $names[$i] . "%\")";
  }


  $query="SELECT first, last, dob
          FROM Actor
          WHERE $addedActors
          ORDER BY last ASC, first ASC;";
  $result=$db->query($query);
  echo "<h2> Actors/Actresses: </h2>"; 
  if (mysqli_num_rows($result) > 0){
    while ($row = $result->fetch_assoc()){
        $first = $row["first"];
        $last = $row["last"];
        echo "<a href='./show_actor.php?first=$first&last=$last'>";
        echo $row["first"]." ".$row["last"].", ".$row["dob"]."<br>";
        echo "</a>";
    }
        
    $result->free();
  }

  echo "<h2> Movies: </h2>"; 
  $addedMovies = "";
  for ($i = 0; $i < count($names); $i++) {
    if ($i > 0){
      $addedMovies = $addedMovies . " AND ";
      // echo $names[$i];
    }
    $addedMovies = $addedMovies . "title LIKE \"%" . $names[$i] . "%\"";
  }


  $query="SELECT title, year
          FROM Movie
          WHERE $addedMovies
          ORDER BY title ASC, year DESC;";
  $result=$db->query($query);

  if (mysqli_num_rows($result) > 0){
    while ($row = $result->fetch_assoc()){
        $title = $row["title"];
        $year = $row["year"];
        echo "<a href='./show_movie.php?title=$title'>";
        echo $row["title"].", ".$row["year"]."<br>";
        echo "</a>";
    }
        
    $result->free();
  }


  $db->close();

?>

</body>
</html>

