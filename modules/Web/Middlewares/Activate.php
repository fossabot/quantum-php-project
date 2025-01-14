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
use Quantum\Hooks\HookManager;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Closure;

/**
 * Class Activate
 * @package Modules\Web\Middlewares
 */
class Activate extends QtMiddleware
{

    /**
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param \Closure $next
     * @return mixed
     * @throws \Quantum\Exceptions\HookException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function apply(Request $request, Response $response, Closure $next)
    {
        list($lang, $token) = route_args();

        if (!$this->checkToken($token)) {
            HookManager::call('pageNotFound');
        }

        $request->set('activation_token', $token);

        return $next($request, $response);
    }

    /**
     * Check token
     * @param string $token
     * @return bool
     * @throws \Exception
     */
    private function checkToken(string $token): bool
    {
        $users = load_users();

        if (is_array($users) && count($users) > 0) {
            foreach ($users as $user) {
                if (isset($user['activation_token']) && $user['activation_token'] == $token) {
                    return true;
                }
            }
        }

        return false;
    }

}
