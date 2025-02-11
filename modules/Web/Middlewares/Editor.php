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

use Quantum\Libraries\Validation\Validator;
use Quantum\Libraries\Validation\Rule;
use Quantum\Middleware\QtMiddleware;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Closure;

/**
 * Class Editor
 * @package Modules\Web\Middlewares
 */
class Editor extends QtMiddleware
{

    /**
     * @var \Quantum\Libraries\Validation\Validator
     */
    private $validator;

    /**
     * Class constructor
     * @param \Quantum\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->validator = new Validator();

        if ($request->hasFile('image')) {
            $this->validator->addRules([
                'image' => [
                    Rule::set('fileSize', 2 * pow(1024, 2)),
                    Rule::set('fileExtension', ['jpeg', 'jpg', 'png']),
                ]
            ]);
        }

        $this->validator->addRules([
            'title' => [
                Rule::set('required'),
                Rule::set('minLen', 10),
                Rule::set('maxLen', 50)
            ],
            'content' => [
                Rule::set('required'),
                Rule::set('minLen', 10),
            ],
        ]);
    }

    /**
     * @param \Quantum\Http\Request $request
     * @param \Quantum\Http\Response $response
     * @param \Closure $next
     * @return mixed
     * @throws \Quantum\Exceptions\AuthException
     * @throws \Quantum\Exceptions\ConfigException
     * @throws \Quantum\Exceptions\CryptorException
     * @throws \Quantum\Exceptions\DatabaseException
     * @throws \Quantum\Exceptions\DiException
     * @throws \Quantum\Exceptions\LoaderException
     * @throws \Quantum\Exceptions\ModelException
     * @throws \Quantum\Exceptions\SessionException
     * @throws \ReflectionException
     */
    public function apply(Request $request, Response $response, Closure $next)
    {
        if (auth()->user()->getFieldValue('role') != 'admin' && auth()->user()->getFieldValue('role') != 'editor') {
            redirect(base_url() . '/' . current_lang());
        }

        if ($request->isMethod('post')) {
            if (!$this->validator->isValid($request->all())) {
                $data = $request->all();
                unset($data['image']);
                session()->setFlash('error', $this->validator->getErrors());
                redirectWith(get_referrer(), $data);
            }
        }

        return $next($request, $response);
    }
}
