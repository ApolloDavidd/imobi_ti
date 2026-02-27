<?php
    require_once(__DIR__ . "/../config/conexao.php");

class Perfil
{
    private ?int     $id;
    private string  $nome;

    public function __construct(?int $id = 0, string $nome)
    {
        $this->id = $id;
        $this->nome = $nome;
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
            case "nome":
                $this->nome = trim($valor);
                break;
        }
    }

    private static function getConexao()
    {
        return (new Conexao())->conexao();
    }

    public static function buscarPorId(int $id)
    {
        $pdo = self::getConexao();
        $sql = "SELECT p.id_perfil, p.nome_perfil FROM perfis AS p WHERE p.id_perfil = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception("ID do perfil não encontrado.");
            return null;
        }

        $perfil = new Perfil(id: $row['id_perfil'], nome: $row['nome_perfil']);
        return $perfil;
    }

    public static function listar()
    {
        $pdo = self::getConexao();
        $sql = "SELECT p.id_perfil, p.nome_perfil FROM perfis AS p 
        ORDER BY p.nome_perfil";

        $stmt = $pdo->query($sql);

        $perfis = [];
        // $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $perfil = new Perfil(
                id: $row['id_perfil'],
                nome: $row['nome_perfil'],
            );

            array_push($perfis, $perfil);
        }
        return $perfis;
    }
}

echo "<pre>";
print_r(Perfil::buscarPorId(1));
print_r(Perfil::listar());   

?>