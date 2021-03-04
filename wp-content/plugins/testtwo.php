<?php
// color_section
        $this->start_controls_section(
            'color_section',
            array(
                'label' => __('Color Section', 'pestico-core'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->start_controls_tabs('tabs_button_style');
        $this->start_controls_tab(
            'tab_button_normal',
            array(
                'label' => __('Normal', 'elementor'),
            )
        );
        $this->add_control(
            'button_color',
            array(
                'label'     => __('Button', 'pestico-core'),
                'separator' => 'before',
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .btn-style-one' => 'background: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'button_textcolor',
            array(
                'label'     => __('Button text', 'pestico-core'),
                'separator' => 'before',
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .btn-style-one' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            array(
                'label' => __('Hover', 'elementor'),
            )
        );
        $this->add_control(
            'button_hover_color',
            array(
                'label'     => __('Button', 'pestico-core'),
                'separator' => 'before',
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .btn-style-one:before' => 'background: {{VALUE}}',

                ),
            )
        );
        $this->add_control(
            'button_hovertextcolor',
            array(
                'label'     => __('Button text', 'pestico-core'),
                'separator' => 'before',
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .btn-style-one:hover' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();