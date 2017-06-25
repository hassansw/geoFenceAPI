<?php

if( $_GET["user_id"] ) {

  $user = $_GET["user_id"];

  $connection = mysqli_connect("localhost","root","","ais") or die("Error " . mysqli_error($connection));

  $state = true;
  $sql = "SELECT * FROM UserAccidents WHERE user_id = $user ";

  $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));

      if($result->num_rows ===0 )
      {
          $json['error'] = "Error";

      }else{
          $emparray = array();

          while($row =mysqli_fetch_assoc($result))
          {
              $emparray[] = $row;
          }
          header("Content-Type:Application/json");
          echo json_encode($emparray);
      }

  //close the db connection
      mysqli_close($connection);
}
?>
