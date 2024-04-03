<section class="top-form-section">
    <div class="row">
        <div class="col-xs-12 wo_item_history_scroll">
            <table class="table invitmhistory">
                <thead class="thead-dark">
                    <tr>
                        <th class="col-sm-2">Date</th>
                        <th class="col-sm-2">User</th>
                        <th class="col-sm-2">Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($woitemhistorylist as $itemhistory){
                        $data = @unserialize($itemhistory['description']); 
                        if ($data === false) {
                            $data = $itemhistory['description'];
                        }
                    ?>
                    <tr>
                        <td><?php echo date('d-M-Y h:i A', strtotime($itemhistory['created'])); ?></td>
                        <td><?php echo $itemhistory['users']['email']; ?></td>
                        <td>
                            <a class="toggleplusminus_aircraft"><i class="fa fa-plus"></i></a>
                            <?php echo $itemhistory['title']; ?>
                            <pre class="history-detail-block hide-block"><?php echo $data;?>
                            </pre>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>