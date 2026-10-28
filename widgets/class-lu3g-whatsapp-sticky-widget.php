<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class LU3G_WhatsApp_Sticky_Widget extends Widget_Base {

	public function get_name() {
		return 'lu3g_whatsapp_sticky';
	}

	public function get_title() {
		return 'WhatsApp Sticky';
	}

	public function get_icon() {
		return 'eicon-button';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_keywords() {
		return [ 'whatsapp', 'sticky', 'chat', 'lu3g', 'bottone', 'contatti', 'telefono', 'email', 'maps' ];
	}

	public function get_style_depends() {
		return [ 'lu3g-wa-sticky' ];
	}

	public function get_script_depends() {
		return [ 'lu3g-wa-sticky' ];
	}

	protected function register_controls() {

		/* ================= CONTENUTO ================= */

		$this->start_controls_section( 'section_mode', [
			'label' => 'Modalità',
		] );

		$this->add_control( 'mode', [
			'label'   => 'Tipo di bottone',
			'type'    => Controls_Manager::SELECT,
			'default' => 'single',
			'options' => [
				'single' => 'Solo WhatsApp',
				'menu'   => 'Menu contatti espandibile',
			],
		] );

		$this->end_controls_section();

		/* ---------- Solo WhatsApp ---------- */
		$this->start_controls_section( 'section_content', [
			'label'     => 'WhatsApp',
			'condition' => [ 'mode' => 'single' ],
		] );

		$this->add_control( 'phone', [
			'label'       => 'Numero o link WhatsApp',
			'type'        => Controls_Manager::TEXT,
			'placeholder' => '+39 333 1234567 oppure https://wa.me/393331234567',
			'description' => 'Numero con prefisso internazionale (es. +39) oppure incolla un link wa.me.',
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		] );

		$this->add_control( 'message', [
			'label'       => 'Messaggio precompilato',
			'type'        => Controls_Manager::TEXTAREA,
			'placeholder' => 'Ciao, vorrei informazioni…',
			'rows'        => 3,
			'dynamic'     => [ 'active' => true ],
		] );

		$this->add_control( 'icon', [
			'label'   => 'Icona',
			'type'    => Controls_Manager::ICONS,
			'default' => [ 'value' => 'fab fa-whatsapp', 'library' => 'fa-brands' ],
		] );

		$this->add_control( 'aria_label', [
			'label'   => 'Etichetta accessibile',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Scrivici su WhatsApp',
		] );

		$this->end_controls_section();

		/* ---------- Menu contatti ---------- */
		$this->start_controls_section( 'section_menu', [
			'label'     => 'Menu contatti',
			'condition' => [ 'mode' => 'menu' ],
		] );

		$this->add_control( 'layout', [
			'label'   => 'Tipo di espansione',
			'type'    => Controls_Manager::SELECT,
			'default' => 'vertical',
			'options' => [
				'vertical'   => 'Verticale',
				'horizontal' => 'Orizzontale',
				'fan'        => 'Ventaglio',
				'pill'       => 'Pillola',
				'card'       => 'Card',
			],
		] );

		$this->add_control( 'card_title', [
			'label'     => 'Titolo card',
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Come possiamo aiutarti?',
			'condition' => [ 'layout' => 'card' ],
		] );

		$this->add_control( 'card_text', [
			'label'       => 'Testo card',
			'type'        => Controls_Manager::TEXTAREA,
			'rows'        => 2,
			'placeholder' => 'Lun–Ven 9:00–18:00',
			'description' => 'Facoltativo: orari, una riga di presentazione…',
			'condition'   => [ 'layout' => 'card' ],
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'type', [
			'label'   => 'Azione',
			'type'    => Controls_Manager::SELECT,
			'default' => 'phone',
			'options' => [
				'whatsapp' => 'WhatsApp',
				'phone'    => 'Chiamata',
				'email'    => 'Email',
				'maps'     => 'Posizione (Google Maps)',
				'link'     => 'Link personalizzato',
			],
		] );

		$repeater->add_control( 'label', [
			'label'   => 'Etichetta',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Chiama',
		] );

		$repeater->add_control( 'value', [
			'label'       => 'Valore',
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => '+39 02 1234567',
			'description' => 'WhatsApp / Chiamata: numero con prefisso. Email: indirizzo. Posizione: indirizzo oppure link di Google Maps. Link: URL completo.',
			'dynamic'     => [ 'active' => true ],
		] );

		$repeater->add_control( 'wa_message', [
			'label'     => 'Messaggio precompilato',
			'type'      => Controls_Manager::TEXTAREA,
			'rows'      => 2,
			'condition' => [ 'type' => 'whatsapp' ],
		] );

		$repeater->add_control( 'email_subject', [
			'label'     => 'Oggetto email',
			'type'      => Controls_Manager::TEXT,
			'condition' => [ 'type' => 'email' ],
		] );

		$repeater->add_control( 'detail', [
			'label'       => 'Testo secondario (card)',
			'type'        => Controls_Manager::TEXT,
			'description' => 'Riga sotto l\'etichetta nel layout Card. Se vuoto mostra numero, email o indirizzo.',
		] );

		$repeater->add_control( 'item_icon', [
			'label'   => 'Icona',
			'type'    => Controls_Manager::ICONS,
			'default' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ],
		] );

		$repeater->add_control( 'item_bg', [
			'label'     => 'Colore sfondo',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .lu3g-wa__item-icon' => 'background-color: {{VALUE}};' ],
		] );

		$repeater->add_control( 'item_color', [
			'label'     => 'Colore icona',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .lu3g-wa__item-icon i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} {{CURRENT_ITEM}} .lu3g-wa__item-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
			],
		] );

		$this->add_control( 'items', [
			'label'       => 'Voci',
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '{{{ label }}}',
			'default'     => [
				[
					'type'      => 'whatsapp',
					'label'     => 'WhatsApp',
					'item_icon' => [ 'value' => 'fab fa-whatsapp', 'library' => 'fa-brands' ],
					'item_bg'   => '#25D366',
				],
				[
					'type'      => 'phone',
					'label'     => 'Chiama',
					'item_icon' => [ 'value' => 'fas fa-phone-alt', 'library' => 'fa-solid' ],
					'item_bg'   => '#1A73E8',
				],
				[
					'type'      => 'email',
					'label'     => 'Scrivici',
					'item_icon' => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ],
					'item_bg'   => '#7E57C2',
				],
				[
					'type'      => 'maps',
					'label'     => 'Dove siamo',
					'item_icon' => [ 'value' => 'fas fa-map-marker-alt', 'library' => 'fa-solid' ],
					'item_bg'   => '#EA4335',
				],
			],
		] );

		$this->add_control( 'menu_icon', [
			'label'     => 'Icona bottone (chiuso)',
			'type'      => Controls_Manager::ICONS,
			'default'   => [ 'value' => 'fas fa-comment-dots', 'library' => 'fa-solid' ],
			'separator' => 'before',
		] );

		$this->add_control( 'close_icon', [
			'label'   => 'Icona bottone (aperto)',
			'type'    => Controls_Manager::ICONS,
			'default' => [ 'value' => 'fas fa-times', 'library' => 'fa-solid' ],
		] );

		$this->add_control( 'menu_aria_label', [
			'label'   => 'Etichetta accessibile',
			'type'    => Controls_Manager::TEXT,
			'default' => 'Contattaci',
		] );

		$this->add_control( 'open_on_hover', [
			'label'        => 'Apri al passaggio del mouse (desktop)',
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		] );

		$this->add_control( 'close_on_click', [
			'label'        => 'Chiudi dopo il clic su una voce',
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		] );

		$this->add_control( 'bar_heading', [
			'label'     => 'Barra fissa',
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'bar_intro', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => 'Sui dispositivi scelti il bottone viene sostituito da una barra in basso con tutte le voci sempre visibili.',
			'content_classes' => 'elementor-descriptor',
		] );

		$this->add_control( 'bar_desktop', [
			'label'        => 'Barra su desktop',
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		] );

		$this->add_control( 'bar_tablet', [
			'label'        => 'Barra su tablet',
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		] );

		// Nome storico "mobile_bar" mantenuto per i widget già configurati.
		$this->add_control( 'mobile_bar', [
			'label'        => 'Barra su mobile',
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		] );

		$this->end_controls_section();

		/* ---------- Visibilità ---------- */
		$this->start_controls_section( 'section_visibility', [
			'label' => 'Visibilità dispositivi',
		] );

		foreach ( [ 'desktop' => 'Desktop', 'tablet' => 'Tablet', 'mobile' => 'Mobile' ] as $key => $label ) {
			$this->add_control( 'show_' . $key, [
				'label'        => 'Mostra su ' . $label,
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => 'Sì',
				'label_off'    => 'No',
				'return_value' => 'yes',
				'default'      => 'yes',
			] );
		}

		$this->end_controls_section();

		/* ================= STILE ================= */

		/* ---------- Posizione ---------- */
		$this->start_controls_section( 'section_position', [
			'label' => 'Posizione',
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'position', [
			'label'   => 'Lato',
			'type'    => Controls_Manager::CHOOSE,
			'options' => [
				'left'  => [ 'title' => 'Sinistra', 'icon' => 'eicon-h-align-left' ],
				'right' => [ 'title' => 'Destra', 'icon' => 'eicon-h-align-right' ],
			],
			'default' => 'right',
			'toggle'  => false,
		] );

		$this->add_responsive_control( 'offset_x', [
			'label'      => 'Distanza orizzontale',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'vw' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 200 ] ],
			'default'    => [ 'size' => 24, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .lu3g-wa' => '--lu3g-wa-x: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'offset_y', [
			'label'      => 'Distanza dal basso',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'vh' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 300 ] ],
			'default'    => [ 'size' => 24, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .lu3g-wa' => '--lu3g-wa-y: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'z_index', [
			'label'     => 'Z-index',
			'type'      => Controls_Manager::NUMBER,
			'default'   => 9999,
			'selectors' => [ '{{WRAPPER}} .lu3g-wa' => 'z-index: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		/* ---------- Bottone principale ---------- */
		$this->start_controls_section( 'section_button_style', [
			'label' => 'Bottone',
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'button_size', [
			'label'      => 'Dimensione bottone',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 30, 'max' => 120 ] ],
			'default'    => [ 'size' => 60, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .lu3g-wa' => '--lu3g-wa-size: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'shape', [
			'label'       => 'Forma',
			'description' => 'Nel menu contatti si applica anche alle voci.',
			'type'        => Controls_Manager::SELECT,
			'default'     => 'circle',
			'options'     => [
				'circle'  => 'Cerchio',
				'rounded' => 'Quadrato arrotondato',
				'square'  => 'Quadrato',
				'leaf'    => 'Goccia',
				'custom'  => 'Personalizzata',
			],
			'selectors_dictionary' => [
				'circle'  => 'border-radius: 50%;',
				'rounded' => 'border-radius: 16px;',
				'square'  => 'border-radius: 0;',
				'leaf'    => 'border-radius: 50% 50% 50% 0;',
				'custom'  => '',
			],
			'selectors' => [ '{{WRAPPER}} .lu3g-wa__btn, {{WRAPPER}} .lu3g-wa__item-icon' => '{{VALUE}}' ],
		] );

		$this->add_responsive_control( 'custom_radius', [
			'label'      => 'Border radius',
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'condition'  => [ 'shape' => 'custom' ],
			'selectors'  => [ '{{WRAPPER}} .lu3g-wa__btn, {{WRAPPER}} .lu3g-wa__item-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		] );

		$this->start_controls_tabs( 'tabs_colors' );

		// Normale.
		$this->start_controls_tab( 'tab_normal', [ 'label' => 'Normale' ] );

		$this->add_control( 'bg_color', [
			'label'     => 'Colore sfondo',
			'type'      => Controls_Manager::COLOR,
			'default'   => '#25D366',
			'selectors' => [ '{{WRAPPER}} .lu3g-wa__btn' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'icon_color', [
			'label'     => 'Colore icona',
			'type'      => Controls_Manager::COLOR,
			'default'   => '#FFFFFF',
			'selectors' => [
				'{{WRAPPER}} .lu3g-wa__btn i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .lu3g-wa__btn svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		// Hover.
		$this->start_controls_tab( 'tab_hover', [ 'label' => 'Hover' ] );

		$this->add_control( 'bg_color_hover', [
			'label'     => 'Colore sfondo',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .lu3g-wa__btn:hover, {{WRAPPER}} .lu3g-wa__btn:focus-visible' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'icon_color_hover', [
			'label'     => 'Colore icona',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .lu3g-wa__btn:hover i, {{WRAPPER}} .lu3g-wa__btn:focus-visible i'     => 'color: {{VALUE}};',
				'{{WRAPPER}} .lu3g-wa__btn:hover svg, {{WRAPPER}} .lu3g-wa__btn:focus-visible svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
			],
		] );

		$this->add_control( 'hover_scale', [
			'label'     => 'Zoom hover',
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 1, 'max' => 1.3, 'step' => 0.01 ] ],
			'default'   => [ 'size' => 1.08 ],
			'selectors' => [ '{{WRAPPER}} .lu3g-wa__btn:hover' => 'transform: scale({{SIZE}});' ],
		] );

		$this->end_controls_tab();

		// Aperto (solo menu).
		$this->start_controls_tab( 'tab_open', [
			'label'     => 'Aperto',
			'condition' => [ 'mode' => 'menu' ],
		] );

		$this->add_control( 'bg_color_open', [
			'label'     => 'Colore sfondo',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .lu3g-wa.is-open .lu3g-wa__btn' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'icon_color_open', [
			'label'     => 'Colore icona',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .lu3g-wa.is-open .lu3g-wa__btn i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .lu3g-wa.is-open .lu3g-wa__btn svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'      => 'button_shadow',
			'selector'  => '{{WRAPPER}} .lu3g-wa__btn',
			'separator' => 'before',
		] );

		$this->add_control( 'pulse', [
			'label'        => 'Animazione pulse',
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		] );

		$this->add_control( 'pulse_color', [
			'label'     => 'Colore pulse',
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(37, 211, 102, 0.5)',
			'condition' => [ 'pulse' => 'yes' ],
			'selectors' => [ '{{WRAPPER}} .lu3g-wa' => '--lu3g-wa-pulse: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		/* ---------- Icona bottone ---------- */
		$this->start_controls_section( 'section_icon_style', [
			'label' => 'Icona bottone',
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'icon_size', [
			'label'      => 'Dimensione icona',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 10, 'max' => 90 ] ],
			'default'    => [ 'size' => 32, 'unit' => 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .lu3g-wa__btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .lu3g-wa__btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		/* ---------- Voci menu ---------- */
		$this->start_controls_section( 'section_items_style', [
			'label'     => 'Voci menu',
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'mode' => 'menu' ],
		] );

		$this->add_responsive_control( 'item_size', [
			'label'      => 'Dimensione voci',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 30, 'max' => 90 ] ],
			'default'    => [ 'size' => 48, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .lu3g-wa' => '--lu3g-wa-item-size: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'item_icon_size', [
			'label'      => 'Dimensione icona voci',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 10, 'max' => 60 ] ],
			'default'    => [ 'size' => 20, 'unit' => 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .lu3g-wa__item-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .lu3g-wa__item-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'item_gap', [
			'label'      => 'Spazio tra le voci',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
			'default'    => [ 'size' => 12, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .lu3g-wa' => '--lu3g-wa-gap: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'item_default_bg', [
			'label'       => 'Colore sfondo predefinito',
			'description' => 'Usato dalle voci senza un colore proprio.',
			'type'        => Controls_Manager::COLOR,
			'selectors'   => [ '{{WRAPPER}} .lu3g-wa__item-icon' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'item_default_color', [
			'label'     => 'Colore icona predefinito',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .lu3g-wa__item-icon i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .lu3g-wa__item-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'item_shadow',
			'selector' => '{{WRAPPER}} .lu3g-wa__item-icon',
		] );

		$this->add_control( 'show_labels', [
			'label'        => 'Mostra etichette',
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'separator'    => 'before',
			'description'  => 'In Orizzontale, Ventaglio e Pillola compaiono come tooltip al passaggio del mouse.',
			'condition'    => [ 'layout!' => 'card' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'label_typography',
			'selector'  => '{{WRAPPER}} .lu3g-wa__label',
			'condition' => [ 'show_labels' => 'yes', 'layout!' => 'card' ],
		] );

		$this->add_control( 'label_color', [
			'label'     => 'Colore testo etichetta',
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'show_labels' => 'yes', 'layout!' => 'card' ],
			'selectors' => [ '{{WRAPPER}} .lu3g-wa__label' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'label_bg', [
			'label'     => 'Sfondo etichetta',
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'show_labels' => 'yes', 'layout!' => 'card' ],
			'selectors' => [ '{{WRAPPER}} .lu3g-wa__label' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_responsive_control( 'fan_radius', [
			'label'      => 'Raggio ventaglio',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 60, 'max' => 220 ] ],
			'default'    => [ 'size' => 110, 'unit' => 'px' ],
			'separator'  => 'before',
			'condition'  => [ 'layout' => 'fan' ],
			'selectors'  => [ '{{WRAPPER}} .lu3g-wa' => '--lu3g-wa-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();

		/* ---------- Pillola ---------- */
		$this->start_controls_section( 'section_pill_style', [
			'label'     => 'Pillola',
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'mode' => 'menu', 'layout' => 'pill' ],
		] );

		$this->add_control( 'pill_note', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => 'Nella pillola le voci sono icone senza cerchio, tutte dello stesso colore. Usa lo stesso colore del bottone per l\'effetto "bottone che si allunga".',
			'content_classes' => 'elementor-descriptor',
		] );

		$this->add_control( 'pill_bg', [
			'label'     => 'Colore sfondo',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .lu3g-wa--layout-pill .lu3g-wa__menu' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'pill_color', [
			'label'     => 'Colore icone',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .lu3g-wa--layout-pill .lu3g-wa__menu' => 'color: {{VALUE}};' ],
		] );

		$this->end_controls_section();

		/* ---------- Card ---------- */
		$this->start_controls_section( 'section_card_style', [
			'label'     => 'Card',
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'mode' => 'menu', 'layout' => 'card' ],
		] );

		$this->add_responsive_control( 'card_width', [
			'label'      => 'Larghezza',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 220, 'max' => 440 ] ],
			'default'    => [ 'size' => 300, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .lu3g-wa' => '--lu3g-wa-card-w: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'card_radius', [
			'label'      => 'Arrotondamento',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 32 ] ],
			'default'    => [ 'size' => 16, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .lu3g-wa--layout-card .lu3g-wa__menu' => 'border-radius: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'card_bg', [
			'label'     => 'Colore sfondo',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .lu3g-wa--layout-card .lu3g-wa__menu' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'card_color', [
			'label'     => 'Colore testo',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .lu3g-wa--layout-card .lu3g-wa__menu' => 'color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'card_title_typography',
			'label'    => 'Tipografia titolo',
			'selector' => '{{WRAPPER}} .lu3g-wa__card-title',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'card_item_typography',
			'label'    => 'Tipografia voci',
			'selector' => '{{WRAPPER}} .lu3g-wa__item-name',
		] );

		$this->end_controls_section();


		/* ---------- Barra mobile ---------- */
		$this->start_controls_section( 'section_bar_style', [
			'label'      => 'Barra fissa',
			'tab'        => Controls_Manager::TAB_STYLE,
			'conditions' => [
				'relation' => 'and',
				'terms'    => [
					[ 'name' => 'mode', 'operator' => '==', 'value' => 'menu' ],
					[
						'relation' => 'or',
						'terms'    => [
							[ 'name' => 'bar_desktop', 'operator' => '==', 'value' => 'yes' ],
							[ 'name' => 'bar_tablet', 'operator' => '==', 'value' => 'yes' ],
							[ 'name' => 'mobile_bar', 'operator' => '==', 'value' => 'yes' ],
						],
					],
				],
			],
		] );

		$this->add_responsive_control( 'bar_item_direction', [
			'label'           => 'Disposizione voci',
			'type'            => Controls_Manager::CHOOSE,
			'options'         => [
				'column' => [ 'title' => 'Icona sopra il testo', 'icon' => 'eicon-v-align-top' ],
				'row'    => [ 'title' => 'Icona accanto al testo', 'icon' => 'eicon-h-align-left' ],
			],
			'default'         => 'row',
			'tablet_default'  => 'row',
			'mobile_default'  => 'column',
			'toggle'          => false,
			'selectors_dictionary' => [
				'column' => 'flex-direction: column; gap: 5px;',
				'row'    => 'flex-direction: row; gap: 10px;',
			],
			'selectors'       => [ '{{WRAPPER}} .lu3g-wa-bar__item' => '{{VALUE}}' ],
		] );

		$this->add_responsive_control( 'bar_item_max', [
			'label'       => 'Larghezza massima voce',
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'px' ],
			'range'       => [ 'px' => [ 'min' => 80, 'max' => 400 ] ],
			'default'     => [ 'size' => 220, 'unit' => 'px' ],
			'description' => 'Su schermi larghi le voci restano raccolte al centro invece di allargarsi su tutta la barra.',
			'selectors'   => [ '{{WRAPPER}} .lu3g-wa-bar' => '--lu3g-wa-bar-item-max: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'bar_shape', [
			'label'   => 'Forma',
			'type'    => Controls_Manager::SELECT,
			'default' => 'full',
			'options' => [
				'full'     => 'A tutta larghezza',
				'floating' => 'Pillola fluttuante',
			],
		] );

		$this->add_responsive_control( 'bar_height', [
			'label'      => 'Altezza',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 44, 'max' => 90 ] ],
			'default'    => [ 'size' => 62, 'unit' => 'px' ],
			'selectors'  => [ '{{WRAPPER}} .lu3g-wa-bar' => '--lu3g-wa-bar-h: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'bar_glass', [
			'label'        => 'Effetto vetro (liquid glass)',
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
			'separator'    => 'before',
			'description'  => 'Con il vetro attivo il "Colore sfondo" viene ignorato: la trasparenza si regola con la tinta.',
		] );

		$this->add_control( 'bar_glass_tint', [
			'label'     => 'Tinta del vetro',
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(255, 255, 255, 0.32)',
			'condition' => [ 'bar_glass' => 'yes' ],
			'selectors' => [ '{{WRAPPER}} .lu3g-wa-bar' => '--lu3g-wa-glass-tint: {{VALUE}};' ],
		] );

		$this->add_control( 'bar_glass_blur', [
			'label'      => 'Sfocatura',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
			'default'    => [ 'size' => 14, 'unit' => 'px' ],
			'condition'  => [ 'bar_glass' => 'yes' ],
			'selectors'  => [ '{{WRAPPER}} .lu3g-wa-bar' => '--lu3g-wa-glass-blur: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_control( 'bar_glass_shine', [
			'label'      => 'Intensità riflessi',
			'type'       => Controls_Manager::SLIDER,
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
			'default'    => [ 'size' => 0.6 ],
			'condition'  => [ 'bar_glass' => 'yes' ],
			'selectors'  => [ '{{WRAPPER}} .lu3g-wa-bar' => '--lu3g-wa-glass-shine: {{SIZE}};' ],
		] );

		$this->add_control( 'bar_glass_refract', [
			'label'        => 'Rifrazione (sperimentale)',
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
			'condition'    => [ 'bar_glass' => 'yes' ],
			'description'  => 'Deforma il contenuto dietro la barra come una lente. Funziona su Chrome, Edge e Android; su Safari e Firefox resta il vetro smerigliato.',
		] );

		$this->add_responsive_control( 'bar_radius', [
			'label'       => 'Border radius',
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'px' ],
			'range'       => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
			'default'     => [ 'size' => 60, 'unit' => 'px' ],
			'description' => 'Metà dell\'altezza o più = pillola perfetta.',
			'condition'   => [ 'bar_shape' => 'floating' ],
			'selectors'   => [ '{{WRAPPER}} .lu3g-wa-bar' => '--lu3g-wa-bar-r: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'bar_offset_x', [
			'label'      => 'Distanza dai lati',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
			'default'    => [ 'size' => 16, 'unit' => 'px' ],
			'condition'  => [ 'bar_shape' => 'floating' ],
			'selectors'  => [ '{{WRAPPER}} .lu3g-wa-bar' => '--lu3g-wa-bar-x: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'bar_offset_y', [
			'label'      => 'Distanza dal basso',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
			'default'    => [ 'size' => 16, 'unit' => 'px' ],
			'condition'  => [ 'bar_shape' => 'floating' ],
			'selectors'  => [ '{{WRAPPER}} .lu3g-wa-bar' => '--lu3g-wa-bar-y: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_responsive_control( 'bar_max_width', [
			'label'      => 'Larghezza massima',
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 240, 'max' => 1400 ] ],
			'default'    => [ 'size' => 480, 'unit' => 'px' ],
			'condition'  => [ 'bar_shape' => 'floating' ],
			'selectors'  => [ '{{WRAPPER}} .lu3g-wa-bar' => '--lu3g-wa-bar-max: {{SIZE}}{{UNIT}};' ],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'bar_shadow',
			'selector' => '{{WRAPPER}} .lu3g-wa-bar',
		] );

		$this->add_control( 'bar_divider_color', [
			'label'     => 'Colore divisori',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .lu3g-wa-bar' => '--lu3g-wa-bar-divider: {{VALUE}};' ],
		] );

		$this->start_controls_tabs( 'tabs_bar_colors', [ 'separator' => 'before' ] );

		// Normale.
		$this->start_controls_tab( 'tab_bar_normal', [ 'label' => 'Normale' ] );

		$this->add_control( 'bar_bg', [
			'label'     => 'Colore sfondo',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .lu3g-wa-bar' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_control( 'bar_color', [
			'label'     => 'Colore testo',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .lu3g-wa-bar' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'bar_icon_color', [
			'label'     => 'Colore icone',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .lu3g-wa-bar__item i'   => 'color: {{VALUE}};',
				'{{WRAPPER}} .lu3g-wa-bar__item svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		// Attivo (al tocco / focus da tastiera).
		$this->start_controls_tab( 'tab_bar_active', [ 'label' => 'Hover / Attivo' ] );

		$this->add_control( 'bar_active_note', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => 'Colori al passaggio del mouse, mentre la voce viene toccata e con il focus da tastiera.',
			'content_classes' => 'elementor-descriptor',
		] );

		$this->add_control( 'bar_active_bg', [
			'label'     => 'Colore sfondo voce',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .lu3g-wa-bar .lu3g-wa-bar__item:hover, {{WRAPPER}} .lu3g-wa-bar .lu3g-wa-bar__item:active, {{WRAPPER}} .lu3g-wa-bar .lu3g-wa-bar__item:focus-visible' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'bar_active_color', [
			'label'     => 'Colore testo',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .lu3g-wa-bar .lu3g-wa-bar__item:hover, {{WRAPPER}} .lu3g-wa-bar .lu3g-wa-bar__item:active, {{WRAPPER}} .lu3g-wa-bar .lu3g-wa-bar__item:focus-visible' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'bar_active_icon_color', [
			'label'     => 'Colore icone',
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .lu3g-wa-bar .lu3g-wa-bar__item:hover i, {{WRAPPER}} .lu3g-wa-bar .lu3g-wa-bar__item:active i, {{WRAPPER}} .lu3g-wa-bar .lu3g-wa-bar__item:focus-visible i'     => 'color: {{VALUE}};',
				'{{WRAPPER}} .lu3g-wa-bar .lu3g-wa-bar__item:hover svg, {{WRAPPER}} .lu3g-wa-bar .lu3g-wa-bar__item:active svg, {{WRAPPER}} .lu3g-wa-bar .lu3g-wa-bar__item:focus-visible svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'bar_typography',
			'selector'  => '{{WRAPPER}} .lu3g-wa-bar__label',
			'separator' => 'before',
		] );

		$this->end_controls_section();
	}

	/* ================= HELPER ================= */

	/**
	 * Solo cifre (per WhatsApp). Accetta anche link wa.me / api.whatsapp.com.
	 */
	private function wa_digits( $raw ) {
		$raw = trim( (string) $raw );
		if ( '' === $raw ) {
			return '';
		}
		if ( preg_match( '/[?&]phone=(\d+)/', $raw, $m ) ) {
			return $m[1];
		}
		$digits = preg_replace( '/\D+/', '', $raw );
		return preg_replace( '/^00/', '', $digits );
	}

	/**
	 * Numero per tel: mantiene il prefisso internazionale con "+".
	 */
	private function tel_number( $raw ) {
		$raw = trim( (string) $raw );
		if ( '' === $raw ) {
			return '';
		}
		$international = ( 0 === strpos( $raw, '+' ) || 0 === strpos( $raw, '00' ) );
		$digits        = preg_replace( '/\D+/', '', $raw );
		if ( $international ) {
			$digits = '+' . preg_replace( '/^00/', '', $digits );
		}
		return $digits;
	}

	/**
	 * Restituisce gli attributi del link per una voce, oppure null se incompleta.
	 */
	private function item_link( $item ) {
		$value = trim( (string) ( $item['value'] ?? '' ) );
		if ( '' === $value ) {
			return null;
		}

		switch ( $item['type'] ?? '' ) {
			case 'whatsapp':
				$phone = $this->wa_digits( $value );
				if ( '' === $phone ) {
					return null;
				}
				$text   = trim( (string) ( $item['wa_message'] ?? '' ) );
				$enc    = rawurlencode( $text );
				$mobile = 'https://wa.me/' . $phone . ( '' !== $text ? '?text=' . $enc : '' );
				$web    = 'https://web.whatsapp.com/send?phone=' . $phone . ( '' !== $text ? '&text=' . $enc : '' );
				return [
					'href'            => $mobile,
					'data-mobile-url' => $mobile,
					'data-web-url'    => $web,
					'target'          => '_blank',
					'rel'             => 'noopener noreferrer nofollow',
				];

			case 'phone':
				$tel = $this->tel_number( $value );
				return '' === $tel ? null : [ 'href' => 'tel:' . $tel ];

			case 'email':
				$email = sanitize_email( $value );
				if ( ! is_email( $email ) ) {
					return null;
				}
				$subject = trim( (string) ( $item['email_subject'] ?? '' ) );
				return [ 'href' => 'mailto:' . $email . ( '' !== $subject ? '?subject=' . rawurlencode( $subject ) : '' ) ];

			case 'maps':
				$url = preg_match( '#^https?://#i', $value )
					? $value
					: 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $value );
				return [
					'href'   => $url,
					'target' => '_blank',
					'rel'    => 'noopener noreferrer',
				];

			case 'link':
				return [
					'href'   => $value,
					'target' => '_blank',
					'rel'    => 'noopener noreferrer',
				];
		}

		return null;
	}

	private function attrs_to_string( array $attrs ) {
		$out = '';
		foreach ( $attrs as $name => $val ) {
			$is_url = in_array( $name, [ 'href', 'data-mobile-url', 'data-web-url' ], true );
			$out   .= sprintf( ' %s="%s"', $name, $is_url ? esc_url( $val ) : esc_attr( $val ) );
		}
		return $out;
	}

	private function root_classes( $s, $extra = [] ) {
		$side    = ( 'left' === ( $s['position'] ?? 'right' ) ) ? 'left' : 'right';
		$classes = array_merge( [ 'lu3g-wa', 'lu3g-wa--' . $side ], $extra );
		foreach ( [ 'desktop', 'tablet', 'mobile' ] as $device ) {
			if ( 'yes' !== ( $s[ 'show_' . $device ] ?? '' ) ) {
				$classes[] = 'lu3g-wa--hide-' . $device;
			}
		}
		if ( 'yes' === ( $s['pulse'] ?? '' ) ) {
			$classes[] = 'lu3g-wa--pulse';
		}
		return implode( ' ', $classes );
	}

	private function editor_note( $text ) {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			echo '<div class="lu3g-wa-placeholder">' . esc_html( $text ) . '</div>';
		}
	}

	/* ================= RENDER ================= */

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( 'menu' === ( $s['mode'] ?? 'single' ) ) {
			$this->render_menu( $s );
		} else {
			$this->render_single( $s );
		}
	}

	private function render_single( $s ) {
		$phone = $this->wa_digits( $s['phone'] ?? '' );
		if ( '' === $phone ) {
			$this->editor_note( 'WhatsApp Sticky: inserisci il numero nel pannello.' );
			return;
		}

		$link = $this->item_link( [
			'type'       => 'whatsapp',
			'value'      => $phone,
			'wa_message' => $s['message'] ?? '',
		] );
		$link['class']      = 'lu3g-wa__btn';
		$link['aria-label'] = ! empty( $s['aria_label'] ) ? $s['aria_label'] : 'WhatsApp';

		$this->editor_note( 'WhatsApp Sticky attivo (bottone fisso nell\'angolo in basso)' );
		?>
		<div class="<?php echo esc_attr( $this->root_classes( $s ) ); ?>">
			<a<?php echo $this->attrs_to_string( $link ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
				<?php Icons_Manager::render_icon( $s['icon'], [ 'aria-hidden' => 'true' ] ); ?>
			</a>
		</div>
		<?php
	}


	/**
	 * Riga secondaria nella card: testo personalizzato oppure il valore leggibile.
	 */
	private function item_detail( $item ) {
		$detail = trim( (string) ( $item['detail'] ?? '' ) );
		if ( '' !== $detail ) {
			return $detail;
		}
		$value = trim( (string) ( $item['value'] ?? '' ) );
		$type  = $item['type'] ?? '';
		if ( in_array( $type, [ 'phone', 'email' ], true ) ) {
			return $value;
		}
		if ( in_array( $type, [ 'whatsapp', 'maps' ], true ) && ! preg_match( '#^https?://#i', $value ) ) {
			return $value;
		}
		return '';
	}

	/**
	 * Filtro SVG per la rifrazione del vetro (stampato una sola volta per pagina).
	 * Rumore morbido a bassa frequenza usato come mappa di spostamento: il contenuto
	 * dietro la barra si piega leggermente, come attraverso una lastra di vetro.
	 */
	private function render_refract_filter() {
		static $printed = false;
		if ( $printed ) {
			return;
		}
		$printed = true;
		?>
		<svg class="lu3g-wa-svg-defs" width="0" height="0" aria-hidden="true" focusable="false">
			<filter id="lu3g-wa-refract" x="0" y="0" width="100%" height="100%" color-interpolation-filters="sRGB">
				<feTurbulence type="fractalNoise" baseFrequency="0.008 0.018" numOctaves="2" seed="7" result="noise" />
				<feGaussianBlur in="noise" stdDeviation="3" result="soft" />
				<feDisplacementMap in="SourceGraphic" in2="soft" scale="36" xChannelSelector="R" yChannelSelector="G" />
			</filter>
		</svg>
		<?php
	}

	private function render_menu( $s ) {
		$items = [];
		foreach ( (array) ( $s['items'] ?? [] ) as $item ) {
			$link = $this->item_link( $item );
			if ( $link ) {
				$items[] = [ 'item' => $item, 'link' => $link ];
			}
		}

		if ( empty( $items ) ) {
			$this->editor_note( 'Menu contatti: compila il campo "Valore" di almeno una voce.' );
			return;
		}

		$layouts = [ 'vertical', 'horizontal', 'fan', 'pill', 'card' ];
		$layout  = in_array( $s['layout'] ?? '', $layouts, true ) ? $s['layout'] : 'vertical';
		$is_card = 'card' === $layout;
		// Barra fissa per dispositivo (solo dove il widget è visibile).
		$bar_devices = [];
		foreach ( [ 'desktop' => 'bar_desktop', 'tablet' => 'bar_tablet', 'mobile' => 'mobile_bar' ] as $device => $key ) {
			if ( 'yes' === ( $s[ $key ] ?? '' ) && 'yes' === ( $s[ 'show_' . $device ] ?? '' ) ) {
				$bar_devices[] = $device;
			}
		}
		$has_bar = ! empty( $bar_devices );

		$extra = [ 'lu3g-wa--menu', 'lu3g-wa--layout-' . $layout ];
		if ( in_array( $layout, [ 'horizontal', 'fan', 'pill' ], true ) ) {
			$extra[] = 'lu3g-wa--tooltips';
		}
		if ( $has_bar ) {
			$extra[] = 'lu3g-wa--has-bar';
			foreach ( $bar_devices as $device ) {
				$extra[] = 'lu3g-wa--bar-' . $device; // nasconde il bottone su quel dispositivo
			}
		}

		$this->editor_note( 'Menu contatti attivo (bottone fisso nell\'angolo in basso)' );

		$menu_id     = 'lu3g-wa-menu-' . $this->get_id();
		$label_open  = ! empty( $s['menu_aria_label'] ) ? $s['menu_aria_label'] : 'Contattaci';
		$show_labels = 'yes' === ( $s['show_labels'] ?? '' );
		$card_title  = trim( (string) ( $s['card_title'] ?? '' ) );
		$card_text   = trim( (string) ( $s['card_text'] ?? '' ) );
		?>
		<div class="<?php echo esc_attr( $this->root_classes( $s, $extra ) ); ?>"
			style="--n:<?php echo (int) count( $items ); ?>"
			data-hover="<?php echo 'yes' === ( $s['open_on_hover'] ?? '' ) ? '1' : '0'; ?>"
			data-close-on-click="<?php echo 'yes' === ( $s['close_on_click'] ?? '' ) ? '1' : '0'; ?>">

			<button type="button" class="lu3g-wa__btn lu3g-wa__toggle"
				aria-expanded="false"
				aria-controls="<?php echo esc_attr( $menu_id ); ?>"
				aria-label="<?php echo esc_attr( $label_open ); ?>"
				data-label-open="<?php echo esc_attr( $label_open ); ?>"
				data-label-close="Chiudi">
				<span class="lu3g-wa__ico lu3g-wa__ico--open"><?php Icons_Manager::render_icon( $s['menu_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
				<span class="lu3g-wa__ico lu3g-wa__ico--close"><?php Icons_Manager::render_icon( $s['close_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
			</button>

			<div class="lu3g-wa__menu" id="<?php echo esc_attr( $menu_id ); ?>" inert>
				<?php if ( $is_card && ( '' !== $card_title || '' !== $card_text ) ) : ?>
					<div class="lu3g-wa__card-head">
						<?php if ( '' !== $card_title ) : ?>
							<p class="lu3g-wa__card-title"><?php echo esc_html( $card_title ); ?></p>
						<?php endif; ?>
						<?php if ( '' !== $card_text ) : ?>
							<p class="lu3g-wa__card-text"><?php echo esc_html( $card_text ); ?></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php
				foreach ( $items as $i => $entry ) :
					$item  = $entry['item'];
					$link  = $entry['link'];
					$label = trim( (string) ( $item['label'] ?? '' ) );

					$link['class']      = 'lu3g-wa__item elementor-repeater-item-' . $item['_id'];
					$link['style']      = '--i:' . (int) $i;
					$link['aria-label'] = $label;
					?>
					<a<?php echo $this->attrs_to_string( $link ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
						<?php if ( $is_card ) : ?>
							<span class="lu3g-wa__item-icon"><?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
							<span class="lu3g-wa__item-text" aria-hidden="true">
								<span class="lu3g-wa__item-name"><?php echo esc_html( $label ); ?></span>
								<?php $detail = $this->item_detail( $item ); ?>
								<?php if ( '' !== $detail ) : ?>
									<span class="lu3g-wa__item-detail"><?php echo esc_html( $detail ); ?></span>
								<?php endif; ?>
							</span>
						<?php else : ?>
							<?php if ( $show_labels && '' !== $label ) : ?>
								<span class="lu3g-wa__label" aria-hidden="true"><?php echo esc_html( $label ); ?></span>
							<?php endif; ?>
							<span class="lu3g-wa__item-icon"><?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
						<?php endif; ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ( $has_bar ) : ?>
			<?php
			$bar_classes = [ 'lu3g-wa-bar' ];
			foreach ( $bar_devices as $device ) {
				$bar_classes[] = 'lu3g-wa-bar--on-' . $device; // mostra la barra su quel dispositivo
			}
			if ( 'floating' === ( $s['bar_shape'] ?? 'full' ) ) {
				$bar_classes[] = 'lu3g-wa-bar--floating';
			}
			$glass   = 'yes' === ( $s['bar_glass'] ?? '' );
			$refract = $glass && 'yes' === ( $s['bar_glass_refract'] ?? '' );
			if ( $glass ) {
				$bar_classes[] = 'lu3g-wa-bar--glass';
			}
			if ( $refract ) {
				$bar_classes[] = 'lu3g-wa-bar--refract';
				$this->render_refract_filter();
			}
			?>
			<nav class="<?php echo esc_attr( implode( ' ', $bar_classes ) ); ?>" aria-label="<?php echo esc_attr( $label_open ); ?>">
				<?php
				foreach ( $items as $entry ) :
					$item  = $entry['item'];
					$link  = $entry['link'];
					$label = trim( (string) ( $item['label'] ?? '' ) );

					$link['class'] = 'lu3g-wa-bar__item';
					?>
					<a<?php echo $this->attrs_to_string( $link ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
						<?php Icons_Manager::render_icon( $item['item_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						<span class="lu3g-wa-bar__label"><?php echo esc_html( $label ); ?></span>
					</a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>
		<?php
	}
}
