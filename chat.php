<?php
session_start();
header("Content-Type: application/json");

include('dbConnection.php');

$userMessage = strtolower(trim($_POST['message'] ?? ''));
$isAction = $_POST['isAction'] ?? false; // New flag for button clicks

if (!isset($_SESSION['chat_flow'])) {
    $_SESSION['chat_flow'] = null;
    $_SESSION['chat_data'] = [];
}

$reply = "Sorry, I'm not able to help with this question.";

// ---------------------- MAIN MENU ----------------------
if (in_array($userMessage, ["hi", "hello", "hey"])) {
    $reply = "👋 Hi! I’m your OSMS assistant. Please choose an option below.";
}

// ---------------------- SERVICE REQUEST FLOW ----------------------
elseif ($userMessage == "service request" && $isAction) {
    $_SESSION['chat_flow'] = "service_request";
    $_SESSION['chat_data'] = [];
    $reply = "Please enter your full name:";
}

elseif ($_SESSION['chat_flow'] == "service_request") {
    $data =& $_SESSION['chat_data'];

    if (!isset($data['name'])) {
        $data['name'] = $userMessage;
        $reply = "Got it. Now enter your email address:";
    }
    elseif (!isset($data['email'])) {
        $data['email'] = $userMessage;
        $reply = "Thanks. Please provide a short description of your issue:";
    }
    elseif (!isset($data['desc'])) {
        $data['desc'] = $userMessage;

        $name = mysqli_real_escape_string($con, $data['name']);
        $email = mysqli_real_escape_string($con, $data['email']);
        $desc = mysqli_real_escape_string($con, $data['desc']);

        $sql = "INSERT INTO submitrequest (request_name, request_email, request_info, request_desc) 
                VALUES ('$name', '$email', 'Chatbot Request', '$desc')";
        if ($con->query($sql) === TRUE) {
            $lastId = $con->insert_id;
            $reply = "Your service request has been submitted successfully! Your Request ID is: **$lastId**.";
        } else {
            $reply = "Failed to save your request. Please try again.";
        }

        $_SESSION['chat_flow'] = null;
        $_SESSION['chat_data'] = [];
    }
}

// ---------------------- STATUS FLOW ----------------------
elseif ($userMessage == "status" && $isAction) {
    $_SESSION['chat_flow'] = "status";
    $reply = "Please enter your Request ID:";
}

elseif ($_SESSION['chat_flow'] == "status") {
    $requestId = intval($userMessage);

    if ($requestId > 0) {
        $sql = "SELECT * FROM assignwork WHERE request_id = $requestId";
        $result = $con->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $reply = "Status for Request ID *$requestId*: \n\n".
                     "Issue: ".$row['request_info']."\n".
                     "Technician: ".$row['request_tech']."\n".
                     "Assigned Date: ".$row['request_date'];
        } else {
            $reply = "Your request with ID $requestId is still pending.";
        }
    } else {
        $reply = "Please enter a valid numeric Request ID.";
    }

    $_SESSION['chat_flow'] = null;
    
}

// ---------------------- RESPONSE ----------------------
echo json_encode(["reply" => $reply]);


