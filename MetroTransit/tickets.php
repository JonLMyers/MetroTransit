<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <title>Tickets</title>
     
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
      </ul>

      <ul id="nav-mobile" class="side-nav">
        <li><a href="tickets.php">Tickets</a></li>
        <li><a href="account.php">Account</a></li>
      </ul>
      <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
</nav>
    <!-- container -->
    <div class="container">
  
        <div class="page-header">
            <h1>Available Tickets</h1>
        </div>
     
<?php
    // include database connection
    include 'db.php';
    include 'settings.php';
    // delete message prompt will be here
 
    // select all data
    $query = "SELECT * FROM tickets ORDER BY id ASC";
    $result = $con->query($query);
 
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
                echo "<th>Action</th>";
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
                echo "<td>{$id}</td>";
                echo "<td>{$start}</td>";
                echo "<td>{$end}</td>";
                echo "<td>&#36;{$cost}</td>";
                echo "<td>";
                    // Buy a ticket
                    echo "<a href='purchase.php?id={$id}' class='btn btn-info m-r-1em'>Buy</a>";
                echo "</td>";
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