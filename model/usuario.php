<?php
class Usuario
{
    private int $id;
    private string $nome;
    private string $email;
    private string $senhaHash;
    private bool $ativo;

    //FORMA CORRETA PARA NÃO PREGUICOSOS


    // public function getId():int{
    //     return $this->id;
    // }
    // public function getNome():string{
    //     return $this->nome;
    // }
    // public function getEmail():string{
    //     return $this->email;
    // }
    // public function getSenhaHash():string{
    //     return $this->senhaHash;
    // }        
    // public function getAtivo():bool{
    //     return $this->ativo;
    // }
    // public function set(){

    // }
    // public function set(){

    // }
    // public function set(){

    // }
    // public function set(){

    // }
    // public function setNome($nome){
    // $this->nome = $nome/
    // 
    // }

    public function __construct(
        ?int $id = 0,
        string $nome,
        string $email,
        string $senhaHash,
        ?bool $ativo = true,
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->senhaHash = $senhaHash;
        $this->ativo = $ativo;
    }


    // Método mágico get e set
    public function __get(string $prop)
    {
        if (property_exists($this, $prop)) {
            return $this->$prop;
        } else {
            throw new Exception("Propriedade {$prop} não existe");
        }
    }

    public function __set($prop, $valor)
    {
        switch ($prop) {
            case "id":
                $this->id = (int)$valor;
                break;
            case "nome":
                $this->id = trim($valor);
                break;
            case "email":
                if(!filter_var($valor, FILTER_VALIDATE_EMAIL)){
                    throw new Exception("E-mail inválido");
                }
                $this->email = $valor;
                break;
            case "senhaHash":
                $this->senhaHash = password_hash($valor, PASSWORD_DEFAULT);
                break;
            case "ativo":
                $this->ativo = (bool)$valor;
                break;
            default:
                throw new Exception("A propriedade $valor não existe");
                break;
        }
    }
}

$usuario1 = new Usuario(nome: "Apollo", email: "apollo@email.com", senhaHash: 123, ativo: false);
// $usuario1->setNome("Apollo David");
echo $usuario1->nome;
