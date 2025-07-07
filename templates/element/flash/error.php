<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="clearfix alert alert-danger content sliding flash-message">
    <?php echo __($message); ?>
    <a href="javascript:void(0);" class="close" onclick="$(this).parent().fadeOut();return false;">&times;</a>
</div>

<script>
$(document).ready(function () {
    setTimeout(function () {
        $('.flash-message').fadeOut('slow', function () {
            $('.outerWrapper').parent().addClass('left');
            $(this).remove();
        });
    }, 1000);
});
</script>
