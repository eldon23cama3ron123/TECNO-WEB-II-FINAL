<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\I18n\I18n;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    private const DEFAULT_LOGIN_CORREO = 'kevinmaydana448@gmail.com';
    private const DEFAULT_LOGIN_PASSWORD = '123456';

    /**
     * @return array<string, string>
     */
    protected function opcionesIdioma(): array
    {
        $labels = [
            'es_ES' => __('Español'),
            'en_US' => __('English'),
            'zh_CN' => __('Chino mandarín'),
            'hi_IN' => __('Hindi'),
            'ar_SA' => __('Árabe'),
            'fr_FR' => __('Francés'),
            'ru_RU' => __('Ruso'),
            'pt_BR' => __('Portugués'),
            'de_DE' => __('Alemán'),
            'ja_JP' => __('Japonés'),
        ];
        $out = [];
        foreach ((array)Configure::read('App.supportedLocales') as $code) {
            $out[$code] = $labels[$code] ?? $code;
        }

        return $out;
    }

    public function register(): ?Response
    {
        $session = $this->request->getSession();
        $auth = $session->read('Auth');
        if (!empty($auth['id'])) {
            return $this->redirect(['controller' => 'Tareas', 'action' => 'index']);
        }

        $localeQuery = $this->request->getQuery('locale');
        if (is_string($localeQuery) && $localeQuery !== '' && $this->isSupportedLocale($localeQuery)) {
            $session->write('UiLocale', $localeQuery);
            if (!$this->request->is('post')) {
                return $this->redirect(['action' => 'register']);
            }
        }

        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $correo = trim((string)($data['correo'] ?? ''));
            $password = (string)($data['password'] ?? '');
            $passwordConfirm = (string)($data['password_confirm'] ?? '');
            $idioma = (string)($data['idioma'] ?? 'es_ES');
            if (!$this->isSupportedLocale($idioma)) {
                $idioma = (string)Configure::read('App.defaultLocale', 'es_ES');
            }
            $session->write('UiLocale', $idioma);

            if ($correo === '') {
                $this->Flash->error(__('El correo es obligatorio.'));
            } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $this->Flash->error(__('El correo no tiene un formato válido.'));
            } elseif ($password === '') {
                $this->Flash->error(__('La contraseña es obligatoria.'));
            } elseif ($password !== $passwordConfirm) {
                $this->Flash->error(__('Las contraseñas no coinciden.'));
            } elseif ($this->Users->find()->where(['correo' => $correo])->count() > 0) {
                $this->Flash->error(__('Ya existe un usuario registrado con ese correo.'));
            } else {
                unset($data['password_confirm']);
                $data['correo'] = $correo;
                $data['language'] = $idioma;
                $data['role_id'] = 2; // Cliente por defecto
                unset($data['idioma']);

                $data['telf'] = $data['telf'] ?? 0;
                $data['telefono'] = $data['telefono'] ?? null;

                $user = $this->Users->patchEntity($user, $data);
                if ($this->Users->save($user)) {
                    $perfil = $this->Users->Perfil->newEntity([
                        'user_id' => $user->id,
                        'idioma' => $idioma,
                    ]);
                    $this->Users->Perfil->save($perfil);

                    $session->write('UiLocale', $idioma);

                    $this->Flash->success(__('Usuario registrado. Ahora puede iniciar sesión.'));

                    return $this->redirect(['action' => 'login']);
                }
                $this->Flash->error(__('No se pudo registrar el usuario. Intente nuevamente.'));
            }
        }

        $this->set(compact('user'));
        $this->set('idiomasRegistro', $this->opcionesIdioma());
        $this->set('localesInterfaz', $this->opcionesIdioma());

        return null;
    }

    public function login(): ?Response
    {
        $session = $this->request->getSession();
        $auth = $session->read('Auth');
        if (!empty($auth['id'])) {
            return $this->redirect(['controller' => 'Tareas', 'action' => 'index']);
        }

        $localeQuery = $this->request->getQuery('locale');
        if (is_string($localeQuery) && $localeQuery !== '' && $this->isSupportedLocale($localeQuery)) {
            $session->write('UiLocale', $localeQuery);
            if (!$this->request->is('post')) {
                return $this->redirect(['action' => 'login']);
            }
        }

        if ($this->request->is('post')) {
            $correo = (string)$this->request->getData('correo');
            $password = (string)$this->request->getData('password');

            $user = $this->Users->find()
                ->contain(['Perfil', 'Roles'])
                ->where(['correo' => $correo])
                ->first();

            if (
                !$user &&
                $correo === self::DEFAULT_LOGIN_CORREO &&
                $password === self::DEFAULT_LOGIN_PASSWORD
            ) {
                $userData = [
                    'correo' => self::DEFAULT_LOGIN_CORREO,
                    'password' => password_hash(self::DEFAULT_LOGIN_PASSWORD, PASSWORD_DEFAULT),
                    'language' => 'es_ES',
                    'role_id' => 1, // Admin por defecto
                ];
                $user = $this->Users->newEntity($userData);
                $user->role_id = 1; // Asegura que sea admin
                if ($this->Users->save($user)) {
                    $user = $this->Users->get($user->id, contain: ['Perfil', 'Roles']);
                }
            }

            $ok = false;
            if ($user && $user->password !== null) {
                $stored = (string)$user->password;

                if (str_starts_with($stored, '$argon2') || preg_match('/^\$2[ayb]\$/', $stored)) {
                    $ok = password_verify($password, $stored);
                } else {
                    $ok = ($stored !== '' && $password !== '' && hash_equals($stored, $password));
                }
            }

            if ($ok && $user) {
                if (!$user->perfil) {
                    $perfil = $this->Users->Perfil->newEntity([
                        'user_id' => $user->id,
                        'idioma' => $user->language ?: 'es_ES',
                    ]);
                    $this->Users->Perfil->save($perfil);
                    $user = $this->Users->get($user->id, contain: ['Perfil']);
                }

                $locale = trim((string)($user->perfil?->idioma ?? ''));
                if ($locale === '' || !$this->isSupportedLocale($locale)) {
                    $locale = $this->normalizeLocale($user->language ?? null);
                }
                $session->write('Auth', [
                    'id' => $user->id,
                    'correo' => $user->correo,
                    'nombre' => $user->nombre,
                    'apellido' => $user->apellido,
                    'locale' => $locale,
                    'role' => $user->role_id === 1 ? 'admin' : 'cliente',
                    'role_id' => $user->role_id,
                ]);
                $session->write('UiLocale', $locale);
                I18n::setLocale($locale);

                $this->Flash->success(__('Bienvenido.'));

                return $this->redirect(['controller' => 'Paises', 'action' => 'index']);
            }

            $this->Flash->error(__('Correo o contraseña inválidos.'));
        }

        $this->set('localesInterfaz', $this->opcionesIdioma());

        return null;
    }

    public function logout(): Response
    {
        $session = $this->request->getSession();
        $loc = $session->read('Auth.locale');
        if (is_string($loc) && $loc !== '' && $this->isSupportedLocale($loc)) {
            $session->write('UiLocale', $loc);
        }
        $session->delete('Auth');
        $this->Flash->success(__('Sesión cerrada.'));

        return $this->redirect(['action' => 'login']);
    }

    public function profile(): ?Response
    {
        $authId = (int)$this->request->getSession()->read('Auth.id');
        $user = $this->Users->get($authId, contain: ['Perfil']);
        if (!$user->perfil) {
            $perfil = $this->Users->Perfil->newEntity([
                'user_id' => $authId,
                'idioma' => $user->language ?: 'es_ES',
            ]);
            $this->Users->Perfil->save($perfil);
            $user = $this->Users->get($authId, contain: ['Perfil']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if (trim((string)($data['password'] ?? '')) === '') {
                unset($data['password']);
            }
            $perfilData = $data['perfil'] ?? [];
            unset($data['perfil']);

            $user = $this->Users->patchEntity($user, $data);
            $perfil = $this->Users->Perfil->patchEntity($user->perfil, $perfilData);

            $idioma = trim((string)($perfil->idioma ?? ''));
            if ($idioma === '' || !$this->isSupportedLocale($idioma)) {
                $idioma = (string)Configure::read('App.defaultLocale', 'es_ES');
            }
            $perfil->idioma = $idioma;
            $user->language = $idioma;

            $okUser = $this->Users->save($user);
            $okPerfil = $this->Users->Perfil->save($perfil);
            if ($okUser && $okPerfil) {
                $this->request->getSession()->write('Auth.locale', $idioma);
                $this->request->getSession()->write('UiLocale', $idioma);
                I18n::setLocale($idioma);
                $this->Flash->success(__('Perfil actualizado.'));

                return $this->redirect(['action' => 'profile']);
            }
            $this->Flash->error(__('No se pudo actualizar el perfil.'));
        }

        $this->set(compact('user'));
        $this->set('idiomasPerfil', $this->opcionesIdioma());

        return null;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Users->find()->contain(['Roles']);
        $users = $this->paginate($query);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, contain: []);
        $this->set(compact('user'));
    }

    /**
     * Obtiene las opciones de roles para formularios
     *
     * @return array<int, string>
     */
    protected function getRolesOptions(): array
    {
        $roles = $this->Users->Roles->find()->select(['id', 'nombre'])->toArray();
        $out = [];
        foreach ($roles as $rol) {
            $out[$rol->id] = $rol->nombre;
        }
        return $out;
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $rolesOptions = $this->getRolesOptions();
        $this->set(compact('user', 'rolesOptions'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $rolesOptions = $this->getRolesOptions();
        $this->set(compact('user', 'rolesOptions'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
