<?php return array(
    'root' => array(
        'name' => 'blockify/theme',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => 'dce8d19aa5d64c2518b1d0314a4184e22288cc2c',
        'type' => 'wordpress-theme',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'blockify/framework' => array(
            'pretty_version' => 'dev-package',
            'version' => 'dev-package',
            'reference' => '7e3d83583b46934fa8a43c6bb1d04fb53a33eb58',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../blockify/framework',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'blockify/theme' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => 'dce8d19aa5d64c2518b1d0314a4184e22288cc2c',
            'type' => 'wordpress-theme',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'tgmpa/tgm-plugin-activation' => array(
            'pretty_version' => 'dev-develop',
            'version' => 'dev-develop',
            'reference' => '2d34264f4fdcfcc60261d490ff2e689f0c33730c',
            'type' => 'library',
            'install_path' => __DIR__ . '/../tgmpa/tgm-plugin-activation',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
    ),
);
