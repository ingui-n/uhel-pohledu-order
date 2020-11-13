<?php
declare(strict_types=1);

include_once __DIR__ . '/PostFilter.php';
include_once __DIR__ . './../Exceptions/OrderException.php';

use app\Exceptions\OrderException;

class Order
{
    const ERROR_CODES = [
        1 => 'Špatný výběr dopravy!',
        2 => 'Neplatné množství!',
        3 => 'Špatně vyplněné jméno!',
        4 => 'Špatně vyplněné příjmení!',
        5 => 'Špatně vyplněná ulice!',
        6 => 'Špatně vyplněné město!',
        7 => 'Neplatné PSČ!',
        8 => 'Neplatné telefonní číslo!',
        9 => 'Neplatný email!'
    ];

    const BOOK_PRICE = 499;
    const TRANSPORT_TYPES = ['cashOnDelivery' => 150, 'moneyTransfer' => 0];

    protected ?string $transport = null;
    protected ?int $quantity = null;
    protected ?string $firstName = null;
    protected ?string $lastName = null;
    protected ?string $street = null;
    protected ?string $town = null;
    protected ?string $zipCode = null;
    protected ?string $phoneNumber = null;
    protected ?string $email = null;
    protected ?int $fullPrice = null;
    protected array $validationsArray = [];

    private array $forbiddenString = ['[', '@', '.', '_', '!', '#', '$', '%', '^', '&', '*', '(', ')', '<', '>', '?', '/', '|', '}', '{', '~', ':', ']'];

    protected ?int $errorNumber=null;

    protected bool $status=false;
    private bool $issetOrder = false;


    public function printOrder(): bool
    {
        return $this->status === false;
    }

    public function sentOrder(): bool
    {
        $this->setOrderedToSession(false);
        try {
            $this->validate();
            $this->sumFullPrice();
            $this->status = true;
        } catch (OrderException $e) {
            $this->errorNumber = $e->getCode();
            return false;
        }
        $this->setOrderedToSession(true);
        $this->onSuccess();
        return true;
    }

    /**
     * Validate all inputs
     * @throws OrderException
     */
    protected function validate(): void
    {
        $this->validateTransport();
        $this->validateQuantity();
        $this->validateFirstName();
        $this->validateLastName();
        $this->validateStreet();
        $this->validateTown();
        $this->validateZipCode();
        $this->validatePhoneNumber();
        $this->validateEmail();
    }

    /**
     * Was order sent?
     * @return bool
     */
    public function isOrderSent(): bool
    {
        return isset($_POST) && isset($_POST["order"]);
    }


    public function onSuccess(): bool
    {
        //todo onSuccess
        return $this->issetOrder;
    }

    public function setOrderedToSession(bool $set): void
    {
        $_SESSION['ordered'] = $set;
    }

    public function getOrderedFromSession(): bool
    {
        if (isset($_SESSION['ordered']))
            return $_SESSION['ordered'];
        return false;
    }

    /**
     * @param bool $throw
     * @return bool
     * @throws OrderException
     */
    private function validateTransport(bool $throw=true): bool
    {
        $transportTypes = self::TRANSPORT_TYPES;

        foreach ($transportTypes as $transportType => $index) {
            if ($this->transport == $transportType) {
                array_push($this->validationsArray, true);
                return true;
            }
        }
        array_push($this->validationsArray, false);
        if($throw === true)
            throw new OrderException("Transport is missing", 1);
        return false;
    }

    /**
     * @param bool $throw
     * @return bool
     * @throws OrderException
     */
    private function validateQuantity(bool $throw=true): bool
    {
        if (is_int($this->quantity))
            if ($this->quantity > 0 && $this->quantity <= 500) {
                array_push($this->validationsArray, true);
                return true;
            }
        array_push($this->validationsArray, false);
        if ($throw === true)
            throw new OrderException("Book quantity is missing", 2);
        return false;
    }

    /**
     * Validate fist name
     * @param bool $throw
     * @return bool
     * @throws OrderException
     */
    private function validateFirstName(bool $throw=true): bool
    {
        if(is_string($this->firstName) && $this->isStringValueValid($this->firstName)) {
            if (!preg_match('/\d/', $this->firstName)) {
                array_push($this->validationsArray, true);
                return true;
            }
        }
        array_push($this->validationsArray, false);
        if($throw === true)
            throw new OrderException("First name is missing", 3);
        return false;
    }

    /**
     * @param bool $throw
     * @return bool
     * @throws OrderException
     */
    private function validateLastName(bool $throw=true): bool
    {
        if(is_string($this->lastName) && $this->isStringValueValid($this->lastName)) {
            if (!preg_match('/\d/', $this->lastName)) {
                array_push($this->validationsArray, true);
                return true;
            }
        }
        array_push($this->validationsArray, false);
        if ($throw === true)
            throw new OrderException("Last name is missing", 4);
        return false;
    }

    /**
     * @param bool $throw
     * @return bool
     * @throws OrderException
     */
    private function validateStreet(bool $throw=true): bool
    {
        if (is_string($this->street) && $this->isStringValueValid($this->street)) {
            if (preg_match('/\d/', $this->street)) {
                array_push($this->validationsArray, true);
                return true;
            }
        }
        array_push($this->validationsArray, false);
        if ($throw === true)
            throw new OrderException("Street is missing", 5);
        return false;
    }

    /**
     * @param bool $throw
     * @return bool
     * @throws OrderException
     */
    private function validateTown(bool $throw=true): bool
    {
        if (is_string($this->town) && $this->isStringValueValid($this->town)) {
            array_push($this->validationsArray, true);
            return true;
        }
        array_push($this->validationsArray, false);
        if ($throw === true)
            throw new OrderException("Town is missing", 6);
        return false;
    }

    /**
     * @param bool $throw
     * @return bool
     * @throws OrderException
     */
    private function validateZipCode(bool $throw=true): bool
    {
        $zipCode = $this->zipCode;

        $zipCode = str_replace(' ', '', $zipCode);
        $zipCode = intval($zipCode);

        if (is_int($zipCode)) {
            if (strlen(strval($zipCode)) == 5) {
                array_push($this->validationsArray, true);
                return true;
            }
        }
        array_push($this->validationsArray, false);
        if ($throw === true)
            throw new OrderException("Zip code is missing", 7);
        return false;
    }

    /**
     * @param bool $throw
     * @return bool
     * @throws OrderException
     */
    private function validatePhoneNumber(bool $throw=true): bool
    {
        $phoneNumber = $this->phoneNumber;

        $phoneNumber = str_replace(' ', '', $phoneNumber);
        $phoneNumber = intval($phoneNumber);

        if (is_int($phoneNumber)) {
            if (strlen(strval($phoneNumber)) == 9) {
                array_push($this->validationsArray, true);
                return true;
            }
        }
        array_push($this->validationsArray, false);
        if ($throw === true)
            throw new OrderException("Phone number is missing", 8);
        return false;
    }

    /**
     * @param bool $throw
     * @return bool
     * @throws OrderException
     */
    private function validateEmail(bool $throw=true): bool
    {
        if (is_string($this->email) && filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            array_push($this->validationsArray, true);
            return true;
        }
        array_push($this->validationsArray, false);
        if ($throw === true)
            throw new OrderException("Email is missing", 9);
        return false;
    }

    /**
     * Checks forbidden characters in input string
     * @param string $value
     * @return bool
     */

    private function isStringValueValid(string $value): bool
    {
        $value = str_replace($this->forbiddenString, '', $value);

        if (strlen($value) > 0 && strlen($value) < 100)
            return true;
        return false;
    }

    private function sumFullPrice(): bool
    {
        $transport = $this->transport;
        $this->fullPrice = self::BOOK_PRICE * $this->quantity + self::TRANSPORT_TYPES[$transport];

        return $this->fullPrice > $transport;
    }

    public function getBookPrice(): int
    {
        return self::BOOK_PRICE;
    }

    /**
     * @param string|null $transport
     */
    public function setTransport(?string $transport): void
    {
        $this->transport = $transport;
    }

    /**
     * @param int|null $quantity
     */
    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * Set first name
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @param string|null $street
     */
    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    /**
     * @param string|null $town
     */
    public function setTown(?string $town): void
    {
        $this->town = $town;
    }

    /**
     * @param string|null $zipCode
     */
    public function setZipCode(?string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @param string|null $phoneNumber
     */
    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param int $num
     * @return string|null
     */
    public function getErrorMessage(int $num): ?string
    {
        $num--;
        $validationError = $this->validationsArray;

        if (isset($validationError[$num]) && $validationError[$num] === false)
            return self::ERROR_CODES[$num + 1];
        return null;
    }

    /**
     * @param int $num
     * @return bool
     */
    public function getValidationError(int $num): bool
    {
        $num--;
        $validationError = $this->validationsArray;

        if (isset($validationError[$num]) && $validationError[$num] === true)
            return false;
        if (isset($validationError[$num]) && $validationError[$num] === false)
            return true;
        return false;
    }

    /**
     * @return bool
     */
    public function getValidationFailed(): bool
    {
        $validationError = $this->validationsArray;

        if ($validationError !== null) {
            foreach ($validationError as $item) {
                if ($item === false)
                    return true;
            }
        } else {
            return true;
        }
        return false;
    }
}
