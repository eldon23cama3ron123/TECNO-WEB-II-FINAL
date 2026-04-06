<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Paises Controller
 *
 * @property \App\Model\Table\PaisesTable $Paises
 */
class PaisesController extends AppController
{
    public function index()
    {
        $paises = $this->paginate($this->Paises->find()->order(['nombre' => 'ASC']));
        $this->set(compact('paises'));
    }

    public function view($id = null)
    {
        $pais = $this->Paises->get($id);
        $this->set(compact('pais'));
    }

    public function add()
    {
        $pais = $this->Paises->newEmptyEntity();
        if ($this->request->is('post')) {
            $pais = $this->Paises->patchEntity($pais, $this->request->getData());
            if ($this->Paises->save($pais)) {
                $this->Flash->success(__('El país se guardó correctamente.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('No se pudo guardar el país. Intente nuevamente.'));
        }
        $this->set(compact('pais'));
    }

    public function edit($id = null)
    {
        $pais = $this->Paises->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $pais = $this->Paises->patchEntity($pais, $this->request->getData());
            if ($this->Paises->save($pais)) {
                $this->Flash->success(__('El país se actualizó correctamente.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('No se pudo actualizar el país. Intente nuevamente.'));
        }
        $this->set(compact('pais'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $pais = $this->Paises->get($id);
        if ($this->Paises->delete($pais)) {
            $this->Flash->success(__('El país se eliminó correctamente.'));
        } else {
            $this->Flash->error(__('No se pudo eliminar el país.')); 
        }

        return $this->redirect(['action' => 'index']);
    }
}
