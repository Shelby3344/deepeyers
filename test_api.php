<?php

$apiKey = 'sk-7f30b08fa36b4604b1a9bfc3c06bdefa';
$endpoint = 'https://api.deepseek.com/chat/completions';

$payload = json_encode([
    'model' => 'deepseek-chat',
    'messages' => [
        ['role' => 'user', 'content' => 'Diga apenas: API funcionando!']
    ],
    'max_tokens' => 50
]);

$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

echo "Testando conexão com DeepSeek API...\n\n";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";

if ($error) {
    echo "Curl Error: " . $error . "\n";
} else {
    $data = json_decode($response, true);
    
    if (isset($data['choices'][0]['message']['content'])) {
        echo "\n✅ SUCESSO!\n";
        echo "Resposta: " . $data['choices'][0]['message']['content'] . "\n";
        echo "Tokens usados: " . ($data['usage']['total_tokens'] ?? 'N/A') . "\n";
    } elseif (isset($data['error'])) {
        echo "\n❌ ERRO DA API:\n";
        echo "Mensagem: " . ($data['error']['message'] ?? 'Desconhecido') . "\n";
        echo "Tipo: " . ($data['error']['type'] ?? 'Desconhecido') . "\n";
    } else {
        echo "\nResposta completa:\n" . $response . "\n";
    }
}
