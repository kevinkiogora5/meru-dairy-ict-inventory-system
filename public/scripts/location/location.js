
let locationGrid = null;

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
    return data.map(location => [
        location.id,
        location.county,
        location.office,
        location.created_at,
        gridjs.html(`
            <button class="btn btn-sm btn-warning" onclick="openModal(${location.id})">Edit</button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${location.id}">Delete</button>
        `)
    ]);
}

function renderGrid() {
    if (!locations || locations.length === 0) {
        $('#grid-wrapper').html('<p class="text-center mt-3">No locations found.</p>');
        return;
    }

    const gridData = buildGridData(locations);
    locationGrid = new gridjs.Grid({
        columns: ['ID', 'County', 'Office', 'Created On', 'Actions'],
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

function fetchLocations() {
    $.get('/location/list', function (data) {
        locations = data;
        if (locationGrid) {
            locationGrid.updateConfig({
                data: buildGridData(locations)
            }).forceRender();
        } else {
            renderGrid();
        }
    });
}

function openModal(id) {
    const location = locations.find((loc) => loc.id == id);
    if (!location) {
        showToast("Location not found", false);
        return;
    }

    $('#exampleModal').modal('show');
    $('#location').data("location-id", id);
    $('#county').val(location.county);
    $('#office').val(location.office);
    $('#tuma').text('Update');
}

$(document).ready(function () {
    fetchLocations();

    // Reset form on modal close
    $('#exampleModal').on('hidden.bs.modal', function () {
        $('#location')[0].reset();
        $('#location').removeData('location-id');
        $('#tuma').text('Save');
    });

    // Handle create/update submission
    $('#location').on('submit', function (e) {
        e.preventDefault();
        const formData = formToJSON(this);
        const locationId = $('#location').data('location-id');
        const url = locationId ? `/location/update/${locationId}` : "/location/create";
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
                $('#location')[0].reset();
                $('#exampleModal').modal('hide');
                $('#location').removeData('location-id');
                $('#tuma').text('Save');
                fetchLocations();
                showToast(response.message);
            },
            error: function (xhr) {
                console.log(xhr);
                sub.html(originalText).prop('disabled', false);
                showToast(parseErrorMessage(xhr), false);
            }
        });
    });

    // Handle delete
    $(document).on('click', '.delete-btn', function () {
        const locationId = $(this).data('id');
        if (!confirm(`Are you sure you want to delete this location? ${locationId}`)) return;

        $.ajax({
            url: '/location/delete/' + locationId,
            type: 'DELETE',
            success: function (response) {
                showToast(response.message);
                fetchLocations();
            },
            error: function (xhr) {
                console.log(xhr);
                showToast(parseErrorMessage(xhr), false);
            }
        });
    });
});
