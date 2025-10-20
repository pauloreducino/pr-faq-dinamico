<?php
/**
 * Plugin Name:       PR Dynamic FAQ Accordion
 * Plugin URI:        https://github.com/pauloreducino/pr-faq-dinamico
 * Description:       Adds a dynamic, SEO-optimized FAQ accordion with Schema.org using the [faq_accordion] shortcode.
 * Version:           1.0
 * Author:            Paulo Reducino
 * Author URI:        https://github.com/pauloreducino
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       pr-faq-dinamico
 */

// Medida de segurança: impede o acesso direto ao arquivo.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Classe estática para armazenar temporariamente os dados dos itens do FAQ
 * enquanto os shortcodes são processados.
 */
class PR_FAQ_Data {
    public static $items = [];
}

/**
 * Shortcode Interno: [faq_item]
 *
 * Captura os dados da pergunta e resposta.
 */
function pr_faq_item_shortcode( $atts, $content = null ) {
    $atts = shortcode_atts(
        [
            'question' => '',
        ],
        $atts,
        'faq_item'
    );

    if ( ! empty( $atts['question'] ) ) {
        PR_FAQ_Data::$items[] = [
            'question'    => $atts['question'],
            'answer_html' => apply_filters( 'the_content', $content ), // Renderiza HTML e parágrafos
        ];
    }

    return '';
}
add_shortcode( 'faq_item', 'pr_faq_item_shortcode' );


/**
 * Shortcode Externo: [faq_accordion]
 *
 * Renderiza o acordeão completo, o schema e enfileira os scripts/estilos.
 */
function pr_faq_accordion_shortcode( $atts, $content = null ) {

    // 1. Resetar e Coletar Dados
    PR_FAQ_Data::$items = [];
    do_shortcode( $content );
    $items = PR_FAQ_Data::$items;

    if ( empty( $items ) ) {
        return '';
    }

    // 2. Preparar Dados para Schema e HTML
    $schema_entities = [];
    $html_items      = '';

    foreach ( $items as $item ) {
        $plain_text_answer = wp_strip_all_tags( $item['answer_html'] );
        $schema_entities[] = [
            '@type'          => 'Question',
            'name'           => $item['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $plain_text_answer,
            ],
        ];
        $html_items .= '
            <div class="faq-item">
                <button class="faq-question">' . esc_html( $item['question'] ) . '</button>
                <div class="faq-answer">
                    <div>' . $item['answer_html'] . '</div>
                </div>
            </div>';
    }

    // 3. Construir o Schema JSON-LD
    $schema_jsonld = '
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": ' . json_encode( $schema_entities, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '
    }
    </script>';

    // 4. Enfileirar CSS e JS
    $css_faq = "
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
        .faq-accordion{font-family:'Poppins',sans-serif;border:1px solid #e0eaf6;border-radius:16px;padding:2.5rem;background-color:#fff;max-width:800px;margin:2rem auto;box-shadow:0 4px 15px rgba(201,223,247,.3)}.faq-item{border-bottom:1px solid #e0eaf6}.faq-item:last-child{border-bottom:none}.faq-question{background:0 0;border:none;text-align:left;width:100%;display:flex;justify-content:space-between;align-items:center;padding:1.5rem 0;cursor:pointer;font-size:1.1rem;font-weight:500;color:#334155;transition:color .2s ease-in-out}.faq-question:hover{color:#378FB5}.faq-question::after{content:'+';flex-shrink:0;margin-left:1rem;width:32px;height:32px;display:flex;justify-content:center;align-items:center;border:1px solid #c9dff7;border-radius:50%;font-size:1.5rem;font-weight:400;color:#378FB5;transition:transform .3s ease,background-color .2s ease}.faq-question.active{color:#378FB5;font-weight:600}.faq-question.active::after{content:'−';transform:rotate(180deg);background-color:#f0f7ff}.faq-answer{max-height:0;overflow:hidden;transition:max-height .4s cubic-bezier(.25,.1,.25,1);color:#475569;font-size:1rem;font-weight:400;line-height:1.6}.faq-answer p{margin-bottom:1rem}.faq-answer ul{list-style-position:inside;padding-left:1rem;margin-bottom:1rem}.faq-answer li{margin-bottom:.5rem}.faq-answer > div{padding-bottom:1.5rem}@media (max-width:768px){.faq-accordion{padding:1.5rem}.faq-question{font-size:1rem;padding:1.2rem 0}.faq-answer{font-size:.95rem}}
    ";

    $js_faq = "
        document.addEventListener('DOMContentLoaded', function () {
            const questions = document.querySelectorAll('.faq-question');
            questions.forEach(question => {
                question.addEventListener('click', () => {
                    const answerContainer = question.nextElementSibling;
                    question.classList.toggle('active');
                    if (question.classList.contains('active')) {
                        answerContainer.style.maxHeight = answerContainer.scrollHeight + 'px';
                    } else {
                        answerContainer.style.maxHeight = null;
                    }
                });
            });
        });
    ";

    wp_register_style('pr-faq-styles', false);
    wp_enqueue_style('pr-faq-styles');
    wp_add_inline_style('pr-faq-styles', $css_faq);

    wp_register_script('pr-faq-script', false, [], null, true);
    wp_enqueue_script('pr-faq-script');
    wp_add_inline_script('pr-faq-script', $js_faq, 'after');

    // 5. Retornar o HTML
    return $schema_jsonld . '<div class="faq-accordion">' . $html_items . '</div>';
}
add_shortcode( 'faq_accordion', 'pr_faq_accordion_shortcode' );