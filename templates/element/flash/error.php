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
    const isDesktop = window.matchMedia("(min-width: 769px)").matches;

    if (!isDesktop) {
        setTimeout(function () {
            $('.flash-message').fadeOut('slow', function () {
                $('.outerWrapper').parent().addClass('left');
            });
        }, 1000);
    }
});

</script>