<?php
include 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        getCustomers();
        break;
    case 'POST':
        createCustomer();
        break;
    case 'PUT':
        updateCustomer();
        break;
    case 'DELETE':
        deleteCustomer();
        break;
}

function getCustomers() {
    global $conn;
    $sql = "SELECT * FROM customers";
    $result = $conn->query($sql);
    
    $customers = array();
    while($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
    echo json_encode($customers);
}

function createCustomer() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
    
    $first_name = $data->first_name;
    $last_name = $data->last_name;
    $email = $data->email;
    $phone_number = $data->phone_number;
    $address = $data->address;
    
    $sql = "INSERT INTO customers (first_name, last_name, email, phone_number, address) 
            VALUES ('$first_name', '$last_name', '$email', '$phone_number', '$address')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "New customer added successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }
}

function updateCustomer() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
    
    $id = $data->id;
    $first_name = $data->first_name;
    $last_name = $data->last_name;
    $email = $data->email;
    $phone_number = $data->phone_number;
    $address = $data->address;
    
    $sql = "UPDATE customers SET first_name='$first_name', last_name='$last_name', email='$email', 
            phone_number='$phone_number', address='$address' WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Customer updated successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }
}

function deleteCustomer() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
    $id = $data->id;
    
    $sql = "DELETE FROM customers WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Customer deleted successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }
}
?>
