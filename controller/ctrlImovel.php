<?php

require_once(__DIR__ . "/../model/imoveis.php");
require_once(__DIR__ . "/../model/fotoImovel.php");

function criarSlug($titulo)
{
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titulo)));
}
// echo "<pre>";
// print_r($_POST);
// echo "<hr>";
// print_r($_FILES);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $imovel = new Imovel(
            id: 0,
            titulo: $_POST['titulo'] ?? '',
            tipo: $_POST['tipo'] ?? '',
            tipo_negocio: $_POST['tipo_negocio'] ?? '',
            descricao: $_POST['descricao'] ?? '',
            preco: (float)($_POST['preco'] ?? 0),
            valor_condominio: (float)($_POST['valor_condominio'] ?? 0),
            valor_iptu: (float)($_POST['valor_iptu'] ?? 0),
            cep: $_POST['cep'] ?? '',
            cidade: $_POST['cidade'] ?? '',
            bairro: $_POST['bairro'] ?? '',
            estado: $_POST['estado'] ?? '',
            endereco: $_POST['endereco'] ?? '',
            quartos: (int)($_POST['quartos'] ?? 0),
            banheiros: (int)($_POST['banheiros'] ?? 0),
            vagas: (int)($_POST['vagas'] ?? 0),
            area: (float)($_POST['area'] ?? 0),
            status: $_POST['status'] ?? 'disponível',
            id_corretor: (int)($_POST['id_corretor'] ?? 0),
            possui_piscina: $_POST['possui_piscina'] ?? false,
            possui_churrasqueira: $_POST['possui_churrasqueira'] ?? false,
            slug: criarSlug($_POST['titulo'] ?? 'imovel')
        );

        if ($imovel->salvar()) {
            $idImovel = $imovel->id;

            if (isset($_FILES['fotos']) && !empty($_FILES['fotos'])) {
                $diretorio = "../uploads/imoveis/$idImovel/";
                if (!is_dir($diretorio))
                    mkdir($diretorio, 0777, true);

                foreach ($_FILES['fotos']['tmp_name'] as $index => $tmpName) {
                    $nomeArquivo = time() . "-" . $_FILES['fotos']['name'][$index];
                    $caminhoFinal = $diretorio . $nomeArquivo;

                    if (move_uploaded_file($tmpName, $caminhoFinal)) {
                        $principal = ((int)$_POST['index_principal'] === $index);

                        $foto = new FotoImovel(
                            id_imovel: $idImovel,
                            caminho: $caminhoFinal,
                            destaque: $principal,
                            ordem: $index + 1
                        );

                        $foto->salvar();
                    }
                }
            }
            header("Location: ../view/painelCadImoveis.php?sucesso=1");
        } else {
            throw new Exception("Erro ao gravar no banco de dados");
        }
    } catch (Exception $e) {
        die("Erro? " . $e->getMessage());
    }
}
