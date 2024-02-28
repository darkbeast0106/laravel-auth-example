<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Regisztrál egy új felhasználót
     *
     * @param RegisterRequest $request A felhasználó adatai validáció után
     * @return JsonResponse a regisztrált felhasználó adatai
     */
    public function register(RegisterRequest $request) {
        // $request->all() nem használható jelszó titkosítása miatt.
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);
        return response()->json($user, 201);
    }

    /**
     * Bejelentkezteti a felhasználót
     *
     * @param LoginRequest $request bejelentkezéshez megadott email és jelszó validálva
     * @return JsonResponse a későbbi műveletekhez használandó access-token. A token a json "token" paraméterén érhető el.
     */
    public function login(LoginRequest $request) {
        // Felhasználó megkeresése e-mail alapján
        $user = User::where("email", $request->email);

        // Ha nem található az e-mail alapján a fehasználó, vagy ha a beírt jelszó nem egyezik meg a regisztrációkor generáltal.
        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json(["message" => "Invalid Credentials"], 401);
        }
        // Token létrehozása és megjelenítése szöveges formában
        $token = $user->createToken("AuthToken")->plainTextToken;
        // Visszaadjuk a tokent a válaszban, hogy későbbi műveletekhez használható legyen
        return response()->json(["token" => $token]);
    }

    /**
     * Kijelentkezteti a felhasználót
     *
     * @return Response sikeres művelet végrehajtás jelzése.
     */
    public function logout() {
        /*
         auth()->user() visszaadja a sanctum által authentikált felhasználót
         currentAccessToken() az authentikációhoz használt access tokent adja vissza, majd azt töröljük
         alternatívaként currentAccessToken() helyett tokens() függvénnyel az összes access tokent lekérdezhetjük, így minden eszközről kijelentkeztethetjük a felhasználót.
         */
        /** @disregard P1013 Undefinded method */
        auth()->user()->currentAccessToken()->delete();
    
        return response()->noContent();
    }
}
