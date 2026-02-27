<?php
require_once(__DIR__ . "/../config/conexao.php");

class Corretor
{
    private ?int     $id;
    private string  $creci;
    private string $telefone;
    private string $whatsapp;
    private bool $ativo;

    public function __construct(
        ?int $id = 0,
        string $creci,
        string $telefone,
        string $whatsapp,
        bool $ativo
    ) {
        $this->id = $id;
        $this->creci = $creci;
        $this->telefone = $telefone;
        $this->whatsapp = $whatsapp;
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
            case "creci":
                $this->creci = trim($valor);
                break;
            case "telefone":
                $this->telefone = trim($valor);
                break;
            case "whatsapp":
                $this->whatsapp = $valor;
                break;
            case "ativo":
                $this->ativo = (bool)$valor;
                break;
        }
    }

    private static function getConexao()
    {
        return (new Conexao())->conexao();
    }

    public static function listarImoveis()
    {
        $pdo = self::getConexao();
        $sql = "SELECT c.id_corretor, c.creci, c.telefone, c.whatsapp, c.ativo
        FROM corretores AS c 
        INNER JOIN usuarios AS u ON c.id_usuario = u.id_usuario 
        ORDER BY c.creci";

        $stmt = $pdo->query($sql);

        $corretores = [];
        // $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $corretor = new Corretor(
                id: $row['id_corretor'],
                creci: $row['creci'],
                telefone: $row['telefone'],
                whatsapp: $row['whatsapp'],
                ativo: $row['ativo']
            );
            array_push($corretores, $corretor);
        }
        return $corretores;
    }

    public static function listarVisitas()
    {
        $pdo = self::getConexao();
        $sql = "SELECT c.id_corretor, c.creci, c.telefone, c.whatsapp, c.ativo
        FROM corretores AS c 
        INNER JOIN usuarios AS u ON c.id_usuario = u.id_usuario 
        ORDER BY c.creci";

        $stmt = $pdo->query($sql);

        $corretores = [];
        // $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $corretor = new Corretor(
                id: $row['id_corretor'],
                creci: $row['creci'],
                telefone: $row['telefone'],
                whatsapp: $row['whatsapp'],
                ativo: $row['ativo']
            );
            array_push($corretores, $corretor);
        }
        return $corretores;
    }

    public static function confirmarVisita()
    {
        $pdo = self::getConexao();
        $sql = "SELECT c.id_corretor, c.creci, c.telefone, c.whatsapp, c.ativo
        FROM corretores AS c 
        INNER JOIN usuarios AS u ON c.id_usuario = u.id_usuario 
        ORDER BY c.creci";

        $stmt = $pdo->query($sql);

        $corretores = [];
        // $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $corretor = new Corretor(
                id: $row['id_corretor'],
                creci: $row['creci'],
                telefone: $row['telefone'],
                whatsapp: $row['whatsapp'],
                ativo: $row['ativo']
            );
            array_push($corretores, $corretor);
        }
        return $corretores;
    }

    public static function desativar()
    {
        $pdo = self::getConexao();
        $sql = "SELECT c.id_corretor, c.creci, c.telefone, c.whatsapp, c.ativo
        FROM corretores AS c 
        INNER JOIN usuarios AS u ON c.id_usuario = u.id_usuario 
        ORDER BY c.creci";

        $stmt = $pdo->query($sql);

        $corretores = [];
        // $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $corretor = new Corretor(
                id: $row['id_corretor'],
                creci: $row['creci'],
                telefone: $row['telefone'],
                whatsapp: $row['whatsapp'],
                ativo: $row['ativo']
            );
            array_push($corretores, $corretor);
        }
        return $corretores;
    }
}
echo "<pre>";
print_r(Corretor::listarImoveis());
