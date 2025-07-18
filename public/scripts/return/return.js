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

function openModal(id) {
    var returnItem = returns.find((retn) => retn.id == id);

    if (!returnItem) {
        showToast("Return not found", false);
        return;
    }

    // Show modal
    $('#exampleModal').modal('show');

    // Store the ID on the form for later use (in update)
    $('#return').data("return-id", id);

    // Fill in form fields
    $('#employee_id').val(returnItem.employee_id).trigger('change'); // trigger change to load items
    setTimeout(() => {
        $('#inventory_item_id').val(returnItem.inventory_item_id);
    }, 200); // delay to ensure items load before selection

    $('#returned').val(returnItem.returned_condition);
    $('#comment').val(returnItem.comments);

    // Change the button text to indicate update
    $('#tuma').text('Update');
}

$(document).ready(function () {

    // 🔄 When employee is selected, load assigned items
    $('#employee_id').on('change', function () {
        const employeeId = $(this).val();
        const inventorySelect = $('#inventory_item_id');

        inventorySelect.empty().prop('disabled', true).append('<option value="">Loading...</option>');

        if (!employeeId) {
            inventorySelect.html('<option value="">Select employee first</option>');
            return;
        }

        $.ajax({
            url: `/return/assignments-by-employee?employee_id=${employeeId}`,
            method: 'GET',
            success: function (assignments) {
                inventorySelect.empty();

                if (assignments.length === 0) {
                    inventorySelect.append('<option value="">No items assigned</option>');
                } else {
                    inventorySelect.append('<option value="">Select an item</option>');
                    assignments.forEach(a => {
                        inventorySelect.append(`<option value="${a.item_id}">${a.name} (${a.serial_number})</option>`);
                    });
                }

                inventorySelect.prop('disabled', false);
            },
            error: function (xhr) {
                inventorySelect.empty().append('<option value="">Failed to load item</option>');
                showToast('Could not load assigned items', false);
                console.error(xhr);
            }
        });
    });

    // ✅ Form submission
    $('#return').on('submit', function (e) {
        e.preventDefault();

        const formData = formToJSON(this);
        const returnId = $('#return').data('return-id');
        const url = returnId ? `/return/update/${returnId}` : "/return/create";
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
                $('#return')[0].reset();
                $('#inventory_item_id').empty().append('<option value="">Select employee first</option>').prop('disabled', true);
                $('#exampleModal').modal('hide');
                $('#return').removeData("return-id");
                $('#tuma').text('Send'); // Reset button
                showToast(response.message);
            },
            error: function (xhr) {
                console.log(xhr);
                sub.html(originalText).prop('disabled', false);

                let errorMessage = 'An unexpected error occurred';

                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                } else if (xhr.responseText) {
                    try {
                        const parsed = JSON.parse(xhr.responseText);
                        errorMessage = parsed.error || xhr.responseText;
                    } catch {
                        errorMessage = xhr.responseText;
                    }
                }

                showToast(errorMessage, false);
            }
        });
    });

    // ❌ Delete return
    $('.delete-btn').on('click', function () {
        const row = $(this).closest('tr');
        const returnId = row.data('id');

        if (!confirm(`Are you sure you want to delete this return? ID: ${returnId}`)) return;

        $.ajax({
            url: '/return/delete/' + returnId,
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
