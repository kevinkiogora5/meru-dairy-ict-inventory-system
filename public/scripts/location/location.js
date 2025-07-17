function showToast(message, success =true){
    Toastify({
         text: message,
        duration: 3000,
        close: true,
        gravity: "top",
        position: "center",
        stopOnFocus: true,
        style: {
            background: success ? "#22c55e" : "#ef4444", // green for success, red for error
            position: "fixed",
            top: "50%",
            left: "50%",
            transform: "translate(-50%, -50%)",
            zIndex: 9999
        }
    }).showToast();
}
function openModal(id) {
    var location = locations.find((loc) => loc.id == id);

    // Show modal
    $('#exampleModal').modal('show');

    // Store the ID on the form for later use (in update)
    $('#location').data("location-id", id);

    // Fill in form fields
    $('#county').val(location.county);
    $('#office').val(location.office);

    // Change the button text to indicate update
    $('#tuma').text('Update');
}
$(document).ready(function(){
  $('#location').on('submit',function (e) {
    e.preventDefault();
    var formData = formToJSON(this);
    var locationId = $('#location').data('location-id');
    var url = locationId ? `/location/update/${locationId}` : "/location/create";
    var sub = $('#tuma');
    sub.html("submitting...").prop('disabled',true)
       console.log("Submitting form payload:", formData);
    $.ajax({
        type: "POST",
            url: url,
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: function(response){
              sub.html("send message").prop('disabled',false)
              $('#location')[0].reset();
              showToast(response.message)
            },
            error: function (xhr) {
    console.log(xhr);
    sub.html('send').prop('disabled', false);

    let errormessage = 'An unexpected error occurred';

    // Try parsing JSON safely
    if (xhr.responseJSON && xhr.responseJSON.error) {
        errormessage = xhr.responseJSON.error;
    } else if (xhr.responseText) {
        try {
            const parsed = JSON.parse(xhr.responseText);
            if (parsed && parsed.error) {
                errormessage = parsed.error;
            } else {
                errormessage = xhr.responseText; // fallback to raw text
            }
        } catch (e) {
            errormessage = xhr.responseText; // if not JSON, show raw response
        }
    }

    alert(errormessage);
}


    })
  })
  $('.delete-btn').on('click', function() {

    var row = $(this).closest('tr');
    var locationId = row.data('id');
    if (!confirm(`Are you sure you want to delete this location? ${locationId}`)) return;
    console.log(locationId);
    $.ajax({
        url: '/location/delete/' + locationId,
        type: 'DELETE',
        success: function(response) {
            showToast(response.message);
            row.remove();
        },
        error: function(xhr) {
            console.log(xhr)
            alert(xhr.responseJSON.error);
        }
    });
});
})
    