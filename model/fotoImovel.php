<?php
require_once(__DIR__ . "/../config/conexao.php");

class FotoImovel
{
    private ?int $id_foto;
    private int $id_imovel;
    private string $caminho;
    private bool $destaque;
    private int $ordem;

    public function __construct(
        ?int $id_foto = 0,
        int $id_imovel = 0,
        string $caminho = "",
        bool $destaque = false,
        int $ordem = 0

    ) {
        $this->id_foto = $id_foto;
        $this->id_imovel = $id_imovel;
        $this->caminho = $caminho;
        $this->destaque = $destaque;
        $this->ordem = $ordem;
    }

    public function __get(string $prop)
    {
        if (property_exists($this, $prop)) {
            return $this->$prop;
        }
        throw new Exception("Propridade $prop não existe");
    }

    //Métodos SET
    public function __set(string $prop, $valor)
    {

        if (property_exists($this, $prop)) {

            switch ($prop) {
                case "id_foto":
                case "id_imovel":
                case "ordem":
                    $this->$prop = (int)$valor;
                    break;
                case "destaque":
                    $this->$prop = (bool)$valor;
                    break;
                default:
                    $this->$prop = is_string($valor) ? trim($valor) : $valor;
                    break;
            }
        } else {
            throw new Exception("Propriedade $prop não existe");
        }
    }

    private static function getConexao()
    {
        return (new Conexao())->conexao();
    }

    public function salvar()
    {
        $pdo = self::getConexao();
        
        // Se for definir como principal, desmarca as outras fotos deste imóvel primeiro
        if ($this->destaque) {
            $sqlReset = "UPDATE imoveis_fotos SET destaque = 0 WHERE id_imovel = :id_imovel";
            $stmtReset = $pdo->prepare($sqlReset);
            $stmtReset->execute([':id_imovel' => $this->id_imovel]);
        }

        $sql = "INSERT INTO imoveis_fotos (id_imovel, caminho, destaque) 
                VALUES (:id_imovel, :caminho, :destaque)";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id_imovel' => $this->id_imovel,
            ':caminho' => $this->caminho,
            ':destaque' => (int)$this->destaque
        ]);
    }

    public static function reordenar() {


    }
}

$fotoImovel = new FotoImovel(
    id_imovel:1,
    caminho:"C:\Windows\System32",
    destaque: true,
    ordem:1
);

$fotoImovel->salvar();