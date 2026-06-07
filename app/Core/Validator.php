<?php

namespace App\Core;

/**
 * Basit kural tabanli dogrulayici.
 * Kurallar: required, email, min:n, max:n, numeric, confirmed, in:a,b, phone, slug, url, file
 */
class Validator
{
    private array $data;
    private array $errors = [];
    private array $labels;

    public function __construct(array $data, array $labels = [])
    {
        $this->data = $data;
        $this->labels = $labels;
    }

    public function validate(array $rules): bool
    {
        foreach ($rules as $field => $ruleSet) {
            $rulesArr = is_array($ruleSet) ? $ruleSet : explode('|', $ruleSet);
            $value = $this->data[$field] ?? null;

            foreach ($rulesArr as $rule) {
                [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);
                $label = $this->labels[$field] ?? $field;

                switch ($name) {
                    case 'required':
                        if ($value === null || (is_string($value) && trim($value) === '')) {
                            $this->add($field, "{$label} alani zorunludur.");
                        }
                        break;
                    case 'email':
                        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->add($field, "{$label} gecerli bir e-posta olmalidir.");
                        }
                        break;
                    case 'min':
                        if ($value !== null && mb_strlen((string) $value) < (int) $param) {
                            $this->add($field, "{$label} en az {$param} karakter olmalidir.");
                        }
                        break;
                    case 'max':
                        if ($value !== null && mb_strlen((string) $value) > (int) $param) {
                            $this->add($field, "{$label} en fazla {$param} karakter olabilir.");
                        }
                        break;
                    case 'numeric':
                        if ($value !== null && $value !== '' && !is_numeric($value)) {
                            $this->add($field, "{$label} sayisal olmalidir.");
                        }
                        break;
                    case 'confirmed':
                        if (($this->data[$field . '_confirmation'] ?? null) !== $value) {
                            $this->add($field, "{$label} dogrulamasi eslesmiyor.");
                        }
                        break;
                    case 'in':
                        $opts = explode(',', (string) $param);
                        if ($value !== null && $value !== '' && !in_array($value, $opts, true)) {
                            $this->add($field, "{$label} gecersiz bir deger.");
                        }
                        break;
                    case 'phone':
                        if ($value && !preg_match('/^[0-9+\s()\-]{7,20}$/', (string) $value)) {
                            $this->add($field, "{$label} gecerli bir telefon olmalidir.");
                        }
                        break;
                    case 'slug':
                        if ($value && !preg_match('/^[a-z0-9\-]+$/', (string) $value)) {
                            $this->add($field, "{$label} yalnizca kucuk harf, rakam ve tire icermelidir.");
                        }
                        break;
                    case 'url':
                        if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
                            $this->add($field, "{$label} gecerli bir URL olmalidir.");
                        }
                        break;
                    case 'accepted':
                        if (!in_array($value, ['1', 'on', 'true', 'yes', true], true)) {
                            $this->add($field, "{$label} onaylanmalidir.");
                        }
                        break;
                }
            }
        }
        return empty($this->errors);
    }

    private function add(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstErrors(): array
    {
        $out = [];
        foreach ($this->errors as $field => $msgs) {
            $out[$field] = $msgs[0];
        }
        return $out;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }
}
