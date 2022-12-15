<?php 
    $currentDateTime = date('Y-m-d');
    $startDate = new \DateTime($currentDateTime);
    $startDate = $startDate->format('Y-m-d');
    $sessionUser = $this->Session->read('Auth.User');
?>
<!-- top tiles -->
<!-- <?php 
    if(!empty($actionItems) || $sessionUser['id'] == 1) {
?>
<div class="row tile_count">
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
        <span class="count_top"><i class="fa fa-user"></i> Total Users</span>
        <div>
            <?php $totalUsers = isset($dashboardData['totalUsers']) ? $dashboardData['totalUsers'] : 0; ?>
            <span class="count" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $totalUsers; ?>"><?php echo $totalUsers; ?></span>
        </div>
        <span class="count_bottom"><i class="green">
            <?php
            $userPercentage = isset($dashboardData['usersPercentage']) ? $dashboardData['usersPercentage'] : 0;
            if ($userPercentage == 0) {
                echo $this->Number->format($userPercentage);
            } else {
                echo $this->Number->precision($userPercentage, 2);
            }
            ?>% </i> From last Week
        </span>
    </div>
</div>
<?php } ?> -->
