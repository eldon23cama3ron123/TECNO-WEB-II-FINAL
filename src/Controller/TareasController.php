<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Response;

/**
 * @property \App\Model\Table\TareasTable $Tareas
 */
class TareasController extends AppController
{
    public function index(): void
    {
        $userId = (int)$this->request->getSession()->read('Auth.id');
        $query = $this->Tareas->find()
            ->where(['Tareas.user_id' => $userId])
            ->orderBy(['Tareas.fecha_limite' => 'ASC', 'Tareas.created' => 'DESC']);

        $estado = $this->request->getQuery('estado');
        if ($estado !== null && $estado !== '') {
            $query->where(['Tareas.estado' => $estado]);
        }

        $q = trim((string)$this->request->getQuery('q'));
        if ($q !== '') {
            $query->where([
                'OR' => [
                    'Tareas.titulo LIKE' => '%' . $q . '%',
                    'Tareas.descripcion_es LIKE' => '%' . $q . '%',
                    'Tareas.descripcion_en LIKE' => '%' . $q . '%',
                ],
            ]);
        }

        $fechaDesde = $this->request->getQuery('fecha_desde');
        if ($fechaDesde !== null && $fechaDesde !== '') {
            $query->where(['Tareas.fecha_limite >=' => $fechaDesde . ' 00:00:00']);
        }
        $fechaHasta = $this->request->getQuery('fecha_hasta');
        if ($fechaHasta !== null && $fechaHasta !== '') {
            $query->where(['Tareas.fecha_limite <=' => $fechaHasta . ' 23:59:59']);
        }

        $tareas = $this->paginate($query);

        $this->set('tareas', $tareas);
        $this->set('estadosFiltro', ['' => __('Todos')] + $this->Tareas->opcionesEstado());
        $this->set('estadoSeleccionado', $estado ?? '');
        $this->set('q', $q);
        $this->set('fechaDesde', $fechaDesde ?? '');
        $this->set('fechaHasta', $fechaHasta ?? '');
        $this->set('opcionesEstado', $this->Tareas->opcionesEstado());
    }

    public function view(string|int|null $id = null): void
    {
        $tarea = $this->fetchOwnedTarea($id !== null && $id !== '' ? (int)$id : null);
        $this->set(compact('tarea'));
        $this->set('opcionesEstado', $this->Tareas->opcionesEstado());
    }

    public function add(): ?Response
    {
        $tarea = $this->Tareas->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['user_id'] = (int)$this->request->getSession()->read('Auth.id');
            $tarea = $this->Tareas->patchEntity($tarea, $data);
            if ($this->Tareas->save($tarea)) {
                $this->Flash->success(__('La tarea se guardó correctamente.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('No se pudo guardar la tarea. Revise los datos.'));
        }
        $this->set(compact('tarea'));
        $this->set('opcionesEstado', $this->Tareas->opcionesEstado());

        return null;
    }

    public function edit(string|int|null $id = null): ?Response
    {
        $tarea = $this->fetchOwnedTarea($id !== null && $id !== '' ? (int)$id : null);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $tarea = $this->Tareas->patchEntity($tarea, $this->request->getData());
            if ($this->Tareas->save($tarea)) {
                $this->Flash->success(__('La tarea se actualizó correctamente.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('No se pudo actualizar la tarea.'));
        }
        $this->set(compact('tarea'));
        $this->set('opcionesEstado', $this->Tareas->opcionesEstado());

        return null;
    }

    public function delete(string|int|null $id = null): ?Response
    {
        $this->request->allowMethod(['post', 'delete']);
        $tarea = $this->fetchOwnedTarea($id !== null && $id !== '' ? (int)$id : null);
        if ($this->Tareas->delete($tarea)) {
            $this->Flash->success(__('La tarea se eliminó correctamente.'));
        } else {
            $this->Flash->error(__('No se pudo eliminar la tarea.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * @throws \Cake\Http\Exception\ForbiddenException
     * @throws \Cake\Datasource\Exception\RecordNotFoundException
     */
    protected function fetchOwnedTarea(?int $id): \App\Model\Entity\Tarea
    {
        if ($id === null) {
            throw new RecordNotFoundException(__('Tarea no válida.'));
        }
        $userId = (int)$this->request->getSession()->read('Auth.id');
        try {
            $tarea = $this->Tareas->get($id, contain: []);
        } catch (RecordNotFoundException $e) {
            throw $e;
        }
        if ((int)$tarea->user_id !== $userId) {
            throw new ForbiddenException(__('No puede acceder a esta tarea.'));
        }

        return $tarea;
    }
}
