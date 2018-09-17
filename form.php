<?php
function get_customer_info($file_location){
    $rows = array();
    foreach (file($file_location, FILE_IGNORE_NEW_LINES) as $line):
        $rows[] = str_getcsv($line);
    endforeach;
    array_shift($rows);
    $customer_names = array();
    $customer_phones = array();
    foreach ($rows as $row):
        array_push($customer_names, $row[0]);
        array_push($customer_phones, $row[1]);
    endforeach;
    return array($customer_names, $customer_phones);
}
if (isset($_POST['submit'])):
    $file = $_FILES['csv_file'];
    $file_name = $file['name'];
    $ext = pathinfo(strtolower($file_name), PATHINFO_EXTENSION);
    if ($ext !== 'csv'):

    else:
        $file_location = $file['tmp_name'];
        $customer_names = get_customer_info($file_location)[0];
        $customer_phones = get_customer_info($file_location)[1];
        print_r($customer_names);
        print_r($customer_phones);
    endif;
endif;
?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="csv_file">Upload a CSV file:</label>
        <input type="file" class="form-control-file" id="csv_file" name="csv_file">
    </div>
    <div class="form-group">
        <label for="message">Message:</label>
        <textarea class="form-control-file" name="message" id="message" cols="30" rows="10"></textarea>
    </div>
    <button class="btn btn-success" name="submit" type="submit">Send</button>
</form>