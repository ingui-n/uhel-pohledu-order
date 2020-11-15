<?php
declare(strict_types=1);

class PostFilter
{
    /**
     * Get post values
     * @param string $postName
     * @return array|null
     */
    public static function getPostValues(string $postName): ?array
    {
        $postValues = null;
        if (isset($_POST) && isset($_POST[$postName])) {
            foreach ($_POST[$postName] as $index => $value) {
                $postValues[$index] = filter_var($value);
            }
        }
        return $postValues;
    }

    /**
     * Clear post
     * @param string|null $postName [if null - clear all post]
     */
    public static function clear(?string $postName=null): void
    {
        if(is_null($postName) && isset($_POST))
        {
            unset($_POST);
        }else{
            if(isset($_POST[$postName]))
                $_POST[$postName] = null;
                unset($_POST[$postName]);
        }
    }
}
