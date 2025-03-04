<?php

declare(strict_types=1);

namespace App\Classes;

use App\Exceptions\AuthorizationException;

class Authorization
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function register(array $data): bool
    {
        if (!preg_match('/^[a-zA-Z]{2,20}$/', $data['username'])) {
            throw new AuthorizationException('Username must contain only Latin characters. Username length from 2 to 20 characters');
        }
        if (!preg_match('/^[a-zA-Z0-9]{2,20}$/', $data['login'])) {
            throw new AuthorizationException('Login must contain only Latin characters and numbers. Login length from 2 to 20 characters');
        }
        if (!preg_match('/^[a-zA-Z]{5,20}$/', $data['password'])) {
            throw new AuthorizationException('Password must contain only Latin characters. Password length from 5 to 20 characters');
        }

        $isLoginExist = $this->database->fetchAll(
            'SELECT login 
            FROM users 
            WHERE login = :login',
            [
                'login' => $data['login']
            ]);
        if (!empty($isLoginExist)) {
            throw new AuthorizationException('Login is already used');
        }

        $result = $this->database->query(
            'INSERT INTO users (username, login, password) 
            VALUES (:username, :login, :password)',
            [
                'username' => $data['username'],
                'login' => $data['login'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
        ]);

        return $result;
    }

    public function login(array $data): string
    {
        if (empty($data['login'])) {
            throw new AuthorizationException('Login should not be empty');
        }
        if (empty($data['password'])) {
            throw new AuthorizationException('Password should not be empty');
        }

        $userData = $this->database->fetchAll(
            'SELECT username, password
            FROM users 
            WHERE login = :login',
            [
                'login' => $data['login']
        ]);

        if (empty($userData)) {
            throw new AuthorizationException('User not found');
        }

        if (password_verify($data['password'], $userData[0]['password'])) {
            return $userData[0]['username']; 
        }

        throw new AuthorizationException('Login or password is incorrect');
    }
}