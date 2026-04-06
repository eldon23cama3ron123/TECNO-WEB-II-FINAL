<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files in this directory must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\I18n\I18n;

/**
 * Application Controller
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
    }

    /**
     * @param string|null $locale Código locale (ej. en_US).
     * @return bool
     */
    protected function isSupportedLocale(?string $locale): bool
    {
        if ($locale === null || $locale === '') {
            return false;
        }
        $allowed = Configure::read('App.supportedLocales');
        if (!is_array($allowed)) {
            return false;
        }

        return in_array($locale, $allowed, true);
    }

    /**
     * Devuelve un locale válido o el predeterminado de la app.
     *
     * @param string|null $locale Candidato.
     * @return string
     */
    protected function normalizeLocale(?string $locale): string
    {
        $default = (string)Configure::read('App.defaultLocale', 'es_ES');
        if ($locale === null || $locale === '') {
            return $default;
        }

        return $this->isSupportedLocale($locale) ? $locale : $default;
    }

    /**
     * Autenticación global e idioma según sesión.
     *
     * @param \Cake\Event\EventInterface<\Cake\Controller\Controller> $event Evento.
     * @return void
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        $controller = (string)$this->request->getParam('controller');
        $action = (string)$this->request->getParam('action');
        $publicActions = ['login', 'register', 'logout'];
        $isPublic = $controller === 'Users' && in_array($action, $publicActions, true);

        if ($isPublic) {
            $session = $this->request->getSession();
            $locale = null;
            if ($action === 'logout') {
                $authLoc = $session->read('Auth.locale');
                if (is_string($authLoc) && $authLoc !== '') {
                    $locale = $this->normalizeLocale($authLoc);
                }
            }
            if ($locale === null) {
                $guestLocale = $session->read('UiLocale');
                $locale = $this->normalizeLocale(is_string($guestLocale) ? $guestLocale : null);
            }
            I18n::setLocale($locale);

            return;
        }

        $session = $this->request->getSession();
        if (empty($session->read('Auth.id'))) {
            $this->Flash->error(__('Debe iniciar sesión para continuar.'));
            $event->setResult($this->redirect(['controller' => 'Users', 'action' => 'login']));
            $event->stopPropagation();

            return;
        }

        $authLocale = $session->read('Auth.locale');
        $locale = $this->normalizeLocale(is_string($authLocale) ? $authLocale : null);
        $session->write('Auth.locale', $locale);
        I18n::setLocale($locale);

        // Control de acceso por rol
        $roleId = (int)($session->read('Auth.role_id') ?? 2);
        
        // Controladores permitidos por rol
        $adminControllers = ['Users', 'Tareas'];
        $clienteControllers = ['Paises'];
        
        // Admin (role_id = 1) acceso a todo
        if ($roleId === 1) {
            return;
        }
        
        // Cliente (role_id = 2) solo acceso a Paises y Users (solo profile/logout)
        if ($roleId === 2) {
            if (!in_array($controller, $clienteControllers, true)) {
                if ($controller === 'Users' && in_array($action, ['profile', 'logout'], true)) {
                    return;
                }
                $this->Flash->error(__('No tienes permiso para acceder a esta sección.'));
                $event->setResult($this->redirect(['controller' => 'Paises', 'action' => 'index']));
                $event->stopPropagation();
                return;
            }
        }
    }
}
