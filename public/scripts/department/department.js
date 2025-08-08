
let departmentGrid = null;

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

function parseErrorMessage(xhr) {
    let errormessage = 'An unexpected error occurred';

    if (xhr.responseJSON?.error) {
        errormessage = xhr.responseJSON.error;
    } else if (xhr.responseText) {
        try {
            const parsed = JSON.parse(xhr.responseText);
            errormessage = parsed?.error || xhr.responseText;
        } catch (e) {
            errormessage = xhr.responseText;
        }
    }

    return errormessage;
}

function buildGridData(data) {
    return data.map(department => [
        department.id,
        department.name,
        department.description,
        department.created_at,
        gridjs.html(`
            <button class="btn btn-sm btn-warning" onclick="openModal(${department.id})">Edit</button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${department.id}">Delete</button>
        `)
    ]);
}

function renderGrid() {
    if (!departments || departments.length === 0) {
        $('#grid-wrapper').html('<p class="text-center mt-3">No departments found.</p>');
        return;
    }

    const gridData = buildGridData(departments);
    departmentGrid = new gridjs.Grid({
        columns: ['ID', 'Name', 'Description', 'Created On', 'Actions'],
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

function fetchDepartments() {
    $.get('/department/list', function (data) {
        departments = data;
        if (departmentGrid) {
            departmentGrid.updateConfig({
                data: buildGridData(departments)
            }).forceRender();
        } else {
            renderGrid();
        }
    });
}

function openModal(id) {
    const section = departments.find((dept) => dept.id == id);
    if (!section) {
        showToast('Department not found', false);
        return;
    }

    $('#exampleModal').modal('show');
    $('#department').data("section-id", id);

    $('#name').val(section.name);
    $('#description').val(section.description);
    $('#tuma').text('Update');
}

$(document).ready(function () {
    fetchDepartments();

    $('#exampleModal').on('hidden.bs.modal', function () {
        $('#department')[0].reset();
        $('#department').removeData('section-id');
        $('#tuma').text('Save');
    });

    $('#department').on('submit', function (e) {
        e.preventDefault();
        const formData = formToJSON(this);
        const sectionId = $('#department').data('section-id');
        const url = sectionId ? `/department/update/${sectionId}` : "/department/create";
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
                $('#department')[0].reset();
                $('#exampleModal').modal('hide');
                $('#department').removeData('section-id');
                $('#tuma').text('Save');
                fetchDepartments();
                showToast(response.message);
            },
            error: function (xhr) {
                console.log(xhr);
                sub.html(originalText).prop('disabled', false);
                showToast(parseErrorMessage(xhr), false);
            }
        });
    });

    $(document).on('click', '.delete-btn', function () {
        const departmentId = $(this).data('id');
        if (!confirm(`Are you sure you want to delete this department? ${departmentId}`)) return;

        $.ajax({
            url: '/department/delete/' + departmentId,
            type: 'DELETE',
            dataType: 'json',
            success: function (response) {
                showToast(response.message);
                fetchDepartments();
            },
            error: function (xhr) {
                console.log(xhr);
                showToast(parseErrorMessage(xhr), false);
            }
        });
    });
});
