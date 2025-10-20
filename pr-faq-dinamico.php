<?php
/**
 * Plugin Name:       Acordeão de FAQ Dinâmico (do Paulo)
 * Plugin URI:        https://www.linkedin.com/in/pauloreducino/
 * Description:       Adiciona um acordeão de FAQ dinâmico com Schema.org através do shortcode [faq_accordion][faq_item]...[/faq_item][/faq_accordion].
 * Version:           2.1
 * Author:            Paulo Reducino
 * Author URI:        https://www.linkedin.com/in/pauloreducino/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       paulo-faq-dinamico
 */

// Medida de segurança: impede o acesso direto ao arquivo.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Paulo_FAQ_Data {
    public static $items = [];
}

function paulo_faq_item_shortcode( $atts, $content = null ) {
    $atts = shortcode_atts(
        [
            'question' => '',
        ],
        $atts,
        'faq_item'
    );
    if ( ! empty( $atts['question'] ) ) {
        Paulo_FAQ_Data::$items[] = [
            'question'    => $atts['question'],
            'answer_html' => apply_filters( 'the_content', $content ),
        ];
    }
    return '';
}
add_shortcode( 'faq_item', 'paulo_faq_item_shortcode' );


function paulo_faq_accordion_shortcode( $atts, $content = null ) {
    
    Paulo_FAQ_Data::$items = [];
    do_shortcode( $content );
    $items = Paulo_FAQ_Data::$items;
    if ( empty( $items ) ) {
        return '';
    }

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

    $schema_jsonld = '
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": ' . json_encode( $schema_entities, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '
    }
    </script>';

    // --- 4. Enfileirar CSS e JS ---
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
    
    wp_register_style('faq-accordion-styles', false);
    wp_enqueue_style('faq-accordion-styles');
    wp_add_inline_style('faq-accordion-styles', $css_faq);

    // --- CORREÇÃO APLICADA AQUI ---
    // Em vez de depender do 'jquery', vamos registrar nosso próprio script "handle"
    // para garantir que o JS seja carregado no rodapé (o 'true' no final).
    wp_register_script('paulo-faq-script-handle', false, [], null, true);
    wp_enqueue_script('paulo-faq-script-handle');
    
    // Agora, adicionamos nosso script inline ao nosso próprio handle.
    wp_add_inline_script('paulo-faq-script-handle', $js_faq, 'after');
    
    return $schema_jsonld . '<div class="faq-accordion">' . $html_items . '</div>';
}
add_shortcode( 'faq_accordion', 'paulo_faq_accordion_shortcode' );