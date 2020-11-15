<?php
declare(strict_types=1);
include_once '../vendor/autoload.php';
include_once __DIR__ . '/config.php';
include_once __DIR__ . '/Order/Order.php';
include_once __DIR__ . '/Order/Exceptions/OrderException.php';
include_once __DIR__ . '/PostFilter/PostFilter.php';
include_once __DIR__ . '/Message/Message.php';

$order = new Order();
$postValues = PostFilter::getPostValues('order');
if(is_array($postValues))
{
    //prepare message
    $message = new Message(config::SMTP_HOST,
        config::SMTP_USER,
        config::EMAIL_PASSWORD,
        config::SENDER_EMAIL,
        config::ORDER_EMAIL
    );

    //fill order
    $order
        ->setTransport($postValues['transport'] ?? null)
        ->setQuantity((int)$postValues['quantity'] ?? null)
        ->setFirstName($postValues['first-name'] ?? null)
        ->setLastName($postValues['last-name'] ?? null)
        ->setStreet($postValues['street'] ?? null)
        ->setTown($postValues['town'] ?? null)
        ->setZipCode($postValues['zip-code'] ?? null)
        ->setPhoneNumber($postValues['phone-number'] ?? null)
        ->setEmail($postValues['email'] ?? null)
        ->setOnSuccessCallback(function(Order $order) use ($message){
            try {
                $message
                    ->setSubject(config::EMAIL_ORDER_SUBJECT)
                    ->send($order);
                return true;
            } catch (\PHPMailer\PHPMailer\Exception $e) {
                return false;
            }
        })
        ->sentOrder();
}