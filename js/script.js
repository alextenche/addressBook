$(document).ready(function () {

    $('#loaderImage').show();

    showContacts();

    // add contact
    $(document).on('submit', '#addContact', function () {

        $('#loaderImage').show();

        // post data from form
        $.post('add_contact.php', $(this).serialize()).done(function (data) {
            $('#addModal').foundation('reveal', 'close');
            showContacts();
        });
        return false;
    });


    // edit contact
    $(document).on('submit', '#editContact', function () {

        $('#loaderImage').show();

        // post data from form
        $.post('edit_contact.php', $(this).serialize()).done(function (data) {
            console.log(data);
            $('.editModal').foundation('reveal', 'close');
            showContacts();
        });
        return false;
    });


    // delete contact
    $(document).on('submit', '#deleteContact', function () {
        // show loader image
        $('#loaderImage').show();

        // post data from form
        $.post('delete_contact.php', $(this).serialize())
            .done(function (data) {
                console.log(data);
                showContacts();
            });
        return false;
    });


});

function showContacts() {
    setTimeout("$('#pageContent').load('contacts.php', function(){$('#loaderImage').hide()})", 1000);
}

// close modal
$(document).on('click', '.close-reveal-modal', function () {
    $('.reveal-modal').foundation('reveal', 'close');
});