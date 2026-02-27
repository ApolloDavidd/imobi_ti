<?php
require_once(__DIR__ . "/../config/conexao.php");

class Usuario
{
    private ?int    $id;
    private int    $idPerfil;
    private string $nome;
    private string $email;
    private string $senhaHash;
    private bool   $ativo;

    private ?string $perfilNome = null;

    public function __construct(?int $id = 0, int $idPerfil, string $nome, string $email, string $senhaHash, ?bool $ativo)
    {
        $this->id = $id;
        $this->idPerfil = $idPerfil;
        $this->nome = $nome;
        $this->email = $email;
        $this->senhaHash = $senhaHash;
        $this->ativo = $ativo;
    }

    // Método mágico Get e Set
    public function __get(string $prop)
    {
        if (property_exists($this, $prop)) {
            return $this->$prop;
        } else {
            throw new Exception("Propriedade {$prop} não existe.");
        }
    }

    public function __set(string $prop, $valor)
    {
        switch ($prop) {
            case "id":
                $this->id = (int)$valor;
                break;
            case "idPerfil":
                $this->idPerfil = $valor;
                break;
            case "nome":
                $this->nome = trim($valor);
                break;
            case "email":
                if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("E-mail inválido.");
                }
                $this->email = $valor;
                break;
            case "senha":
                $this->senhaHash = password_hash($valor, PASSWORD_DEFAULT);
                break;
            case "ativo":
                $this->ativo = (bool)$valor;
                break;
            case "perfilNome":
                $this->perfilNome = $valor;
                break;
            default:
                throw new Exception("Propriedade {$prop} não permitida!");
        }
    }

    private static function getConexao()
    {
        return (new Conexao())->conexao();
    }

    public function inserir()
    {
        $pdo = self::getConexao();

        $sql = "INSERT INTO `usuarios` (`nome`, `email`, `senha`, `ativo`, `id_perfil`) VALUES (:nome, :email, :senha, :ativo, :idPerfil)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':nome'     => $this->nome,
            ':email'    => $this->email,
            ':senha'    => $this->senhaHash,
            ':ativo'    => $this->ativo,
            ':idPerfil' => $this->idPerfil,
        ]);

        $ultimoId = $pdo->lastInsertId();
        if ($ultimoId <= 0) {
            throw new Exception("Não foi possível inserir o usuário");
        }
        return $ultimoId;
    }

    public static function listar()
    {
        $pdo = self::getConexao();
        $sql = "SELECT u.id_usuario, u.nome, u.email, u.ativo, u.id_perfil, 
        p.nome_perfil AS perfil_nivel
        FROM usuarios AS u 
        INNER JOIN perfis AS p ON p.id_perfil = u.id_perfil 
        ORDER BY u.nome";

        $stmt = $pdo->query($sql);

        $usuarios = [];
        // $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuario = new Usuario(
                id: $row['id_usuario'],
                idPerfil: $row['id_perfil'],
                nome: $row['nome'],
                email: $row['email'],
                senhaHash: "",
                ativo: (bool)$row['ativo']
            );

            $usuario->perfilNome = $row['perfil_nivel'];

            array_push($usuarios, $usuario);
        }
        return $usuarios;
    }

    public static function buscarPorId(int $id){
            $pdo = self::getConexao();
            $sql = "SELECT u.id_usuario, u.nome, u.email, u.ativo, u.id_perfil, p.nome_perfil AS perfil_nivel FROM usuarios AS u INNER JOIN perfis AS p ON p.id_perfil = u.id_perfil WHERE u.id_usuario = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
            if(!$row){
                throw new Exception("ID do usuário não encontrado.");
                return null;
            }
 
            $usuario = new Usuario(id: $row['id_usuario'], nome: $row['nome'], email: $row['email'], senhaHash: "", idPerfil: $row['id_perfil'], ativo: (bool)$row['ativo']);
            $usuario -> perfilNome = $row['perfil_nivel'];
            return $usuario;
        }
 
        public static function buscarPorEmail(string $email){
            $pdo = self::getConexao();
            $sql = "SELECT u.id_usuario, u.nome, u.email, u.ativo, u.id_perfil, p.nome_perfil AS perfil_nivel FROM usuarios AS u INNER JOIN perfis AS p ON p.id_perfil = u.id_perfil WHERE u.email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
 
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
            if(!$row){
                throw new Exception("E-mail do usuário não encontrado.");
                return null;
            }
 
            $usuario = new Usuario(id: $row['id_usuario'], nome: $row['nome'], email: $row['email'], senhaHash: "", idPerfil: $row['id_perfil'], ativo: (bool)$row['ativo']);
            $usuario -> perfilNome = $row['perfil_nivel'];
            return $usuario;
        }
}

// $usuario1 = new Usuario(idPerfil:1, nome:"Alessandro", email:"alessandro@gmail.com", senhaHash: "123", ativo:false);

// $usuario1 = new Usuario(
//     nome: "Rhanna",
//     email: "rhanna1@gmail.com",
//     senhaHash: "123456789",
//     idPerfil: 2,
//     ativo: true
// );

//    $usuario1->inserir();
echo "<pre>";
// print_r($usuario1->listar());
print_r(Usuario::listar());
// echo "</pre>";

// Usuario::listar();
// $usuario1->nome = "Alessandro Souza <br>";
// echo $usuario1->nome;
// echo $usuario1->senhaHash;
