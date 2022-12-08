<?php return array(
    'root' => array(
        'name' => 'blockify/theme',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => 'd6c4dfd64c1ba9e1a0d6d38618ab8d10edfa5c71',
        'type' => 'wordpress-theme',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'blockify/framework' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => 'f3909ee33778ed593a34317e9cd411dbe90bca85',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../blockify/framework',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'blockify/theme' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => 'd6c4dfd64c1ba9e1a0d6d38618ab8d10edfa5c71',
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
