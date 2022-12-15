<?php 
exec('git describe --always', $version_mini_hash);
$version = isset($version_mini_hash[0]) ? "v." . $version_mini_hash[0] : '';
?>
<footer>
    <div class="pull-right">
        Copyright ©<?php echo date('Y'); ?> Aircraft
    </div>
    <div class="clearfix"></div>
</footer>