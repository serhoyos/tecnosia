<?php

namespace App\Controllers;

use App\Models\AIDiagnostic;
use App\Models\IdeaModel;

/**
 * Clase AIController
 * * Se encarga de la integración con la API de Google Gemini para
 * generar diagnósticos técnicos automáticos sobre las ideas de negocio.
 */
class AIController
{
    // Reemplaza con tu clave de API real de Google AI Studio
    private $apiKey = ''; 
    private $diagnosticModel;
    private $ideaModel;

    public function __construct()
    {
        $this->diagnosticModel = new AIDiagnostic();
        $this->ideaModel = new IdeaModel();
    }

    /**
     * Genera un diagnóstico completo para una idea específica.
     * * @param int $ideaId ID de la idea en la base de datos.
     * @return bool|int Retorna el ID del diagnóstico guardado o false si falla.
     */
    public function generateDiagnostic($ideaId)
    {
        // 1. Obtener los datos de la idea desde el modelo
        $idea = $this->ideaModel->findById($ideaId);
        if (!$idea) {
            return false;
        }

        // 2. Configuración del modelo Gemini 3 Flash Preview
        $model = "gemini-3-flash-preview"; 
        $url = "https://generativelanguage.googleapis.com/v1beta/models/" . $model . ":generateContent?key=" . $this->apiKey;

        // 3. Construcción del prompt de ingeniería para validación de negocios
        $prompt = "Actúa como un experto en validación de startups y metodologías ágiles. " .
                  "Analiza la siguiente idea de negocio: '" . $idea['titulo'] . "'. " .
                  "Descripción del proyecto: '" . $idea['descripcion'] . "'. " .
                  "Sector económico: " . $idea['sector'] . ". " .
                  "Tu tarea es generar un diagnóstico técnico. " .
                  "Responde estrictamente en formato JSON válido con las siguientes llaves: " .
                  "coherence_analysis (un párrafo analizando la viabilidad), " .
                  "risks_identified (una lista de los principales riesgos), " .
                  "focus_suggestions (una lista de sugerencias para mejorar el enfoque). " .
                  "No incluyas texto explicativo fuera del JSON.";

        $payload = json_encode([
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ]
        ]);

        // 4. Ejecución de la petición mediante cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

        $response = curl_exec($ch);
        $result = json_decode($response, true);
        curl_close($ch);

        // 5. Validación de la respuesta de la API
        if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return false;
        }

        $aiRawText = $result['candidates'][0]['content']['parts'][0]['text'];

        // 6. Extractor Robusto de JSON (Mejora técnica sugerida)
        // Buscamos el contenido que se encuentra entre la primera { y la última }
        // Esto evita que el sistema falle si la IA añade comentarios adicionales.
        $dataClean = [];
        if (preg_match('/\{.*\}/s', $aiRawText, $matches)) {
            $dataClean = json_decode($matches[0], true);
        } else {
            // Fallback: Intento de limpieza manual si no hay llaves detectadas
            $aiRawTextClean = trim(str_replace(['```json', '```'], '', $aiRawText));
            $dataClean = json_decode($aiRawTextClean, true);
        }

        // 7. Persistencia de los resultados en la base de datos
        // Se asegura que los datos sean procesados como string aunque vengan como array de la IA
        return $this->diagnosticModel->store([
            'idea_id'            => $ideaId,
            'raw_response'       => $aiRawText,
            'coherence_analysis' => $dataClean['coherence_analysis'] ?? 'El análisis no se pudo procesar correctamente.',
            'risks_identified'   => isset($dataClean['risks_identified']) 
                                    ? (is_array($dataClean['risks_identified']) ? implode(", ", $dataClean['risks_identified']) : $dataClean['risks_identified']) 
                                    : 'Información no disponible.',
            'focus_suggestions'  => isset($dataClean['focus_suggestions']) 
                                    ? (is_array($dataClean['focus_suggestions']) ? implode(", ", $dataClean['focus_suggestions']) : $dataClean['focus_suggestions']) 
                                    : 'Información no disponible.'
        ]);
    }
}