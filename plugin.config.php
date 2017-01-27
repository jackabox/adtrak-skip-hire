<?php

return [
	
	/**
     * The version constraint.
     */
    'version' => 2.0,

    /**
     * The asset path.
     */
    'assets' => '/resources/assets/',

    'sentryUrl' => 'https://b8752607c9f74cfcbc12681e3b50dedc:2ba1e8b0f0264760947658a89cfb0f33@sentry.io/122240',
    
    /**
     * Views
     */
    'views' => __DIR__ . '/resources/views',

    /**
     * Templates
     */
    'templates' => __DIR__ . '/resources/templates/',

    /**
     * Activate
     */
    'activators' => [
        __DIR__ . '/app/activate.php'
    ],

    /**
     * Deactivate
     */
    'deactivators' => [
        __DIR__ . '/app/deactivate.php'
    ],

    /**
     * Loader
     */
    'loader' => [
        __DIR__ . '/app/loader.php'
    ],

    /**
     * The styles and scripts to auto-load.
     */
    'enqueue' => [
        __DIR__ . '/app/enqueue.php'
    ]
];