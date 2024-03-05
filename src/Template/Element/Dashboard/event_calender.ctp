<?php
$filterprev = date('Y-m', strtotime(" -1 month", $filter));
$filternext = date('Y-m', strtotime(" +1 month", $filter));
?>
<div class="col-md-12 prevnextbtn">
    <div class="col-md-4 pd0">
        <a href="javascript:void(0);" data-val="<?php echo $filterprev; ?>" class="clknextprevbtn previous">« Previous</a>
    </div>
    <div class="col-md-4 d-flex align-items-center clearfix calendarblock">
        <div class="cheadingblock">
            <div class="d-flex align-items-center cheading">  
                <i class="fa fa-calendar fa-1x cicon"></i>
                <h2 class="month font-weight-bold mb-0 text-uppercase ch2"><?php echo date("F Y", $filter); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 pd0">
        <a href="javascript:void(0);" data-val="<?php echo $filternext; ?>" class="clknextprevbtn next">Next »</a>
    </div>
</div>
<div class="clearboth">
    <ol class="day-names list-unstyled">
        <li class="font-weight-bold text-uppercase">Sun</li>
        <li class="font-weight-bold text-uppercase">Mon</li>
        <li class="font-weight-bold text-uppercase">Tue</li>
        <li class="font-weight-bold text-uppercase">Wed</li>
        <li class="font-weight-bold text-uppercase">Thu</li>
        <li class="font-weight-bold text-uppercase">Fri</li>
        <li class="font-weight-bold text-uppercase">Sat</li>
    </ol>

    <ol class="days list-unstyled">
        <?php 
        $startday = date('N',strtotime(date('Y-m-01', $filter)));
        if($startday < 7){
            for($i = 1; $i<= $startday; $i++){
                echo '<li>
                    <div class="date">&nbsp;</div>
                </li>';
            }
        }
        
        for($i=1; $i<=date("t", $filter); $i++){ 
            $inc = $i<10 ? '0'.$i : $i;
            $date = date("Y-m-$inc", $filter);
            $startday = date('N',strtotime($date));
        ?>
        <li>
            <div class="date" <?php  if($date == date('Y-m-d')){ ?>style="color:#d9534f;"<?php }else if($startday == 6 || $startday == 7){ ?>style="color:#bbb;"<?php } ?>><?php echo $i; ?></div>
            <?php
            if(!empty($dashboardevents[$date])){
                $currentdate = date('Y-m-d');
                foreach($dashboardevents[$date] as $event){
                    $event_start = $event['event_start_date'];
                    $event_end = $event['event_end_date'];

                    if(strtotime($date) >= strtotime($event_start) && strtotime($date) <= strtotime($event_end)){
            ?>
            <div class="event" title="<?php echo $event['event_name']; ?>"><?php echo (strlen($event['event_name']) >= 30) ? substr($event['event_name'], 0, 30).'..' : $event['event_name'];?></div>
            <?php }}} ?>
        </li>
    <?php } ?>
    </ol>
</div>