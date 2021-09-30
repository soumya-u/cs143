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
      Actor Info:
    </h2>
    <p class="lead">A page that shows links to all movies that an actor has been in.</p>

    <h3>
      <?php
        $firstname = $_GET["first"];
        $lastname = $_GET["last"];

        echo "Name: $firstname $lastname<br>";
      ?>
    </h3>
    <br>
    <h2>
      Movies:
    </h2>
  </div>

  <?php
    $firstname = $_GET["first"];
    $lastname = $_GET["last"];

    $db = new mysqli("localhost", "cs143", "", "cs143");

    if ($db->connect_error) {
      die("Database connection failed: " . $db->connect_error);
    }

    //Show links to the movies that the actor was in.
    $query="SELECT DISTINCT title
            FROM Actor A, Movie M, MovieActor MA
            WHERE first='$firstname' AND last='$lastname' AND A.id = MA.aid AND MA.mid = M.id;";

    $result=$db->query($query);

    if (mysqli_num_rows($result) > 0){
      while ($row = $result->fetch_assoc()){
        $title = $row["title"];
        echo "<a href='./show_movie.php?title=$title'>";
        echo "$title"."<br>";
        echo "</a>";
      }
          
      $result->free();
    }


    $db->close();
  ?>


</body>
</html>

