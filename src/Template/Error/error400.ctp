<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->layout = 'error';
?>

<div class="col-md-12">
    <div class="col-middle">
        <div class="text-center text-center">
            <h1 class="error-number">404</h1>
            <h2>Sorry but we couldn't find this page</h2>
            <p>This page you are looking for does not exist.
            </p>
            <div class="mid_center">
                <div class="form-group">
                <?php
                    echo $this->Html->link(__('Back'), 'javascript:history.back()', ['class' => 'btn btn-info', 'escape' => false]);
                    //$prefixArr = array('admin', 'pilots');
                    $prefix = $this->request->getParam('prefix');
                    //check prefix in prefix arrays
                    if ($prefix == 'admin') {
                        $url = '/'.$prefix;
                    } else if ($prefix == 'pilots') {
                        $url = '/'.$prefix.'/pilots/dashboard';
                    } else {
                       $url = '/'; 
                    }
                    echo $this->Html->link("Go To Home Page", $url, ['class' => 'btn btn-success', 'escape' => false]);
                ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php /*
if (Configure::read('debug')) :
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error400.ctp');

    $this->start('file');
?>
<?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong>SQL Query: </strong>
        <?= h($error->queryString) ?>
    </p>
<?php endif; ?>
<?php if (!empty($error->params)) : ?>
        <strong>SQL Query Params: </strong>
        <?php Debugger::dump($error->params) ?>
<?php endif; ?>
<?= $this->element('auto_table_warning') ?>
<?php
if (extension_loaded('xdebug')) :
    xdebug_print_function_stack();
endif;

$this->end();
endif;
?>
<h2><?= h($message) ?></h2>
<p class="error">
    <strong><?= __d('cake', 'Error') ?>: </strong>
    <?= __d('cake', 'The requested address {0} was not found on this server.', "<strong>'{$url}'</strong>") ?>
</p>

*/ ?>