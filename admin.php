<?php
include 'admin_session.php';
include 'db.php';

// Handle user management actions
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $sql = "DELETE FROM users WHERE id = $user_id";
    $conn->query($sql);
}

// Handle client management actions
if (isset($_POST['delete_client'])) {
    $client_id = $_POST['client_id'];
    $sql = "DELETE FROM clients WHERE id = $client_id";
    $conn->query($sql);
}

// Handle adding new user
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email= $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    // Check if username already exists
    $check_sql = "SELECT * FROM users WHERE username = '$username'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "Username already exists. Please choose a different username.";
    } else {
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
        if ($conn->query($sql) === TRUE) {
            echo "User added successfully.";
            echo '<script>window.setTimeout(function(){ window.location = "admin.php"; }, 2000);</script>'; // Redirect after 2 seconds
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Handle adding new client
if (isset($_POST['add_client'])) {
    $name = $_POST['name'];
    $phone_no = $_POST['phone_no'];
    $id_no = $_POST['id_no'];

    // Check if client already exists
    $check_sql = "SELECT * FROM clients WHERE name = '$name' AND phone_no = '$phone_no' AND id_no = '$id_no'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "Client already exists.";
    } else {
        $sql = "INSERT INTO clients (name, phone_no, id_no) VALUES ('$name', '$phone_no', '$id_no')";
        if ($conn->query($sql) === TRUE) {
            echo "Client added successfully.";
            echo '<script>window.setTimeout(function(){ window.location = "admin.php"; }, 2000);</script>'; // Redirect after 2 seconds
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Fetch all users
$sql_users = "SELECT id, username, role FROM users";
$result_users = $conn->query($sql_users);

// Fetch all clients
$sql_clients = "SELECT id, name, phone_no, id_no FROM clients";
$result_clients = $conn->query($sql_clients);

$search_query = ""; // Initialize the variable

// Fetch all clients
$sql = "SELECT id, name, phone_no, id_no, check_in, check_out FROM clients";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        header {
            background-color: #2D2C8E;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .table-container {
            overflow-x: auto;
            max-height: 100px;
        }
        .action-links, .logout-link {
            text-align: center;
            margin-top: 20px;
        }
        footer {
            background-color: #F48312;
            height: 130px;
            text-align: center;
            color: white;
            padding: 20px 0;
        }
        .form-container {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        .form-container1 {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        section
        {
            background-color: white;
        }
    </style>
</head>
<body>
    <header class= "text-white text-center py-3">
        <h1>KNLS E-RESOURCE MANAGEMENT SYSTEM</h1>
        <h2>Admin Panel</h2>
        <div class="action-links">
            <a href="index.php" class="btn btn-primary">Home</a>
            <a href="register_client.php" class="btn btn-primary">Register Client</a>
            <a href="check_in.php" class="btn btn-primary">Check In</a>
            <a href="check_out.php" class="btn btn-primary">Check Out</a>
            <a href="register_computer.php" class="btn btn-primary">Computer</a>
        </div>
    </header>
    <section class="bg-white">
    <div class="container mt-5">
        <div class="container">
    <div class="row">
    <div class="container mt-5">
            <div class="row">
                <!-- Users Table and Form -->
                <div class="col-md-6">
                    <h2>Manage Users</h2>
                    <div class="table-container mb-4">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_users->num_rows > 0) {
                                    while ($row = $result_users->fetch_assoc()) {
                                        echo "<tr>
                                                <td>{$row['id']}</td>
                                                <td>{$row['username']}</td>
                                                <td>{$row['role']}</td>
                                                <td>
                                                    <form method='post' action=''>
                                                        <input type='hidden' name='user_id' value='{$row['id']}'>
                                                        <button type='submit' name='delete_user' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</button>
                                                    </form>
                                                </td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No users found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-container1">
                        <h2>Add New User</h2>
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Role:</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <button type="submit" name="add_user" class="btn btn-primary btn-block">Add User</button>
                        </form>
                    </div>
                </div>

                <!-- Clients Table and Form -->
                <div class="col-md-6">
                    <h2>Manage Clients</h2>
                    <div class="table-container mb-4">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Phone No</th>
                                    <th>ID No</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_clients->num_rows > 0) {
                                    while ($row = $result_clients->fetch_assoc()) {
                                        echo "<tr>
                                                <td>{$row['id']}</td>
                                                <td>{$row['name']}</td>
                                                <td>{$row['phone_no']}</td>
                                                <td>{$row['id_no']}</td>
                                                <td>
                                                    <form method='post' action=''>
                                                        <input type='hidden' name='client_id' value='{$row['id']}'>
                                                        <button type='submit' name='delete_client' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this client?\")'>Delete</button>
                                                    </form>
                                                </td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>No clients found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-container">
                        <h2>Add New Client</h2>
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="phone_no">Phone No:</label>
                                <input type="text" class="form-control" id="phone_no" name="phone_no" required>
                            </div>
                            <div class="form-group">
                                <label for="id_no">ID No:</label>
                                <input type="text" class="form-control" id="id_no" name="id_no" required>
                            </div>
                            <button type="submit" name="add_client" class="btn btn-primary btn-block">Add Client</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <a href="admin_logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
</section>
 <footer>
     <p>&copy; <?php echo date("Y"); ?>
 </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
