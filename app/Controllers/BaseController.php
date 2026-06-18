<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    /**
     * Uppercase all string values in an array, except specified keys.
     * Standard pattern applied across all modules for uniform data entry.
     *
     * @param array $data     The data array to normalize
     * @param array $exclude  Keys to skip (e.g. email, password, hashed fields)
     */
    protected function uppercaseFields(array $data, array $exclude = ['email_address', 'password', 'password_confirm']): array
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $exclude, true)) {
                continue;
            }
            if (is_string($value)) {
                $data[$key] = mb_strtoupper($value);
            }
        }
        return $data;
    }
}
