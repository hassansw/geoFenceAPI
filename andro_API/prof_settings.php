<?php

if( $_POST["user_id"] && $_POST["user_name"] && $_POST["user_email"] && $_POST["user_contact"] && $_POST["address"] ) {

  $user_id = $_POST["user_id"];
  $user_name = $_POST["user_name"];
  $user_email = $_POST["user_email"];
  $user_address = $_POST["user_address"];
  $user_contact = $_POST["user_contact"];

  $connection = mysqli_connect("localhost","root","","ais") or die("Error " . mysqli_error($connection));

  $sql = "Update Users set user_name = '$user_name', user_email = '$user_email', user_address = '$user_address', user_contact = '$user_contact' WHERE user_id = $user_id   ";

  $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));

      if($result->num_rows ===0 )
      {
          $json['error'] = "Error";

      }else{

          echo json_encode("");
      }

  //close the db connection
      mysqli_close($connection);
}

if( $_POST["user_id"] && $_POST["user_pass"] ) {

  $user_id = $_POST["user_id"];
  $user_pass = $_POST["user_pass"];

  $connection = mysqli_connect("localhost","wwwmmssa_hasan","Classw@007","wwwmmssa_my_samp_db") or die("Error " . mysqli_error($connection));

  $sql = "Update Users set user_pass = '$user_pass' WHERE user_id = $user_id   ";

  $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));

      if($result->num_rows ===0 )
      {
          $json['error'] = "Error";

      }else{

          echo json_encode("");
      }

  //close the db connection
      mysqli_close($connection);
}

?>
