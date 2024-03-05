<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Core\App;

class DashboardHelper extends Helper
{
    public function getDashboardEventListHTML($dashboardeventlist){
        $listofeventhtml = '';
        
        foreach($dashboardeventlist as $key=>$dashboardevent){
            $activeclass = '';
            if($key == '0'){
                $activeclass = 'editdashboardevent-active';
            }
            $listofeventhtml .= '<tr class="editdashboardevent '.$activeclass.'" data-val="'.$dashboardevent['id'].'">';
            $listofeventhtml .= '<td>'.$dashboardevent['event_name'].'</td>';
            $listofeventhtml .= '<td>'.$dashboardevent['event_description'].'</td>';
            $listofeventhtml .= '<td>'.$dashboardevent['event_start_date'].'</td>';
            $listofeventhtml .= '<td>'.$dashboardevent['event_end_date'].'</td>';
            $listofeventhtml .= '</tr>';
        }

        return $listofeventhtml;
    }

    public function getNewsFeedListHTML($newsfeedlist){
        $listofnewsfeedhtml = '';
        
        foreach($newsfeedlist as $key=>$newsfeed){
            $activeclass = '';
            if($key == '0'){
                $activeclass = 'editnewsfeed-active';
            }
            $listofnewsfeedhtml .= '<tr class="editnewsfeed '.$activeclass.'" data-val="'.$newsfeed['id'].'">';
            $listofnewsfeedhtml .= '<td>'.$newsfeed['news_feed'].'</td>';
            $listofnewsfeedhtml .= '<td>'.(!empty($newsfeed['status']) ? 'Active' : 'Inactive').'</td>';
            $listofnewsfeedhtml .= '</tr>';
        }

        return $listofnewsfeedhtml;
    }
}

?>