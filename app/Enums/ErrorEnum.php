<?php

namespace App\Enums;

enum ErrorEnum: string
{
    case USER_NOT_FOUND = 'User not found';
    case TRANSACTION_NOT_FOUND = 'Transaction not found';
    case COMPTE_NOT_FOUND = 'Compte not found';
    case UNAUTHORIZED = 'Unauthorized';
    case FORBIDDEN_ADMIN = 'Forbidden: Admin access required';
    case FORBIDDEN_CLIENT = 'Forbidden: Client access required';
    case ACCOUNT_NOT_FOUND = 'Account not found';
    case ACCOUNT_NOT_ACTIVE = 'Account is not active';
    case INSUFFICIENT_BALANCE = 'Insufficient balance';
    case DELETE_TRANSACTION_NOT_ALLOWED = 'Deleting transactions is not allowed for audit purposes';
    case CANNOT_DELETE_USER_WITH_ACCOUNTS = 'Cannot delete user with accounts';
    case TITULAIRE_NOT_FOUND = 'Titulaire not found';
    case CANNOT_DELETE_ACCOUNT_WITH_TRANSACTIONS = 'Cannot delete account with transactions';
    case ACCOUNT_ALREADY_BLOCKED = 'Account is already blocked';
    case ACCOUNT_NOT_BLOCKED = 'Account is not blocked';

    public function message(): string
    {
        return $this->value;
    }
}