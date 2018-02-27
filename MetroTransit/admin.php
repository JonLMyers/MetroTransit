<?php
session_start();
if (!isset($_SESSION["id"]))
 {
    header("location: login.php");
 }

if($_COOKIE['admin'] != "true"){
    header("location: login.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'db.php';
    $mysqli = $con;
    $userid = $_SESSION['id'];
    $ticketid = urldecode($_GET["id"]);

    $param_id = $_POST["id"];
    $param_start = $_POST["start"];
    $param_end = $_POST["end"];
    $param_cost = $_POST["cost"]; 

    $sql = "INSERT INTO tickets (id, start, end, cost) VALUES (?, ?, ?, ?)";
                
    if($stmt = $mysqli->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ssss", $param_id, $param_start, $param_end, $param_cost);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Redirect to login page
            header("location: tickets.php");
        } else{
            echo "Something went wrong. Please try again later.";
        }
    }

    // Close statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>


  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <meta charset="UTF-8">
    <title>Admin Page</title>

    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<nav class="white" role="navigation">
    <div class="nav-wrapper container">
      <a id="logo-container" href="/" class="brand-logo">MetroTransit</a>
      <ul class="right hide-on-med-and-down">
          <li><a href="tickets.php">Tickets</a></li>
          <li><a href="account.php">Account</a></li>
      </ul>

      <ul id="nav-mobile" class="side-nav">
        <li><a href="tickets.php">Tickets</a></li>
        <li><a href="account.php">Account</a></li>
      </ul>
      <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
  </nav>
  
    <div class="wrapper">
        <h2>Create Ticket</h2>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <div class="form-group">
                <label>ID</label>
                <input type="text" name="id"class="form-control" value="<?php echo $param_id; ?>">
                <span class="help-block"></span>
            </div>    
            <div class="form-group">
                <label>Start</label>
                <input type="text" name="start" class="form-control" value="<?php echo $param_start; ?>">
                <span class="help-block"></span>
            </div>
            <div class="form-group">
                <label>End</label>
                <input type="text" name="end" class="form-control" value="<?php echo $param_end; ?>">
                <span class="help-block"></span>
            </div>
            <div class="form-group">
                <label>Cost ($######.##)</label>
                <input type="text" name="cost" class="form-control" value="<?php echo $param_cost; ?>">
                <span class="help-block"></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
    </div>    
</body>
</html>