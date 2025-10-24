# Exceptions Personnalisées

Ce répertoire contient des classes d'exceptions personnalisées pour l'application Laravel. Ces exceptions sont conçues pour gérer des scénarios d'erreur spécifiques de manière structurée, en étendant une classe de base `CustomException`.

## Exception de Base

### CustomException
- **Fichier**: `CustomException.php`
- **Description**: Classe de base abstraite pour toutes les exceptions personnalisées. Elle étend la classe `Exception` de PHP et prend un `ErrorEnum` pour fournir des messages d'erreur cohérents.
- **Utilisation**:
  ```php
  use App\Exceptions\CustomException;
  use App\Enums\ErrorEnum;

  throw new CustomException(ErrorEnum::ACCOUNT_NOT_FOUND);
  ```

## Exceptions Spécifiques

Les classes d'exceptions spécifiques suivantes sont disponibles, chacune correspondant à une erreur dans `ErrorEnum`:

### UserNotFoundException
- **Fichier**: `UserNotFoundException.php`
- **Erreur**: `ErrorEnum::USER_NOT_FOUND`
- **Description**: Levée lorsqu'un utilisateur n'est pas trouvé.

### AccountNotFoundException
- **Fichier**: `AccountNotFoundException.php`
- **Erreur**: `ErrorEnum::ACCOUNT_NOT_FOUND`
- **Description**: Levée lorsqu'un compte n'est pas trouvé.

### CompteNotFoundException
- **Fichier**: `CompteNotFoundException.php`
- **Erreur**: `ErrorEnum::COMPTE_NOT_FOUND`
- **Description**: Levée lorsqu'un compte (compte) n'est pas trouvé.

### TransactionNotFoundException
- **Fichier**: `TransactionNotFoundException.php`
- **Erreur**: `ErrorEnum::TRANSACTION_NOT_FOUND`
- **Description**: Levée lorsqu'une transaction n'est pas trouvée.

### InsufficientBalanceException
- **Fichier**: `InsufficientBalanceException.php`
- **Erreur**: `ErrorEnum::INSUFFICIENT_BALANCE`
- **Description**: Levée lorsqu'il y a un solde insuffisant pour un retrait.

### UnauthorizedException
- **Fichier**: `UnauthorizedException.php`
- **Erreur**: `ErrorEnum::UNAUTHORIZED`
- **Description**: Levée lorsque l'accès est non autorisé.

## Exceptions Supplémentaires

Pour les autres erreurs dans `ErrorEnum`, suivez le même modèle pour créer des classes d'exceptions correspondantes. Par exemple:

- `AccountNotActiveException` pour `ErrorEnum::ACCOUNT_NOT_ACTIVE`
- `DeleteTransactionNotAllowedException` pour `ErrorEnum::DELETE_TRANSACTION_NOT_ALLOWED`
- Et ainsi de suite.

## Utilisation dans les Services et Contrôleurs

Utilisez ces exceptions dans vos services et contrôleurs pour fournir une gestion d'erreur claire:

```php
use App\Exceptions\AccountNotFoundException;

if (!$account) {
    throw new AccountNotFoundException();
}
```

## Gestion dans le Gestionnaire d'Exceptions

Mettez à jour `app/Exceptions/Handler.php` pour gérer ces exceptions personnalisées de manière appropriée, peut-être en retournant des codes de statut HTTP spécifiques ou des messages.

## Notes

- Toutes les exceptions étendent `CustomException`, ce qui assure une messagerie d'erreur cohérente via `ErrorEnum`.
- Cette structure permet une extension et une maintenance faciles de la gestion des erreurs dans l'application.