<?php
session_start();
include_once __DIR__ . '/php/Order.php';
include_once __DIR__ . '/php/PostFilter.php';

//$_POST['order'] = null;
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles/style.css">
    <title>Objednávka | Úhel pohledu</title>
</head>

<body>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Koupit knihu</button>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Objednávka</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php

                if ($order->printOrder() === true) {
                    $bookPrice = $order->getBookPrice();

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
                                        <p class="error-message error__transport"><?php echo $order->getErrorMessage(1); ?></p>
                                        <div class="transport__left">
                                            <input type="radio" name="order[transport]" class="<?php echo $order->getValidationError(1) ? 'error-input' : ''; ?>" id="radio" value="cashOnDelivery" data-price="150" checked>
                                            <label for="radio"><img src="images/book.jpg" class="picture-book" alt="Kniha"></label>
                                            <p class="book-price"><span class="book__price"><?php echo $bookPrice; ?></span>,-/ks</p>
                                            <p>+ Dobírka 150 Kč</p>
                                            <p>Česká pošta - doporučeně:</p>
                                        </div>
                                        <div class="transport__right">
                                            <input type="radio" name="order[transport]" class="<?php echo $order->getValidationError(1) ? 'error-input' : ''; ?>" id="radio" value="moneyTransfer" data-price="0">
                                            <label for="radio"><img src="images/book.jpg" class="picture-book" alt="Kniha"></label>
                                            <p class="book-price"><span class="book__price"><?php echo $bookPrice; ?></span>,-/ks</p>
                                            <p>Platba převodem</p>
                                            <p>Česká pošta - doporučeně:</p>
                                        </div>
                                    </div>
                                    <div class="book-quantity">
                                        <label for="quantity">Množství:</label>
                                        <input type="number" name="order[quantity]" class="form-item <?php echo $order->getValidationError(2) ? 'error-input' : ''; ?>" id="quantity" required value="<?php echo $values['quantity'] ?? '1' ?>">
                                        <label for="quantity">ks</label>
                                        <p>Celkem: <span class="total-price">0</span> Kč</p>
                                        <p class="error-message error__quantity"><?php echo $order->getErrorMessage(2); ?></p>
                                    </div>
                                    <div class="form-text-inputs">
                                        <p>Fakturační a dodací adresa:</p>
                                        <label for="first-name">Jméno:</label>
                                        <input type="text" name="order[first-name]" class="form-item <?php echo $order->getValidationError(3) ? 'error-input' : ''; ?>" id="first-name" required value="<?php echo $values['first-name'] ?? '' ?>">
                                        <p class="error-message error__first-name"><?php echo $order->getErrorMessage(3); ?></p>
                                        <label for="last-name">Příjmení:</label>
                                        <input type="text" name="order[last-name]" class="form-item <?php echo $order->getValidationError(4) ? 'error-input' : ''; ?>" id="last-name" required value="<?php echo $values['last-name'] ?? '' ?>">
                                        <p class="error-message error__last-name"><?php echo $order->getErrorMessage(4); ?></p>
                                        <label for="street">Ulice a číslo:</label>
                                        <input type="text" name="order[street]" class="form-item <?php echo $order->getValidationError(5) ? 'error-input' : ''; ?>" id="street" required value="<?php echo $values['street'] ?? '' ?>">
                                        <p class="error-message error__street"><?php echo $order->getErrorMessage(5); ?></p>
                                        <label for="town">Město:</label>
                                        <input type="text" name="order[town]" class="form-item <?php echo $order->getValidationError(6) ? 'error-input' : ''; ?>" id="town" required value="<?php echo $values['town'] ?? '' ?>">
                                        <p class="error-message error__town"><?php echo $order->getErrorMessage(6); ?></p>
                                        <label for="zip-code">PSČ:</label>
                                        <input type="text" name="order[zip-code]" class="form-item <?php echo $order->getValidationError(7) ? 'error-input' : ''; ?>" id="zip-code" required value="<?php echo $values['zip-code'] ?? '' ?>">
                                        <p class="error-message error__zip-code"><?php echo $order->getErrorMessage(7); ?></p>
                                        <label for="phone-number">Telefon:</label>
                                        <input type="text" name="order[phone-number]" class="form-item <?php echo $order->getValidationError(8) ? 'error-input' : ''; ?>" id="phone-number" required value="<?php echo $values['phone-number'] ?? '' ?>">
                                        <p class="error-message error__phone-number"><?php echo $order->getErrorMessage(8); ?></p>
                                        <label for="email">E-mail:</label>
                                        <input type="text" name="order[email]" class="form-item <?php echo $order->getValidationError(9) ? 'error-input' : ''; ?>" id="email" required value="<?php echo $values['email'] ?? '' ?>">
                                        <p class="error-message error__email"><?php echo $order->getErrorMessage(9); ?></p>
                                    </div>
                                    <input type="checkbox" class="form-item" id="terms" required value="">
                                    <label for="terms">Souhlasím s <a href="<?php //terms.html ?>">obchodními podmínkami a zpracováním
                                            údajů pro účely vyřízení objednávky</a>.</label>
                                    <p class="error__terms"></p>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                }else {
                    ?>
                    <div>
                        <p>Objednávka byla úspěšně vyřízena!</p>
                        <p>Na vaší e-mailovou adresu Vám byla zaslána faktura o provedené objednávce.</p>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Zavřít</button>
                <input type="submit" class="btn btn-primary submit-button" value="Odeslat objednávku">
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script src="scripts/script.js"></script>

</body>
