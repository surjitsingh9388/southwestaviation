<?php
$reportHtml = '<table>';
if(!empty($reportdata['customers']['customer_name'])){
    $reportHtml .= '<tr>
                        <td>'.$reportdata['customers']['customer_name'].'</td>
                    </tr>';
}
if(!empty($reportdata['customers']['address'])){
    $reportHtml .= '<tr>
                        <td>'.$reportdata['customers']['address'].'</td>
                    </tr>';
}
if(!empty($reportdata['customers']['address2'])){
    $reportHtml .= '<tr>
                        <td>'.$reportdata['customers']['address2'].'</td>
                    </tr>';
}
$citystatepin = '';
if(!empty($reportdata['customers']['city'])){
    $citystatepin .= $reportdata['customers']['city'];
}
if(!empty($reportdata['customers']['state'])){
    $citystatepin .= !empty($citystatepin) ? ', '.$reportdata['customers']['state'].' ' : $reportdata['customers']['state'].' ';
}
if(!empty($reportdata['customers']['zip'])){
    $citystatepin .= $reportdata['customers']['zip'];
}
if(!empty($citystatepin)){
    $reportHtml .= '<tr>
                        <td>'.$citystatepin.'</td>
                    </tr>';
}
$reportHtml .= '</table>';

echo $reportHtml;

?>