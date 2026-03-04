<?php
require_once(__DIR__ . "/../config/conexao.php");

class Imovel
{
    private ?int $id;
    private int $id_corretor;
    private string  $titulo;
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
    private bool $possui_piscina;
    private bool $possui_churrasqueira;
    private string $slug;
    private string $data_criacao;

    public function __construct(
        ?int $id = 0,
        int $id_corretor = 0,
        string  $titulo = "",
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
        string $status = "",
        bool $possui_piscina = false,
        bool $possui_churrasqueira = false,
        string $slug = "",
        ?string $data_criacao = null
    ) {
        $this->id = $id;
        $this->id_corretor = $id_corretor;
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
        $this->possui_piscina = $possui_piscina;
        $this->possui_churrasqueira = $possui_churrasqueira;
        $this->slug = $slug;
        $this->data_criacao = $data_criacao ?? date('Y-m-d H:i:s');
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
        if (property_exists($this, $prop)) {
            switch ($prop) {
                // 1. Números Inteiros
                case "id":
                case "id_corretor":
                case "quartos":
                case "banheiros":
                case "vagas":
                    $this->$prop = (int)$valor;
                    break;

                // 2. Números Decimais (Valores e Medidas)
                case "preco":
                case "valor_condominio":
                case "valor_iptu":
                case "area":
                    $this->$prop = (float)$valor;
                    break;

                // 3. Booleanos (Verdadeiro ou Falso)
                case "possui_piscina":
                case "possui_churrasqueira":
                    $this->$prop = (bool)$valor;
                    break;

                // 4. Textos (Strings)
                default:
                    $this->$prop = is_string($valor) ? trim($valor) : $valor;
                    break;
            }
        } else {
            throw new Exception("Propriedade {$prop} não existe.");
        }
    }
    private static function getConexao()
    {
        return (new Conexao())->conexao();
    }

    public function salvar()
    {
        $pdo = self::getConexao();

        if ($this->id > 0) {
            // UPDATE
            $sql = "UPDATE `imoveis` SET 
                    titulo = :titulo, tipo = :tipo, tipo_negocio = :tipo_negocio, descricao = :descricao, 
                    preco = :preco, valor_condominio = :valor_condominio, valor_iptu = :valor_iptu, 
                    cep = :cep, cidade = :cidade, bairro = :bairro, estado = :estado, endereco = :endereco, 
                    quartos = :quartos, banheiros = :banheiros, vagas = :vagas, area = :area, 
                    status = :status, id_corretor = :id_corretor, possui_piscina = :possui_piscina, 
                    possui_churrasqueira = :possui_churrasqueira, slug = :slug 
                    WHERE id_imovel = :id";
        } else {
            // INSERT
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

        if($this->id > 0){
            $params [':id'] = $this->id;
        }else{
            $params[':data_criacao'] = $this->data_criacao;
        }

        $res = $stmt->execute($params);

        if($res && $this->id==0){
            $this->id = (int)$pdo->lastInsertId();
        }
            return $res;
        
    }
}

try{
    $imovel = new Imovel(
        id:0,
        titulo:"Casa de Luxo no Parque do Carmo",
        tipo:"Casa",
        tipo_negocio:"venda",
        descricao: "Maravilhosa casa localizada no Pq do Carmo, ideais para familia de luxo",
        preco: 10000000.50,
        valor_condominio: 0.0,
        valor_iptu: 1200.00,
        cep: "08450-000",
        cidade: "São Paulo",
        bairro: "Itaquera",
        estado: "SP",
        endereco:"Rua Avenida Travessa Itaquera",
        quartos: 4,
        banheiros: 2,
        vagas: 2,
        area: 350.50,
        status:"Disponivel",
        id_corretor:1,
        possui_piscina: true,
        possui_churrasqueira: true,
        slug: "casa-pq-do-carmo"
    );
}catch(Exception $e){
    echo $e->getMessage();
}

?>