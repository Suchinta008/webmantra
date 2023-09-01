<?php 
include "connection.php";

$msg=[];
$output_dir = "upload/";/* Path for file upload */

if (isset($_POST['submit'])) 
{
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $city = $_POST['city'];

    if (!isset($name) || empty($name) || strlen($name) < 2 || strlen($name) > 50) {
        $msg['name'] = 'Please enter valid name with minimum 2 characters and maximum of 50 characters';
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $msg['name'] = 'Only alphabets and white spaces are allowed in the name.';
    }
    if (!isset($number) || empty($number) || (strlen($number) !== 10)) {
        $msg['number'] = "Please enter valid 10 digit phone number.";
    } elseif (!preg_match("/^\d+$/", $number)) {
        $msg['number'] = "Please enter a valid phone number.";
    }
    if (!isset($email) || empty($email)) {
        $msg['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg['email'] = "Email is not valid.";
    }
    if(empty($country)) {
        $msg['country'] = 'Please Select a value for the country name';
    }
    if(empty($state)) {
        $msg['state'] = 'Please Select a value for the state name';
    }
    if(empty($city)) {
        $msg['city'] = 'Please Select a value for the city name';
    }
//  Image 

if (!empty($_FILES['picture']['name'][0])) {
    $total_files = count($_FILES['picture']['name']);
    $uploaded_files = array();

    for ($i = 0; $i < $total_files; $i++) {
        $file_name = $_FILES['picture']['name'][$i];
        $file_tmp = $_FILES['picture']['tmp_name'][$i];
        $file_size = $_FILES['picture']['size'][$i];
        $file_type = $_FILES['picture']['type'][$i];
        $file_ext = strtolower(end(explode('.', $file_name)));
        $extensions = array("jpeg", "jpg", "png", "gif");

        if (in_array($file_ext, $extensions) === false) {
            $msg['image'] = "Extension not allowed, please choose a valid image file.";
        }

        if ($file_size > 2097152) {
            $msg['image'] = "File size must be less than 2 MB.";
        }

        if (empty($msg)) {
            $new_file_name = uniqid() . "." . $file_ext;
            $target_file = $output_dir . $new_file_name;
            if (move_uploaded_file($file_tmp, $target_file)) {
                $uploaded_files[] = $new_file_name; // Add the file name to the array
            }
        }
    }

    // Combine all uploaded file names into a comma-separated string
    $pic = implode(',', $uploaded_files);
}
    if (empty($msg)) {
            $sql = "INSERT INTO contact_form (full_name, phone_number, email, country, state, city, pic) VALUES ('$name','$number','$email','$country','$state','$city','$new_file_name')";
            if ($conn->query($sql) === true) {
                header("Location: index.php?success=1");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
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
      <a href="action.php">
          <button type="button" class="btn btn-warning float-right">Action</button>
      </a>
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
                
            <div class="form-group">
                <label for="pic">
                    <input type="file" name="picture[]" multiple>
                </label>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

