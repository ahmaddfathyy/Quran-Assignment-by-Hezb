<?php

// Connection to the database
$host = 'localhost';
$dbname = 'your_db_name';
$username = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }

// Check if the form is submitted
if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $hezb = $_POST['hezb'];

    //get the last assigned hezb
    $lastHezb = $conn->query("SELECT MAX(toHezb) FROM hezb")->fetchColumn();
    //calculate the next assigned hezb
    $fromHezb = $lastHezb+1;
    $toHezb = $fromHezb+$hezb-1;

    // Insert data into the database
    $sql = "INSERT INTO hezb (name, fromHezb, toHezb) VALUES (:name, :fromHezb, :toHezb)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':fromHezb', $fromHezb);
    $stmt->bindParam(':toHezb', $toHezb);
    $stmt->execute();

    //retrive the assigned hezb range
    $sql = "SELECT * FROM hezb WHERE name = :name ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $result = $stmt->fetch();

    echo "You are assigned Hezb(s)  ".$result['fromHezb']." to ".$result['toHezb'];
}

?>
