<?php
declare(strict_types=1);

class PostFilter
{
    protected ?array $postValues = null;

    public function __construct(string $postName) {
        if (isset($_POST) && isset($_POST[$postName])) {
            foreach ($_POST[$postName] as $index => $value) {
                $this->postValues[$index] = filter_var($value);
            }
        } else {
            $this->postValues = null;
        }
    }

    /**
     * @return array|null
     */
    public function getPostValues(): ?array
    {
        return $this->postValues;
    }
}
