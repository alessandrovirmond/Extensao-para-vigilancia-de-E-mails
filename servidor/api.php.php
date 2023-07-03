<?php

// Caminho para o arquivo JSON
$jsonFilePath = 'emails.json';

// Verifica se o arquivo JSON existe
if (!file_exists($jsonFilePath)) {
    // Cria um array vazio
    $data = array();
} else {
    // Lê o conteúdo do arquivo JSON
    $jsonData = file_get_contents($jsonFilePath);
    
    // Converte o JSON em array associativo
    $data = json_decode($jsonData, true);
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Origin: http://cefet-rj.br/');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
    




// Verifica o método de requisição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém o corpo da requisição POST
    $postData = file_get_contents('php://input');
    
    // Verifica se o corpo da requisição é um JSON válido
    if ($postData === false || !json_decode($postData)) {
        // Retorna uma resposta de erro se o JSON for inválido
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'O corpo da requisição não é um JSON válido.'));
        exit;
    }
    
    // Converte o JSON em array associativo
    $postArray = json_decode($postData, true);
    
    // Verifica se o campo 'email' foi enviado
    if (isset($postArray['email']) && !empty($postArray['email'])) {
        // Obtém o e-mail enviado
        $email = $postArray['email'];
        
        // Adiciona o e-mail ao array de dados
        $data[] = array(
            'email' => $email
        );
        
        // Converte o array em JSON
        $jsonData = json_encode($data);
        
        // Escreve o JSON de volta no arquivo
        file_put_contents($jsonFilePath, $jsonData);
        
        // Retorna uma resposta de sucesso
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'E-mail adicionado com sucesso.'));
        exit;
    } else {
        // Retorna uma resposta de erro se o campo 'email' estiver faltando
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'O campo "email" é obrigatório.'));
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retorna a lista de e-mails em formato JSON
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
} else {
    // Retorna uma resposta de erro para outros métodos de requisição
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Método de requisição inválido.'));
    exit;
}

?>
