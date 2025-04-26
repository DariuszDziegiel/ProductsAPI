API setup & run instruction


Instrukcja uruchomienia z Make i bez make.
- Jak uruchomic testy




Notyfikacje:
Napisać, że wysyłane asynchronicznie za pośrednictwem systemu kolejek RabbitMQ.
Bez blokowania procesu dodawania produktu (core domain).

Awaria w module notyfikacji nie zatrzyma procesu dodawania produktu.


Mail: Mailcatcher panel:  http://localhost:1080/
Phpmyadmin: ....
Dokumentacja: /api/doc
File log: var/log/product.saved.event.log


Może plany na rozwój
- filtrowanie danych przychodzących z payloadów API (XSS, SQL injection - Użycie HtmlPurifier lub innej biblioteki filtrującej)
- DepTrac testy architektury
- rate limiting dla wywołań API
- Doc w Open Api (NelmioAPiDocBundle)
- rejestrowanie zmian - podpięcie middleware pod command.bus i obsługiwanych komend

## Tech Stack
    



**Framework:** Symfony 7.2.5
**PHP:** PHP 8.4


## Acknowledgements
- [Problem n+1 w Doctrine](https://koddlo.pl/doctrine-problem-n1-i-mozliwe-rozwiazania/)
