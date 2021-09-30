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

  <?php
    $nameErr = $ratingErr = "";
    $name = $rating = $comment = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["name"])) {
        $nameErr = "Name is required";
      } else {
        $name = test_input($_POST["name"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
          $nameErr = "Only letters and white space allowed";
        }
        if (strlen($name) > 20){
          $nameErr = "Name must be under 20 characters";
        }
      }

      if (empty($_POST["comment"])) {
        $comment = "";
      } else {
        $comment = test_input($_POST["comment"]);
      }

      if (empty($_POST["rating"])) {
        $ratingErr = "Rating is required";
      } else {
        $rating = test_input($_POST["rating"]);
      }
    }

    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
  ?>



  <div class= "col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <h2>
      Input Comments:
    </h2>
    <p class="lead">A page that lets users add comments to movies.</p>
  </div>

  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?'. http_build_query($_GET); ?>" method="POST">
    <fieldset>
      <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main form-group">
        <label for="name">Name:</label>
        <span class = "error">* <?php echo $nameErr;?></span><br>
        <input type="text" class="form-control" name="name" placeholder="Mr. Anonymous">
      </div>
      <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main form-group">
        <label for="rating">Rating:</label>
        <span class = "error">* <?php echo $ratingErr;?></span><br>
        <select class="form-control" name="rating">
          <option>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
        </select>
      </div>
      <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main form-group">
        <label for="exampleTextarea">Comment:</label>
        <textarea class="form-control" name="comment" rows="3"></textarea>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </fieldset>
  </form>

   <?php
    $mid = $_GET["mid"];
    $datetime = date('Y-m-d H:i:s');
    $comment = "\"" . $comment . "\"";

    $db = new mysqli("localhost", "cs143", "", "cs143");

    if ($db->connect_error) {
      die("Database connection failed: " . $db->connect_error);
    }

    // print ($name);
    // print ($datetime);
    // print ($mid);

    // print ($rating);
    // print ($comment);

    //Add the comment into DB
    if (empty($nameErr) and empty($ratingErr)){
      $query = "INSERT INTO Review 
                VALUES ('$name', '$datetime', '$mid', '$rating', '$comment');";
      $result=$db->query($query);

      if ($result == TRUE){ //inserted properly
        // print("hi");
        // echo "hi";
          $query_sec="SELECT title
            FROM Movie M
            WHERE M.id='$mid';";

          $result_sec=$db->query($query_sec);

          if (mysqli_num_rows($result_sec) > 0){
            while ($row = $result_sec->fetch_assoc()){
              $title = $row["title"];
              echo "<a href='./show_movie.php?title=$title'>";
              echo "<h2>Click this to go back to the movie you just rated<h2><br>";
              echo "</a>";
            }
                
            $result_sec->free();
          }
      }

    }


    $db->close();

   ?> 

</body>
</html>