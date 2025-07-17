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
    var category = categories.find((cat) => cat.id == id);

    // Show modal
    $('#exampleModal').modal('show');

    // Store the ID on the form for later use (in update)
    $('#category').data("category-id", id);

    // Fill in form fields
    $('#type').val(category.type);
    $('#description').val(category.description);

    // Change the button text to indicate update
    $('#tuma').text('Update');
}
$(document).ready(function(){
  $('#category').on('submit',function (e) {
    e.preventDefault();
    var formData = formToJSON(this);
    var categoryId = $('#category').data('category-id');
     var url = categoryId ? `/category/update/${categoryId}` : "/category/create";
    var sub = $('#tuma');
    sub.html("submitting...").prop('disabled',true)
    $.ajax({
        type: "POST",
            url: url,
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: function(response){
              sub.html("send message").prop('disabled',false)  
              $('#category')[0].reset();
              showToast(response.message);
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
    var categoryId = row.data('id');
    if (!confirm(`Are you sure you want to delete this category? ${categoryId}`)) return;
     console.log(categoryId);
    $.ajax({
        url: '/category/delete/' + categoryId,
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