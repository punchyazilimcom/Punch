<?php

namespace App\Models;

class Admin extends Model
{
    protected string $table = 'admins';

    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', strtolower(trim($email)));
    }
}
