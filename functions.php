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
    $twilio = new Client(TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN);
    $message_sids = array();
    $message_statuses = array();
    $message_dates = array();
    foreach ($customer_phones as $phone):
        $message = $twilio->messages
            ->create($phone,
                array(
                    "body" => $message_body,
                    "from" => TWILIO_NUMBER,
                )
            );
        array_push($message_sids, $message->sid);
        array_push($message_statuses, $twilio->messages($message->sid)->fetch()->status);
        array_push($message_dates, $twilio->messages($message->sid)->fetch()->dateSent->format('Y-m-d H:i:s'));
    endforeach;
    return array($message_sids, $message_statuses, $message_dates);
}

function save_message_detail($names, $phones, $message, $sids, $statuses, $dates){
    $headers =  ['Name', 'Cellphone number', 'Message', 'Message_sid', 'Status', 'Sent_date-time'];
    $file = fopen(BASE_PRO.'/message_detail.csv', 'w');
    fputcsv($file, $headers);
    foreach($names as $key => $content){
        fputcsv($file, [$names[$key], $phones[$key], $message, $sids[$key], $statuses[$key], $dates[$key]]);
    }
}