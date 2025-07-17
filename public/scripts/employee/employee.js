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
    var employee = employees.find((emp) => emp.id == id);

    // Show modal
    $('#exampleModal').modal('show');

    // Store the ID on the form for later use (in update)
    $('#employee').data("employee-id", id);

    // Fill in form fields
    $('#first_name').val(employee.first_name);
    $('#last_name').val(employee.last_name);
    $('#email').val(employee.email);
    $('#phone').val(employee.phone);
    $('#department_id').val(employee.department_id);

    // Change the button text to indicate update
    $('#tuma').text('Update');
}
$(document).ready(function(){
  $('#employee').on('submit',function (e) {
    e.preventDefault();
    var formData = formToJSON(this);
    var employeeId = $('#employee').data('employee-id');
    var url = employeeId ? `/employee/update/${employeeId}` : "/employee/create";
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
              $('#employee')[0].reset();
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
    var employeeId = row.data('id');
    if (!confirm(`Are you sure you want to delete this employee? ${employeeId}`)) return;
     console.log(employeeId);
    $.ajax({
        url: '/employee/delete/' + employeeId,
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