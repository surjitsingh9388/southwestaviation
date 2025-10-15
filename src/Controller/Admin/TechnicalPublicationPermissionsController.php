<?php
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use Cake\View\View;
    use Dompdf\Dompdf;
    use Cake\Datasource\FactoryLocator;
    use Cake\ORM\Locator\LocatorAwareTrait;

    class TechnicalPublicationPermissionsController extends AppController
    {
        protected \App\Model\Table\UsersTable $Users;
        protected \App\Model\Table\TechnicalPublicationsTable $TechnicalPublications;
        protected \App\Model\Table\TechnicalPublicationPermissionsTable $TechnicalPublicationPermissions;

        public function initialize():void {
            parent::initialize();
            $this->Users = $this->fetchTable('Users');
            $this->TechnicalPublications = $this->fetchTable('TechnicalPublications');
            $this->TechnicalPublicationPermissions = $this->fetchTable('TechnicalPublicationPermissions');

            $this->loadComponent('TechnicalPublication');
            $this->loadComponent('User');
        }

        public function index()
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Permissions', $actionStatus))
                {
                    $actionItems = $actionStatus['Permissions'];
                }
            }

            $this->Users = $this->fetchTable('Users');
            $this->TechnicalPublications = $this->fetchTable('TechnicalPublications');
            $this->TechnicalPublicationPermissions = $this->fetchTable('TechnicalPublicationPermissions');

            $technicalPublications = $this->TechnicalPublications
                                            ->find()
                                            ->contain(['TechnicalPublicationPermissions'])
                                            ->order(['main_page_id' => 'ASC', 'parent_id' => 'ASC', 'id' => 'ASC'])
                                            ->all()
                                            ->toArray();

            // Group by main_page_id (SWAS, BVAC, RJC)
            $grouped = [];
            foreach ($technicalPublications as $item) {
                $grouped[$item->main_page_id][] = $item;
            }

            // Build tree for each group
            foreach ($grouped as $key => $items) {
                $grouped[$key] = $this->buildTree($items);
            }

            $mainPageHeadings = [
                '1' => 'SWAS',
                '2' => 'BVAC',
                '3' => 'RJC',
            ];

            $roles = $this->User->getRoles();
            $roles = implode(',', array_keys($roles));

            $allAdmins = $this->Users->find('list', array (
                                                'keyField' => 'id',
                                                'valueField' => 'full_name'
                                            ))
                                        ->where(['Users.role_id IN ('.$roles.')', 'Users.id !=' => 1, 'Users.suspended' => 0])
                                        ->orderByAsc('Users.full_name')
                                        ->toArray();
            
            
            $technicalPublications = $this->TechnicalPublication->getTechnicalPublicationData();

            $this->set(compact('actionItems', 'technicalPublications', 'allAdmins', 'grouped', 'mainPageHeadings'));
        }

        private function buildTree($elements, $parentId = null) {
            $branch = [];

            foreach ($elements as $element) {
                if ((int)$element->parent_id === (int)$parentId) {
                    $children = $this->buildTree($elements, $element->id);
                    if ($children) {
                        $element->children = $children;
                    } else {
                        $element->children = [];
                    }
                    $branch[] = $element;
                }
            }

            return $branch;
        }

        public function savePermissions()
        {
            if ($this->request->is('post')) {
                $userId = $this->request->getData('user_id');
                $submitted = $this->request->getData('permissions', []);

                $existing = $this->TechnicalPublicationPermissions
                    ->find('list', [
                        'keyField' => 'technical_publication_id',
                        'valueField' => 'id'
                    ])
                    ->where(['user_id' => $userId])
                    ->toArray();

                foreach ($submitted as $pubId => $perm) {
                    if(!is_numeric($pubId)){
                        continue;
                    }
                    $add    = !empty($perm['add']) ? 1 : 0;
                    $edit   = !empty($perm['edit']) ? 1 : 0;
                    $view   = !empty($perm['view']) ? 1 : 0;
                    $delete = !empty($perm['delete']) ? 1 : 0;

                    if (isset($existing[$pubId])) {
                        $entity = $this->TechnicalPublicationPermissions->get($existing[$pubId]);
                    } else {
                        $entity = $this->TechnicalPublicationPermissions->newEmptyEntity();
                        $entity->user_id = $userId;
                        $entity->technical_publication_id = $pubId;
                    }

                    $entity->action_add    = $add;
                    $entity->action_edit   = $edit;
                    $entity->action_view   = $view;
                    $entity->action_delete = $delete;
                    $entity->updated_by    = $this->Authentication->getIdentity()->id ?? null;

                    $this->TechnicalPublicationPermissions->save($entity);

                    unset($existing[$pubId]);
                }

                if (!empty($existing)) {
                    $this->TechnicalPublicationPermissions->deleteAll([
                        'id IN' => $existing
                    ]);
                }

                $this->Flash->success('Permissions updated successfully.');
                return $this->redirect($this->referer());
            }
        }

        public function getUserPermissions($userId = null)
        {
            $this->request->allowMethod(['get']);

            $permissions = $this->TechnicalPublicationPermissions
                ->find()
                ->where(['user_id' => $userId])
                ->all();

            $data = [];

            foreach ($permissions as $perm) {
                $techpubldata = $this->TechnicalPublications->get($perm->technical_publication_id);

                $mainId = $techpubldata->main_page_id;
                
                $data[$mainId][$perm->technical_publication_id] = [
                    'add'    => (bool)$perm->action_add,
                    'edit'   => (bool)$perm->action_edit,
                    'view'   => (bool)$perm->action_view,
                    'delete' => (bool)$perm->action_delete,
                ];
            }

            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['permissions' => $data]));
        }

        public function getPermissionByTechPublId(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post')) {
                    $postData = $this->request->getData();
                    if(!empty($postData['technical_publication_id'])){
                        $permissions = $this->TechnicalPublicationPermissions
                                            ->find()
                                            ->contain(['Users'])
                                            ->where(['technical_publication_id' => $postData['technical_publication_id']])
                                            ->all();

                        $this->set(compact('permissions'));
                        $this->viewBuilder()->setLayout('ajax');
                        $this->render('/element/TechnicalPublications/tech_publ_user_permission');
                    }else{
                        echo 'Failed';die;
                    }
                }else{
                    echo 'Failed';die;
                }
            }
        }

        public function updatePermissions()
        {
            $this->request->allowMethod(['post', 'ajax']);
            $data = $this->request->getData();

            $permission = $this->TechnicalPublicationPermissions->get($data['id']);
            $permission = $this->TechnicalPublicationPermissions->patchEntity($permission, [
                'action_add' => $data['action_add'],
                'action_edit' => $data['action_edit'],
                'action_view' => $data['action_view'],
                'action_delete' => $data['action_delete']
            ]);

            if ($this->TechnicalPublicationPermissions->save($permission)) {
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode(['success' => true]));
            }

            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['success' => false]));
        }

    }
?>