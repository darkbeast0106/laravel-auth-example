# Laravel token authentikáció

Tokenes authentikáció megvalósítása Laravel keretrendszerben, [Laravel Sanctum](https://laravel.com/docs/10.x/sanctum) segítségével.

## Megvalósítás lépései

Legújabb laravel verziókban alapból telepítve a Laravel Sanctum. ha mégsem lenne telepítve, az alábbi lépéseket kell végrehajtani:

* Sanctum telepítése:

  ```sh
  composer require laravel/sanctum
  ```

* Sanctum konfiguráció és szükséges migrációk másolása az alkalmazáshoz.

  ```sh
  php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
  ```

* User modell konfigurálása

  * `use HasApiTokens` sor hozzáadása a model osztály elejére

Ezek után az alábbi műveleteket kell végrehajtani:

* [Request](./app/Http/Requests) osztályok létrehozása, a regisztráció és bejelentkezés műveletek validálásához.
* [Controller](./app/Http/Controllers/API/AuthController.php) létrehozása authentikáció megvalósításához.
* [Api útvonalak](./routes/api.php) definiálása
