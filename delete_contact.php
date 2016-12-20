<?php
include(__DIR__ . '/core/init.php');

$db = new Database;

$db->query("DELETE FROM contacts WHERE id = :id");

$db->bind(':id', $_POST['id']);

if ($db->execute()) {
    echo $_POST['id'];
    echo "contact was deleted";
} else {
    echo "could not delete contact";
}
