<?php
if( $_POST["email"] || $_POST["pass"] ) {
    $m=$_POST['email'];
    $p=$_POST['pass'];

    //open connection to mysql db
    $connection = mysqli_connect("localhost","root","","ais") or die("Error " . mysqli_error($connection));

    //fetch table rows from mysql db
    $sql = "select name, email, pass, contact from users where email='$m' and pass='$p'; ";



    $result = mysqli_query($connection, $sql) or die("Error in Selecting " . mysqli_error($connection));

    if($result->num_rows ===0 )
    {
        $json['error'] = 'Acount created'; 

    }else{     
        $emparray = array();

        while($row =mysqli_fetch_assoc($result))
        {
            
            $emparray[] = $row;
        }
        echo json_encode($emparray);
    }


//close the db connection
    mysqli_close($connection); 
}

?>