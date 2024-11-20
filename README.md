# Laravel Brewery API

## Descrizione
Web application Laravel che permette agli utenti di autenticarsi, ottenere un token e utilizzare le API di OpenBreweryDB tramite un proxy per visualizzare una lista di birre paginata.

## Features
- Autenticazione tramite token (Laravel Sanctum)
- Proxy API verso OpenBreweryDB
- Lista paginata di birre
- Documentazione Swagger
- Test delle performance per valutare l'architettura ottimale

---

## Installazione

### Prerequisiti
- Docker
- Composer
- PHP >= 8.1

### Istruzioni
1. Clona il repository:
```bash
   git clone https://github.com/username/repository.git

   cd repository
```

2. Copia il file .env: Copia il file .env.example come .env e configuralo se necessario:
```bash
cp .env.example .env

```
3. Genera la chiave dell'applicazione:
```bash
php artisan key:generate

```

4. Avvia i container Docker:
```bash
docker-compose up -d

```

5. La documentazione delle API è disponibile all'indirizzo:
```bash
http://localhost:8080/api/documentation

```

### Utilizzo delle API e documenazione swagger

Per utilizzare le api si puo utilizzare direttamente swagger
quindi fare il login con la relativa  (Authentication/login => try it out =>Execute)
una volta ricevuto il token si puo salvare nella pagina (pulsante Authorize con bordo verde in alto a destra )
una volta salvato il token quindi si possono provare anche il risultato del altra api (Breweries =>get)
