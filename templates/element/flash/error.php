<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="clearfix alert alert-danger flash-message content sliding">
    <?php echo __($message); ?>
    <a href="#" class="close" onclick="$(this).parent().fadeOut();return false;">&times;</a>
</div>
<script>
$(document).ready(function () {
    setTimeout(function () {
        $('.flash-message').fadeOut('slow', function () {
            $('.outerWrapper').parent().addClass('left');
        });
    }, 1000);
});

</script>