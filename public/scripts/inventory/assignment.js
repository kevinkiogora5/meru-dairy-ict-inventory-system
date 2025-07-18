function showToast(message, success = true) {
    Toastify({
        text: message,
        duration: 3000,
        close: true,
        gravity: "top",
        position: "center",
        stopOnFocus: true,
        style: {
            background: success ? "#22c55e" : "#ef4444",
            position: "fixed",
            top: "50%",
            left: "50%",
            transform: "translate(-50%, -50%)",
            zIndex: 9999
        }
    }).showToast();
}

function openModal(id) {
    const assigne = assignment.find((assign) => assign.id == id);
    if (!assigne) {
        showToast("Assignment not found", false);
        return;
    }

    $('#exampleModal').modal('show');
    $('#assignment').data("assignment-id", id);

    // Populate fields
    $('#employee_id').val(assigne.employee_id);
    $('#location_id').val(assigne.location_id);
    $('#notes').val(assigne.notes);

    // Disable inventory dropdown and set selected item
    const invSelect = $('#inventory_item_id');
    invSelect.empty().append(`<option value="${assigne.inventory_item_id}">${assigne.items_name}</option>`);
    invSelect.prop('disabled', true).val(assigne.inventory_item_id);

    $('#tuma').text('Update');
}

$(document).ready(function () {
    // ⬇️ Load unassigned items when modal is opened for new assignment
    $('#exampleModal').on('show.bs.modal', function () {
        const assignmentId = $('#assignment').data("assignment-id");
        const invSelect = $('#inventory_item_id');

        if (!assignmentId) {
            invSelect.empty().append('<option>Loading...</option>').prop('disabled', true);

            $.ajax({
                url: '/inventory/unassigned-items',
                method: 'GET',
                success: function (items) {
                    invSelect.empty().append('<option value="">-- Select Inventory Item --</option>');

                    if (items.length === 0) {
                        invSelect.append('<option disabled>No available items</option>');
                    } else {
                        items.forEach(item => {
                            invSelect.append(`<option value="${item.id}">${item.name} (${item.serial_number})</option>`);
                        });
                    }

                    invSelect.prop('disabled', false);
                },
                error: function (xhr) {
                    console.error(xhr);
                    invSelect.empty().append('<option disabled>Error loading items</option>');
                    showToast('Failed to load unassigned items.', false);
                }
            });
        }
    });

    $('#assignment').on('submit', function (e) {
        e.preventDefault();

        const formData = formToJSON(this);
        const assignmentId = $('#assignment').data('assignment-id');
        const url = assignmentId ? `/assignment/update/${assignmentId}` : "/assignment/create";
        const sub = $('#tuma');
        const originalText = sub.text();

        sub.html("Submitting...").prop('disabled', true);

        $.ajax({
            type: "POST",
            url: url,
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: function (response) {
                sub.html(originalText).prop('disabled', false);
                $('#assignment')[0].reset();
                $('#inventory_item_id').empty().append('<option value="">-- Select Inventory Item --</option>').prop('disabled', true);
                $('#exampleModal').modal('hide');
                $('#assignment').removeData("assignment-id");
                $('#tuma').text('Save');
                showToast(response.message);
            },
            error: function (xhr) {
                console.log(xhr);
                sub.html(originalText).prop('disabled', false);

                let errormessage = 'An unexpected error occurred';

                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errormessage = xhr.responseJSON.error;
                } else if (xhr.responseText) {
                    try {
                        const parsed = JSON.parse(xhr.responseText);
                        errormessage = parsed.error || xhr.responseText;
                    } catch (e) {
                        errormessage = xhr.responseText;
                    }
                }

                showToast(errormessage, false);
            }
        });
    });

    $('.delete-btn').on('click', function () {
        const row = $(this).closest('tr');
        const assignmentId = row.data('id');

        if (!confirm(`Are you sure you want to delete this assignment? ${assignmentId}`)) return;

        $.ajax({
            url: '/assignment/delete/' + assignmentId,
            type: 'DELETE',
            success: function (response) {
                showToast(response.message);
                row.remove();
            },
            error: function (xhr) {
                console.log(xhr);
                const error = xhr.responseJSON?.error || 'Delete failed.';
                showToast(error, false);
            }
        });
    });
});
