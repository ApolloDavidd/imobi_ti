<?php
require_once(__DIR__ . "/../config/conexao.php");

class Imovel
{
    private ?int $id;
    private string $titulo;
    private string $tipo;
    private string $tipo_negocio;
    private string $descricao;
    private float $preco;
    private float $valor_condominio;
    private float $valor_iptu;
    private string $cep;
    private string $cidade;
    private string $bairro;
    private string $estado;
    private string $endereco;
    private int $quartos;
    private int $banheiros;
    private int $vagas;
    private float $area;
    private string $status;
    private int $id_corretor;
    private bool $possui_piscina;
    private bool $possui_churrasqueira;
    private string $slug;
    private string $data_criacao;

    private ?string $foto_principal = null;

    public function __construct(
        ?int $id = 0,
        string $titulo = "",
        string $tipo = "",
        string $tipo_negocio = "",
        string $descricao = "",
        float $preco = 0.0,
        float $valor_condominio = 0.0,
        float $valor_iptu = 0.0,
        string $cep = "",
        string $cidade = "",
        string $bairro = "",
        string $estado = "",
        string $endereco = "",
        int $quartos = 0,
        int $banheiros = 0,
        int $vagas = 0,
        float $area = 0.0,
        string $status = "disponivel",
        int $id_corretor = 0,
        bool $possui_piscina = false,
        bool $possui_churrasqueira = false,
        string $slug = "",
        ?string $data_criacao = null

    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->tipo = $tipo;
        $this->tipo_negocio = $tipo_negocio;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->valor_condominio = $valor_condominio;
        $this->valor_iptu = $valor_iptu;
        $this->cep = $cep;
        $this->cidade = $cidade;
        $this->bairro = $bairro;
        $this->estado = $estado;
        $this->endereco = $endereco;
        $this->quartos = $quartos;
        $this->banheiros = $banheiros;
        $this->vagas = $vagas;
        $this->area = $area;
        $this->status = $status;
        $this->id_corretor = $id_corretor;
        $this->possui_piscina = $possui_piscina;
        $this->possui_churrasqueira = $possui_churrasqueira;
        $this->slug = $slug;
        // Inicializa com a data atual se não for fornecida 
        $this->data_criacao = $data_criacao ?? date('Y-m-d H:i:s');
    }

    // Método GET

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
                case "id":
                case "quartos":
                case "banheiros":
                case "vagas":
                case "id_corretor":
                    $this->$prop = (int)$valor;
                    break;
                case "preco":
                case "valor_condominio":
                case "valor_iptu":
                case "area":
                    $this->$prop = (float)$valor;
                    break;
                case "possui_piscina":
                case "possui_churrasqueira":
                    $this->$prop = (bool)$valor;
                    break;
                default:
                    $this->$prop = is_string($valor) ? trim($valor) : $valor;
                    break;
            }
        } else {
            throw new Exception("Propriedade $prop não existe");
        }
    } // FIM MÉTODO __SET

    private static function getConexao()
    {
        return (new Conexao())->conexao();
    }

    public function salvar()
    {
        $pdo = self::getConexao();

        if ($this->id > 0) {
            // ATUALIZAR
            $sql = "UPDATE `imoveis` SET 
                    titulo = :titulo, tipo = :tipo, tipo_negocio = :tipo_negocio, descricao = :descricao, 
                    preco = :preco, valor_condominio = :valor_condominio, valor_iptu = :valor_iptu, 
                    cep = :cep, cidade = :cidade, bairro = :bairro, estado = :estado, endereco = :endereco, 
                    quartos = :quartos, banheiros = :banheiros, vagas = :vagas, area = :area, 
                    status = :status, id_corretor = :id_corretor, possui_piscina = :possui_piscina, 
                    possui_churrasqueira = :possui_churrasqueira, slug = :slug 
                    WHERE id_imovel = :id";
        } else {
            // INSERIR
            $sql = "INSERT INTO `imoveis` (
                titulo, tipo, tipo_negocio, descricao, preco, valor_condominio, valor_iptu, 
                cep, cidade, bairro, estado, endereco, quartos, banheiros, vagas, area, 
                status, id_corretor, possui_piscina, possui_churrasqueira, slug, data_criacao
                ) VALUES (
                :titulo, :tipo, :tipo_negocio, :descricao, :preco, :valor_condominio, :valor_iptu, 
                :cep, :cidade, :bairro, :estado, :endereco, :quartos, :banheiros, :vagas, :area, 
                :status, :id_corretor, :possui_piscina, :possui_churrasqueira, :slug, :data_criacao
                )";
        }

        $stmt = $pdo->prepare($sql);

        $params = [
            ':titulo' => $this->titulo,
            ':tipo' => $this->tipo,
            ':tipo_negocio' => $this->tipo_negocio,
            ':descricao' => $this->descricao,
            ':preco' => $this->preco,
            ':valor_condominio' => $this->valor_condominio,
            ':valor_iptu' => $this->valor_iptu,
            ':cep' => $this->cep,
            ':cidade' => $this->cidade,
            ':bairro' => $this->bairro,
            ':estado' => $this->estado,
            ':endereco' => $this->endereco,
            ':quartos' => $this->quartos,
            ':banheiros' => $this->banheiros,
            ':vagas' => $this->vagas,
            ':area' => $this->area,
            ':status' => $this->status,
            ':id_corretor' => $this->id_corretor,
            ':possui_piscina' => (int)$this->possui_piscina,
            ':possui_churrasqueira' => (int)$this->possui_churrasqueira,
            ':slug' => $this->slug
        ];

        if ($this->id > 0) {
            $params[':id'] = $this->id;
        } else {
            $params[':data_criacao'] = $this->data_criacao;
        }

        $res = $stmt->execute($params);

        if ($res && $this->id == 0) {
            $this->id = (int)$pdo->lastInsertId();
        }

        return $res;
    }


    public function excluir()
    {
        $pdo = self::getConexao();

        // 1. Deleta as fotos do banco primeiro por causa da chave estrangeira
        $stmt1 = $pdo->prepare("DELETE FROM `fotos_imovel` WHERE `id_imovel` = ?");
        $stmt1->execute([$this->id]);

        // 2. Deleta o imovel
        $stmt2 = $pdo->prepare("DELETE FROM `imoveis` WHERE `id_imovel` = ?");
        return $stmt2->execute([$this->id]);
    }

    public static function listarComFoto()
    {
        $pdo = self::getConexao();

        // Mapeamos 'id_imovel' para 'id' via Alias para coincidir com a propriedade da classe
        $sql = "SELECT 
                i.id_imovel AS id, 
                i.titulo, i.tipo, i.tipo_negocio, i.descricao, i.preco, 
                i.valor_condominio, i.valor_iptu, i.cep, i.cidade, i.bairro, 
                i.estado, i.endereco, i.quartos, i.banheiros, i.vagas, i.area, 
                i.status, i.id_corretor, i.possui_piscina, i.possui_churrasqueira, 
                i.slug, i.data_criacao,
                f.caminho AS foto_principal 
            FROM imoveis i 
            LEFT JOIN fotos_imovel f ON i.id_imovel = f.id_imovel AND f.destaque = 1
            ORDER BY i.id_imovel DESC";

        $stmt = $pdo->query($sql);

        // MAPEIA O ARRAY DE RETORNO EM UM OBJETO 'Imovel'
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Imovel');
    }

    public static function listarComFiltros(array $filtros = [])
    {
        $pdo = self::getConexao();

        $sql = "SELECT i.id_imovel AS id,
                i.titulo, i.tipo, i.tipo_negocio, i.descricao, i.preco,
                i.valor_condominio, i.valor_iptu, i.cep, i.cidade, i.bairro,
                i.estado, i.endereco, i.quartos, i.banheiros, i.vagas, i.area,
                i.status, i.id_corretor, i.possui_piscina, i.possui_churrasqueira,
                i.slug, i.data_criacao, f.caminho AS foto_principal
            FROM imoveis i
            LEFT JOIN fotos_imovel f ON i.id_imovel = f.id_imovel AND f.destaque = 1
            WHERE 1=1";

        $params = [];
        if (!empty($filtros['tipo'])) {
            $sql .= " AND i.tipo = ?";
            $params[] = $filtros['tipo'];
        }

        if (!empty($filtros['status'])) {
            $sql .= " AND i.status = ?";
            $params[] = $filtros['status'];
        }

        if (!empty($filtros['busca'])) {
            $sql .= " AND (i.titulo LIKE ? OR i.bairro LIKE ? OR i.cidade LIKE ?)";
            $busca = "%" . $filtros['busca'] . "%";

            $params[] = $busca;
            $params[] = $busca;
            $params[] = $busca;
        }

        $sql .= " ORDER BY i.id_imovel DESC";

        // $stmt = $pdo->query($sql);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        // echo "<pre>";
        // print_r($stmt->debugDumpParams());

        // // MAPEIA O ARRAY DE RETORNO EM UM OBJETO 'Imovel'
        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Imovel');
    }
}
