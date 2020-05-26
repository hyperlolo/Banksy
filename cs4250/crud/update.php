<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Include config file
include "../../info.php";
include "../phpFuncs.php";

// Define variables and initialize with empty values
$emailAddress = $employeeID = $employeeSSN = $firstName = $lastName = $age = $phoneNum = $country = $streetAddress = $cityAddress = $stateAddress = $zipCode = "";
$emailAddress_err = $employeeID_err = $employeeSSN_err = $firstName_err = $lastName_err = $age_err = $phoneNumber_err = $country_err = $street_err = $city_err = $state_err = $zipCodeAddress_err = "";

// Processing form data when form is submitted
if (isset($_POST["employeeID"]) && !empty($_POST["employeeID"])) {
    //Employees email
    $employeeID = strval($_POST["employeeID"]);

    
    $input_emailAddress = trim($_POST["input_emailAddress"]);
    
    if (empty($input_emailAddress)) {
        $emailAddress_err = "Please enter an email address.";
    } elseif (filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        $emailAddress_err = "Please enter a valid email address.";
    } else {
        $emailAddress = $input_emailAddress;
        $emailAddress_err = "";
    }
    //Asks for employees SSN
    $input_employeeSSN = trim($_POST["input_employeeSSN"]);
    if (empty($input_employeeSSN)) {
        $employeeSSN_err = "Please enter a Social Security Number.";
    } else {
        $ssn_validate = str_replace('-', '', $input_employeeSSN);
        $pattern = '/^[0-9]{9}$/';
        if (preg_match($pattern, $ssn_validate)) {
            $employeeSSN = $input_employeeSSN;
            $employeeSSN_err = "";
        } else {
            $employeeSSN_err = "Please enter a valid Social Security Number, in this format: AAA-GG-SSSS";
        }
    }

    //Employees first name
    $input_firstName = trim($_POST["input_firstName"]);
    if (empty($input_firstName)) {
        $firstName_err = "Please enter a name.";
    } elseif (!filter_var($input_firstName, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $firstName_err = "Please enter a valid first name with only letters.";
    } else {
        $firstName = $input_firstName;
        $firstName_err = "";
    }
    //Emplyoees last name
    $input_lastName = trim($_POST["input_lastName"]);
    if (empty($input_lastName)) {
        $lastName_err = "Please enter a lastName.";
    } elseif (!filter_var($input_lastName, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $lastName_err = "Please enter a valid last name.";
    } else {
        $lastName = $input_lastName;
        $lastName_err = "";
    }

    //How old is the employee
    $input_age = trim($_POST["age"]);
    if (empty($input_age)) {
        $age_err = "Please enter your age.";
    } else {
        $age = $input_age;
        $age_err = "";
    }

    //Employees phone number
    $input_phoneNum = trim($_POST["input_phoneNum"]);
    if (empty($input_phoneNum)) {
        $phoneNumber_err = "Please enter the phone number.";
    } elseif (!sanitiPhoneNum($input_phoneNum) == true) {
        $phoneNumber_err = "Please enter a valid format.";
    } else {
        $phoneNum = $input_phoneNum;
        $phoneNumber_err = "";
    }

    $input_country = trim($_POST["input_country"]);
    if (empty($input_country)) {
        $country_err = "Please enter your country.";
    } else {
        $country = $input_country;
        $country_err = "";
    }

    // Validate address
    $input_streetAddress = trim($_POST["streetAddress"]);
    if (empty($input_streetAddress)) {
        $street_err = "Please enter a street address.";
    } else {
        $streetAddress = $input_streetAddress;
        $street_err = "";
    }

    $input_cityAddress = trim($_POST["cityAddress"]);
    if (empty($input_cityAddress)) {
        $city_err = "Please enter a city.";
    } else {
        $cityAddress = $input_cityAddress;
        $city_err = "";
    }

    $input_stateAddress = trim($_POST["stateAddress"]);
    if (empty($input_stateAddress)) {
        $state_err = "Please enter a state address.";
    } else {
        $stateAddress = $input_stateAddress;
        $state_err = "";
    }

    $input_zipCodeAddress = trim($_POST["zipCode"]);
    if (empty($input_zipCodeAddress)) {
        $zipCodeAddress_err = "Please enter a zipCode address.";
    } else {
        $zipCode = $input_zipCodeAddress;
        $zipCodeAddress_err = "";
    }
    // End of Address

    // Check input errors before inserting employee into database
    if (empty($emailAddress_err) && empty($employeeID_err) && empty($employeeSSN_err) && empty($firstName_err) && empty($lastName_err) && empty($bankID_err) && empty($age_err) && empty($phoneNumber_err) && empty($country_err) && empty($street_err) && empty($city_err) && empty($state_err) && empty($zipCodeAddress_err)) {
        // Prepare an insert statement
        $sql = "UPDATE Employee SET emailAddress = :emailAddress, employeeSSN = :employeeSSN, firstName = :firstName, lastName = :lastName, phoneNumber = :phoneNumber, age = :age, country = :country, street = :street, state= :state, city = :city, zipCode = :zipCode WHERE employeeID = :employeeID ;";
        $pdo = new PDO("mysql:host=localhost;dbname=gdeleon;charset=utf8mb4", $username, $password);
        // Disable errors
        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $param_emailAddress = $emailAddress;
            $param_employeeID = $employeeID;
            $param_employeeSSN = $employeeSSN;
            $param_firstName = $firstName;
            $param_lastName = $lastName;
            $param_age = $age;
            $param_phoneNum = $phoneNum;
            $param_country = $country;
            $param_street = $streetAddress;
            $param_city = $cityAddress;
            $param_state = $stateAddress;
            $param_zipCode = $zipCode;

            // Set parameters
            $stmt->bindParam(":employeeID", $param_employeeID);
            $stmt->bindParam(":emailAddress", $param_emailAddress);
            $stmt->bindParam(":employeeSSN", $param_employeeSSN);
            $stmt->bindParam(":firstName", $param_firstName);
            $stmt->bindParam(":lastName", $param_lastName);
            $stmt->bindParam(":age", $param_age);
            $stmt->bindParam(":phoneNumber", $param_phoneNum);
            $stmt->bindParam(":country", $param_country);
            $stmt->bindParam(":street", $param_street);
            $stmt->bindParam(":city", $param_city);
            $stmt->bindParam(":state", $param_state);
            $stmt->bindParam(":zipCode", $param_zipCode);

            // Attempt to execute the prepared statement
            if ($test = $stmt->execute()) {
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        unset($stmt);
    }
    unset($pdo);
}else{ // Populate fields
    // Check existane of id paramter before processing further
    if(isset($_GET["employeeID"]) && !empty(trim($_GET["employeeID"]))){

        //Connects to the database
        $pdo = new PDO("mysql:host=localhost;dbname=gdeleon", $username, $password);
         // Get URL parameter

        $employeeID = strval(trim($_GET["employeeID"]));

         // Prepare a select statement
         $sql = "SELECT * FROM employee WHERE employeeID = :id";

         if($stmt = $pdo->prepare($sql)){
              //Bind variables to the preparded statement as parameters
              $stmt->bindParam(":id", $param_id);

              // Set parameters employeeID
              $param_id = $employeeID;

              // Attempt to execute the prepared statement
              if($stmt->execute()){
                   if($stmt->execute()){
                        if($stmt->rowCount() == 1 ){
                             $row = $stmt->fetch(PDO::FETCH_ASSOC);

                             // Retieve individual field value
                             $emailAddress = $row["emailAddress"];
                             $employeeID = $row["employeeID"];
                             $employeeSSN = $row["employeeSSN"];
                             $firstName = $row["firstName"];
                             $lastName = $row["lastName"];
                             $age = $row["age"];
                             $phoneNum = $row["phoneNumber"];
                             $country = $row["country"];
                             $street = $row["street"];
                             $cityAddress = $row["city"];
                             $stateAddress = $row["state"];
                             $zipCode = $row["zipCode"];
                        } else{
                             // URL doesn't contain valid ID. redirect to error page
                             header("location: error.php");
                             exit();
                        }
                   } else{
                        echo "Opps! Something went wrong. Please try again later.";
                   }
              }

              // Close statement
              unset($stmt);

              // Close Connection
              unset($pdo);
         } else{
              // URL doesn't contain id parameter. Redirect to error page.
              header("location: error.php");
              exit();
         }

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper {
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
                        <h2>Update Record</h2>
                    </div>
                    <p>Please fill this form and submit to update employee record to the database.</p><br/>
                    <p><b>Employee being updated: </b><?php echo ($firstName)." "?> </p><br/>
                    <p><b>Employee ID: </b><?php echo $employeeID ?></p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER["REQUEST_URI"])); ?>" method="POST">
                        <!-- Email Address -->
                        <div class="form-group <?php echo (!empty($emailAddress_err)) ? 'has-error' : ''; ?>">
                            <label>Email Address</label>
                            <input type="text" name="input_emailAddress" class="form-control" value="<?php echo $emailAddress; ?>">
                            <span class="help-block"><?php echo $emailAddress_err; ?></span>
                        </div>
                        <!-- Employee SSN -->
                        <div class="form-group <?php echo (!empty($employeeSSN_err)) ? 'has-error' : ''; ?>">
                            <label>Employee SSN</label>
                            <input type="text" name="input_employeeSSN" class="form-control" value="<?php echo $employeeSSN; ?>">
                            <span class="help-block"><?php echo $employeeSSN_err; ?></span>
                        </div>

                        <!-- Employee Name -->
                        <div class="form-group <?php echo (!empty($firstName_err)) ? 'has-error' : ''; ?>">
                            <label>First Name</label>
                            <input type="text" name="input_firstName" class="form-control" value="<?php echo $firstName; ?>">
                            <span class="help-block"><?php echo $firstName_err; ?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($lastName_err)) ? 'has-error' : ''; ?>">
                            <label>Last Name</label>
                            <input type="text" name="input_lastName" class="form-control" value="<?php echo $lastName; ?>">
                            <span class="help-block"><?php echo $lastName_err; ?></span>
                        </div>


                        <div class="form-group <?php echo (!empty($age_err)) ? 'has-error' : ''; ?>">
                            <label>Age</label>
                            <input type="text" name="age" class="form-control" value="<?php echo $age; ?>">
                            <span class="help-block"><?php echo $age_err; ?></span>
                        </div>

                        <!-- Employee Phone Number -->
                        <div class="form-group <?php echo (!empty($phoneNumber_err)) ? 'has-error' : ''; ?>">
                            <label>Phone Number</label>
                            <input type="text" name="input_phoneNum" class="form-control" value="<?php echo $phoneNum; ?>">
                            <span class="help-block"><?php echo $phoneNumber_err; ?></span>
                        </div>

                        <!-- Employee Country-->
                        <div class="form-group <?php echo (!empty($country_err)) ? 'has-error' : ''; ?>">
                            <label>Employee Country</label>
                            <input type="text" name="input_country" class="form-control" value="<?php echo $country; ?>">
                            <span class="help-block"><?php echo $country_err; ?></span>
                        </div>

                        <!-- Employee Address -->
                        <!-- Employee Street -->
                        <div class="form-group <?php echo (!empty($street_err)) ? 'has-error' : ''; ?>">
                            <label>Employee Street Address</label>
                            <input type="text" name="streetAddress" class="form-control" value="<?php echo $street; ?>">
                            <span class="help-block"><?php echo $street_err; ?></span>
                        </div>

                        <!-- Employee city -->
                        <div class="form-group <?php echo (!empty($city_err)) ? 'has-error' : ''; ?>">
                            <label>Employee City</label>
                            <input type="text" name="cityAddress" class="form-control" value="<?php echo $cityAddress; ?>">
                            <span class="help-block"><?php echo $city_err; ?></span>
                        </div>

                        <!-- Employee State -->
                        <div class="form-group <?php echo (!empty($state_err)) ? 'has-error' : ''; ?>">
                            <label>Employee State</label>
                            <input type="text" name="stateAddress" class="form-control" value="<?php echo $stateAddress; ?>">
                            <span class="help-block"><?php echo $state_err; ?></span>
                        </div>

                        <!-- Employee Zip -->
                        <div class="form-group <?php echo (!empty($zipCodeAddress_err)) ? 'has-error' : ''; ?>">
                            <label>Employee Zip Address</label>
                            <input type="text" name="zipCode" class="form-control" value="<?php echo $zipCode; ?>">
                            <span class="help-block"><?php echo $zipCodeAddress_err; ?></span>
                        </div>
                        <input type="hidden" name="employeeID" value="<?php echo $employeeID; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>