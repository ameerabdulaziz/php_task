<?php
require_once 'functions.php';

if (isset($_POST['submit'])):
    $file = $_FILES['csv_file'];
    $file_name = $file['name'];
    $ext = pathinfo(strtolower($file_name), PATHINFO_EXTENSION);
    if ($ext !== 'csv'):
        $_SESSION['error'] = 'Sorry, CSV file is the only accepted!';
        header('Location: index.php');
        return;
    else:
        $file_location = $file['tmp_name'];
        $customer_names = get_customer_info($file_location)[0];
        $customer_phones = get_customer_info($file_location)[1];
        $message_body = $_POST['message'];
        $message_detail = send_message_to_customers($customer_phones, $message_body);
        save_message_detail($customer_names, $customer_phones, $message_body, $message_detail[0], $message_detail[1], $message_detail[2]);
        $_SESSION['success'] = 'Message sent!';
//        header('Location: index.php');
//        return;
    endif;
endif;
?>

<form action="" method="post" enctype="multipart/form-data">
    <?php if (isset($_SESSION['error'])):?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error'] ?><?php unset($_SESSION['error']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php elseif (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success'] ?><?php unset($_SESSION['success']); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif;?>
    <div class="form-group">
        <label for="csv_file">Upload a CSV file:</label>
        <input type="file" class="form-control-file" id="csv_file" name="csv_file" required>
    </div>
    <div class="form-group">
        <label for="message">Message:</label>
        <textarea class="form-control-file" name="message" id="message" cols="30" rows="10" required></textarea>
    </div>
    <button class="btn btn-success" name="submit" type="submit">Send</button>
</form>