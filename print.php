<?php
include 'dbconnect.php'; 
include 'header.php'; 
include 'sidebar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Global Styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

/* Container */
.container {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    margin: 50px auto;
    text-align: center;
    
}

/* Heading */
h2 {
    color: #333;
    font-weight: 600;
    margin-bottom: 20px;
}

/* Form Styles */
form {
    text-align: left;
}

label {
    font-weight: 500;
    margin-bottom: 5px;
    display: block;
    color: #555;
}

select {
    width: 100%;
    padding: 12px;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-size: 16px;
    transition: 0.3s;
}

select:focus {
    border-color: #007bff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
    outline: none;
}

/* Submit Button */
button {
    width: 100%;
    padding: 12px;
    font-size: 18px;
    font-weight: bold;
    color: #fff;
    background: #007bff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #0056b3;
}

/* Print Styles */
@media print {
    body {
        background: white;
    }

    .container {
        box-shadow: none;
        border: none;
    }

    button, select {
        display: none; /* Hide form and buttons during printing */
    }
}

        </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Print Records</h2>
        <form action="print_records.php" method="POST">
            <label for="recordType">Select Record Type:</label>
            <select name="recordType" class="form-control" required>
                <option value="customer">Customer Records</option>
                <option value="pledge">Pledge Records</option>
                <option value="release">Release Records</option>
            </select>
            <button type="submit" class="btn btn-primary mt-3">Print</button>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $('#loadRecords').click(function() {
                var recordType = $('#recordType').val();
                if (recordType == "") {
                    alert("Please select a record type!");
                    return;
                }

                $.ajax({
                    url: "print_records.php",
                    method: "POST",
                    data: { recordType: recordType },
                    beforeSend: function() {
                        $('#recordsTable').html('<p>Loading records...</p>');
                    },
                    success: function(response) {
                        $('#recordsTable').html(response);
                    },
                    error: function() {
                        alert("Error loading records.");
                    }
                });
            });
        });
    </script>
</body>

</html>
