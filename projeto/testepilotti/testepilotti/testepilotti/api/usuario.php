<?php
    require_once '../config.php';
    require_once INCLUDE_PATH.'/classes/mysql.php';
    header('Content-Type: application/json; charset=utf-8');

    class Usuario{
        private $email;
        private $senha;

        public function getEmail(){
            return $this->email;
        }

        public function setEmail($email){
            $this->email = $email;
        }

        public function getSenha(){
            return $this->senha;
        }

        public function setSenha($senha){
            $this->senha = $senha;
        }

        public function criaSQL($email, $senha){

        $verificador = MySql::conectar()->prepare("SELECT * FROM `usuarios` WHERE email = ?");
        $verificador->execute([$email]);
        $info = $verificador->fetch(\PDO::FETCH_ASSOC);

        if(!$info){
            $sql = MySql::conectar()->prepare("INSERT INTO `usuarios` (email, senha) VALUES (?,?)");
            $this->setEmail($email);
            $this->setSenha($senha);
            $sql->execute([$this->getEmail(), $this->getSenha()]);
        }else{
            echo json_encode(['erro' => 'Já existe um usuário com esse e-mail cadastrado!']);
            exit;
        }
            
        }


    }

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        http_response_code(405);
        echo json_encode(['erro' => 'Email e senha são obrigatórios.']);
        exit;
    }

    $email = $_POST['email'] ?? null;
    $senha = $_POST['senha'] ?? null;

    if(!$email || !$senha){
        http_response_code(400);
        echo json_encode(['erro' => 'Email e senha são obrigatórios.']);
        exit;
    }

    $usuario = new Usuario();
    $usuario->criaSQL($email, criptografar($senha));

    echo json_encode(['sucesso' => true]);

?>