<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // a beküldött adatokat ellenőrzése és feldolgozása

    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    // ellenőrzések és adatbázis-műveletek elvégzése:

    // e-mail formátumát ellenőrzése
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Hibás e-mail formátum";
        exit();
    }

    // jelszó erősségének ellenőrzése
    if (strlen($password) < 8) {
        echo "A jelszónak legalább 8 karakter hosszúnak kell lennie";
        exit();
    }

    // a jelszó és a jelszó megerősítés egyezés ellenőrzése
    if ($password !== $confirmPassword) {
        echo "A jelszavak nem egyeznek";
        exit();
    }

    // adatbáziskapcsolat beállítása, ide majd be kell illeszteni valamit attól függően, hogy szervert vagy saját gépet használunk majd
    $host = "127.0.0.1";
    $dbname = "movie";
    $username = "admin";
    $dbPassword = "password";

    try {
        // kapcsolódás az adatbázishoz
        $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbPassword);

        // adatbázis kapcsolat beállítása az exception dobására hibák esetén
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // adatok tárolása az adatbázisban
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $dbh->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // sikeres regisztráció után irány a bejelentkezési oldal
        header("Location: login.html");
        exit();
    } catch (PDOException $e) {
        echo "Adatbázis kapcsolódási hiba: " . $e->getMessage();
        exit();
    }
}
else{
    echo "hiba";
    exit();
}
?>
