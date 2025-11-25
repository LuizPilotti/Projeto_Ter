<?php
require_once '../config.php';
require_once INCLUDE_PATH.'/classes/mysql.php';
header('Content-Type: application/json; charset=utf-8');

class Usuario {
    private $dados;
    private $logged = false;

    public function criaSQL($emailPuro, $senhaPura){
        // Email é criptografado da MESMA FORMA que foi salvo no banco
        $emailCript = criptografar($emailPuro);

        $sql = MySql::conectar()->prepare("SELECT * FROM `usuarios` WHERE email = ?");
        $sql->execute([$emailPuro]);
        $info = $sql->fetch(\PDO::FETCH_ASSOC);

        if(!$info){
            // Nenhum usuário com esse email
            $this->logged = false;
            return;
        }

        // Descriptografa a senha do banco
        $senhaDB = descriptografar($info['senha']);

        // Compara a senha digitada (pura) com a senha do banco (já descriptografada)
        if($senhaDB === $senhaPura){
            $this->logged = true;
            $this->dados = $info;
        } else {
            $this->logged = false;
        }
    }

    public function isLogged(){
        return $this->logged;
    }

    public function getDadosSanitizados(){
        if(!$this->dados) return null;

        $dados = $this->dados;
        // NUNCA devolve senha pra API
        unset($dados['senha']);
        return $dados;
    }
}

// --- Validação da requisição ---

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido. Use POST.']);
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
// Aqui manda EMAIL E SENHA PUROS, sem criptografar
$usuario->criaSQL($email, $senha);

if(!$usuario->isLogged()){
    http_response_code(401);
    echo json_encode([
        'sucesso' => false,
        'erro' => 'Credenciais inválidas.'
    ]);
    exit;
}

$_SESSION['usuarioId'] = $usuario->getDadosSanitizados()['id'];

echo json_encode([
    'sucesso' => true,
    'usuario' => $usuario->getDadosSanitizados()
]);
