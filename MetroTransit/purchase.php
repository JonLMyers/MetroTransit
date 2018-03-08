<?php 
session_start();
  if (!isset($_SESSION["id"]))
   {
      header("location: login.php");
   }
   
include 'db.php';
$userid = $_SESSION['id'];
$ticketid = urldecode($_GET["id"]);

// Validate ticket
if(empty(trim($_GET["id"]))){
    $ticket_err = "Please select a valid ticket";
    echo($ticket_err);
} else{
    $mysqli = $con;

    $users_credits = getcredits($userid, $mysqli);
    $ticket_cost = getcost($ticketid, $mysqli);

    if($users_credits < $ticket_cost){
        echo "Not enough credits";
    }
    else{
        if(!transaction($userid, $users_credits, $ticket_cost, $mysqli)){
            echo "Something went wrong.";
        }
        if(false){

        }
        else{        
            // Prepare an insert statement
            $sql = "INSERT INTO users_tickets (user_id, ticket_id) VALUES (?, ?)";
            
            if($stmt = $mysqli->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ss", $param_userid, $param_ticketid);
                
                // Set parameters
                $param_userid = $userid;
                $param_ticketid = $ticketid;
                
                // Attempt to execute the prepared statement
                if($stmt->execute()){
                    // Redirect to login page
                    header("location: account.php");
                } else{
                    echo "Something went wrong. Please try again later.";
                }
            }        
        }
    }

    // Close statement
    $stmt->close();
}

function transaction($userid, $usercredits, $ticketcost, $mysqli){
    $totalcredits = $usercredits - $ticketcost;

    $sql = "UPDATE users SET credits = ? WHERE id = ?";
    if($stmt = $mysqli->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("si", $totalcredits, $userid);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            $result = true;
        } else{
            $result = false;
        }
    }

    // Close statement
    $stmt->close();   
    return $result;
}

#Vulnerable to SQLi
function getcost($param_id, $mysqli){
    $result = "";
    $sql = "SELECT cost FROM tickets WHERE id = $param_id";
    $data = $mysqli->query($sql);
    if($data->num_rows > 0){
        while ($row = $data->fetch_assoc()){
            extract($row);
            $result = $cost;
        }
    }

    return $result; 
}

function getcredits($param_id, $mysqli){
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

    // Close statement
    $stmt->close();
    return $result;  
}
?>