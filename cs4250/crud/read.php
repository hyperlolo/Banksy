<?php
// Check existence of id parameter before processing further
if(isset($_GET["Employee_ID"]) && !empty(trim($_GET["Employee_ID"]))){
    // Include config file
    include "../../info.php";
    include "../../phpFuncs.php";
    
    // Prepare a select statement

    $pdo = new PDO('mysql:host=localhost;dbname=gdeleon;charset=utf8mb4', $username, $password);

    $sql = "SELECT * FROM Employee E WHERE E.employeeID = :employeeID;";
    
    
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":employeeID", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["Employee_ID"]);
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            if($stmt->rowCount() == 1 ){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Retrieve individual field value
                $emailAddress = $row["emailAddress"];
                $employee_ID = $row["employeeID"];
                $employee_SSN = $row["employeeSSN"];
                $firstName = $row["firstName"];
                $lastName = $row["lastName"];
                $age = $row["age"];
                $phoneNumber = $row["phoneNumber"];
                $country = $row["country"];
                $street = $row["street"];
                $city = $row["city"];
                $state = $row["state"];
                $zip = $row["zipCode"];

                $address = $street . '<br />' . $city . ', ' . $state . ' ' . $zip;

            } elseif ($stmt1->rowCount() == 1 ){
                $row = $stmt1->fetch(PDO::FETCH_ASSOC);
                
                // Retrieve individual field value
                $emailAddress = $row["emailAddress"];
                $employee_ID = $row["employeeID"];
                $employee_SSN = $row["employeeSSN"];
                $firstName = $row["firstName"];
                $lastName = $row["lastName"];
                $age = $row["age"];
                $phoneNumber = $row["phoneNumber"];
                $country = $row["country"];
                $street = $row["street"];
                $city = $row["city"];
                $state = $row["state"];
                $zip = $row["zipCode"];

                $address = $street . '<br />' . $city . ', ' . $zip;

            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    unset($stmt);
    
    // Close connection
    unset($pdo);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>View Record</h1>
                    </div>
                    <div class="form-group">
                        <label>Name:</label>
                        <p class="form-control-static"><?php echo $row["firstName"] . " " . $row["lastName"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth:</label>
                        <p class="form-control-static"><?php echo $age; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Employee ID:</label>
                        <p class="form-control-static"><?php echo $employee_ID; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Employee SSN:</label>
                        <p class="form-control-static"><?php echo $employee_SSN; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <p class="form-control-static"><?php echo $address; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Country: </label>
                        <p class="form-control-static"><?php echo $country; ?></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>