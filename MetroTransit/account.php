<?php
// Initialize the session
session_start();
  if (!isset($_SESSION["username"]))
   {
      header("location: login.php");
   }
 
function getcredits(){
    include 'db.php';
    $mysqli = $con;
    $param_id = $_SESSION['id'];
    $result = "";
    $sql = "SELECT id FROM users WHERE id = ?";
    if($stmt = $mysqli->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // store result
            $stmt->store_result();
            
            if($stmt->num_rows == 0){
                $id_err = "User does not exist.";
            } else{
                $query = "SELECT credits FROM users WHERE id = $param_id";
                $data = $mysqli->query($query);
                while ($row = $data->fetch_assoc()){
                    extract($row);
                    $result = $credits;
                }              
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    return $result;
}

?>
<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <title>Account</title>
     
    <!-- Latest compiled and minified Bootstrap CSS -->

         
    <!-- custom css -->
    <style>
    .m-r-1em{ margin-right:1em; }
    .m-b-1em{ margin-bottom:1em; }
    .m-l-1em{ margin-left:1em; }
    .mt0{ margin-top:0; }
    </style>
 
</head>

<body>
<nav class="white" role="navigation">
    <div class="nav-wrapper container">
      <a id="logo-container" href="/" class="brand-logo">MetroTransit</a>
      <ul class="right hide-on-med-and-down">
          <li><a href="tickets.php">Tickets</a></li>
          <li><a href="account.php">Account</a></li>
          <li><a href="logout.php">Logout</a></li>
      </ul>

      <ul id="nav-mobile" class="side-nav">
        <li><a href="tickets.php">Tickets</a></li>
        <li><a href="account.php">Account</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
      <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
</nav>
    <!-- container -->
    <div class="container">
  
        <div class="page-header">
            <h1>Account Summary</h1>
            <h4>Credits: <?php echo(getcredits()); ?></h4>
        </div>

     
<?php
    // include database connection
    include 'db.php';
    $mysqli = $con;
    
    // select all data
    $query = "SELECT *  FROM tickets inner join users_tickets ON tickets.id = users_tickets.ticket_id inner join users on users.id = ?";
    
    if($stmt = $mysqli->prepare($query)){
        
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_userid);
        
        // Set parameters
        $param_userid = trim($_SESSION["id"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // store result
            //$stmt->store_result();
            $result = $stmt->get_result();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    // this is how to get number of rows returned
    $num = $result->num_rows;
 
    //check if more than 0 record found
    if($num > 0){
 
        echo "<table class='table table-hover table-responsive table-bordered'>";//start table
 
            //creating our table heading
            echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Start</th>";
                echo "<th>Destination</th>";
                echo "<th>Price</th>";
            echo "</tr>";
     
        // retrieve our table contents
        // fetch() is faster than fetchAll()
        // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
        while ($row = $result->fetch_assoc()){
            // extract row
            // this will make $row['firstname'] to
            // just $firstname only
            extract($row);
     
            // creating new table row per record
            echo "<tr>";
                echo "<td>{$ticket_id}</td>";
                echo "<td>{$start}</td>";
                echo "<td>{$end}</td>";
                echo "<td>{$cost}</td>";
            echo "</tr>";
        }
 
        // end table
        echo "</table>";
     
    }
 
    // if no records found
    else{
        echo "<div class='alert alert-danger'>No records found.</div>";
    }
?>
         
    </div> <!-- end .container -->
     
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
   
<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 
<!-- confirm delete record will be here -->
 
</body>
</html>