<?php


class Order
{
    private string $transport = '';
    private string $quantity = '';
    private string $firstName = '';
    private string $lastName = '';
    private string $street = '';
    private string $town = '';
    private string $zipCode = '';
    private string $phoneNumber = '';
    private string $email = '';

    private bool $issetOrder = false;

    private array $forbiddenString = ['[', '_', '!', '#', '$', '%', '^', '&', '*', '(', ')', '<', '>', '?', '/', '|', '}', '{', '~', ':', ']'];

    public function makeOrder(): bool
    {
        if ($this->getAllInputsFromPost()) {
            $this->issetOrder = 1;
            return 1;
        }
        return 0;
    }

    public function onSuccess(): bool
    {
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
                                            return 1;
        return 0;

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
     * @return bool
     */

    private function getTransportFromSession(): bool
    {
        if (isset($_POST['order-transport'])) {
            $this->transport = $_POST['order-transport'];
            return 1;
        }
        return 0;
    }

    private function getQuantityFromPost(): bool
    {
        if (isset($_POST['order-quantity'])) {
            $quantity = $_POST['order-quantity'];

            if ($this->isInputValueValid('intQuantity', $quantity))
                return 1;
        }
        return 0;
    }

    /**
     * @return bool
     */

    private function getFirstNameFromPost(): bool
    {
        if (isset($_POST['order-first-name'])) {
            $firstName = $_POST['order-first-name'];

            if ($this->isInputValueValid('stringName', $firstName))
                return 1;
        }
        return 0;
    }

    /**
     * @return bool
     */

    private function getLastNameFromPost(): bool
    {
        if (isset($_POST['order-last-name'])) {
            $lastName = $_POST['order-last-name'];

            if ($this->isInputValueValid('stringName', $lastName, 1))
                return 1;
        }
        return 0;
    }

    /**
     * @return bool
     */

    private function getStreetFromPost(): bool
    {
        if (isset($_POST['order-street'])) {
            $street = $_POST['order-street'];

            if ($this->isInputValueValid('floatStreet', $street))
                return 1;
        }
        return 0;
    }

    /**
     * @return bool
     */

    private function getTownFromPost(): bool
    {
        if (isset($_POST['order-town'])) {
            $town = $_POST['order-town'];

            if ($this->isInputValueValid('stringTown', $town))
                return 1;
        }
        return 0;
    }

    /**
     * @return bool
     */

    private function getZipCodeFromPost(): bool
    {
        if (isset($_POST['order-zip-code'])) {
            $zipCode = $_POST['order-zip-code'];

            if ($this->isInputValueValid('IntZipCode', $zipCode))
                return 1;
        }
        return 0;
    }

    /**
     * @return bool
     */

    private function getPhoneNumberFromPost(): bool
    {
        if (isset($_POST['order-phone-number'])) {
            $phoneNumber = $_POST['order-phone-number'];

            if ($this->isInputValueValid('intPhoneNumber', $phoneNumber))
                return 1;
        }
        return 0;
    }

    /**
     * @return bool
     */

    private function getEmailFromPost(): bool
    {
        if (isset($_POST['order-email'])) {
            $email = $_POST['order-email'];

            if ($this->isInputValueValid('floatEmail', $email))
                return 1;
        }
        return 0;
    }

    /**
     * Checks input value, saves it to private variable
     * @param string $type
     * @param string $value
     * @param bool|null $level
     * @return bool
     */

    private function isInputValueValid(string $type, string $value, ?bool $level = false): bool
    {
        switch ($type) {
            case 'intQuantity':
                if (is_int($value)) {
                    if ($value > 0 && $value <= 50) {
                        $this->quantity = $value;
                        return 1;
                    }
                }
                break;
            case 'stringName':
                if (!is_int($value) && $this->isStringValueValid($value)) {
                    if (!preg_match('/\d/', $value)) {
                        if ($value > 2 && $value < 20) {
                            $level ? $this->firstName = $value : $this->lastName = $value;
                            return 1;
                        }
                    }
                }
                break;
            case 'floatStreet':
                if ($this->isStringValueValid($value)) {
                    if (preg_match('/\d/', $value)) {
                        $this->street = $value;
                        return 1;
                    }
                }
                break;
            case 'stringTown':
                if ($this->isStringValueValid($value)) {
                    $this->town = $value;
                    return 1;
                }
                break;
            case 'intZipCode':
                if (is_int($value) && strlen($value) == 5) {
                    $this->zipCode = $value;
                    return 1;
                }
                break;
            case 'intPhoneNumber':
                if (is_int($value) && strlen($value) == 9) {
                    $this->phoneNumber = $value;
                    return 1;
                }
                break;
            case 'floatEmail':
                if ($this->isStringValueValid($value, 1) && substr_count($value, '@') == 1)
                    if (strpos($value, '@') != '') {
                        $atIndex = strpos($value, '@') + 1;
                        $domain = substr($value, $atIndex);

                        if (strpos($domain, '.') != '') {
                            $this->email = $value;
                            return 1;
                        }
                    }
                break;
        }
        return 0;
    }

    /**
     * Checks forbidden characters in input string
     * @param string $value
     * @param bool|null $email
     * @return bool
     */

    private function isStringValueValid(string $value, ?bool $email = false): bool
    {
        $value = str_replace($this->forbiddenString, '', $value);
        if (!$email) {
            $value = str_replace('@', '', $value);
            $value = str_replace('.', '', $value);
        }

        if ($value > 0 && $value < 100)
            return 1;
        return 0;
    }
}
