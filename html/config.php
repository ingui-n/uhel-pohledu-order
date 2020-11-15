<?php
declare(strict_types=1);


class config
{
    const
        //book price
        BOOK_PRICE = 499,

        //transport prices
        TRANSPORT_TYPES = [
                'cashOnDelivery' => 150,
                'moneyTransfer' => 0
        ],

        //smptp host
        SMTP_HOST = 'mail.look-e.cz',

        //smpt user
        SMTP_USER = 'noreply@uhel-pohledu.cz',

        //email password
        EMAIL_PASSWORD = "",

        //sender email
        SENDER_EMAIL = "noreply@uhel-pohledu.cz",

        //email order subject
        EMAIL_ORDER_SUBJECT = "Nová objednávka",

        //order email receiver
        ORDER_EMAIL = 'objednavka@uhel-pohledu.cz',

        //back link
        BACK_LINK = "",

        //terms link
        TERMS_LINK = "";
}