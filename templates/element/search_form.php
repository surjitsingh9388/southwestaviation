<?php
$placeholder = 'Search Maintenance Items';
if($title == 'custom') {
	$placeholder = 'Search Aircraft';
}
?>
<div class="input-group search-control mb-0">
    <input id="searchItem" type="text" class="form-control" placeholder="<?php echo $placeholder; ?>">
    <div class="input-group-btn">
        <button class="btn btn-default" type="submit">
            <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img')); ?>
        </button>
    </div>
</div>