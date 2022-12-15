<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Database\Expression\QueryExpression;

class CategoryComponent extends Component {
    /**
     * GetCategories method
     * This function is used to get list of all planes.
     *
     * @return array.
     */
    public function getAirCompCats() {
        $airCompCatModel = TableRegistry::get('categories');
        $airCompCats = $airCompCatModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'category_name'
        ))->toArray();
        return $airCompCats;
    }
    
}