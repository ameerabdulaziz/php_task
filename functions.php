<?php
require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;

require_once 'config.php';

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

function send_message_to_customers($customer_phones, $message_body){
    $account_sid = TWILIO_ACCOUNT_SID;
    $auth_token = 'TWILIO_AUTH_TOKEN';
    $twilio_number = "TWILIO_NUMBER";
    $client = new Client($account_sid, $auth_token);
    foreach ($customer_phones as $phone):
        $client->messages->create(
            $phone,
            array(
                'from' => $twilio_number,
                'body' => $message_body
            )
        );
    endforeach;
}