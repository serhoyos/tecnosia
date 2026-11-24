<?php

namespace App\Services;

class IdeaEvaluator
{
    /**
     * IdeaEvaluator V2.5
     *
     * Motor de inferencia determinista basado exclusivamente en reglas.
     *
     * Dimensiones:
     * 1. Problema / necesidad
     * 2. Segmento de cliente
     * 3. Propuesta de valor
     * 4. Monetización
     * 5. Diferenciación
     * 6. Nivel de detalle
     * 7. Sesgos / buzzwords
     * 8. Alcance
     * 9. Coherencia sectorial
     *
     * V2.5:
     * - Mantiene el motor 100 % determinista.
     * - Catálogo centralizado de sectores.
     * - Distingue sector principal de mercado atendido.
     * - Mejora la detección de sectores alternativos.
     * - Reduce falsos positivos sectoriales.
     * - No penaliza automáticamente la combinación SaaS + industria.
     * - Corrige la detección segura de palabras.
     * - Evita premiar múltiples apariciones del mismo concepto.
     * - Mejora detección de problema, cliente y diferenciación.
     * - Añade señales de madurez cuantificables.
     * - Puntuación final estrictamente entre 1 y 10.
     */
    public static function evaluate($ideaData)
    {
        $titulo = self::normalize($ideaData['titulo'] ?? '');
        $descripcion = self::normalize($ideaData['descripcion'] ?? '');

        $sector = trim($ideaData['sector'] ?? 'General');

        if ($sector === '') {
            $sector = 'General';
        }

        $score = 0.0;

        $insights = [];
        $riesgos = [];
        $recomendaciones = [];

        /*
         * ============================================================
         * CATÁLOGO DE SECTORES
         * ============================================================
         */

        $sectorRules = self::getSectorRules();

        if (!isset($sectorRules[$sector])) {
            $sector = 'General';
        }

        $sectorRule = $sectorRules[$sector];

        /*
         * ============================================================
         * REGLA 1 - PROBLEMA / NECESIDAD
         *
         * Máximo: 2.0 pts
         * ============================================================
         */

        $problemaFuerte = self::containsAny($descripcion, [
            'problema',
            'necesita',
            'necesitan',
            'dificultad',
            'dificultades',
            'error',
            'errores',
            'falta de',
            'pérdida de',
            'perdida de',
            'demora',
            'demoras',
            'alto costo',
            'altos costos',
            'alto coste',
            'altos costes',
            'poco tiempo',
            'dificulta',
            'ineficiente',
            'ineficiencia',
            'actualmente utiliza',
            'actualmente usan',
            'actualmente',
            'pierden',
            'pérdidas',
            'perdidas',
            'descontrol',
            'falla',
            'fallas',
            'costoso',
            'costosa',
            'lento',
            'lenta',
            'lentitud',
            'dificultad para',
            'no puede',
            'no pueden',
            'carece de',
            'sin acceso',
            'falta',
            'tiempo limitado',
            'presupuesto limitado',
            'no cuenta con',
            'no cuentan con'
        ]);

        $problemaIndefinido = self::containsAny($descripcion, [
            'no tengo claro',
            'todavía no sé',
            'todavia no se',
            'no sé exactamente',
            'no se exactamente',
            'no sabemos',
            'para cualquier cosa',
            'puede utilizarse para diferentes cosas',
            'diferentes cosas',
            'ayudar a las personas',
            'ayudar a todo el mundo',
            'no está definido',
            'no esta definido',
            'no sabemos qué problema',
            'no sabemos que problema'
        ]);

        $problemaEspecifico = self::containsAny($descripcion, [
            'actualmente',
            'cada día',
            'cada dia',
            'diariamente',
            'semanalmente',
            'mensualmente',
            'frecuentemente',
            'con frecuencia',
            'constantemente',
            'cuando',
            'por semana',
            'por mes',
            'por día',
            'por dia',
            'cada semana',
            'cada mes',
            'todos los días',
            'todos los dias'
        ]);

        $problemaCuantificado = self::hasNumericEvidence($descripcion);

        if ($problemaFuerte && !$problemaIndefinido) {

            if ($problemaEspecifico || $problemaCuantificado) {

                $score += 2.0;

                $insights[] =
                    'La propuesta presenta evidencia clara de un problema o necesidad de mercado y aporta contexto sobre su frecuencia o impacto.';

            } else {

                $score += 1.75;

                $insights[] =
                    'La propuesta presenta evidencia clara de un problema o necesidad de mercado que busca resolver.';

                $recomendaciones[] =
                    'Para fortalecer la hipótesis, cuantifica la frecuencia o impacto del problema cuando sea posible.';
            }

        } elseif ($problemaFuerte) {

            $score += 1.0;

            $insights[] =
                'La propuesta presenta un indicio de necesidad, pero su formulación requiere mayor precisión.';

            $riesgos[] =
                'El problema o necesidad no se encuentra definido con suficiente precisión.';

            $recomendaciones[] =
                'Describe con precisión qué problema enfrenta el usuario, con qué frecuencia ocurre y qué impacto tiene.';

        } else {

            $riesgos[] =
                'La descripción no define con claridad el problema o necesidad que origina la idea de negocio.';

            $recomendaciones[] =
                'Antes de desarrollar la solución, define el problema específico que vas a resolver y por qué es relevante para el cliente.';
        }

        /*
         * ============================================================
         * REGLA 2 - SEGMENTO DE CLIENTE
         *
         * Máximo: 1.5 pts
         * ============================================================
         */

        $clienteEspecifico = self::containsAny($descripcion, [
            'pequeñas tiendas',
            'tiendas de barrio',
            'pequeños comerciantes',
            'estudiantes universitarios',
            'estudiantes',
            'madres',
            'padres',
            'empresas pequeñas',
            'empresas medianas',
            'pymes',
            'emprendedores',
            'freelancers',
            'restaurantes',
            'hoteles',
            'agricultores',
            'productores agrícolas',
            'productores agricolas',
            'pacientes',
            'profesionales',
            'consultores',
            'turistas',
            'viajeros',
            'conductores',
            'transportistas',
            'propietarios de',
            'dueños de',
            'negocios locales',
            'comercios locales',
            'pequeños negocios',
            'comercios pequeños',
            'familias',
            'hogares'
        ]);

        $clienteGenerico = self::containsAny($descripcion, [
            'clientes',
            'usuarios',
            'personas',
            'el público',
            'publico',
            'todos',
            'cualquier persona',
            'todo el mundo',
            'consumidores'
        ]);

        $clienteDemasiadoAmplio = self::containsAny($descripcion, [
            'todas las personas',
            'todo tipo de personas',
            'cualquier persona',
            'cualquier usuario',
            'todos los usuarios',
            'todo el mundo',
            'para cualquiera',
            'todos los clientes',
            'cualquier cliente'
        ]);

        if ($clienteEspecifico && !$clienteDemasiadoAmplio) {

            $score += 1.5;

            $insights[] =
                'Se identifica un segmento de mercado específico y suficientemente acotado.';

        } elseif ($clienteGenerico || $clienteDemasiadoAmplio) {

            $score += 0.75;

            $riesgos[] =
                'El segmento de clientes es demasiado genérico y requiere mayor definición.';

            $recomendaciones[] =
                'Define un nicho inicial concreto y evita dirigir la propuesta a un público demasiado amplio.';

        } else {

            $riesgos[] =
                'No se identifica claramente quién es el cliente o usuario objetivo.';

            $recomendaciones[] =
                'Define quién es tu cliente ideal y qué características lo hacen especialmente relevante para la solución.';
        }

        /*
         * ============================================================
         * REGLA 3 - PROPUESTA DE VALOR
         *
         * Máximo: 2.0 pts
         * ============================================================
         */

        $beneficioConcreto = self::containsAny($descripcion, [
            'reducir errores',
            'reducir costos',
            'reducir costes',
            'ahorrar tiempo',
            'ahorrar dinero',
            'automatizar',
            'evitar errores',
            'aumentar ventas',
            'aumentar ingresos',
            'disminuir',
            'reducir',
            'mejorar',
            'optimizar',
            'agilizar',
            'facilitar',
            'simplificar',
            'entregas rápidas',
            'entregas rapidas',
            'alertas automáticas',
            'alertas automaticas',
            'reportes',
            'controlar',
            'control',
            'precios accesibles',
            'acceso rápido',
            'acceso rapido',
            'personalizado',
            'personalizada',
            'seguimiento',
            'organizar',
            'ahorrar',
            'evitar',
            'disminuir tiempos',
            'reducir tiempos',
            'mayor eficiencia',
            'mejor experiencia'
        ]);

        $incluyeMetricas = self::hasNumericEvidence($descripcion);

        if ($beneficioConcreto) {

            $score += 1.5;

            $insights[] =
                'La propuesta plantea beneficios concretos asociados con la solución.';

            if ($incluyeMetricas) {

                $score += 0.5;

                $insights[] =
                    'La propuesta incluye indicadores cuantificables que permiten evaluar el beneficio esperado.';

            } else {

                $recomendaciones[] =
                    'Cuando sea posible, cuantifica el beneficio esperado mediante tiempo, dinero, porcentaje, ventas u otra métrica.';
            }

        } else {

            $score += 0.5;

            $riesgos[] =
                'La propuesta de valor es demasiado genérica y no demuestra claramente qué beneficio obtiene el cliente.';

            $recomendaciones[] =
                'Explica qué beneficio concreto obtiene el cliente mediante la solución y cómo puede verificarse.';
        }

        /*
         * ============================================================
         * REGLA 4 - MONETIZACIÓN
         *
         * Máximo: 1.5 pts
         * ============================================================
         */

        $monetizacionBasica = self::containsAny($descripcion, [
            'suscripción',
            'suscripcion',
            'pago',
            'pagar',
            'pagará',
            'pagara',
            'venta',
            'vender',
            'vendemos',
            'comisión',
            'comision',
            'freemium',
            'ingresos',
            'precio',
            'precios',
            'tarifa',
            'mensualmente',
            'mensual',
            'anualmente',
            'anual',
            'por producto',
            'por usuario',
            'por servicio',
            'por pedido',
            'por venta',
            'cobrar',
            'cobro'
        ]);

        $finanzasAvanzadas = self::containsAny($descripcion, [
            'costos operativos',
            'costes operativos',
            'margen',
            'ticket promedio',
            'flujo de caja',
            'rentabilidad',
            'inversión inicial',
            'roi',
            'punto de equilibrio',
            'costos fijos',
            'costos variables',
            'costes fijos',
            'costes variables',
            'margen bruto',
            'margen neto',
            'costo de adquisición',
            'coste de adquisición',
            'cac',
            'lifetime value',
            'ltv'
        ]);

        if ($monetizacionBasica) {

            $score += 1.0;

            $insights[] =
                'Se identifica un mecanismo básico de generación de ingresos.';

            if ($finanzasAvanzadas) {

                $score += 0.5;

                $insights[] =
                    'La propuesta contempla elementos financieros que aportan mayor madurez al modelo de negocio.';
            }

        } else {

            $riesgos[] =
                'No se identifica claramente cómo generará ingresos la idea de negocio.';

            $recomendaciones[] =
                'Establece cómo, cuánto y con qué frecuencia pagará el cliente por la propuesta.';
        }

        /*
         * ============================================================
         * REGLA 5 - DIFERENCIACIÓN
         *
         * Máximo: 1.0 pt
         * ============================================================
         */

        $diferenciadorConcreto = self::containsAny($descripcion, [
            'patente',
            'tecnología propia',
            'tecnologia propia',
            'alianza exclusiva',
            'alianza estratégica exclusiva',
            'red de distribución',
            'algoritmo propio',
            'base de datos propia',
            'proceso certificado',
            'exclusivo',
            'exclusiva',
            'personalización',
            'personalizacion',
            'integración propia',
            'integracion propia',
            'automatización',
            'automatizacion',
            'alertas automáticas',
            'alertas automaticas',
            'entrega el mismo día',
            'entrega el mismo dia',
            'entrega programada',
            'modelo de suscripción',
            'plan gratuito',
            'plan especializado',
            'servicio especializado',
            'atención personalizada',
            'atencion personalizada',
            'entrega rápida',
            'entrega rapida',
            'proceso propio',
            'metodología propia',
            'metodologia propia'
        ]);

        $diferenciacionGenerica = self::containsAny($descripcion, [
            'innovador',
            'innovadora',
            'innovadores',
            'único',
            'única',
            'unica',
            'diferenciador',
            'diferenciadora',
            'tecnología avanzada',
            'tecnologia avanzada',
            'mejor',
            'revolucionar',
            'revolucionario',
            'revolucionaria',
            'escalable',
            'disruptivo',
            'disruptiva'
        ]);

        if ($diferenciadorConcreto) {

            $score += 1.0;

            $insights[] =
                'Se identifica una característica concreta que puede diferenciar la propuesta frente a alternativas existentes.';

        } elseif ($diferenciacionGenerica) {

            $score += 0.25;

            $riesgos[] =
                'Se afirma que la propuesta es innovadora o diferente, pero no se demuestra claramente qué la diferencia.';

            $recomendaciones[] =
                'Sustituye afirmaciones generales de innovación por una característica concreta, verificable y relevante para el cliente.';

        } else {

            $score += 0.5;

            $recomendaciones[] =
                'Define qué característica, proceso, modelo o beneficio diferencia realmente tu propuesta de las alternativas existentes.';
        }

        /*
         * ============================================================
         * REGLA 6 - NIVEL DE DETALLE
         *
         * Máximo: 1.0 pt
         * ============================================================
         */

        $numeroPalabras = self::wordCount($descripcion);

        if ($numeroPalabras >= 100) {

            $score += 1.0;

            $insights[] =
                'La descripción presenta un nivel de detalle elevado para una evaluación inicial.';

        } elseif ($numeroPalabras >= 50) {

            $score += 0.75;

            $insights[] =
                'La descripción proporciona un nivel de detalle suficiente para realizar una evaluación inicial.';

        } elseif ($numeroPalabras >= 25) {

            $score += 0.5;

        } else {

            $score += 0.25;

            $riesgos[] =
                'La descripción es demasiado breve para evaluar adecuadamente el modelo de negocio.';

            $recomendaciones[] =
                'Amplía la descripción indicando problema, cliente, solución, beneficio y modelo de negocio.';
        }

        /*
         * ============================================================
         * REGLA 7 - SESGOS Y BUZZWORDS
         * ============================================================
         */

        $sesgosCompetencia = self::containsAny($descripcion, [
            'no tenemos competencia',
            'sin competencia',
            'nadie más hace esto',
            'nadie mas hace esto',
            'somos los mejores',
            'somos los únicos',
            'somos los unicos',
            'únicos en el mercado',
            'unicos en el mercado',
            'éxito asegurado',
            'exito asegurado',
            'la mejor app',
            'la mejor aplicación',
            'la mejor aplicacion',
            'nadie ofrece',
            'nadie ofrece algo similar'
        ]);

        $buzzwords = self::containsAny($descripcion, [
            'revolucionar el mercado',
            'revolucionar el mundo',
            'revolucionaria',
            'revolucionario',
            'tecnología avanzada',
            'tecnologia avanzada',
            'innovadora',
            'innovador',
            'única',
            'unica',
            'escalable',
            'disruptiva',
            'disruptivo'
        ]);

        if ($sesgosCompetencia) {

            $score -= 1.0;

            $riesgos[] =
                'Se utilizan afirmaciones absolutas sobre competencia o superioridad que no están respaldadas por evidencia.';

            $recomendaciones[] =
                'Considera también la competencia indirecta y describe ventajas verificables en lugar de afirmaciones absolutas.';

        } elseif ($buzzwords) {

            $score -= 0.25;

            $recomendaciones[] =
                'Sustituye términos generales como "innovador", "revolucionario" o "tecnología avanzada" por características concretas y verificables.';
        }

        /*
         * ============================================================
         * REGLA 8 - ALCANCE
         *
         * Máximo: 0.5 pts
         * ============================================================
         */

        $alcanceAmplio = self::containsAny($descripcion, [
            'muchas funciones',
            'diferentes cosas',
            'para cualquier persona',
            'hace de todo',
            'todo en uno',
            'para todo',
            'para cualquier necesidad',
            'todas las actividades',
            'todas las necesidades',
            'cualquier actividad',
            'cualquier necesidad',
            'todo tipo de problemas'
        ]);

        if ($alcanceAmplio) {

            $riesgos[] =
                'La idea presenta un alcance demasiado amplio o elementos de indefinición que dificultan establecer un mercado y un caso de uso concreto.';

            $recomendaciones[] =
                'Reduce el alcance inicial y define un problema, usuario y caso de uso específico.';

        } else {

            $score += 0.5;
        }

        /*
         * ============================================================
         * REGLA 9 - COHERENCIA SECTORIAL
         *
         * V2.5
         *
         * Separamos:
         *
         * A) Evidencia del sector principal.
         * B) Evidencia tecnológica.
         * C) Evidencia de mercado atendido.
         *
         * Esto evita errores como:
         *
         * SaaS + tiendas
         *
         * => no necesariamente significa E-commerce/Retail.
         *
         * El sector describe la naturaleza principal de la solución,
         * mientras que las palabras del dominio pueden describir
         * simplemente al cliente o mercado objetivo.
         * ============================================================
         */

        $terminosSector = $sectorRule['terms'];
        $technologyTerms = $sectorRule['technologyTerms'];

        $evidenciaSectorial = self::countMatches(
            $descripcion,
            $terminosSector
        );

        $evidenciaTecnologica = self::countMatches(
            $descripcion,
            $technologyTerms
        );

        $sectorExplicito = self::containsAny(
            $descripcion,
            $sectorRule['explicitTerms'] ?? []
        );

        if ($sector === 'General') {

            $score += 1.0;

        } elseif ($evidenciaSectorial >= $sectorRule['minimumEvidence']) {

            $score += 1.0;

            $insights[] =
                'La propuesta presenta elementos coherentes con el sector seleccionado: ' .
                $sector . '.';

        } else {

            if (!$sectorRule['requiresTechnology']) {

                $score += 0.5;

                $riesgos[] =
                    'La descripción contiene poca evidencia específica sobre la actividad operativa del sector ' .
                    $sector . '.';

                $recomendaciones[] =
                    'Describe brevemente cómo funcionará la operación principal del negocio dentro del sector seleccionado.';

            } else {

                if ($evidenciaTecnologica >= 1) {

                    $score += 0.75;

                    $insights[] =
                        'La propuesta presenta elementos tecnológicos coherentes con el sector seleccionado: ' .
                        $sector . '.';

                } else {

                    $score += 0.25;

                    $riesgos[] =
                        'La propuesta se presenta dentro de un sector tecnológico, pero contiene poca evidencia sobre cómo funcionará técnicamente la solución.';

                    $recomendaciones[] =
                        'Describe brevemente la tecnología, infraestructura, integración o proceso técnico principal que requiere la solución.';
                }
            }
        }

        /*
         * Si el sector fue declarado explícitamente en la descripción
         * mediante lenguaje muy claro, no necesitamos exigir señales
         * adicionales.
         */
        if ($sectorExplicito && $sector !== 'General') {

            if ($evidenciaSectorial < $sectorRule['minimumEvidence']) {

                $score += 0.25;

                $insights[] =
                    'La descripción declara de forma explícita su relación con el sector seleccionado.';
            }
        }

        /*
         * ============================================================
         * REGLA 10 - DETECCIÓN DE SECTOR ALTERNATIVO
         *
         * V2.5:
         *
         * No basta con contar palabras.
         *
         * Un sector alternativo solamente se propone cuando:
         *
         * 1. Existe evidencia fuerte del otro sector.
         * 2. La evidencia supera claramente la evidencia del sector actual.
         * 3. No se trata simplemente del mercado atendido por una
         *    solución tecnológica.
         * ============================================================
         */

        $sectorAlternativo = self::detectAlternativeSector(
            $descripcion,
            $sector,
            $sectorRules
        );

        if ($sectorAlternativo !== null) {

            $riesgos[] =
                'La propuesta podría corresponder mejor al sector "' .
                $sectorAlternativo .
                '" que al sector seleccionado.';

            $recomendaciones[] =
                'Revisa el sector seleccionado o agrega los elementos que justifiquen la clasificación actual.';
        }

        /*
         * ============================================================
         * REGLA 11 - MADUREZ DE LA FORMULACIÓN
         *
         * No añade puntos directamente.
         *
         * Sirve para detectar si existen suficientes elementos
         * explícitos en la descripción.
         * ============================================================
         */

        $componentes = 0;

        if ($problemaFuerte && !$problemaIndefinido) {
            $componentes++;
        }

        if ($clienteEspecifico && !$clienteDemasiadoAmplio) {
            $componentes++;
        }

        if ($beneficioConcreto) {
            $componentes++;
        }

        if ($monetizacionBasica) {
            $componentes++;
        }

        if ($diferenciadorConcreto) {
            $componentes++;
        }

        if ($componentes >= 4) {

            $insights[] =
                'La propuesta contiene la mayoría de los componentes fundamentales para una evaluación inicial.';

        } elseif ($componentes <= 1) {

            $riesgos[] =
                'La formulación contiene pocos elementos verificables para evaluar la viabilidad inicial del negocio.';

            $recomendaciones[] =
                'Completa la propuesta especificando problema, cliente objetivo, solución, beneficio, monetización y diferenciación.';
        }

        /*
         * ============================================================
         * NORMALIZACIÓN
         *
         * El score se limita estrictamente entre 1 y 10.
         * ============================================================
         */

        $score = max(1.0, min(10.0, $score));
        $score = round($score, 1);

        /*
         * ============================================================
         * VEREDICTO
         * ============================================================
         */

        if ($score >= 8.0) {

            $veredicto = 'AVANZAR';
            $semaforo = 'VERDE';

            $insights[] =
                'La idea presenta una estructura suficientemente sólida para avanzar hacia actividades de validación.';

            $recomendaciones[] =
                'La idea cuenta con una base sólida. Valida sus principales supuestos antes de realizar inversiones significativas.';

        } elseif ($score >= 5.0) {

            $veredicto = 'VALIDAR MÁS';
            $semaforo = 'AMARILLO';

            $recomendaciones[] =
                'La idea tiene una base inicial, pero conviene validar y refinar los riesgos detectados antes de invertir significativamente.';

        } else {

            $veredicto = 'REESTRUCTURAR';
            $semaforo = 'ROJO';

            $recomendaciones[] =
                'Existen vacíos importantes en la formulación de la idea. Replantea sus elementos fundamentales antes de avanzar hacia el desarrollo.';
        }

        /*
         * ============================================================
         * LIMPIEZA
         * ============================================================
         */

        $insights = array_values(array_unique($insights));
        $riesgos = array_values(array_unique($riesgos));
        $recomendaciones = array_values(array_unique($recomendaciones));

        return [
            'score'           => $score,
            'semaforo'        => $semaforo,
            'veredicto'       => $veredicto,
            'insights'        => $insights,
            'riesgos'         => $riesgos,
            'recomendaciones' => $recomendaciones
        ];
    }

    /**
     * ================================================================
     * CATÁLOGO DE SECTORES
     * ================================================================
     */
    private static function getSectorRules()
    {
        return [

            'General' => [
                'terms' => [],
                'explicitTerms' => [],
                'technologyTerms' => [],
                'requiresTechnology' => false,
                'minimumEvidence' => 1
            ],

            'Tecnología / Software (SaaS)' => [
                'terms' => [
                    'software',
                    'saas',
                    'aplicación',
                    'aplicacion',
                    'plataforma',
                    'web',
                    'sistema',
                    'servidor',
                    'nube',
                    'base de datos',
                    'api',
                    'integración',
                    'integracion',
                    'automatización',
                    'automatizacion',
                    'servicio digital',
                    'servicio en línea',
                    'servicio en linea'
                ],

                'explicitTerms' => [
                    'saas',
                    'software como servicio',
                    'software',
                    'plataforma saas',
                    'servicio digital'
                ],

                'technologyTerms' => [
                    'software',
                    'saas',
                    'aplicación',
                    'aplicacion',
                    'plataforma',
                    'web',
                    'servidor',
                    'nube',
                    'base de datos',
                    'api',
                    'código',
                    'codigo',
                    'desarrollo',
                    'sistema'
                ],

                'requiresTechnology' => true,
                'minimumEvidence' => 1
            ],

            'E-commerce / Retail' => [
                'terms' => [
                    'tienda',
                    'tiendas',
                    'comercio',
                    'retail',
                    'inventario',
                    'stock',
                    'producto',
                    'productos',
                    'venta',
                    'ventas',
                    'proveedor',
                    'almacén',
                    'almacen',
                    'envío',
                    'envio',
                    'distribución',
                    'distribucion',
                    'e-commerce',
                    'comercio electrónico',
                    'comercio electronico',
                    'tienda online',
                    'tienda en línea',
                    'tienda en linea'
                ],

                'explicitTerms' => [
                    'e-commerce',
                    'retail',
                    'comercio electrónico',
                    'comercio electronico',
                    'tienda online',
                    'tienda en línea',
                    'tienda en linea'
                ],

                'technologyTerms' => [
                    'e-commerce',
                    'plataforma',
                    'aplicación',
                    'aplicacion',
                    'web',
                    'tienda online',
                    'tienda en línea',
                    'tienda en linea'
                ],

                'requiresTechnology' => false,
                'minimumEvidence' => 1
            ],

            'Agrotech' => [
                'terms' => [
                    'agricultura',
                    'agrícola',
                    'agricola',
                    'cultivo',
                    'cultivos',
                    'cosecha',
                    'siembra',
                    'granja',
                    'campo',
                    'terreno',
                    'suelo',
                    'riego',
                    'ganado',
                    'ganadería',
                    'ganaderia',
                    'productor agrícola',
                    'productor agricola',
                    'clima',
                    'plaga',
                    'fertilizante',
                    'semilla'
                ],

                'explicitTerms' => [
                    'agrotech',
                    'agritech',
                    'agricultura tecnológica',
                    'agricultura tecnologica'
                ],

                'technologyTerms' => [
                    'sensores',
                    'iot',
                    'drones',
                    'satélite',
                    'satelite',
                    'software',
                    'plataforma',
                    'datos',
                    'automatización',
                    'automatizacion'
                ],

                'requiresTechnology' => true,
                'minimumEvidence' => 1
            ],

            'Fintech' => [
                'terms' => [
                    'finanzas',
                    'financiero',
                    'financiera',
                    'pago',
                    'pagos',
                    'banco',
                    'bancario',
                    'bancaria',
                    'crédito',
                    'credito',
                    'préstamo',
                    'prestamo',
                    'inversión',
                    'inversion',
                    'seguro',
                    'seguros',
                    'transacción',
                    'transaccion',
                    'fraude',
                    'dinero',
                    'cartera',
                    'fintech'
                ],

                'explicitTerms' => [
                    'fintech',
                    'tecnología financiera',
                    'tecnologia financiera',
                    'servicios financieros digitales'
                ],

                'technologyTerms' => [
                    'plataforma',
                    'aplicación',
                    'aplicacion',
                    'software',
                    'api',
                    'billetera digital',
                    'wallet',
                    'automatización',
                    'automatizacion',
                    'seguridad'
                ],

                'requiresTechnology' => true,
                'minimumEvidence' => 1
            ],

            'Salud Digital / Healthtech' => [
                'terms' => [
                    'salud',
                    'médico',
                    'medico',
                    'médica',
                    'medica',
                    'paciente',
                    'clínica',
                    'clinica',
                    'hospital',
                    'diagnóstico',
                    'diagnostico',
                    'tratamiento',
                    'terapia',
                    'historial médico',
                    'historial medico',
                    'nutrición',
                    'nutricion',
                    'nutricional',
                    'bienestar',
                    'telemedicina',
                    'farmacia'
                ],

                'explicitTerms' => [
                    'healthtech',
                    'salud digital',
                    'tecnología sanitaria',
                    'tecnologia sanitaria',
                    'tecnología de salud',
                    'tecnologia de salud'
                ],

                'technologyTerms' => [
                    'plataforma',
                    'aplicación',
                    'aplicacion',
                    'software',
                    'telemedicina',
                    'historial digital',
                    'datos médicos',
                    'datos medicos',
                    'dispositivo',
                    'monitoreo',
                    'sensores'
                ],

                'requiresTechnology' => true,
                'minimumEvidence' => 1
            ],

            'Educación / Edtech' => [
                'terms' => [
                    'educación',
                    'educacion',
                    'educativo',
                    'educativa',
                    'estudiante',
                    'estudiantes',
                    'alumno',
                    'alumnos',
                    'profesor',
                    'profesores',
                    'docente',
                    'docentes',
                    'curso',
                    'cursos',
                    'aprendizaje',
                    'enseñanza',
                    'ensenanza',
                    'aula',
                    'clase',
                    'clases',
                    'capacitación',
                    'capacitacion',
                    'formación',
                    'formacion',
                    'pedagogía',
                    'pedagogia',
                    'universidad',
                    'colegio',
                    'escuela'
                ],

                'explicitTerms' => [
                    'edtech',
                    'educación digital',
                    'educacion digital',
                    'tecnología educativa',
                    'tecnologia educativa'
                ],

                'technologyTerms' => [
                    'plataforma',
                    'aplicación',
                    'aplicacion',
                    'software',
                    'curso online',
                    'curso en línea',
                    'curso en linea',
                    'aula virtual',
                    'contenido digital',
                    'sistema'
                ],

                'requiresTechnology' => true,
                'minimumEvidence' => 1
            ],

            'Gastronomía / Alimentos' => [
                'terms' => [
                    'comida',
                    'alimentos',
                    'alimentación',
                    'alimentacion',
                    'restaurante',
                    'restaurantes',
                    'cocina',
                    'cocinero',
                    'chef',
                    'menú',
                    'menu',
                    'receta',
                    'recetas',
                    'ingredientes',
                    'almuerzo',
                    'desayuno',
                    'cena',
                    'bebida',
                    'bebidas',
                    'panadería',
                    'panaderia',
                    'repostería',
                    'reposteria',
                    'delivery',
                    'domicilio',
                    'preparación',
                    'preparacion'
                ],

                'explicitTerms' => [
                    'gastronomía',
                    'gastronomia',
                    'alimentos',
                    'industria alimentaria',
                    'industria de alimentos'
                ],

                'technologyTerms' => [
                    'aplicación',
                    'aplicacion',
                    'plataforma',
                    'pedidos online',
                    'pedidos en línea',
                    'pedidos en linea',
                    'delivery',
                    'automatización',
                    'automatizacion'
                ],

                'requiresTechnology' => false,
                'minimumEvidence' => 1
            ],

            'Servicios Profesionales / Consultoría' => [
                'terms' => [
                    'consultoría',
                    'consultoria',
                    'consultor',
                    'consultora',
                    'asesoría',
                    'asesoria',
                    'asesor',
                    'asesora',
                    'servicios profesionales',
                    'abogado',
                    'abogados',
                    'contador',
                    'contabilidad',
                    'marketing',
                    'publicidad',
                    'diseño',
                    'diseñador',
                    'disenador',
                    'ingeniería',
                    'ingenieria',
                    'arquitectura',
                    'recursos humanos',
                    'capacitación',
                    'capacitacion',
                    'auditoría',
                    'auditoria'
                ],

                'explicitTerms' => [
                    'consultoría',
                    'consultoria',
                    'servicios profesionales',
                    'asesoría',
                    'asesoria'
                ],

                'technologyTerms' => [
                    'software',
                    'plataforma',
                    'aplicación',
                    'aplicacion',
                    'automatización',
                    'automatizacion',
                    'herramienta digital',
                    'portal'
                ],

                'requiresTechnology' => false,
                'minimumEvidence' => 1
            ],

            'Logística y Transporte' => [
                'terms' => [
                    'logística',
                    'logistica',
                    'transporte',
                    'entrega',
                    'entregas',
                    'envío',
                    'envio',
                    'despacho',
                    'ruta',
                    'rutas',
                    'vehículo',
                    'vehiculo',
                    'conductor',
                    'conductores',
                    'flota',
                    'carga',
                    'paquetería',
                    'paqueteria',
                    'almacén',
                    'almacen',
                    'distribución',
                    'distribucion'
                ],

                'explicitTerms' => [
                    'logística',
                    'logistica',
                    'transporte',
                    'logística y transporte',
                    'logistica y transporte'
                ],

                'technologyTerms' => [
                    'gps',
                    'rastreo',
                    'seguimiento',
                    'plataforma',
                    'aplicación',
                    'aplicacion',
                    'software',
                    'optimización de rutas',
                    'optimizacion de rutas',
                    'automatización',
                    'automatizacion'
                ],

                'requiresTechnology' => false,
                'minimumEvidence' => 1
            ],

            'Medio Ambiente / Cleantech' => [
                'terms' => [
                    'medio ambiente',
                    'ambiental',
                    'reciclaje',
                    'residuos',
                    'contaminación',
                    'contaminacion',
                    'energía',
                    'energia',
                    'energía renovable',
                    'energia renovable',
                    'solar',
                    'eólica',
                    'eolica',
                    'sostenible',
                    'sostenibilidad',
                    'carbono',
                    'emisiones',
                    'agua',
                    'eficiencia energética',
                    'eficiencia energetica',
                    'reutilización',
                    'reutilizacion'
                ],

                'explicitTerms' => [
                    'cleantech',
                    'tecnología limpia',
                    'tecnologia limpia',
                    'tecnología ambiental',
                    'tecnologia ambiental'
                ],

                'technologyTerms' => [
                    'sensores',
                    'software',
                    'plataforma',
                    'monitoreo',
                    'medición',
                    'medicion',
                    'automatización',
                    'automatizacion',
                    'iot',
                    'paneles solares',
                    'baterías',
                    'baterias'
                ],

                'requiresTechnology' => true,
                'minimumEvidence' => 1
            ],

            'Entretenimiento y Medios' => [
                'terms' => [
                    'entretenimiento',
                    'medios',
                    'media',
                    'contenido',
                    'contenidos',
                    'video',
                    'videos',
                    'película',
                    'pelicula',
                    'música',
                    'musica',
                    'podcast',
                    'streaming',
                    'juegos',
                    'videojuegos',
                    'creadores',
                    'creador de contenido',
                    'audiencia',
                    'televisión',
                    'television',
                    'radio',
                    'eventos'
                ],

                'explicitTerms' => [
                    'entretenimiento',
                    'medios digitales',
                    'industria audiovisual',
                    'media'
                ],

                'technologyTerms' => [
                    'plataforma',
                    'aplicación',
                    'aplicacion',
                    'streaming',
                    'software',
                    'contenido digital',
                    'red social',
                    'algoritmo'
                ],

                'requiresTechnology' => false,
                'minimumEvidence' => 1
            ],

            'Turismo y Hospitalidad' => [
                'terms' => [
                    'turismo',
                    'turista',
                    'turistas',
                    'viaje',
                    'viajes',
                    'viajero',
                    'viajeros',
                    'hotel',
                    'hoteles',
                    'hospedaje',
                    'alojamiento',
                    'hostal',
                    'resort',
                    'tour',
                    'tours',
                    'excursión',
                    'excursion',
                    'destino',
                    'destinos',
                    'reservación',
                    'reservacion',
                    'reserva',
                    'reservas',
                    'hospitalidad',
                    'guía turístico',
                    'guia turistico'
                ],

                'explicitTerms' => [
                    'turismo',
                    'hospitalidad',
                    'turismo y hospitalidad',
                    'sector turístico',
                    'sector turistico'
                ],

                'technologyTerms' => [
                    'plataforma',
                    'aplicación',
                    'aplicacion',
                    'reservas online',
                    'reservas en línea',
                    'reservas en linea',
                    'software',
                    'sistema de reservas'
                ],

                'requiresTechnology' => false,
                'minimumEvidence' => 1
            ]
        ];
    }

    /**
     * ================================================================
     * DETECCIÓN DE SECTOR ALTERNATIVO
     * ================================================================
     *
     * V2.5:
     *
     * Se evita considerar automáticamente como sector alternativo
     * una industria que simplemente representa al cliente objetivo.
     *
     * Ejemplo:
     *
     * "SaaS para pequeñas tiendas"
     *
     * Tecnología / Software (SaaS)
     * + Retail como mercado atendido
     *
     * no debería producir automáticamente:
     *
     * "podría corresponder mejor a Retail".
     */
    private static function detectAlternativeSector(
        $descripcion,
        $sectorActual,
        array $sectorRules
    ) {
        $mejorSector = null;
        $mejorPuntaje = 0;

        foreach ($sectorRules as $sector => $rules) {

            if ($sector === 'General' || $sector === $sectorActual) {
                continue;
            }

            $matches = self::countMatches(
                $descripcion,
                $rules['terms']
            );

            if ($matches < 2) {
                continue;
            }

            /*
             * Si el sector alternativo es tecnológico y la descripción
             * contiene suficiente tecnología, no lo consideramos
             * automáticamente dominante.
             */
            $technologyMatches = self::countMatches(
                $descripcion,
                $rules['technologyTerms']
            );

            /*
             * Sectores que frecuentemente funcionan como "mercado"
             * de una solución tecnológica.
             */
            $isCommonTargetSector = in_array($sector, [
                'E-commerce / Retail',
                'Logística y Transporte',
                'Salud Digital / Healthtech',
                'Educación / Edtech',
                'Fintech',
                'Turismo y Hospitalidad'
            ], true);

            if (
                $isCommonTargetSector &&
                $sectorActual === 'Tecnología / Software (SaaS)'
            ) {
                /*
                 * Para desplazar un SaaS hacia otro sector debe existir
                 * evidencia bastante fuerte y específica.
                 */
                if ($matches < 4) {
                    continue;
                }
            }

            /*
             * Una señal explícita del sector pesa más que una palabra
             * incidental.
             */
            $explicitMatch = self::containsAny(
                $descripcion,
                $rules['explicitTerms'] ?? []
            );

            $puntaje = $matches;

            if ($explicitMatch) {
                $puntaje += 2;
            }

            if ($technologyMatches > 0 && $rules['requiresTechnology']) {
                $puntaje += 1;
            }

            if ($puntaje > $mejorPuntaje) {

                $mejorPuntaje = $puntaje;
                $mejorSector = $sector;
            }
        }

        /*
         * Requiere una señal suficientemente fuerte.
         */
        if ($mejorPuntaje < 4) {
            return null;
        }

        return $mejorSector;
    }

    /**
     * ================================================================
     * CUENTA CONCEPTOS DISTINTOS
     * ================================================================
     */
    private static function countMatches($text, array $terms)
    {
        $count = 0;

        foreach ($terms as $term) {

            if (self::containsAny($text, [$term])) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * ================================================================
     * EVIDENCIA NUMÉRICA
     * ================================================================
     */
    private static function hasNumericEvidence($text)
    {
        return preg_match(
            '/\b\d+(?:[.,]\d+)?\s*(?:%|horas?|d[ií]as?|semanas?|meses?|años?|minutos?|pesos|d[oó]lares|euros|ventas|clientes|productos|pedidos)\b/ui',
            $text
        ) === 1;
    }

    /**
     * ================================================================
     * CONTADOR DE PALABRAS
     * ================================================================
     *
     * str_word_count() no es ideal para textos con caracteres
     * Unicode. Utilizamos una expresión compatible con español.
     */
    private static function wordCount($text)
    {
        preg_match_all(
            '/\b[\p{L}\p{N}]+\b/u',
            $text,
            $matches
        );

        return count($matches[0]);
    }

    /**
     * ================================================================
     * NORMALIZACIÓN
     * ================================================================
     */
    private static function normalize($text)
    {
        $text = trim((string) $text);

        if ($text === '') {
            return '';
        }

        return mb_strtolower($text, 'UTF-8');
    }

    /**
     * ================================================================
     * COMPRUEBA SI APARECE CUALQUIER TÉRMINO
     * ================================================================
     *
     * V2.5:
     * Se reemplaza el lookbehind variable de V2.3 por límites
     * mediante grupos no capturantes.
     */
    private static function containsAny($text, array $terms)
    {
        foreach ($terms as $term) {

            $term = trim((string) $term);

            if ($term === '') {
                continue;
            }

            $termEscaped = preg_quote($term, '/');

            $pattern =
                '/(?:^|[^\p{L}\p{N}])' .
                $termEscaped .
                '(?=$|[^\p{L}\p{N}])/ui';

            if (preg_match($pattern, $text)) {
                return true;
            }
        }

        return false;
    }
}