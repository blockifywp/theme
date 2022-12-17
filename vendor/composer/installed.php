<?php return array(
    'root' => array(
        'name' => 'blockify/theme',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => 'ea4e409abff267e5432b79afccf119ca80a9852b',
        'type' => 'wordpress-theme',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'blockify/framework' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => 'ff9badc1a588f081d6db8b54e7888f2d3d93107c',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../blockify/framework',
            'aliases' => array(
                0 => '9999999-dev',
            ),
            'dev_requirement' => false,
        ),
        'blockify/theme' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => 'ea4e409abff267e5432b79afccf119ca80a9852b',
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
