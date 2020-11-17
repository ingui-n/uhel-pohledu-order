<?php
declare(strict_types=1);

use app\Exceptions\OrderException;

class Order
{
    const
        FORBIDDEN_STRINGS = ['[', '@', '.', '_', '!', '#', '$', '%', '^', '&', '*', '(', ')', '<', '>', '?', '/', '|', '}', '{', '~', ':', ']'],
        ERROR_CODES = [
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

    protected int $bookPrice;
    protected array $transportTypes;
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
    protected ?int $errorNumber = null;
    protected bool $status = false;
    private bool $issetOrder = false;
    protected bool $completed = false;

    /**
     * On success callback
     * @var null|callable
     */
    protected $onSuccessCallback = null;

    /**
     * Order constructor.
     * load config
     */
    public function __construct()
    {
        $this->bookPrice = config::BOOK_PRICE;
        $this->transportTypes = config::TRANSPORT_TYPES;
    }


    /**
     * Print order condition
     * @return bool
     */
    public function printOrder(): bool
    {
        return $this->issetOrder === false || ($this->status === false && $this->issetOrder === true);
    }

    /**
     * Try to sent order
     * @return bool
     */
    public function sentOrder(): bool
    {
        $this->issetOrder = true;
        try {
            $this->validate();
            $this->sumFullPrice();
            $this->status = true;
        } catch (OrderException $e) {
            $this->errorNumber = $e->getCode();
            $this->status = false;
            return false;
        }

        if (is_callable($this->onSuccessCallback)) {
            $onSuccessFn = $this->onSuccessCallback;
            $this->completed = $onSuccessFn($this);
        } else {
            $this->completed = true;
        }
        return $this->completed;
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
     * @param bool $throw
     * @return bool
     * @throws OrderException
     */
    private function validateTransport(bool $throw = true): bool
    {
        $transportTypes = $this->transportTypes;

        foreach ($transportTypes as $transportType => $index) {
            if ($this->transport == $transportType) {
                array_push($this->validationsArray, true);
                return true;
            }
        }
        array_push($this->validationsArray, false);
        if ($throw === true)
            throw new OrderException("Transport is missing", 1);
        return false;
    }

    /**
     * @param bool $throw
     * @return bool
     * @throws OrderException
     */
    private function validateQuantity(bool $throw = true): bool
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
    private function validateFirstName(bool $throw = true): bool
    {
        if (is_string($this->firstName) && $this->isStringValueValid($this->firstName)) {
            if (!preg_match('/\d/', $this->firstName)) {
                array_push($this->validationsArray, true);
                return true;
            }
        }
        array_push($this->validationsArray, false);
        if ($throw === true)
            throw new OrderException("First name is missing", 3);
        return false;
    }

    /**
     * @param bool $throw
     * @return bool
     * @throws OrderException
     */
    private function validateLastName(bool $throw = true): bool
    {
        if (is_string($this->lastName) && $this->isStringValueValid($this->lastName)) {
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
    private function validateStreet(bool $throw = true): bool
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
    private function validateTown(bool $throw = true): bool
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
    private function validateZipCode(bool $throw = true): bool
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
    private function validatePhoneNumber(bool $throw = true): bool
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
    private function validateEmail(bool $throw = true): bool
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
        $value = str_replace(self::FORBIDDEN_STRINGS, '', $value);

        if (strlen($value) > 0 && strlen($value) < 100)
            return true;
        return false;
    }

    /**
     * Sum full price
     * @return float|int|mixed
     */
    public function sumFullPrice()
    {
        $transport = $this->transport;
        return $this->fullPrice = $this->bookPrice * $this->quantity + $this->transportTypes[$transport];
    }

    /**
     * Get book price with VAT
     * @return int
     */
    public function getBookPrice(): int
    {
        return $this->bookPrice;
    }

    /**
     * Set transport
     * @param string|null $transport
     * @return Order
     */
    public function setTransport(?string $transport): self
    {
        $this->transport = $transport;
        return $this;
    }

    /**
     * Set quantity
     * @param int|null $quantity
     * @return Order
     */
    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Set first name
     * @param string|null $firstName
     * @return Order
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Set last name
     * @param string|null $lastName
     * @return Order
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Set street
     * @param string|null $street
     * @return Order
     */
    public function setStreet(?string $street): self
    {
        $this->street = $street;
        return $this;
    }

    /**
     * Set town
     * @param string|null $town
     * @return Order
     */
    public function setTown(?string $town): self
    {
        $this->town = $town;
        return $this;
    }

    /**
     * Set zip code
     * @param string|null $zipCode
     * @return Order
     */
    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * Set phone number
     * @param string|null $phoneNumber
     * @return Order
     */
    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * Set email
     * @param string|null $email
     * @return Order
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
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

    /**
     * @return string|null
     */
    public function getTransport(): ?string
    {
        return $this->transport;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @return string|null
     */
    public function getTown(): ?string
    {
        return $this->town;
    }

    /**
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Get transport types
     * @return array|int[]
     */
    public function getTransportTypes(): array
    {
        return $this->transportTypes;
    }

    /**
     * Set on success callback
     * @param callable $onSuccessCallback
     * @return Order
     */
    public function setOnSuccessCallback(callable $onSuccessCallback): self
    {
        $this->onSuccessCallback = $onSuccessCallback;
        return $this;
    }

    /**
     * Was order completed (order was sent && all inputs are valid && on success)
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }
}
