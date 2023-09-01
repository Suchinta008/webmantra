<?php
include "connection.php";


if (isset($_POST['delete_single'])) {
    $delete_id = $_POST['delete_id'];
    $delete_sql = "DELETE FROM contact_form WHERE id = $delete_id";
    if ($conn->query($delete_sql) !== TRUE) {
        echo "Error deleting record: " . $conn->error;
    }
}

if (isset($_POST['delete_multiple'])) {
    // Loop through selected IDs and delete them
    if (isset($_POST['selected_ids'])) {
        foreach ($_POST['selected_ids'] as $id) {
            $delete_sql = "DELETE FROM contact_form WHERE id = $id";
            if ($conn->query($delete_sql) !== TRUE) {
                echo "Error deleting record: " . $conn->error;
            }
        }
    }
}

$sql = "SELECT * FROM contact_form";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Data</h2>
        <form method="post" action="">
            <table class="table">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><input type="checkbox" name="selected_ids[]" value="<?php echo $row['id']; ?>"></td>
                                <td><?php echo $row['full_name']; ?></td>
                                <td><?php echo $row['phone_number']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['country']; ?></td>
                                <td><?php echo $row['state']; ?></td>
                                <td><?php echo $row['city']; ?></td>
                                <td>
                                    <a class="btn btn-info" href="update.php?id=<?php echo $row['id']; ?>">Edit</a>
                                    <form method="post">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete_single" class="btn btn-danger" onclick="alert('Are you sure you want to Delete this Data')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    }else echo"No data Found";
                    ?>
                </tbody>
            </table>
            <button type="submit" name="delete_multiple" class="btn btn-danger" onclick="alert('Are you sure you want to delete Multiple Data')">Delete Selected</button>
        </form>
    </div>
</body>
</html>