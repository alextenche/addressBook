<?php
include(__DIR__ . '/core/init.php');

$db = new Database;

$db->query("DELETE FROM contacts WHERE id = :id");

$db->bind(':id', $_POST['id']);

if ($db->execute()) {
    echo $_POST['id'];
    echo "Contact was deleted";
} else {
    echo "Could not delete contact";
}
