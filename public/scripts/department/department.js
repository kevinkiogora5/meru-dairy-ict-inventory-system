function showToast(message, success = true) {
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

function parseErrorMessage(xhr) {
    let errormessage = 'An unexpected error occurred';

    if (xhr.responseJSON && xhr.responseJSON.error) {
        errormessage = xhr.responseJSON.error;
    } else if (xhr.responseText) {
        try {
            const parsed = JSON.parse(xhr.responseText);
            if (parsed && parsed.error) {
                errormessage = parsed.error;
            } else {
                errormessage = xhr.responseText;
            }
        } catch (e) {
            errormessage = xhr.responseText;
        }
    }

    return errormessage;
}
function openModal(id) {
    var section = departments.find((dept) => dept.id == id);

    // Show modal
    $('#exampleModal').modal('show');

    // Store the ID on the form for later use (in update)
    $('#department').data("section-id", id);

    // Fill in form fields
    $('#name').val(section.name);
    $('#description').val(section.description);

    // Change the button text to indicate update
    $('#tuma').text('Update');
}
$(document).ready(function () {
    // ✅ 1. Render Grid.js
    if (typeof departments !== 'undefined' && departments.length > 0) {
        new gridjs.Grid({
            columns: ['ID', 'Name', 'Description', 'Created On', 'Actions'],
            data: departments.map(department => [
                department.id,
                department.name,
                department.description,
                department.created_at,
                gridjs.html(`
                    <button class="btn btn-sm btn-warning" onclick="openModal(${department.id})">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${department.id}">Delete</button>
                `)
            ]),
            pagination: {
                enabled: true,
                limit: 5,
                summary: true
            },
            search: true,
            sort: true
        }).render(document.getElementById("grid-wrapper"));
    } else {
        $('#grid-wrapper').html('<p>No employee found.</p>');
    }


$(document).ready(function () {
    // Handle form submission (create department)
    $('#department').on('submit', function (e) {
    e.preventDefault();
    var formData = formToJSON(this);
    var sectionId = $('#department').data('section-id'); // ✅ Get from form
    var url = sectionId ? `/department/update/${sectionId}` : "/department/create";
    var sub = $('#tuma');
    sub.html("Submitting...").prop('disabled', true);

    $.ajax({
        type: "POST",
        url: url,
        data: JSON.stringify(formData),
        contentType: 'application/json',
        success: function (response) {
            sub.html("Save").prop('disabled', false);
            $('#department')[0].reset();
            $('#department').removeData("product-id"); // ✅ Reset to create mode
            $('#submitBtn').text('Save'); // Optional: reset button label
            showToast(response.message);
        },
        error: function (xhr) {
            console.log(xhr);
            sub.html("Save").prop('disabled', false);
            const errormessage = parseErrorMessage(xhr);
            showToast(errormessage, false);
        }
    });
});

    // Handle delete button click
$(document).on('click', '.delete-btn', function () {
        const departmentId = $(this).data('id');

        if (!confirm(`Are you sure you want to delete this department? ${departmentId}`)) return;

        $.ajax({
            url: '/department/delete/' + departmentId,
            type: 'DELETE',
            dataType: 'json',
            success: function (response) {
                showToast(response.message); // ✅ show success toast
                row.remove();
            },
            error: function (xhr) {
                console.log(xhr);
                const errormessage = parseErrorMessage(xhr);
                showToast(errormessage, false); // ✅ show error toast
            }
        });
    });
});
});
