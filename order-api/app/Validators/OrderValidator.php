<?php

namespace App\Validators;

class OrderValidator
{
    public function validate(array $order): array
    {
        $errors = [];
        
        if ($this->containsNonEnglish($order['name'])) {
            $errors[] = '400 - Name contains non-English characters';
        }
        
        if (!$this->isCapitalized($order['name'])) {
            $errors[] = '400 - Name is not capitalized';
        }
        
        if ($order['price'] > 2000) {
            $errors[] = '400 - Price is over 2000';
        }
        
        if (!in_array($order['currency'], ['TWD', 'USD'])) {
            $errors[] = '400 - Currency format is wrong';
        }

        return [
            'isValid' => empty($errors),
            'errors' => $errors
        ];
    }

    private function containsNonEnglish(string $str): bool
    {
        return preg_match('/[^\x20-\x7E]/', $str);
    }

    private function isCapitalized(string $str): bool
    {
        $words = explode(' ', $str);
        foreach ($words as $word) {
            if (!empty($word) && !ctype_upper($word[0])) {
                return false;
            }
        }
        return true;
    }
}