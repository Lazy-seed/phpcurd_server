<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

require 'db.php';

$request_method = $_SERVER['REQUEST_METHOD'];

// CREATE - Insert a new record
function createRecord() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];

    $query = "INSERT INTO records (name, email, phone) VALUES ('$name', '$email', '$phone')";
    if ($conn->query($query) === TRUE) {
        echo json_encode(["message" => "Record created successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }
}

// READ - Get all records
function getAllRecords() {
    global $conn;
    $query = "SELECT * FROM records";
    $result = $conn->query($query);
    
    if (!$result) {
        // Log and return the error
        echo json_encode(["message" => "Error: " . $conn->error]);
        return;
    }
    
    $records = [];
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
    echo json_encode($records);
}


// UPDATE - Update a record by ID
function updateRecord($id) {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];

    $query = "UPDATE records SET name='$name', email='$email', phone='$phone' WHERE id=$id";
    if ($conn->query($query) === TRUE) {
        echo json_encode(["message" => "Record updated successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }
}

// DELETE - Delete a record by ID
function deleteRecord($id) {
    global $conn;
    $query = "DELETE FROM records WHERE id=$id";
    if ($conn->query($query) === TRUE) {
        echo json_encode(["message" => "Record deleted successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }
}

// Routes and method handling
switch ($request_method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            getSingleRecord($id);
        } else {
            getAllRecords();
        }
        break;
    case 'POST':
        createRecord();
        break;
    case 'PUT':
        $id = $_GET['id'];
        updateRecord($id);
        break;
    case 'DELETE':
        $id = $_GET['id'];
        deleteRecord($id);
        break;
    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;
}
?>
