<?php return array(
    'root' => array(
        'name' => 'blockify/theme',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => '99dcfb61fd4a44df8ee15b56764e80720cfe805f',
        'type' => 'wordpress-theme',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'blockify/framework' => array(
            'pretty_version' => 'dev-package',
            'version' => 'dev-package',
            'reference' => 'b512ca8feee1942c90e98da75ac1f456b28cfc53',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../blockify/framework',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'blockify/theme' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => '99dcfb61fd4a44df8ee15b56764e80720cfe805f',
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
