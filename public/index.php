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

use Symfony\Component\HttpFoundation\JsonResponse;

$app = require_once __DIR__ . '/../app.php';

/** @var $app \Silex\Application */

/* Detect language by accept-language header */
$app->before(function () use ($app) {
    // i18n
    // Set locale according to HTTP Accept-Language Header
    /** @var $request \Symfony\Component\HttpFoundation\Request */
    $request = $app['request'];
    $locale = $request->getPreferredLanguage(array_keys($app['locales']));

    /** @var $i18n \Symfony\Component\Translation\Translator */
    $i18n = $app['translator'];
    $i18n->setLocale($locale);
});

/* Routing */
$app->get('/index.html', 'controllers.helloworld:sayHello');
$app->get('/', 'controllers.helloworld:sayHello');

// REST
$app->get('/rest/api/1/messages', 'controllers.messages:index');
$app->get('/rest/api/1/messages/{id}', 'controllers.messages:get');
$app->post('/rest/api/1/messages', 'controllers.messages:create');
$app->put('/rest/api/1/messages/{id}', 'controllers.messages:update');
$app->delete('/rest/api/1/messages/{id}', 'controllers.messages:delete');

/* Exception handling */
$app->error(function (\Exception $e, $code) use ($app) {
    /** @var $request \Symfony\Component\HttpFoundation\Request */
    $request = $app['request'];

    // REST Request? Return as JSON.
    if (strpos($request->getUri(), "/rest/api/1")) {
        $message = $app['translator']->trans($e->getMessage());
        $response = array(
            'message' => $message
        );

        if ($app['debug']) {
            $response['stacktrace'] = $e->getTraceAsString();
        }

        return new JsonResponse($response, $code);
    }

    return $app['twig']->render("error.html", array(
        'code' => $code,
        'message' => $app['translator']->trans($e->getMessage()),
        'stacktrace' => $e->getTraceAsString()
    ));
});

$app->run();