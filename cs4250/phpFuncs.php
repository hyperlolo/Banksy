<?php
include "../info.php";
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


/* This is our check for making sure that the user does not upload empty strings */
function check() {
    $errorResult = array();
    $command_Valid_Flag = false;

    $uString = $_POST["userQuery"];

    // Only these valid commands will be accepted from the user
    if(empty($_POST["userQuery"])) {    // Query is empty
        $errorResult["userQuery"] = "Your query search was empty, please click the home button to go back.";
    } else {                            //Query is sanitized
        $pieces = explode(" ", $_POST["userQuery"]);
        $part = strtolower($pieces[0]);
    }
    return $errorResult;    
}



// Prints Query
function printTable($result){
    // Gets column Field names of PDO container
    for ($i = 0; $i < $result->columnCount(); $i++) {
        $col = $result->getColumnMeta($i);
        $columns[] = $col['name'];
    }

    // Start Table creation.
    echo '<table class="blueTable"><tr>';

    // Displays the column field names in th html tag.
    echo '<thead>';
    foreach($columns as $fieldName){
        echo '<th>' .$fieldName .'</th>';
    }
    echo '</tr></thead><tbody>';
    
        // Prints out all data field values table row (td html tag).
        foreach($result as $row){
            echo '<tr>';
            foreach( $row as $fieldData){
                echo '<td>' .$fieldData .'</td>';
            }
        }

        echo '</tbody></table>';
        // echo "TABLE CREATED";

    return;
}


    // Ad HOC Query protects against sql injections  
    function adhocquery (){

        $servername = "hopper.csustan.edu";
        $dbname = "gdeleon";
        $newStr = $_POST['userQuery'];
 
    
    
        // Create connection to db
        // $conn = new mysqli($servername, $username, $password, $dbname);
        $conn = new PDO('mysql:host=localhost; dbname=gdeleon; charset=utf8mb4', $username, $password);
        
        // Check connection
        if ($conn->connect_error) {
            die("<h4 class='text-center'>Database Connection: Failed</h4>");
        } else {
          echo "<h4 class='text-center'>Database Connection: Succesful </h4>";
        } 

        $result = $conn->query($newStr);

        if ($result->num_rows > 0) {        //Header
          echo "<br><div style='display:block;'><table class='blueTable'><thead><tr>";
          while($row = $result->fetch_assoc()) {
            foreach($row as $x => $x_value) {
              echo "<td>".$x."</td>";
            }
            echo"</tr></thead><tbody><tr>";
            foreach($row as $x => $x_value) {
              echo "<td>".$x_value."</td>";
            }
            echo "<tr>";
            break;
          }

        }
        
        if ($result->num_rows > 0) {
          // output data of each row (Tuples)
          while($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach($row as $x => $x_value) {
              echo "<td>".$x_value."</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table></div>";
      } else {
        echo "<h4 class='text-center'>Invalid query</h4>";
      }
        // Close connection.
        $result = NULL;
        $conn = close();
    }

    /* Runs user Query and protects against sql injections */
    function rUserQuery() {




        // Admin server configure woe's, admin should fix this.
        include "../info.php";

            try{
                // Creating PDO call and sanitize it.
                $conn = new PDO('mysql:host=localhost;dbname=gdeleon;charset=utf8mb4', $username, $password);
                $newStr = $_POST['userQuery'];
                
                // Check connection
                if ($conn->connect_error) {
                    die("<h4 class='text-center'>Database Connection: Failed </h4>");
                } else {
                echo "<h4 class='text-center'>Database Connection: Succesful </h4>";
                } 
                // CHECKS FOR SQL INJECTIONS by greping the user input for the no-no words
                if (preg_match("/DROP/", $newStr) || preg_match("/drop/", $newStr)){
                    die("<br><h3>Query: '$newStr' contains the following banned keyword: 'DROP'. Try Again. </h3>");
                }
                if (preg_match("/ALTER/", $newStr) || preg_match("/alter/", $newStr)){
                    die("<br><h3>Query: '$newStr' contains the following banned keyword: 'ALTER'. Try Again. </h3>");
                }
                if (preg_match("/DELETE/", $newStr) || preg_match("/delete/", $newStr)){
                    die("<br><h3>Query: '$newStr' contains the following banned keyword: 'ALTER'. Try Again. </h3>");
                }
                
                $newStr = htmlspecialchars($newStr);

                $result = $conn->query( $newStr, PDO::FETCH_ASSOC);
                
                $numRow = $result->rowCount();

                if ($numRow > 0){
                    echo '<h2>Number of Rows effected:' . $numRow.'</h2>';
                    printTable($result);
                }elseif ($numRow == 0){
                    echo '<h2>No Rows effected.</h2>';
                }
                else{
                    echo '<h2>Invalid</h2>';
                }

                // Close connection.
                $result = NULL;
                $conn = close();

            } catch(PDOException $e){
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            
        return $erroresult;
    }


//This is another check we have which sanitizes the phone numbers that a user inputs into our database
function sanitiPhoneNum($phoneNum) {
    //This part is to allow the user to use ".", "-", and "+" within the inputted phone number
    $filterNum = filter_var($phoneNum, FILTER_SANITIZE_NUMBER_INT);
    //This will remove the "-" in the phone number
    $legitPhone = str_replace("-","", $filterNum);
    //Finally the length of the phone number will be checked to make sure it is valid
    if(strlen($legitPhone)<10 || strlen($legitPhone)> 14) {
        return false;
    } else {
        return true;
    }

}

//This part of the code is used specifically for the CRUD functionality, this will format the phone numbers in our database
function formatPhone($phoneNumber) {
    $cleaned = preg_replace('/[^[:digit:]]/', '', $phoneNumber);
    preg_match('/(\d{3})(\d{3})(\d{4})/', $cleaned, $matches);
    return "({$matches[1]}) {$matches[2]}-{$matches[3]}";
}

//Employees ID random gen
function random_str(
    int $length = 16,
    string $keyspace = '0123456789'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

?>




