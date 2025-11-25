<?php
//Autor: Caio Henrique Mota Cuadra
class MySql {
    private static $pdo;

    public static function conectar() {
        if (self::$pdo == null) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=' . HOST . ';dbname=' . DATABASE . ';charset=utf8mb4',
                    USER,
                    PASSWORD,
                    [
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (Exception $e) {
                echo '<p>Erro ao conectar no MySql</p><br>' . $e->getMessage();
            }
        }
        return self::$pdo;
    }
}
?>
