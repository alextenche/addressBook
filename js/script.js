$(document).ready(function(){
	// show loader image
	$('#loaderImage').show();

	// show contacts on page load
	showContacts();
});

function showContacts(){
	console.log('showing contacts');
	setTimeout("$('#pageContent').load('contacts.php', function(){$('#loaderImage').hide()})",1000);
}