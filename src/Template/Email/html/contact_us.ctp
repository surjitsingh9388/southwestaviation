<html lang="en">
    <head>
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    
    <body style="margin: 0; padding: 0">
        <table width="100%" style="max-width:650px; margin-left: 10px; font-family: arial,verdana; color: #1c1c1c; box-shadow: 0 0 2px #ddd; font-size: 15px; text-align: center;" background: #fff; border="0" cellspacing="0" cellpadding="0">
            <tr>
                <th style="height: 100px; padding: 0 5px; color: #fff" valign="middle">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td style="text-align: center; border-bottom: 4px solid #dddddc; padding: 10px 0;">
                                <a href="<?php echo $data['url']; ?>" target="_blank" style="display: inline-block">
                                    <?php echo $this->Html->image("tuxedo-logo.png", ['fullBase' => true, 'alt' => 'Tuxedo Air', 'title' => 'Tuxedo Air', 'style' => "max-width: 220px;"]); ?> 
                                </a>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <td style="padding: 10px 1%; text-align: left;">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="font-family: arial, verdana; font-size: 14px">
                                <p style="margin: 0 0 20px 0"><strong>Hi Admin</strong></p>
                                <p style="padding: 0 0 5px 25px; margin:0">You have receive a request from contact us page.</p>
                                <p style="padding: 0 0 5px 50px;margin:0">
                                    <strong>Name:</strong> <?php echo $data['name']; ?>
                                </p>
                                <p style="padding: 0 0 5px 50px;margin:0">
                                    <strong>Email:</strong> <?php echo $data['email']; ?>
                                </p>
                                <p style="padding: 0 0 5px 50px;margin:0">
                                    <strong>Message:</strong> <?php echo $data['message']; ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-family: arial, verdana; font-size: 14px">
                                <p style="line-height: 15px; margin-bottom: 12px; padding-top: 15px;">
                                    With Regards,
                                    <p style="padding: 0 0 5px 25px; margin:0"><?php echo $data['senderTitle']; ?></p>
                                    <p style="padding: 0 0 5px 25px; margin:0"><strong>Email:</strong> <?php echo $data['senderEmail']; ?></p>
                                    <p style="padding: 0 0 5px 25px; margin:0"><strong>Phone:</strong> <?php echo $data['senderPhone']; ?></p>
                                    <p style="padding: 0 0 5px 25px; margin:0"><strong>Address:</strong> <?php echo $data['senderAddress']; ?></p>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="background: #1c1c1c; padding: 8px 0;color:#fff;font-size: 12px;">
                    Copyright &copy;<?php echo date('Y'); ?> Tuxedo Air America, LLC
                </td>
            </tr>
        </table>
    </body>
</html>