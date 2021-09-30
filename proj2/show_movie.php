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
      Movie Info:
    </h2>
    <p class="lead">A page that shows links to all actors/actresses in the movie, the average score of the movie, and user comments.</p>
    <h3>
      <?php
        $title = $_GET["title"];
        echo "Title: $title<br>";
      ?>
    </h3>
    <br>
    </br>
    <h2>
      Actors/Actresses and Roles:
    </h2>
  </div>


  <?php
    $title = $_GET["title"];


    $db = new mysqli("localhost", "cs143", "", "cs143");

    if ($db->connect_error) {
      die("Database connection failed: " . $db->connect_error);
    }

    //Show links to the actors/actresses that were in this movie.
    $query="SELECT DISTINCT last, first, role
            FROM Actor A, Movie M, MovieActor MA
            WHERE (M.title='$title' AND M.id = MA.mid AND MA.aid = A.id);";

    $result=$db->query($query);

    if (mysqli_num_rows($result) > 0){
      while ($row = $result->fetch_assoc()){
        $first = $row["first"];
        $last = $row["last"];
        echo "<a href='./show_actor.php?first=$first&last=$last'>";
        echo $row["first"]." ".$row["last"].", ".$row["role"]."<br>";
        echo "</a>";
      }
          
      $result->free();
    }

    //Show the average score of the movie based on user feedback.
    echo "<br>";
    echo "<h2> Average Score of Movie: </h2>";

    $query="SELECT AVG(R.rating) AS avg
            FROM Movie M, Review R
            WHERE title='$title' AND M.id = R.mid;";

    $result=$db->query($query);

    if (mysqli_num_rows($result) > 0){
      while ($row = $result->fetch_assoc()){
        $rating = $row["avg"];
        echo $row["avg"]."<br>";
        // echo "<h2> $row["avg"] </h2>";
      }
          
      $result->free();
    }

    //Show all user comments.
    echo "<br>";
    echo "<h2> Comments: </h2>";

    $query = "SELECT *
              FROM Movie M, Review R
              WHERE title='$title' 
              AND M.id = R.mid;";
    $result=$db->query($query);

    if (mysqli_num_rows($result) > 0){
      while ($row = $result->fetch_assoc()){
        $name = $row["name"];
        $time = $row["time"];
        $rating = $row["rating"];
        $comment = $row["comment"];
        echo $name." at " .$time.": " . $rating . "/5, " . $comment."<br>";
      }
          
      $result->free();
    }


    //Get MID
    $query="SELECT id
            FROM Movie M
            WHERE M.title='$title';";
    $result=$db->query($query);

    if (mysqli_num_rows($result) > 0){
      while ($row = $result->fetch_assoc()){
        $mid = $row["id"];
      }
          
      $result->free();
    }

    echo "<a href='./input.php?mid=$mid'>";
    echo "<h2>Add a comment about this movie<h2><br>";
    echo "</a>";



    $db->close();
  ?>


  </body>
</html>

