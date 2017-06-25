<?php
if( $_GET["name"]) {
    header('Content-Type: application/json');
    $name = $_GET['name'];
    $email = $_GET['email'];
    $pass = $_GET['pass'];
    $contact = $_GET['contact'];
    $subsId = $_GET['subsId'];
    $userAddress =$_GET['address'];
    $numFam = $_GET['numFam'];
    
    $connection = mysqli_connect("localhost","root","","ais") or die("Error " . mysqli_error($connection));

    $state = true;
    $sql1 = "Select user_subsId from Users where user_subsId = $subsId ";

    $check = mysqli_query($connection, $sql1);

    if ($check->num_rows > 0){

        $json['status'] = 'exists';
        echo json_encode($json);
        

    } else {

        $state = true;
        $sql = "INSERT INTO Users ( `user_name`, `user_password`, `user_email`, `user_contact`, `user_numFamilyMembers`, `user_address`, `user_subsId`)
                VALUES ( '$name', '$pass', '$email', $contact, $numFam, '$userAddress', '$subsId');";

        $inserted = mysqli_query($connection, $sql);

        if ($inserted == 1 ) { $json['status'] = 'success'; } 
        else { $json['status'] = 'error'; }
         
        echo json_encode($json);
    }


    mysqli_close($connection);
}

?>