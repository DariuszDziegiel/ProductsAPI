API setup & run instruction


Instrukcja uruchomienia z Make i bez make.
- Jak uruchomic testy
- Gdzie dokumentacja



Notyfikacje:
Napisać, że wysyłane asynchronicznie za pośrednictwem systemu kolejek RabbitMQ.
Bez blokowania procesu dodawania produktu (core domain).

Awaria w module notyfikacji nie zatrzyma procesu dodawania produktu.


Mail: Mailcatcher panel:  http://localhost:1080/
File log: var/log/product.saved.event.log


Może plany na rozwój
- filtrowanie danych przychodzących z payloadów API (XSS, SQL injection - Użycie HtmlPurifier lub innej biblioteki filtrującej)  
