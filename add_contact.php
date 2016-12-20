<?php
include(__DIR__ . '/core/init.php');

$db = new Database;

$query = "INSERT INTO contacts 
          (first_name, last_name, email, phone, address1, address2, city, state, zipcode, contact_group, notes)
		  VALUES
		  (:first_name,:last_name,:email,:phone,:address1,:address2, :city, :state, :zipcode, :contact_group, :notes)";

$db->query($query);

// bind values
$db->bind(':first_name', $_POST['first_name']);
$db->bind(':last_name', $_POST['last_name']);
$db->bind(':email', $_POST['email']);
$db->bind(':phone', $_POST['phone']);
$db->bind(':address1', $_POST['address1']);
$db->bind(':address2', $_POST['address2']);
$db->bind(':city', $_POST['city']);
$db->bind(':state', $_POST['state']);
$db->bind(':zipcode', $_POST['zipcode']);
$db->bind(':contact_group', $_POST['contact_group']);
$db->bind(':notes', $_POST['notes']);

if ($db->execute()) {
    echo "contact was added";
} else {
    echo "could not add contact";
}
