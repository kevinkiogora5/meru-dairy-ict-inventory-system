<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --text-muted: #6c757d;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8f9fa;
        }
        
        .page-wrapper {
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        .card-footer {
            padding: 10px 20px;
            color: #fff;
        }
        
        .counter-icon { font-size: 2.5rem; opacity: 0.7; }
        .counter-heading { font-size: 2.5rem; font-weight: 600; margin: 0; }
        .counter-text { color: var(--text-muted); margin: 0; font-size: 1rem; }
    </style>
</head>
<body>

<main class="page-wrapper mt-2">
    <div class="container-fluid">
        <h4 class="mb-3">System Overview</h4>
        <div class="row" id="unified-summary-container">
            <div class="text-center p-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading summary data...</p>
            </div>
        </div>

        <h4 class="mb-3 mt-4">Items by Category</h4>
        <div class="row" id="dashboard-category-cards-container">
            <div class="text-center p-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading category data...</p>
            </div>
        </div>

    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
// A simple function to generate a consistent hash from a string
function stringToHslColor(str, s, l) {
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    const h = hash % 360;
    return `hsl(${h}, ${s}%, ${l}%)`;
}

// Global variables to hold data
let items = [];
let categories = [];
let assignments = [];
let returns = [];

// Function to dynamically render ALL top-level summary cards
function renderAllSummaryCards(totalItems, availableItems, totalAssignments, totalReturns) {
    const container = $('#unified-summary-container');
    container.empty(); // Clear previous cards

    const cardsHtml = `
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0 text-white">${totalItems}</h4>
                            <p class="mb-0">Total Items</p>
                        </div>
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0 text-white">${availableItems}</h4>
                            <p class="mb-0">Available Items</p>
                        </div>
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0 text-white">${totalAssignments}</h4>
                            <p class="mb-0">Total Assignments</p>
                        </div>
                        <i class="fas fa-clipboard-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0 text-white">${totalReturns}</h4>
                            <p class="mb-0">Total Returns</p>
                        </div>
                        <i class="fas fa-undo-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.append(cardsHtml);
}

// Function to dynamically render the category-specific cards
function renderCategoryCards(categories, items) {
    const container = $('#dashboard-category-cards-container');
    container.empty(); // Clear previous cards

    if (categories && categories.length > 0) {
        categories.forEach(category => {
            const uniqueColor = stringToHslColor(category.type, 70, 60);
            
            // Count items for this specific category
            const count = items.filter(item => item.category_type === category.type).length;

            const cardHtml = `
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card h-100" style="border-left: 5px solid ${uniqueColor};">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="col">
                                    <h4 class="counter-heading" style="color: ${uniqueColor};">${count}</h4>
                                    <p class="counter-text">${category.type}</p>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-box counter-icon" style="color: ${uniqueColor};"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer" style="background-color: ${uniqueColor};">
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="mb-0 text-white">Items in this category</p>
                                <i class="fas fa-arrow-right text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.append(cardHtml);
        });
    } else {
        container.html('<p class="text-center">No categories found to display.</p>');
    }
}

// Main function to fetch all dashboard data
function fetchAllDashboardData() {
    $.when(
        $.get('/item/list'),
        $.get('/category/list'),
        $.get('/assignment/list'),
        $.get('/return/list')
    ).done(function(itemResponse, categoryResponse, assignmentResponse, returnResponse) {
        // Set global variables with data from the API calls
        items = itemResponse[0];
        categories = categoryResponse[0];
        assignments = assignmentResponse[0];
        returns = returnResponse[0];

        // Calculate metrics
        const totalItems = items.length;
        const availableItems = items.filter(item => item.status === 'Available').length;
        const totalAssignments = assignments.length;
        const totalReturns = returns.length;
        
        // Render all the dashboard elements
        renderAllSummaryCards(totalItems, availableItems, totalAssignments, totalReturns);
        renderCategoryCards(categories, items);
        
    }).fail(function(xhr) {
        console.error("Failed to fetch dashboard data:", xhr);
        const errorMessage = '<p class="text-center text-danger">Error loading dashboard data. Please check your API endpoints.</p>';
        $('#unified-summary-container').html(errorMessage);
        $('#dashboard-category-cards-container').html(errorMessage);
    });
}

// Run the main function on page load
$(document).ready(function() {
    fetchAllDashboardData();
});
</script>

</body>
</html>