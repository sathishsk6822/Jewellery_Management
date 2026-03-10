<?php
require __DIR__ . '/vendor/autoload.php';
include 'header.php'; // Include header
include 'sidebar.php'; // Include sidebar

use Twilio\Rest\Client;

include 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipient = $_POST['recipient'];
    $message = $_POST['message'];

    if (!empty($recipient) && !empty($message)) {
        $sid = "YOUR_TWILIO_SID"; // Twilio Account SID
        $token = "YOUR_TWILIO_AUTH_TOKEN"; // Twilio Auth Token
        $twilio = new Client($sid, $token);

        // Format recipient number (India +91)
        $recipient = preg_replace("/[^0-9]/", "", $recipient);
        if (substr($recipient, 0, 1) == '0') {
            $recipient = substr($recipient, 1);
        } else if (substr($recipient, 0, 3) == '+91') {
            $recipient = substr($recipient, 3);
        } else if (substr($recipient, 0, 2) == '91') {
            $recipient = substr($recipient, 2);
        }

        $formatted_recipient = "+91" . $recipient; // Ensure +91 prefix

        try {
            $message_sent = $twilio->messages
                ->create($formatted_recipient, [
                    "from" => "+13133626718",
                    "body" => $message
                ]);

            if ($message_sent->status == 'queued' || $message_sent->status == 'sent' || $message_sent->status == 'delivered') {
                $stmt = $conn->prepare("INSERT INTO sms_history (recipient, message, sent_at) VALUES (?, ?, NOW())");
                $stmt->bind_param("ss", $formatted_recipient, $message);
                $stmt->execute();
                $stmt->close();

                echo json_encode(["success" => true, "message_sid" => $message_sent->sid]);
                exit;
            } else {
                echo json_encode(["success" => false, "error" => "SMS failed: " . $message_sent->error_message]);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(["success" => false, "error" => "SMS failed: " . $e->getMessage()]);
            exit;
        }
    } else {
        echo json_encode(["success" => false, "error" => "Recipient and message required"]);
        exit;
    }
}
?>

<style>
    body {
        background-color: rgb(252, 252, 252);
        font-family: 'Arial', sans-serif;
        color: #333;
        margin: 20px;
        padding: 0;
    }

    h3 {
        color: #0056b3;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
    }

    /* Form Styling */
    .sms-container {
        width: 600px;
        margin-left: 250px;
    }

    form {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
        display: block;
        margin-top: 10px;
    }

    input,
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .buttons {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
        transition: background 0.3s;
        width: 100%;
        margin-top: 10px;
    }

    .buttons:hover {
        background: linear-gradient(45deg, #0056b3, #007bff);
    }

    /* SMS History Styling */
    .sms-history-container {
        width: 600px;
        margin: auto;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background: #007bff;
        color: white;
    }

    td {
        color: #333;
    }

    tr:hover {
        background: #f1f1f1;
    }

    /* Responsive Design */
    @media (max-width: 768px) {

        .sms-container,
        .sms-history-container {
            width: 90%;
        }
    }
</style>

<div class="sms-container">
    <h3>Send SMS</h3>
    <form id="smsForm">
        <label>Recipient Phone Number:</label>
        <input type="text" id="recipient" name="recipient" class="form-control" required>

        <label>Message:</label>
        <textarea id="message" name="message" class="form-control" required></textarea>

        <button type="submit" class="buttons">Send SMS</button>
    </form>
    <!-- SMS History Section -->
    <div class="sms-history-container">
        <h3>SMS History</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Recipient</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody id="smsHistory">
                <!-- SMS History will be loaded here dynamically -->
            </tbody>
        </table>
    </div>
</div>






<script>
    // Send SMS and Update History in Real-Time
    document.getElementById("smsForm").addEventListener("submit", function (event) {
        event.preventDefault();
        let formData = new FormData(this);

        fetch("send_sms.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("SMS sent successfully!");
                    document.getElementById("smsForm").reset();
                    fetchHistory(); // Reload history
                } else {
                    alert("Error: " + data.error);
                }
            });
    });

    // Delete SMS function
    function deleteSMS(id) {
        if (confirm("Are you sure you want to delete this SMS record?")) {
            fetch("delete_sms.php", {
                method: "POST",
                body: new URLSearchParams({ id: id })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("SMS record deleted successfully!");
                        document.getElementById("sms_" + id).remove();
                    } else {
                        alert("Error: " + data.error);
                    }
                });
        }
    }

    // Fetch SMS History
    function fetchHistory() {
        fetch("fetch_sms_history.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById("smsHistory").innerHTML = data;
            });
    }

    // Load history on page load
    fetchHistory();
    // Load history on page load
    fetchHistory();
</script>