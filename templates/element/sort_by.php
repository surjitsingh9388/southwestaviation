<div class="inputWrap btn-group sortWrap">
    <div class="sortTxt">Sort By</div>
    <?php
    if($title == 'custom') {
    ?>
    <select class="selectpicker" id="sortById">
        <option>Aircraft</option>
        <option>Reported Date</option>
        <option>Reported Hours</option>
        <option>Reported Landings</option>
        <option>Status</option>
    </select>
    <?php
    } else {
    ?>
    <select class="selectpicker actionCls" id="sortById">
        <option>Aircraft</option>
        <option value="atacode">ATA Code</option>
        <option value="description">Description</option>
        <option value="lastcomplied">Last Complied With</option>
        <option value="intervals">Intervals</option>
        <option value="nextDue">Next Due</option>
        <option value="remaining">Remaining</option>
        <option value="status">Due Status</option>
    </select>
    <?php
    }
    ?>
</div>