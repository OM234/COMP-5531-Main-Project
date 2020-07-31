<?php

function connectDB() {
    $conn = mysqli_init();
    mysqli_ssl_set($conn,NULL,NULL, "./db/BaltimoreCyberTrustRoot.crt.pem", NULL, NULL) ;
    mysqli_real_connect($conn, 'mainproject.mysql.database.azure.com', 'cxc55311@mainproject',
        'T0Bmha0^vTKD0gThJad4&lnP#7LY%j2Y', 'cxc55311', 3306, MYSQLI_CLIENT_SSL);
    if (mysqli_connect_errno($conn)) {
        die('Failed to connect to MySQL: '.mysqli_connect_error());
    }
    return $conn;
}

function closeDB($conn) {
    mysqli_close($conn);
}


//$conn = connectDB();
//$username = "Landry_G52";
//$sql = "select UserName from user where UserName = '$username'";
//$result = $conn->query($sql);
//
//if ($result->num_rows > 0) {
//    while ($row = $result->fetch_assoc()) {
//        echo "username: " . $row["UserName"] . "\n";
//    }
//}




