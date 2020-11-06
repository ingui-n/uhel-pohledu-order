<?php
session_start();
include_once __DIR__ . '/php/Order.php';
include_once __DIR__ . '/php/PostFilter.php';

$post = new PostFilter('order');
$order = new Order();

//if($values = is_array($post->getPostValues()))
if(is_array($post->getPostValues())) {
    $values = $post->getPostValues();
    $order->setTransport($values['transport'] ?? null);
    $order->setQuantity($values['quantity'] ?? null);
    $order->setFirstName($values['first-name'] ?? null);
    $order->setLastName($values['last-name'] ?? null);
    $order->setStreet($values['street'] ?? null);
    $order->setTown($values['town'] ?? null);
    $order->setZipCode($values['zip-code'] ?? null);
    $order->setPhoneNumber($values['phone-number'] ?? null);
    $order->setEmail($values['email'] ?? null);
    $order->sentOrder();
}

?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/style.css">
    <title>Objednávka | Úhel pohledu</title>
</head>

<body>
<?php
$bookPrice = $order->getBookPrice();

print_r($order->isInvalid('firstName'));//todo error classes

if ($order->printOrder() === true) {
    ?>
    <div class="container">
        <div class="centered">
            <p>Objednávky do <b>zahraničí</b>, v počtu <b>více než X ks</b>, nebo s <b>osobním převzetím v Mladé
                    Boleslavi</b>
                vyřizujeme <b>s individuálním přístupem</b>. Kontaktujte nás prosím na
                <a href="mailto:objednavky@uhel-pohledu.cz">objednavky@uhel-pohledu.cz</a>.</p>
            <div class="form">
                <form method="post" name="order">
                    <input type="hidden" name="order">
                    <div class="transport">
                        <div class="transport__left">
                            <input type="radio" name="order[transport]" class="<?php echo $order->isInvalid('transport') ? 'error-class' : ''; ?>" id="radio" value="cashOnDelivery" data-price="150" checked>
                            <label for="radio"><img src="images/book.jpg" alt="Kniha"></label>
                            <p class="book-price"><span class="book__price"><?php echo $bookPrice; ?></span>,-/ks</p>
                            <p>+ Dobírka 150 Kč</p>
                            <p>Česká pošta - doporučeně:</p>
                        </div>
                        <div class="transport__right">
                            <input type="radio" name="order[transport]" class="<?php echo $order->isInvalid('transport') ? 'error-class' : ''; ?>" id="radio" value="moneyTransfer" data-price="0">
                            <label for="radio"><img src="images/book.jpg" alt="Kniha"></label>
                            <p class="book-price"><span class="book__price"><?php echo $bookPrice; ?></span>,-/ks</p>
                            <p>Platba převodem</p>
                            <p>Česká pošta - doporučeně:</p>
                        </div>
                    </div>
                    <div class="book-quantity">
                        <label for="quantity">Množství:</label>
                        <input type="number" name="order[quantity]" class="form-item <?php echo $order->isInvalid('quantity') ? 'error-class' : ''; ?>" id="quantity" pattern="[0-9].{1,3}" required value="<?php echo $values['quantity'] ?? 1 ?>">
                        <label for="quantity">ks</label>
                        <p>Celkem: <span class="total-price">0</span> Kč</p>
                    </div>
                    <div class="form-text-inputs">
                        <p>Fakturační a dodací adresa:</p>
                        <label for="first-name">Jméno:</label>
                        <input type="text" name="order[first-name]" class="form-item <?php echo $order->isInvalid('firstName') ? 'error-class' : ''; ?>" id="first-name" min="2" max="20" required value="<?php echo $values['first-name'] ?? '' ?>">
                        <label for="last-name">Příjmení:</label>
                        <input type="text" name="order[last-name]" class="form-item <?php echo $order->isInvalid('lastName') ? 'error-class' : ''; ?>" id="last-name" min="2" max="20" required value="<?php echo $values['last-name'] ?? '' ?>">
                        <label for="street">Ulice a číslo:</label>
                        <input type="text" name="order[street]" class="form-item <?php echo $order->isInvalid('street') ? 'error-class' : ''; ?>" id="street" min="4" max="70" required value="<?php echo $values['street'] ?? '' ?>">
                        <label for="town">Město:</label>
                        <input type="text" name="order[town]" class="form-item <?php echo $order->isInvalid('town') ? 'error-class' : ''; ?>" id="town" min="2" max="70" required value="<?php echo $values['town'] ?? '' ?>">
                        <label for="zip-code">PSČ:</label>
                        <input type="number" name="order[zip-code]" class="form-item <?php echo $order->isInvalid('zipCode') ? 'error-class' : ''; ?>" id="zip-code" min="5" required value="<?php echo $values['zip-code'] ?? '' ?>">
                        <label for="phone-number">Telefon:</label>
                        <input type="number" name="order[phone-number]" class="form-item <?php echo $order->isInvalid('phoneNumber') ? 'error-class' : ''; ?>" id="phone-number" min="8" required value="<?php echo $values['phone-number'] ?? '' ?>">
                        <label for="email">E-mail:</label>
                        <input type="text" name="order[email]" class="form-item <?php echo $order->isInvalid('email') ? 'error-class' : ''; ?>" id="email" min="4" max="50" required value="<?php echo $values['email'] ?? '' ?>">
                    </div>
                    <input type="checkbox" class="form-item" id="terms" required value="/checkbox">
                    <label for="terms">Souhlasím s <a href="<?php //terms.html ?>">obchodními podmínkami a zpracováním
                            údajů pro účely vyřízení objednávky</a>.</label>
                    <input type="submit" class="submit-button__true" value="Odeslat objednávku">
                </form>
                <button class="submit-button__false">Odeslat objednávku</button>
            </div>
        </div>
    </div>
    <?php
}else {

    //objednávka byla úspěšně odeslána ...

}
?>
<script src="scripts/script.js"></script>
</body>
