let itemGrid = null;
let items = [];

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
    const item = items.find(it => it.id == id);
    if (!item) {
        showToast('Item not found', false);
        return;
    }

    $('#exampleModal').modal('show');
    $('#item').data("item-id", id);

    $('#name').val(item.name);
    $('#description').val(item.description);
    $('#model').val(item.model);
    $('#brand').val(item.brand);
    $('#item_condition').val(item.item_condition);
    $('#serial_number').val(item.serial_number);
    $('#category_id').val(item.category_id);

    $('#tuma').text('Update');
}

function buildGridData(data) {
    return data.map(item => [
        item.id,
        item.name,
        item.model,
        item.brand,
        item.status,
        item.item_condition,
        item.serial_number,
        item.category_type,
        item.created_at,
        gridjs.html(`
            <button class="btn btn-sm btn-warning" onclick="openModal(${item.id})">Edit</button>
            <button class="btn btn-sm btn-danger delete-btn" data-id="${item.id}">Delete</button>
        `)
    ]);
}

function renderGrid() {
    if (!items || items.length === 0) {
        $('#grid-wrapper').html('<p class="text-center mt-3">No items found.</p>');
        return;
    }

    const gridData = buildGridData(items);
    itemGrid = new gridjs.Grid({
        columns: ['ID', 'Name', 'Model', 'Brand', 'Status', 'Condition', 'Serial Number', 'Type', 'Posted On', 'Actions'],
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

function fetchItems() {
    $.get('/item/list', function (data) {
        items = data;
        if (itemGrid) {
            itemGrid.updateConfig({
                data: buildGridData(items)
            }).forceRender();
        } else {
            renderGrid();
        }
    });
}

$(document).ready(function () {
    fetchItems();

    $('#exampleModal').on('hidden.bs.modal', function () {
        const itemId = $('#item').data('item-id');
        if (itemId) {
            $('#item')[0].reset();
            $('#item').removeData('item-id');
            $('#tuma').text('Save');
        }
    });

    $('#item').on('submit', function (e) {
        e.preventDefault();

        const formData = formToJSON(this);
        const itemId = $('#item').data('item-id');
        const url = itemId ? `/item/update/${itemId}` : "/item/create";
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
                $('#item')[0].reset();
                $('#exampleModal').modal('hide');
                $('#item').removeData("item-id");
                $('#tuma').text('Save');
                fetchItems();
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
        const itemId = $(this).data('id');
        if (!confirm(`Are you sure you want to delete this item? ${itemId}`)) return;

        $.ajax({
            url: `/item/delete/${itemId}`,
            type: 'DELETE',
            success: function (response) {
                showToast(response.message);
                fetchItems();
            },
            error: function (xhr) {
                console.log(xhr);
                const errormsg = xhr.responseJSON?.error || 'Delete failed.';
                showToast(errormsg, false);
            }
        });
    });
});
