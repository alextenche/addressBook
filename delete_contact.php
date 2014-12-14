<?php include('core/init.php');

$db = new Database;

// run query
$db->query("DELETE FROM contacts WHERE id = :id");

// bind values
$db->bind(':id', $_POST['id']);

if($db->execute()){
	echo $_POST['id'];
	echo "Contact was deleted";
} else {
	echo "Could not delete contact";
}