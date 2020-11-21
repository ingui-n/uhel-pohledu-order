<?php
if (isset($order) && $order instanceof Order) {
    ?>
    <div class="container">
        <p class="head-p">Objednávky do <b>zahraničí</b>, v počtu <b>více než X ks</b>, nebo s <b>
                osobním převzetím v Mladé Boleslavi</b> vyřizujeme <b>s individuálním přístupem</b>.
            Kontaktujte nás prosím na
            <a href="mailto:objednavky@uhel-pohledu.cz">objednavky@uhel-pohledu.cz</a>.
        </p>
        <form method="post" name="order">
            <p class="error-message error__transport"><?php echo $order->getErrorMessage(1); ?></p>
            <div class="transport">
                <div class="transport__left">
                    <label for="radio1" class="radio-container">
                        <input type="radio" name="order[transport]"
                               class="radio <?php echo $order->getValidationError(1) ? 'error-input' : ''; ?>"
                               id="radio1"
                               value="cashOnDelivery"
                               data-price="<?php echo config::TRANSPORT_TYPES['cashOnDelivery']; ?>" checked>
                        <img src="<?php echo config::PICTURE_PATH; ?>" class="picture-book" alt="Kniha">
                        <span class="checkmark"></span>
                    </label>
                    <div class="transport-option">
                        <p class="book-price"><span class="book__price"><?php echo $order->getBookPrice(); ?></span>,-/ks
                        </p>
                        <?php
                        if (config::TRANSPORT_TYPES['cashOnDelivery'] > 0) {
                            ?>
                            <p>+ Dobírka <?php echo config::TRANSPORT_TYPES['cashOnDelivery']; ?> Kč</p>
                            <?php
                        }
                        ?>
                        <p>Česká pošta - doporučeně</p>
                    </div>
                </div>
                <div class="transport__right">
                    <label for="radio2" class="radio-container">
                        <input type="radio" name="order[transport]"
                               class="radio <?php echo $order->getValidationError(1) ? 'error-input' : ''; ?>"
                               id="radio2"
                               value="moneyTransfer"
                               data-price="<?php echo config::TRANSPORT_TYPES['moneyTransfer']; ?>">
                        <img src="<?php echo config::PICTURE_PATH; ?>" class="picture-book" alt="Kniha">
                        <span class="checkmark"></span>
                    </label>
                    <div class="transport-option">
                        <p class="book-price"><span class="book__price"><?php echo $order->getBookPrice(); ?></span>,-/ks
                        </p>
                        <p>Platba převodem</p>
                        <p>Česká pošta - doporučeně</p>
                    </div>
                </div>
            </div>
            <div class="book-quantity">
                <input type="number" name="order[quantity]"
                       class="form-item <?php echo $order->getValidationError(2) ? 'error-input' : ''; ?>" id="quantity"
                       required value="<?php echo $postValues['quantity'] ?? '1'; ?>" min="1">
                <label for="quantity" class="basic-p quantity-label">ks</label>
                <p class="basic-p">Celkem: <span class="total-price">0</span> Kč</p>
            </div>
            <p class="error-message error__quantity"><?php echo $order->getErrorMessage(2); ?></p>
            <p class="form-head">Fakturační a dodací adresa:</p>
            <div class="form-text-inputs">
                <label for="first-name">Jméno:</label>
                <div>
                    <input type="text" name="order[first-name]"
                           class="form-item <?php echo $order->getValidationError(3) ? 'error-input' : ''; ?>"
                           id="first-name" required value="<?php echo $postValues['first-name'] ?? ''; ?>">
                    <p class="basic-p error-message error__first-name"><?php echo $order->getErrorMessage(3); ?></p>
                </div>
                <label for="last-name" class="basic-p">Příjmení:</label>
                <div>
                    <input type="text" name="order[last-name]"
                           class="form-item <?php echo $order->getValidationError(4) ? 'error-input' : ''; ?>"
                           id="last-name" required value="<?php echo $postValues['last-name'] ?? ''; ?>">
                    <p class="basic-p error-message error__last-name"><?php echo $order->getErrorMessage(4); ?></p>
                </div>
                <label for="street" class="basic-p">Ulice a číslo:</label>
                <div>
                    <input type="text" name="order[street]"
                           class="form-item <?php echo $order->getValidationError(5) ? 'error-input' : ''; ?>"
                           id="street"
                           required value="<?php echo $postValues['street'] ?? ''; ?>">
                    <p class="basic-p error-message error__street"><?php echo $order->getErrorMessage(5); ?></p>
                </div>
                <label for="town" class="basic-p">Město:</label>
                <div>
                    <input type="text" name="order[town]"
                           class="form-item <?php echo $order->getValidationError(6) ? 'error-input' : ''; ?>" id="town"
                           required value="<?php echo $postValues['town'] ?? ''; ?>">
                    <p class="basic-p error-message error__town"><?php echo $order->getErrorMessage(6); ?></p>
                </div>
                <label for="zip-code" class="basic-p">PSČ:</label>
                <div>
                    <input type="text" name="order[zip-code]"
                           class="form-item <?php echo $order->getValidationError(7) ? 'error-input' : ''; ?>"
                           id="zip-code"
                           required value="<?php echo $postValues['zip-code'] ?? ''; ?>">
                    <p class="basic-p error-message error__zip-code"><?php echo $order->getErrorMessage(7); ?></p>
                </div>
                <label for="phone-number" class="basic-p">Telefon:</label>
                <div>
                    <input type="text" name="order[phone-number]"
                           class="form-item <?php echo $order->getValidationError(8) ? 'error-input' : ''; ?>"
                           id="phone-number" required value="<?php echo $postValues['phone-number'] ?? ''; ?>">
                    <p class="basic-p error-message error__phone-number"><?php echo $order->getErrorMessage(8); ?></p>
                </div>
                <label for="email" class="basic-p">E-mail:</label>
                <div>
                    <input type="text" name="order[email]"
                           class="form-item <?php echo $order->getValidationError(9) ? 'error-input' : ''; ?>"
                           id="email"
                           required value="<?php echo $postValues['email'] ?? ''; ?>">
                    <p class="basic-p error-message error__email"><?php echo $order->getErrorMessage(9); ?></p>
                </div>
            </div>
            <div class="terms">
                <div class="checkbox-block">
                    <label for="terms" class="label-terms">
                        <input type="checkbox" class="form-item" id="terms" required value="">
                        Souhlasím s <a href="<?php echo config::TERMS_LINK; ?>">obchodními podmínkami a zpracováním
                            údajů pro účely
                            vyřízení objednávky</a>.
                        <span class="checkmark-checkbox"></span>
                    </label>
                </div>
                <p class="error-message error__terms"></p>
            </div>
            <div class="buttons">
                <a href="<?php echo config::BACK_LINK; ?>">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Zavřít</button>
                </a>
                <input type="submit" class="btn btn-primary submit-button" value="Odeslat objednávku">
            </div>
        </form>
    </div>
    <?php
}