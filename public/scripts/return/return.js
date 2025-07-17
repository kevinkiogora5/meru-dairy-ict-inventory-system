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
    var returnItem = returns.find((retn) => retn.id == id);

    // Show modal
    $('#exampleModal').modal('show');

    // Store the ID on the form for later use (in update)
    $('#return').data("return-id", id);

    // Fill in form fields
    $('#employee_id').val(returnItem.employee_id);
    $('#inventory_item_id').val(returnItem.inventory_item_id);
    $('#returned').val(returnItem.returned_condition);
    $('#comment').val(returnItem.comments);

    // Change the button text to indicate update
    $('#tuma').text('Update');
}
$(document).ready(function(){
  $('#return').on('submit',function (e) {
    e.preventDefault();
    var formData = formToJSON(this);
    var returnId = $('#return').data('return-id');
     var url = returnId ? `/return/update/${returnId}` : "/return/create";
    var sub = $('#tuma');
    sub.html("submitting...").prop('disabled',true)
    $.ajax({
        type: "POST",
            url: url,
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: function(response){
              sub.html("send message").prop('disabled',false)  
              $('#return')[0].reset();
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
    var returnId = row.data('id');
    if (!confirm(`Are you sure you want to delete this return? ${returnId}`)) return;
     console.log(returnId);
    $.ajax({
        url: '/return/delete/' + returnId,
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
}

)