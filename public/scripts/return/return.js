let returns = [];
let returnGrid = null;

// Helper function to handle Toast messages
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

// Helper function to parse error messages from AJAX responses
function parseErrorMessage(xhr) {
    let errorMessage = 'An unexpected error occurred';
    if (xhr.responseJSON?.error) {
        errorMessage = xhr.responseJSON.error;
    } else if (xhr.responseText) {
        try {
            const parsed = JSON.parse(xhr.responseText);
            errorMessage = parsed?.error || xhr.responseText;
        } catch {
            errorMessage = xhr.responseText;
        }
    }
    return errorMessage;
}

// Helper function to build data for the GridJS table
function buildGridData(data) {
    return data.map(returnItem => [
        returnItem.id,
        returnItem.employee_email,
        returnItem.inventory_item_name,
        returnItem.returned_condition,
        returnItem.return_date,
        returnItem.comments,
        gridjs.html(`
            <button class="btn btn-sm btn-warning" onclick="openModal(${returnItem.id})">Edit</button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${returnItem.id}">Delete</button>
        `)
    ]);
}

// Function to render or update the GridJS table
function renderGrid() {
    if (!returns || returns.length === 0) {
        $('#grid-wrapper').html('<p class="text-center mt-3">No returns found.</p>');
        return;
    }

    const gridData = buildGridData(returns);
    if (returnGrid) {
        returnGrid.updateConfig({ data: gridData }).forceRender();
    } else {
        returnGrid = new gridjs.Grid({
            columns: ['ID', 'Employee', 'Returned Item', 'Condition', 'Date', 'Comments', 'Actions'],
            data: gridData,
            pagination: {
                enabled: true,
                limit: 5,
                summary: true
            },
            search: true,
            sort: true
        }).render(document.getElementById("grid-wrapper"));
    }
}

// Function to fetch all return records
function fetchReturns() {
    $.get('/return/list', function (data) {
        returns = data;
        renderGrid();
    }).fail(function(xhr) {
        showToast('Failed to fetch returns.', false);
        console.error('Error fetching returns:', xhr);
    });
}

/**
 * Main function to open the modal.
 * If an ID is passed, it's an update; otherwise, it's a new creation.
 */
function openModal(id = null) {
    $('#return')[0].reset();
    $('#return').removeData('return-id');
    $('#tuma').text('Send');
    $('#inventory_item_id').empty().append('<option value="">Select employee first</option>').prop('disabled', true);
    
    if (id) {
        const returnItem = returns.find((r) => r.id == id);
        if (!returnItem) {
            showToast("Return not found", false);
            return;
        }
        $('#return').data("return-id", id);
        $('#tuma').text('Update');
        $('#employee_id').val(returnItem.employee_id);
        
        // Pass the selected ID to the change handler
        $('#employee_id').trigger('change', [returnItem.inventory_assignment_id]);
        
        $('#returned').val(returnItem.returned_condition);
        $('#comment').val(returnItem.comments);
    } else {
        $('#tuma').text('Send');
    }

    $('#exampleModal').modal('show');
}

/**
 * Populates the dropdown based on the API endpoint and mode.
 * @param {string} url - The API endpoint to fetch data from.
 * @param {string|number} [selectedId] - The ID of the item to pre-select.
 */
function populateDropdown(url, selectedId) {
    const inventorySelect = $('#inventory_item_id');
    inventorySelect.empty().prop('disabled', true).append('<option>Loading...</option>');

    $.ajax({
        url: url,
        method: 'GET',
        success: function (data) {
            inventorySelect.empty();
            if (data.length === 0) {
                inventorySelect.append('<option value="">No items found</option>');
            } else {
                inventorySelect.append('<option value="">Select an item</option>');
                data.forEach(a => {
                    inventorySelect.append(`<option value="${a.id}">${a.name} (${a.serial_number})</option>`);
                });
            }
            inventorySelect.prop('disabled', false);

            if (selectedId) {
                inventorySelect.val(selectedId);
            }
        },
        error: function (xhr) {
            showToast(parseErrorMessage(xhr), false);
            console.error(xhr);
            inventorySelect.empty().append('<option value="">Failed to load items</option>').prop('disabled', false);
        }
    });
}

$(document).ready(function () {
    fetchReturns();

    $('#newReturnButton').on('click', function() {
        openModal();
    });

    $('#exampleModal').on('hidden.bs.modal', function () {
        $('#return')[0].reset();
        $('#return').removeData('return-id');
        $('#tuma').text('Send');
        $('#inventory_item_id').empty().append('<option value="">Select employee first</option>').prop('disabled', true);
    });

    // The logic is now the same for create and update
    // We always fetch assigned items for a return
    $('#employee_id').on('change', function (event, preselectedId = null) {
        const employeeId = $(this).val();
        
        if (!employeeId) {
            $('#inventory_item_id').html('<option value="">Select employee first</option>').prop('disabled', true);
            return;
        }
        
        const apiUrl = `/return/assignments-by-employee?employee_id=${employeeId}`;
        
        populateDropdown(apiUrl, preselectedId);
    });

    $('#return').on('submit', function (e) {
        e.preventDefault();
        const returnId = $('#return').data('return-id');
        const formData = formToJSON(this);

        const selectedValue = formData.inventory_assignment_id;

        if (!selectedValue) {
            showToast("Please select an item to return.", false);
            return;
        }

        let url = '';
        let finalData = {};
        
        if (returnId) {
            finalData = { ...formData, inventory_assignment_id: selectedValue };
            delete finalData.inventory_item_id;
            url = `/return/update/${returnId}`;
        } else {
            finalData = { ...formData, inventory_assignment_id: selectedValue };
            delete finalData.inventory_item_id;
            url = '/return/create';
        }

        const sub = $('#tuma');
        const originalText = sub.text();

        sub.html('Submitting...').prop('disabled', true);

        $.ajax({
            type: "POST",
            url: url,
            data: JSON.stringify(finalData),
            contentType: 'application/json',
            success: function (response) {
                sub.html(originalText).prop('disabled', false);
                $('#exampleModal').modal('hide');
                fetchReturns();
                showToast(response.message);
            },
            error: function (xhr) {
                console.error(xhr);
                sub.html(originalText).prop('disabled', false);
                showToast(parseErrorMessage(xhr), false);
            }
        });
    });

    // Delete return
    $(document).on('click', '.delete-btn', function () {
        const returnId = $(this).data('id');
        if (!confirm(`Are you sure you want to delete this return? ID: ${returnId}`)) return;

        $.ajax({
            url: '/return/delete/' + returnId,
            type: 'DELETE',
            success: function (response) {
                showToast(response.message);
                fetchReturns();
            },
            error: function (xhr) {
                console.error(xhr);
                showToast(parseErrorMessage(xhr), false);
            }
        });
    });
});

// A simple helper to convert form data to a JSON object
function formToJSON(form) {
    const data = {};
    $(form).serializeArray().forEach(field => {
        data[field.name] = field.value;
    });
    return data;
}