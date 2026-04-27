<?php
// app/Core/GeminiClient.php

namespace App\Core;

class GeminiClient
{
    private $apiKey;
    private $endpoint;

    public function __construct()
    {
        // Recuperamos las credenciales centralizadas
        $this->apiKey = GEMINI_API_KEY;
        $this->endpoint = GEMINI_ENDPOINT;
    }

    /**
     * Realiza una llamada cURL segura a la API de Gemini.
     *
     * @param string $prompt El prompt estructurado en español.
     * @return array|null El JSON decodificado como array o null si falla.
     */
    public function callAPI(string $prompt)
    {
        // 1. Estructurar el cuerpo de la petición según requiere Gemini
        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];

        $json_data = json_encode($data);

        // 2. Configurar cURL para conexión segura HTTPS
        $ch = curl_init($this->endpoint . '?key=' . $this->apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Devolver respuesta como string
        curl_setopt($ch, CURLOPT_POST, true);           // Petición POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data); // Datos
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'          // Header requerido
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Forzar validación SSL (Seguridad UNAD)

        // 3. Ejecutar y capturar respuesta
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        // 4. Manejo de errores de conexión técnica
        if ($curl_error) {
            error_log("TECNOSIA_IA_ERROR: cURL falló: " . $curl_error);
            return null;
        }

        if ($http_code !== 200) {
            error_log("TECNOSIA_IA_ERROR: HTTP Código " . $http_code . ". Respuesta: " . $response);
            return null;
        }

        // 5. Decodificar JSON y verificar estructura
        $result = json_decode($response, true);

        // Gemini devuelve una estructura anidada que debemos verificar.
        if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            error_log("TECNOSIA_IA_ERROR: Estructura JSON inesperada. Respuesta completa: " . $response);
            return null;
        }

        // Extraemos y decodificamos el JSON INTERNO que le pedimos a la IA
        $ai_text_response = $result['candidates'][0]['content']['parts'][0]['text'];
        
        // Limpiamos posibles caracteres extraños o markdown de la IA
        $clean_json = trim($ai_text_response, "```json\n");
        $clean_json = trim($clean_json, "```");

        $final_json = json_decode($clean_json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("TECNOSIA_IA_ERROR: El JSON interno generado por la IA no es válido: " . json_last_error_msg());
            return null;
        }

        return $final_json;
    }
}