<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title></title>
        <style>
            body{font-family: Arial, Helvetica, sans-serif;}
            .top-header { font-size: 13px; font-weight: 600; line-height: 22px; border-bottom: 2px solid #000; padding-bottom: 6px; }
            .main-table { margin-top: 50px;}
            table { width: 100%; }
            .mid-header { font-size: 21px; text-align: center; font-weight: bold; padding-top: 8px; line-height: 26px;}
            .main-td { border-bottom: 4px solid #000; padding-bottom: 15px;}
            .hr-right { padding-right: 59px; }
            .second-row td { padding-top: 10px; }
            .rm-border{ border-bottom: 0px; padding-bottom: 0px; }
            .rs-table { font-size: 12px; margin-top: 30px; width: 100%; margin: auto; }
            .rs_td{ border-bottom: 1px solid #808080; padding: 2px 0px 5px 0px;}
            .rs-table thead td{ border-bottom: 1px solid #808080; padding-bottom: 5px; }
            .rs-table tbody td{ border-bottom: 1px solid #808080; padding: 10px 0px 5px 0px; }
            .date-group-table { font-weight: bold; font-size: 19px; padding: 0px; border-top: 3px solid #000; margin: 10px 0px 5px 0px; }
            .date-group-table span { border-bottom: 2px solid #000; font-style: italic; text-align: center;}
            @media print {body {-webkit-print-color-adjust: exact;}}
            .table-container{width: 100%; padding: 0 15px; margin: 0 auto;}
            .cust-estimates-table{font-family: Arial, Helvetica, sans-serif; border-collapse: collapse; font-size: 12px; line-height: 15px; color: #000; width: 100%;}
            .cust-estimates-table td{padding: 5px 8px; line-height: 22px; vertical-align: top;}
            .cust-estimates-table-body{ border-top: 2px solid #838181 !important; margin-top: 5px; width:100%;}
            .value-table{display: inline-block; width: auto;}
            .td-border{ border: 1px solid #838181 !important;}
            .border-top{ border-top: 1px solid #838181 !important; width:100%;}
            .border-btm-solid{border-bottom: 2px solid #838181 !important;}
            .td-bg{ background-color: #d3d3d3; border-bottom: 1px solid #838181; font-size: 12px;font-weight: bold;}
            .title{font-size: 17px; font-weight: bold;}
            .impInfo{ font-size: 13px; font-weight: bold; padding: 30px 0;}
            .logo{ max-width: 200px; height: auto;}
            .total-amount{ max-width: 200px; margin-right: 0; margin-left: auto;}
            .total-amount *{ display: block; padding: 10px 0; }
            .text-center{ text-align: center;}
            .text-end{text-align: right;}
            p{margin: 0;}
            .pb-0{padding-bottom: 0 !important;}
            .p-0{padding: 0 !important;}
            .ps-0{
                padding-left: 0 !important;
            }
            .pe-0{
                padding-right: 0 !important;
            }
            .pe-1{
                padding-right: 10px !important;
            }
            .p-5{
                padding: 5px !important;
            }
            .mt-0{
                margin-top: 0 !important;
            }
            .p-tb-10{
                padding-top: 10px;
                padding-bottom: 10px;
            }
            .nowrap{
                white-space: nowrap !important;
            }
            .d-block{
                display: block;
            }
            .cust-invoice-table{
                font-family: Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                font-size: 12px;
                line-height: 15px;
                color: #000;
                width: 100%;
            }
            .cust-invoice-table td{
                padding: 5px 8px;
                line-height: 22px;
                vertical-align: top;
            }
            .cust-invoice-table-body{
                font-size: 12px;
                text-align:center;
            }
            .cost-table-data{
                border: 2px solid #000 !important;
                margin-top: 8px;
            }
            .cost-table-data tbody{
                border: 2px solid #000 !important;
            }
            .cost-table-data tbody td{
                padding: 20px;
                font-size: 17px;
            }
            .cost-table-data .value-title{
                font-size: 13px;
                border-bottom: 3px solid #000 !important;
            }
            
            p{
                font-size:12px;
            }
            
            .cust-invoice-td-border{
                border: 1px solid #000 !important;
            }
            .cust-invoice-border-top{
                border-top: 1px solid #000 !important;
            }
            .border-btm{
                border-bottom: 1px solid #000 !important;
            }
            .border-db{
                border: 2px solid #000 !important;
            }
            .border-tb-db{
                border-top: 2px solid #000 !important;
                border-bottom: 2px solid #000 !important;
            }
            .border-top-db{
                border-top: 2px solid #000 !important;
                width:100%;
            }
            .border-btm-db{
                border-bottom: 2px solid #000 !important;
            }
            .border-left{
                border-left: 1px solid #000;
            }
            .bg-gray{
                background-color: #d3d3d3 !important;
            }
            .item-group-table{
                font-family: Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                font-size: 15px !important;
                line-height: 15px;
                color: #000;
                width: 100%;
            }
            .item-group-table th,
            .item-group-table td{
                padding: 5px 8px;
                line-height: 22px;
                vertical-align: top;
            }
            .item-group-table-body{
                margin-top: 8px;
            }
            .headingTitle{
                font-size: 18px;
                font-weight: bold;
            }
            .text-start{
                text-align: left;
            }
            .text-center{
                text-align: center;
            }
            .text-end{
                text-align: right;
            }
            .align-middle{
                vertical-align: middle;
            }
            .table-border th,
            .table-border td{
                border: 1px solid #000;
            }
            .time-table{
                font-family: Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                border: none;
                font-size: 15px;
                line-height: 15px;
                color: #000;
                width: 100%;
            }
            .time-table th,
            .time-table td{
                padding: 5px 8px;
                line-height: 22px;
                vertical-align: top;
            }

            input[type="checkbox"]{
                -webkit-appearance: initial;
                appearance: initial;
                background: gray;
                width: 40px;
                height: 40px;
                border: none;
                position: relative;
            }
            input[type="checkbox"]:checked {
                background: red;
            }
        </style>
    </head>
    <body>
        <?php
            if($report_type == '1'){
                echo $this->element('Inventory/customer_otc/work_order_report/customer_address_report', ['report_type'=>$report_type, 'settings'=>$settings]);
            }else if($report_type >= '5' && $report_type <= '11'){
                echo $this->element('Inventory/customer_otc/work_order_report/customer_estimates', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings]);
            }else if(($report_type >= '12' && $report_type <= '23') || ($report_type >= '46' && $report_type <= '47')){
                echo $this->element('Inventory/customer_otc/work_order_report/customer_invoice', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings]);
            }else if($report_type >= '24' && $report_type <= '27'){
                echo $this->element('Inventory/customer_otc/work_order_report/discrepancy_action_report', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings]);
            }else if($report_type >= '28' && $report_type <= '35'){
                echo $this->element('Inventory/customer_otc/work_order_report/item_by_group', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings]);
            }else if($report_type >= '39' && $report_type <= '40'){
                echo $this->element('Inventory/customer_otc/work_order_report/maintenance_printout', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings, 'report_date'=>$report_date]);
            }else if($report_type == '41'){
                echo $this->element('Inventory/customer_otc/work_order_report/maintenance_printout_bytask', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings]);
            }else if($report_type == '42'){
                echo $this->element('Inventory/customer_otc/work_order_report/maintenance_printout_bytask2', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings]);
            }else if($report_type == '43'){
                echo $this->element('Inventory/customer_otc/work_order_report/osr_profit_report', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings]);
            }else if($report_type >= '48' && $report_type <= '57'){
                if($report_type == '49' || $report_type == '56'){
                    echo $this->element('Inventory/customer_otc/work_order_report/services_list', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings, 'signoff_category'=>$signoff_category]);
                }else{
                    echo $this->element('Inventory/customer_otc/work_order_report/services_list', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings]);
                }
            }else if($report_type == '58'){
                echo $this->element('Inventory/customer_otc/work_order_report/signoffs_list', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings]);
            }else if($report_type >= '59' && $report_type <= '60'){
                echo $this->element('Inventory/customer_otc/work_order_report/technicians_on_items', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings]);
            }else if($report_type >= '61' && $report_type <= '70'){
                echo $this->element('Inventory/customer_otc/work_order_report/time_report', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings]);
            }else if($report_type == '71'){
                echo $this->element('Inventory/customer_otc/work_order_report/warranty_estimates', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings]);
            }else if($report_type >= '72' && $report_type <= '74'){
                echo $this->element('Inventory/customer_otc/work_order_report/warranty_invoice', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings]);
            }else if($report_type == '37'){
                echo $this->element('Inventory/customer_otc/work_order_report/wo_logbook_labels', ['report_type'=>$report_type, 'reportdata'=>$reportdata, 'settings'=>$settings, 'log_book_category'=>$log_book_category, 'postData'=>$postData, 'statementdata'=>$statementdata]);
            }
        ?>
    </body>
</html>