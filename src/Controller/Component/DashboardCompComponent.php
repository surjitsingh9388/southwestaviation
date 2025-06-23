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
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

class DashboardCompComponent extends Component {
    public array $components = ['Authentication.Authentication'];

    protected \App\Model\Table\NewsFeedsTable $NewsFeeds;
    protected \App\Model\Table\EventsTable $Events;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    public function getEventList(){
        $this->Events = $this->getController()->fetchTable('Events');
        $dashboardeventlist = $this->Events->find('all')->select($this->Events);

        return $dashboardeventlist;
    }

    public function getSelectedMonthEventList($month){
        $connection = ConnectionManager::get('default');
        
        $event_date = date('Y-m-01', strtotime($month));

        $dashboardeventlist = $connection
                            ->execute(
                                'SELECT * FROM events WHERE DATE(event_start_date) >= :event_date OR DATE(event_end_date) >= :event_date order by id desc',
                                ['event_date' => $event_date])
                            ->fetchAll('assoc');
        $eventlist = [];
        foreach($dashboardeventlist as $row){
            $currentDate = strtotime($row['event_start_date']);
            $endDate = strtotime($row['event_end_date']);
            
            while ($currentDate <= $endDate) {
                $thisDate = date('Y-m-d', $currentDate); 
                $currentDate = strtotime('+1 day', $currentDate);
                $eventlist[$thisDate][] = $row;
            }
        }
        
        return $eventlist;
    }

    public function getNewsFeedList(){
        $this->NewsFeeds = $this->getController()->fetchTable('NewsFeeds');

        $dashboardnewsfeedlist = $this->NewsFeeds->find('all', ['order'=>'id desc'])->select($this->NewsFeeds);

        return $dashboardnewsfeedlist;
    }

    public function getCurrentNewsFeed(){
        $this->NewsFeeds = $this->getController()->fetchTable('NewsFeeds');

        $dashboardnewsfeedlist = $this->NewsFeeds->find('all', ['order'=>'id desc'])->where(['status'=>'1'])->select($this->NewsFeeds)->first();

        return $dashboardnewsfeedlist;
    }
}

?>