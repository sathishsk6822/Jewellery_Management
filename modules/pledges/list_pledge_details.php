<?php include '../../includes/dbconnect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pledge Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body{
            background-color:rgb(225, 237, 250);
        }
        </style>
</head>
<body>

<div class="container mt-4">
    <h2>Pledge Details</h2>

    <!-- Search Box -->
    <input type="text" id="searchCustomerId" class="form-control mb-3" placeholder="Search by Customer ID...">
    
    <!-- Scrollable Table -->
    <div class="table-responsive" style="height: 500px; overflow-y: auto;" id="pledgeTableContainer">
        <table class='table table-bordered table-striped'>
            <thead class='table-dark'>
                <tr>
                    <th>ID</th>
                    <th>Accountant ID</th>
                    <th>Receipt Number</th>
                    <th>Customer ID</th>
                    <th>Father's Name</th>
                    <th>Jewel Weight</th>
                    <th>Jewel Description</th>
                    <th>Amount</th>
                    <th>Jewel Value</th>
                    <th>Pledge Date</th>
                    <th>Retailer ID</th>
                    <th>Interest (1.5%)</th>
                    <th>Interest Amount (1.5%)</th>
                    <th>Interest (1.25%)</th>
                    <th>Interest Amount (1.25%)</th>
                    <th>Release Date</th>
                    <th>Paid Amount</th>
                    <th>Interest Amount</th>
                    <th>Interest Amount (1.25%)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="pledgeTableBody">
                <!-- Data will be loaded here dynamically -->
            </tbody>
        </table>
        <div id="loadingMessage" class="text-center mt-2" style="display: none;">Loading more records...</div>
    </div>
</div>

<script>
var page = 1;
var searchQuery = "";
var isFetching = false;

$(document).ready(function() {
    fetchPledges(); // Load initial data

    $("#pledgeTableContainer").on("scroll", function() {
        var scrollTop = $(this).scrollTop();
        var innerHeight = $(this).innerHeight();
        var scrollHeight = this.scrollHeight;

        if (scrollTop + innerHeight >= scrollHeight - 10) {
            fetchPledges();
        }
    });

    // Handle Search Input
    $("#searchCustomerId").on("input", function() {
        searchQuery = $(this).val().trim();

        // If search field is empty, reset the table and reload all data
        if (searchQuery === "") {
            resetAndFetchAll();
        } else {
            page = 1;
            $("#pledgeTableBody").html(""); // Clear table for new results
            fetchPledges(true); // Load filtered results
        }
    });
});

// Reset table and fetch all records when search field is cleared
function resetAndFetchAll() {
    page = 1;
    $("#pledgeTableBody").html(""); // Clear table
    searchQuery = ""; // Reset search query
    fetchPledges(); // Reload all data
}

// Fetch data from the server
function fetchPledges(isSearch = false) {
    if (isFetching) return;
    isFetching = true;

    $.ajax({
        url: '../../handlers/fetch_pledges.php",
        type: "GET",
        data: { page: page, customer_id: searchQuery },
        success: function(response) {
            if (page === 1) {
                $("#pledgeTableBody").html(response); // Reset table on new search
            } else {
                $("#pledgeTableBody").append(response); // Append data on scroll
            }
            page++;
            isFetching = false;
        },
        error: function() {
            console.log("Error loading data");
            isFetching = false;
        }
    });
}

</script>

</body>
</html>
