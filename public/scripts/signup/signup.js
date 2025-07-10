$(document).ready(function () {
    $('#signup').on('submit', function (e) {
        e.preventDefault();
        const form = $(this)[0];

        const submit = $('#ingia');
        submit.html("Sending...").prop('disabled', true);

        $.ajax({
            type: "POST",
            url: '/create',
            data: formToJSON(form), // Convert form data to JSON
            success: function (response) {
                submit.html("Add").prop('disabled', false);
                $('#signup')[0].reset();
                alert(response.message); // Show success message
                window.location.href = '/login';
            },

            error: function (xhr) {
    submit.html("Add").prop('disabled', false);

    let message = 'An unexpected error occurred. Please try again later.';

    if (xhr.responseJSON && xhr.responseJSON.error) {
        const errorData = xhr.responseJSON.error;

        if (Array.isArray(errorData)) {
            // If errors are in array format
            message = errorData.join('\n');
        } else if (typeof errorData === 'object') {
            // If errors are in { field: message } format
            message = Object.values(errorData).join('\n');
        } else if (typeof errorData === 'string') {
            // Single error message
            message = errorData;
        }
    }

    Toastify({
        text: message,
        duration: 5000,
        close: true,
        gravity: "top",
        position: "right",
        stopOnFocus: true,
        style: { background: "#ef4444" }
    }).showToast();
}

        });
    });
});
// This script handles the signup form submission
// It sends the form data to the server and handles the response