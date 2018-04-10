<?php
$search = $_GET['search'];
search($search);

function search($search){
    include 'settings.php';
    switch($SearchSetting){
        case "High":
            searchHigh($search);
            break;
        case "Low":
            searchLow($search);
            break;
    }

    return true;
}

function searchLow($search){
    include 'db.php';
    include 'settings.php';
    $mysqli = $con;
    if($search == '*' || $search == ''){
        $result = $mysqli->query("SELECT * FROM tickets ORDER BY id ASC");
    }
    else{
        $result = $mysqli->query("SELECT * FROM tickets where cost = '$search' ORDER BY id ASC");
    }
    
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
        while ($row = mysqli_fetch_assoc($result)){
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
    return true;
}

function searchHigh($search){
    include 'db.php';
    include 'settings.php';
    $mysqli = $con;
    // select all data
    if($search == '*' || $search == ''){
        $query = "SELECT * FROM tickets ORDER BY id ASC";
        $stmt = $mysqli->prepare($query);
        #$stmt->bind_param("s", $search);
        $stmt->execute();
        $stmt->bind_result($id, $start, $end, $cost);
        $stmt->store_result();
    }
    else{
        $stmt = $mysqli->prepare("SELECT * FROM tickets where cost = ? ORDER BY id ASC");
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $stmt->bind_result($id, $start, $end, $cost);
        $stmt->store_result();
    }
    
    $num = $stmt->num_rows;
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
        while ($stmt->fetch()){
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
    $stmt->close();
    return true;
}
?>