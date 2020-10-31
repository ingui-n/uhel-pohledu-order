<?php
declare(strict_types=1);

use app\Exceptions\OrderException;

class Order
{
    const ERROR_CODES = [
        1 => '',
    ];

    const BOOK_PRICE = 499;
    const TRANSPORT_TYPES = ['cashOnDelivery' => 150, 'moneyTransfer' => 0];

    private int $transport = 0;
    private int $quantity = 0;
    private int $fullPrice = 0;
    private string $firstName = '';
    private string $lastName = '';
    private string $street = '';
    private string $town = '';
    private int $zipCode = 0;
    private int $phoneNumber = 0;
    private string $email = '';

    private bool $issetOrder = false;

    private array $forbiddenString = ['[', '_', '!', '#', '$', '%', '^', '&', '*', '(', ')', '<', '>', '?', '/', '|', '}', '{', '~', ':', ']'];

    private bool $inputsValid = false;
    private ?int $errorCode=null;

    /**
     * Was order sent?
     * @return bool
     */
    public function isOrderSent(): bool
    {
        return isset($_POST) && isset($_POST["order"]);
    }

    private function validateInputs(): bool
    {
        $this->inputsValid = false;
        try {
            $this->getTransportFromSession();


            return $this->inputsValid = true;
        } catch (OrderException $e) {
            $this->errorCode = $e->getCode();
        }
        return $this->inputsValid = false;
    }


    public function makeOrder(): bool
    {
        if ($this->getAllInputsFromPost()) {
            if ($this->sumFullPrice()) {
                $this->issetOrder = true;
                //return 1;
            }
        }
        return false;
    }

    public function onSuccess(): bool
    {
        //todo onSuccess
        return $this->issetOrder;
    }

    /**
     * Get ALL values form POST
     * @return bool
     */

    private function getAllInputsFromPost(): bool
    {
        if ($this->getTransportFromSession())
            if ($this->getQuantityFromPost())
                if ($this->getFirstNameFromPost())
                    if ($this->getLastNameFromPost())
                        if ($this->getStreetFromPost())
                            if ($this->getTownFromPost())
                                if ($this->getZipCodeFromPost())
                                    if ($this->getPhoneNumberFromPost())
                                        if ($this->getEmailFromPost())
                                            return true;
        return false;

        /*$arr1 = ['order-quantity', 'order-first-name', 'order-last-name', 'order-street', 'order-town', 'order-zip-code', 'order-phone-number', 'order-email'];
        $arr2 = ['quantity', 'firstName', 'lastName', 'street', 'town', 'zipCode', 'phoneNumber', 'email'];

        foreach ($arr1 as $value => $item) {
            if (!$this->getItemFromPost($item, $arr2[$value]))
                return 0;
        }
        return 1;*/
    }

    /*private function getItemFromPost(string $postName, string $name): bool
    {
        if (isset($_POST[$postName])) {
            $this->$name = $_POST[$postName];
            return 1;
        }
        return 0;
    }*/

    /**
     * Get form values form POST
     * @param bool $throw
     * @return bool
     * @throws OrderException
     */
    private function getTransportFromSession(bool $throw=true): bool
    {
        if (isset($_POST['order-transport'])) {
            $transport = $_POST['order-transport'];
            $transportTypes = self::TRANSPORT_TYPES;

            foreach ($transportTypes as $transportType => $i) {
                if ($transport == $transportType) {
                    $this->transport = $i;
                    return true;
                }
            }
        }
        if($throw === true)
            throw new OrderException("Transport missing", 1);
        return false;
    }

    private function getQuantityFromPost(): bool
    {
        if (isset($_POST['order-quantity'])) {
            $quantity = $_POST['order-quantity'];

            if ($this->isInputValueValid('intQuantity', $quantity))
                return true;
        }
        return false;
    }

    /**
     * @return bool
     */

    private function getFirstNameFromPost(): bool
    {
        if (isset($_POST['order-first-name'])) {
            $firstName = $_POST['order-first-name'];

            if ($this->isInputValueValid('stringName', $firstName))
                return true;
        }
        return false;
    }

    /**
     * @return bool
     */

    private function getLastNameFromPost(): bool
    {
        if (isset($_POST['order-last-name'])) {
            $lastName = $_POST['order-last-name'];

            if ($this->isInputValueValid('stringName', $lastName, true))
                return true;
        }
        return false;
    }

    /**
     * @return bool
     */

    private function getStreetFromPost(): bool
    {
        if (isset($_POST['order-street'])) {
            $street = $_POST['order-street'];

            if ($this->isInputValueValid('floatStreet', $street))
                return true;
        }
        return false;
    }

    /**
     * @return bool
     */

    private function getTownFromPost(): bool
    {
        if (isset($_POST['order-town'])) {
            $town = $_POST['order-town'];

            if ($this->isInputValueValid('stringTown', $town))
                return true;
        }
        return false;
    }

    /**
     * @return bool
     */

    private function getZipCodeFromPost(): bool
    {
        if (isset($_POST['order-zip-code'])) {
            $zipCode = $_POST['order-zip-code'];

            if ($this->isInputValueValid('intZipCode', $zipCode))
                return true;
        }
        return false;
    }

    /**
     * @return bool
     */

    private function getPhoneNumberFromPost(): bool
    {
        if (isset($_POST['order-phone-number'])) {
            $phoneNumber = $_POST['order-phone-number'];

            if ($this->isInputValueValid('intPhoneNumber', $phoneNumber))
                return true;
        }
        return false;
    }

    /**
     * @return bool
     */

    private function getEmailFromPost(): bool
    {
        if (isset($_POST['order-email'])) {
            $email = $_POST['order-email'];

            if ($this->isInputValueValid('floatEmail', $email))
                return false;
        }
        return true;
    }

    /**
     * Checks input value, saves it to private variable
     * @param string $type
     * @param string $value
     * @param bool|null $level
     * @return bool
     */

    public function isInputValueValid(string $type, string $value, ?bool $level = false): bool
    {
        switch ($type) {
            case 'intQuantity':
                if (is_numeric($value)) {
                    if ($value > 0 && $value <= 100) {
                        $this->quantity = $value;
                        return true;
                    }
                }
                break;
            case 'stringName':
                if (!is_numeric($value) && $this->isStringValueValid($value)) {
                    if (!preg_match('/\d/', $value)) {
                        if (strlen($value) > 2 && strlen($value) < 20) {
                            $level ? $this->firstName = $value : $this->lastName = $value;
                            return true;
                        }
                    }
                }
                break;
            case 'floatStreet':
                if ($this->isStringValueValid($value)) {
                    if (preg_match('/\d/', $value)) {
                        $this->street = $value;
                        return true;
                    }
                }
                break;
            case 'stringTown':
                if ($this->isStringValueValid($value)) {
                    $this->town = $value;
                    return true;
                }
                break;
            case 'intZipCode':
                if (is_numeric($value) && strlen($value) == 5) {
                    $this->zipCode = $value;
                    return true;
                }
                break;
            case 'intPhoneNumber':
                if ($this->isStringValueValid($value)) {
                    $value = str_replace(' ', '', $value);
                    if (is_numeric($value) && strlen($value) == 9) {
                        $this->phoneNumber = $value;
                        return true;
                    }
                }
                break;
            case 'floatEmail':
                if ($this->isStringValueValid($value, true) && substr_count($value, '@') == 1) {
                    if (strpos($value, '@') != '') {
                        echo $value;
                        $atIndex = strpos($value, '@') + 1;
                        $domain = substr($value, $atIndex);

                        if (strpos($domain, '.') != '') {
                            $this->email = $value;
                            return true;
                        }
                    }
                }
                break;
        }
        return false;
    }

    /**
     * Checks forbidden characters in input string
     * @param string $value
     * @param bool|null $email
     * @return bool
     */

    public function isStringValueValid(string $value, ?bool $email = false): bool
    {
        $value = str_replace($this->forbiddenString, '', $value);
        if (!$email) {
            $value = str_replace('@', '', $value);
            $value = str_replace('.', '', $value);
        }
        if (strlen($value) > 0 && strlen($value) < 100)
            return true;
        return false;
    }

    private function sumFullPrice(): bool
    {
        $transport = $this->transport;
        $this->fullPrice = self::BOOK_PRICE * $this->quantity + $transport;

        return $this->fullPrice > $transport;
    }
}
