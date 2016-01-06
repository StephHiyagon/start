<?php
/**
 *  Start! - An open source Doodle.com clone
 *
 *  Copyright (C) 2016  Lennart Rosam <hello@takuto.de>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 **/

// Doctrine CLI configuration

require __DIR__ . '/vendor/autoload.php';

define('BASE_PATH', __DIR__);

$env = getenv('APPLICATION_ENV');

if(!$env) {
    echo "*** WARNING ***\n";
    echo "Please specify your APPLICATION_ENV variable which is used to determaine what config to use!\n";
    echo "On Unix-like systems, run 'export APPLICATION_ENV=foo' to achive this.\n\n";
    echo "Defaulting to 'config'.\n";
    
    $env = 'config';
}

$app = new Silex\Application();
$app->register(new Igorw\Silex\ConfigServiceProvider(BASE_PATH . "/resources/config/$env.php"));
$newDefaultAnnotationDrivers = array(
       __DIR__ . "/src/"
);

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache());

$driverImpl = $config->newDefaultAnnotationDriver($newDefaultAnnotationDrivers);
$config->setMetadataDriverImpl($driverImpl);

$config->setProxyDir(__DIR__ . "/resources/cache/doctrine/proxy");
$config->setProxyNamespace("Proxies");

$em = \Doctrine\ORM\EntityManager::create($app['db.options'], $config);

$helpers = new Symfony\Component\Console\Helper\HelperSet(array(
        'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
        'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em),
));
