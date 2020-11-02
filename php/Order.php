<?php
declare(strict_types=1);

include_once __DIR__ . '/PostFilter.php';

use app\Exceptions\OrderException;

class Order
{
    const ERROR_CODES = [
        1 => '',
        2 => '',
        3 => '',
        4 => '',
        5 => '',
        6 => '',
        7 => '',
        8 => '',
        9 => '',
        10 => '',
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

    private array $forbiddenString = ['[', '@', '.', '_', '!', '#', '$', '%', '^', '&', '*', '(', ')', '<', '>', '?', '/', '|', '}', '{', '~', ':', ']'];

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
        $values = new PostFilter('order');


        try {
            if ($values == $values->getPostValues() && $values->getPostValues() > 0) {
                $postValues = $values->getPostValues();
                $this->setTransport($postValues['order-transport']);
                $this->setQuantity($postValues['order-quantity']);
                $this->setFirstName($postValues['order-first-name']);
                $this->setLastName($postValues['order-last-name']);
                $this->setStreet($postValues['order-street']);
                $this->setTown($postValues['order-town']);
                $this->setZipCode($postValues['order-zip-code']);
                $this->setPhoneNumber($postValues['order-phone-number']);
                $this->setEmail($postValues['order-email']);

                return $this->inputsValid = true;
            } else {
                throw new OrderException("Something went wrong", 10);
            }
        } catch (OrderException $e) {
            $this->errorCode = $e->getCode();
        }
        return $this->inputsValid = false;
    }


    public function makeOrder(): bool
    {
        if ($this->validateInputs()) {
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
     * @throws OrderException
     */
/*
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
    }*/

    /**
     * Get form values form POST
     * @param string $value
     * @param bool $throw
     * @return bool
     * @throws OrderException
     */

    private function setTransport(string $value, bool $throw=true): bool
    {
        $transportTypes = self::TRANSPORT_TYPES;

        foreach ($transportTypes as $transportType => $index) {
            if ($value == $transportType) {
                $this->transport = $index;
                return true;
            }
        }
        if($throw === true)
            throw new OrderException("Transport missing", 1);
        return false;
    }

    private function setQuantity(string $value, bool $throw=true): bool
    {
        if (is_numeric($value)) {
            if ($value > 0 && $value <= 100) {
                $this->quantity = $value;
                return true;
            }
        }
        if ($throw === true)
            throw new OrderException("Book quantity is missing", 2);
        return false;
    }

    private function setFirstName(string $value, bool $throw=true): bool
    {
        if (!is_numeric($value) && $this->isStringValueValid($value)) {
            if (!preg_match('/\d/', $value)) {
                if (strlen($value) > 2 && strlen($value) < 20) {
                    $this->firstName = $value;
                    return true;
                }
            }
        }
        if ($throw === true)
            throw new OrderException("First name is missing", 3);
        return false;
    }

    private function setLastName(string $value, bool $throw=true): bool
    {
        if (!is_numeric($value) && $this->isStringValueValid($value)) {
            if (!preg_match('/\d/', $value)) {
                if (strlen($value) > 2 && strlen($value) < 20) {
                    $this->lastName = $value;
                    return true;
                }
            }
        }
        if ($throw === true)
            throw new OrderException("Last name is missing", 4);
        return false;
    }

    private function setStreet(string $value, bool $throw=true): bool
    {
        if ($this->isStringValueValid($value)) {
            if (preg_match('/\d/', $value)) {
                $this->street = $value;
                return true;
            }
        }
        if ($throw === true)
            throw new OrderException("Street is missing", 5);
        return false;
    }

    private function setTown(string $value, bool $throw=true): bool
    {
        if ($this->isStringValueValid($value)) {
            $this->town = $value;
            return true;
        }
        if ($throw === true)
            throw new OrderException("Town is missing", 6);
        return false;
    }

    private function setZipCode(string $value, bool $throw=true): bool
    {
        $value = str_replace(' ', '', $value);
        if (is_numeric($value) && strlen($value) == 5) {
            $this->zipCode = $value;
            return true;
        }
        if ($throw === true)
            throw new OrderException("Zip code is missing", 7);
        return false;
    }

    private function setPhoneNumber(string $value, bool $throw=true): bool
    {
        if ($this->isStringValueValid($value)) {
            $value = str_replace(' ', '', $value);
            if (is_numeric($value) && strlen($value) == 9) {
                $this->phoneNumber = $value;
                return true;
            }
        }
        if ($throw === true)
            throw new OrderException("Phone number is missing", 8);
        return false;
    }

    public function setEmail(string $value, bool $throw=true): bool
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->email = $value;
            return true;
        }
        if ($throw === true)
            throw new OrderException("Email is missing", 9);
        return false;
    }

    /**
     * Checks forbidden characters in input string
     * @param string $value
     * @return bool
     */

    public function isStringValueValid(string $value): bool
    {
        $value = str_replace($this->forbiddenString, '', $value);

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
