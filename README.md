# Order form for uhel-pohledu.cz

## Config
For config constants go to config.php file:
- BOOK_PRICE = numeric integer value with book full price with VAT
- TRANSPORT_TYPES = [
    'cashOnDelivery' => integer value of cash on delivery price
    'moneyTransfer'  => integer value of money transfer price 
]
- SMTP_HOST = smtp server
- SMTP_USER = smtp user
- EMAIL_PASSWORD = password to connect to smtp
- SENDER_EMAIL = email address that will be showed as sender
- ORDER_EMAIL = email address to sent info about order
- BACK_LINK = back link button from form
- TERMS_LINK = link to open shop terms
- PICTURE_PATH = path to product picture

## INSTALLATION
1) RUN command 'composer update' to download libraries of third parity
2) Check your settings in config.php
3) Place folder with code anywhere you want
4) enjoy
