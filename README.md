# ProductsAPI 1.0.0

Proste API do zarządzania produktami i ich kategoriami.

## Uruchomienie lokalne

**Sklonuj projekt**

`````
  git clone https://github.com/DariuszDziegiel/ProductsAPI.git
`````

**Przejdź do katalogu projektu**

```
  cd ProductsAPI
```

**Uruchom kontenery docker**

```
  make start
```
Lub jeśli nie masz narzędzia make:
````
docker compose up -d
````

## Dokumentacja
http://localhost/api/doc

Lub bezpośrednio w pliku yaml (standar OpenAPI 3):
````
config/packages/nelmio_api_doc.yaml
````



## Uruchomienie testów PHPUnit

Aby uruchomić testy wykonaj:

```
  make test
```
Lub jeśli nie masz narzędzia make:

````
docker compose exec -it apache vendor/bin/phpunit --colors=always --testdox 
````

Notyfikacje zostały przetestowane zarówno w testach jednostkowych, jak i integracyjnych.

## Notyfikacje

**Panel Mailcatcher do podglądu wysłanych emaili po zapisie produktu:** http://localhost:1080

**Plik log z notyfikacjami po zapisie produktu:** var/log/product.saved.event.log

Notyfikacje zostały zrealizowane jako osobny moduł - bounded context (src/Notification). Moduły Api i Notification są niezależne i nie mają bezpośredniej wiedzy o swoim istnieniu.

Moduł Api komunikuje się z modułem notyfikacji za pomocą 'event.bus', który służy do publikowania zdarzeń w systemie. Po zapisaniu produktu, odpowiednie zdarzenie jest publikowane i obsługiwane asynchronicznie przez system kolejek RabbitMQ.
Proces dodawania produktu nie jest blokowany przez wysyłkę powiadomień.

Awaria modułu notyfikacji nie zatrzymuje procesu zapisu produktu. 
Błędnie obsłużony event trafia do kolejki failed. Po usunięciu błędu w aplikacji wiadomość można ponownie przetworzyć (messenger:failed:retry) lub usunąć (messenger:failed:remove).


### Rozszerzenie o nowy rodzaj notyfikacji np. SMS, Slack,...

Należy zaimplementować interfejs **ProductSavedNotifierInterface** oraz użyć tagu **product.saved.event.notifier**.
Resztą zajmie się symfonowy tagged iterator w **src/Notification/Application/ProductSavedNotificationHandler.php**.

Przykładowa implementacja notyfikacji SMS:

Plik: **src/Notification/Infrastructure/ProductSavedNotifier/ProductSavedSMSNotifier.php**
```php 
#[Autoconfigure(tags: ['product.saved.event.notifier'])]
readonly class ProductSavedSMSNotifier implements ProductSavedNotifierInterface
{
    // inject SMS provider gateway to constructor
    public function __construct()
    {
    }

    public function notify(ProductSavedEvent $productSavedEvent): void
    {
        // And send SMS by your favorite provider gateway:)
    }
}
```

## Roadmapa projektu
- **[gotowe]** API do zarządzania produktami i powiązanymi kategoriami
- Filtrowanie danych przychodzących z payloadów użytkowników (Przeciwdziałanie atakom XSS, SQL injection - użycie np. Html Purifier lub innej biblioteki filtrującej). Ważne z punktu widzenia security
- Autoryzacja do API
- Testy architektury w Deptrac
- Rate limiting dla wywołań API
- Rejestrowanie wszystkich zmian w- podpięcie middleware pod command.bus i obsługiwanych komend

## Tech Stack
- Symfony 7.2.5
- MySQL 8.0.42,
- PHP 8.4
- PHPUnit 12.1,
- RabbitMQ 4.1.0
- Apache 2.4
