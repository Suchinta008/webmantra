<?php 

include "connection.php";
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $edit_sql = "SELECT * FROM contact_form WHERE id=$id";
    $edit_result = $conn->query($edit_sql);

    if ($edit_result->num_rows == 1) {
        $edit_row = $edit_result->fetch_assoc();
        $name = $edit_row['full_name'];
        $number = $edit_row['phone_number'];
        $email = $edit_row['email'];
        $country = $edit_row['country'];
        $state = $edit_row['state'];
        $city = $edit_row['city'];
    }
}

if (isset($_POST['submit'])) {
    $newName = $_POST['name'];
    $newNumber = $_POST['number'];
    $newEmail = $_POST['email'];
    $newCountry = $_POST['country'];
    $newState = $_POST['state'];
    $newCity = $_POST['city'];
    $id = $_POST['id'];
    

    $update_sql = "UPDATE contact_form SET
        full_name = '$newName',
        phone_number = '$newNumber',
        email = '$newEmail',
        country = '$newCountry',
        state = '$newState',
        city = '$newCity'
        WHERE id = $id";

    if ($conn->query($update_sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5">
      <span class="text-primary h2 mb-5">Contact Form</span>
     
      <div class="mb-2"></div>
        <?php
            if (isset($_GET['success']) && $_GET['success'] == 1) {
                echo "<p class='text-success'>Form submitted successfully!</p>";
                echo '<meta http-equiv="refresh" content="1.5;url=index.php">';
            }
        ?>
        <form id="contactForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"  autocomplete="off" enctype="multipart/form-data">
            <div class="form-group">
              <label for="fullName" class="text-danger h5">* Full Name:</label>
              <input type="text" name="name" class="form-control" id="fullName" placeholder="Enter your full name"  value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
              <input type="hidden" name="id" value="<?php echo $id;?>">
              <span class="text-danger"><?php if(isset($msg['name'])): ?><p><?php echo $msg['name']; ?><?php endif; ?></span>
            </div>

            <div class="form-group" >
              <label for="phoneNumber" class="text-danger h5">* Phone Number:</label>
              <input type="tel" name="number" class="form-control" id="phoneNumber" placeholder="Enter your phone number" value="<?php echo isset($number) ? htmlspecialchars($number) : ''; ?>">
              <span class="text-danger"><?php if(isset($msg['number'])): ?><p><?php echo $msg['number']; ?><?php endif; ?></span>
            </div>

            <div class="form-group">
              <label for="email" class="text-danger h5">* Email:</label>
              <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email address" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
              <span class="text-danger"><?php if(isset($msg['email'])): ?><p><?php echo $msg['email']; ?><?php endif; ?></span>
            </div>
                     
            <div class="form-group">
                <label for="country" class="text-danger h5">* Country:</label>
                <select name="country" id="country" class="form-control">
                    <option value="">Please Select Your Country</option>
                    <option value="Country 1" <?php if(isset($country) && $country == 'Country 1') echo 'selected="selected"'; ?>>Country 1</option>
                    <option value="Country 2" <?php if(isset($country) && $country == 'Country 2') echo 'selected="selected"'; ?>>Country 2</option>
                </select>
                <span class="text-danger"><?php if(isset($msg['country'])): ?><p><?php echo $msg['country']; ?><?php endif; ?></span>
            </div>
                
            <div class="form-group">
                <label for="state" class="text-danger h5">* State:</label>
                <select name="state" id="state" class="form-control">
                    <option value="">Please Select your State</option>
                    <option value="State 1" <?php if(isset($state) && $state == 'State 1') echo 'selected="selected"'; ?>>State 1</option>
                    <option value="State 2" <?php if(isset($state) && $state == 'State 2') echo 'selected="selected"'; ?>>State 2</option>
                </select>
                <span class="text-danger"><?php if(isset($msg['state'])): ?><p><?php echo $msg['state']; ?><?php endif; ?></span>
            </div>
                
            <div class="form-group">
                <label for="city" class="text-danger h5">* City:</label>
                <select name="city" id="city" class="form-control">
                    <option value="">Please Select Your City</option>
                    <option value="City 1" <?php if(isset($city) && $city == 'City 1') echo 'selected="selected"'; ?>>City 1</option>
                    <option value="City 2" <?php if(isset($city) && $city == 'City 2') echo 'selected="selected"'; ?>>City 2</option>
                </select>
                <span class="text-danger"><?php if(isset($msg['city'])): ?><p><?php echo $msg['city']; ?><?php endif; ?></span>    
            </div>
          
            <button type="submit" name="submit" class="btn btn-primary" onclick="alert('Data Updated')">Submit</button>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

