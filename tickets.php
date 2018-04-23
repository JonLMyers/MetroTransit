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
        <h3>Search by Ticket Price</h3> 
	    <form  method="get" action="/tickets.php"  id="searchform"> 
	      <input type="text" name="search"> 
          <input type="hidden" value ="search.php" name="file">
	      <button type="submit">Search</button> 
	    </form> 
    

<?php 
include 'settings.php';
switch($LFISetting){
    case "High":
        lfi_High($search);
        break;
    case "Low":
        lfi_Low($search);
        break;
}


function lfi_low(){
    $file = $_GET['file'];
    if(isset($file)){
        include("$file");
    }
    else{
        header('Location: http://' + $HomeSetting + '/tickets.php?file=search.php&search=*');
        exit();
    }
}

function lfi_high(){
    include 'search.php';
}

function get_tickets(){
    $curl = curl_init();
    $url = 'http://127.0.0.1:5000/tickets';

    curl_setopt($curl, CURLOPT_GET, 1);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    curl_close($curl);

    foreach ($result->items as $item) {
        $id = $item->id;
        $start = $item->start;
        $end = $item->end;
        $cost = $item->cost;
        // Prepare an insert statement
        $sql = "INSERT INTO tickets (start, end, cost, id) VALUES (?, ?, ?, ?)";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssss", $start, $end, $cost, $id);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
        else{
            echo "Something went wrong. Please try again later.";
        }

        // Close connection
        $mysqli->close();
     }

    return $result;
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