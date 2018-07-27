<?php
$servername = "localhost";
$username = "bqian";
$password = "";
$db = "db_main";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";

$userid="bqian";
$password="pwd";
$email="bqian@email.com";


$pdo = new PDO("mysql:host=localhost;dbname=db_main", $username);
$sql = "delete from user_profile where user_seq_id in (select seq_id from user where user_id = :user_id and hashed_password = :password)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":user_id", $userid, PDO::PARAM_STR);
$stmt->bindParam(":password", $password, PDO::PARAM_STR);
$stmt->execute();
$stmt->closeCursor();

$sql = "delete from user where user_id = ? and hashed_password = ?";
$call = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($call, 'ss', $userid, $password);
mysqli_stmt_execute($call);

$call = mysqli_prepare($conn, 'CALL create_user(?, ?, ?, @seq_id)');
mysqli_stmt_bind_param($call, 'sss', $userid, $password, $email);
mysqli_stmt_execute($call);

var_dump($call);
$select = $conn->query('SELECT @seq_id');
echo $select;
if($select) {
    while($row = $select->fetch_assoc()) {
        var_dump($row);
        echo "seq id= " . $row["seq_id"]. "\n";
    }
}  


$sql = "select count(*) as count from user";
$result = $conn->query($sql);
if($result) {
    while($row = $result->fetch_assoc()) {
        echo "count = " . $row["count"]. "\n";
    }
}
