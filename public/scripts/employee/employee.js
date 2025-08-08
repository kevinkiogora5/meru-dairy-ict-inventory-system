let employees = [];
let employeeGrid = null;

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

function formToJSON(form) {
    const formData = new FormData(form);
    const object = {};
    formData.forEach((value, key) => {
        object[key] = value;
    });
    return object;
}

function openModal(id) {
    const employee = employees.find((emp) => emp.id == id);
    if (!employee) {
        showToast('Employee not found', false);
        return;
    }

    $('#exampleModal').modal('show');
    $('#employee').data("employee-id", id);

    $('#first_name').val(employee.first_name);
    $('#last_name').val(employee.last_name);
    $('#email').val(employee.email);
    $('#phone').val(employee.phone);
    $('#department_id').val(employee.department_id);

    $('#tuma').text('Update');
}

function buildGridData(data) {
    return data.map(employee => [
        employee.id,
        employee.first_name,
        employee.last_name,
        employee.email,
        employee.phone,
        employee.department_name,
        employee.created_at,
        gridjs.html(`
            <button class="btn btn-sm btn-warning" onclick="openModal(${employee.id})">Edit</button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${employee.id}">Delete</button>
        `)
    ]);
}

function renderGrid() {
    if (!employees || employees.length === 0) {
        $('#grid-wrapper').html('<p class="text-center mt-3">No employees found.</p>');
        return;
    }

    const gridData = buildGridData(employees);
    employeeGrid = new gridjs.Grid({
        columns: ['ID', 'First Name', 'Last Name', 'Employee Id', 'Phone', 'Department', 'Created On', 'Actions'],
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

function fetchEmployees() {
    $.get('/employee/list', function (data) {
        employees = data;
        if (employeeGrid) {
            employeeGrid.updateConfig({
                data: buildGridData(employees)
            }).forceRender();
        } else {
            renderGrid();
        }
    });
}

$(document).ready(function () {
    fetchEmployees();

    $('#exampleModal').on('hidden.bs.modal', function () {
        $('#employee')[0].reset();
        $('#employee').removeData('employee-id');
        $('#tuma').text('Save');
    });

    $('#employee').on('submit', function (e) {
        e.preventDefault();

        const formData = formToJSON(this);
        const employeeId = $('#employee').data('employee-id');
        const url = employeeId ? `/employee/update/${employeeId}` : "/employee/create";

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
                $('#employee')[0].reset();
                $('#exampleModal').modal('hide');
                $('#employee').removeData('employee-id');
                $('#tuma').text('Save');
                fetchEmployees();
                showToast(response.message);
            },
            error: function (xhr) {
                console.log(xhr);
                sub.html(originalText).prop('disabled', false);

                let errormessage = 'An unexpected error occurred';

                if (xhr.responseJSON?.error) {
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

    $(document).on('click', '.delete-btn', function () {
        const employeeId = $(this).data('id');
        if (!confirm(`Are you sure you want to delete this employee? ${employeeId}`)) return;

        $.ajax({
            url: `/employee/delete/${employeeId}`,
            type: 'DELETE',
            success: function (response) {
                showToast(response.message);
                fetchEmployees();
            },
            error: function (xhr) {
                console.log(xhr);
                const errormsg = xhr.responseJSON?.error || 'Delete failed.';
                showToast(errormsg, false);
            }
        });
    });
});
