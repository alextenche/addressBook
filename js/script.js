$(document).ready(function(){
	// show loader image
	$('#loaderImage').show();

	// show contacts on page load
	showContacts();

	// add contact
	$(document).on('submit', '#addContact', function(){
		// show loader image
		$('#loaderImage').show();

		// post data from form
		$.post('add_contact.php', $(this).serialize()).done(function(data){
			console.log(data);
			$('#addModal').foundation('reveal','close');
			showContacts();
		});
		return false;
	});
	
	
	// edit contact
	$(document).on('submit', '#editContact', function(){
		// show loader image
		$('#loaderImage').show();

		// post data from form
		$.post('edit_contact.php', $(this).serialize()).done(function(data){
			console.log(data);
			$('.editModal').foundation('reveal','close');
			showContacts();
		});
		return false;
	});
	
});

// show contacts
function showContacts(){
	console.log('showing contacts');
	setTimeout("$('#pageContent').load('contacts.php', function(){$('#loaderImage').hide()})",1000);
}

// close modal
$(document).on('click', '.close-reveal-modal', function(){
	$('.reveal-modal').foundation('reveal', 'close');
});