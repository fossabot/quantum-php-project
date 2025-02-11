<?php

/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 2.5.0
 */

namespace Modules\Web\Middlewares;

use Quantum\Middleware\QtMiddleware;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Closure;

/**
 * Class Auth
 * @package Modules\Web\Middlewares
 */
class Auth extends QtMiddleware
{

    /**
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param \Closure $next
     * @return mixed
     * @throws \Quantum\Exceptions\AuthException
     * @throws \Quantum\Exceptions\ConfigException
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\LoaderException
     * @throws \ReflectionException
     */
    public function apply(Request $request, Response $response, Closure $next)
    {
        if (!auth()->check()) {
            redirect(base_url() . '/' . current_lang() . '/signin');
        }

        return $next($request, $response);
    }

}
