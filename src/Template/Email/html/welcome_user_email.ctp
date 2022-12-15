<html lang="en">
    <head>
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>
    <body style="margin: 0; padding: 0">
        <table width="100%" style="max-width:650px; margin: 0 auto; font-family: arial,verdana; color: #1c1c1c; box-shadow: 0 0 2px #ddd; font-size: 15px; text-align: center; 
               background: #fff;" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <th style="padding: 0 15px; border-bottom: 4px solid #dddddc;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td style="padding: 10px 0;">
                                <a href="<?php echo $data['url']; ?>" target="_blank" style="display: inline-block">
                                    <?php echo $this->Html->image("tuxedo-logo.png", ['fullBase' => true, 'alt' => 'Tuxedo Air', 'title' => 'Tuxedo Air', 'style' => "max-width: 200px; width: 100%"]); ?>
                                </a>
                            </td>
                            <td style="text-align: right;">
                                <div style="background: #26517b; color: #fff; font-size: 15px; font-weight: bold; display: inline-block; padding: 8px 10px;letter-spacing: 1px; margin-left: 10px;">
                                  918-298-3718
                                </div>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <td style="padding: 20px 5%; text-align: left;">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="font-family: arial, verdana; font-size: 14px">
                                <p style="margin: 0 0 20px 0">Dear <strong><?php echo h($data['name']); ?></strong>,</p>
                                <p style="margin: 0 0 15px 0">Welcome, and thank you for joining Tuxedo Air!</p>
                                <p style="padding: 0 0 5px 0; margin: 10px 0 0 0">Please use following credentials to log-in to the <?php echo h($data['userType']); ?> Portal:</p>
                                <p style="padding: 0 0 5px 0; margin:0">
                                    <strong>URL:</strong> <a href="<?php echo $data['url']; ?>"><?php echo h($data['url']); ?></a>
                                </p>
                                <p style="padding: 0 0 5px 0;margin:0">
                                    <strong>Email:</strong> <?php echo $data['email']; ?>
                                </p>
                                <p style="padding: 0 0 5px 0;margin:0">
                                    <strong>Password:</strong> <?php echo $data['password']; ?>
                                </p>
                                <p style="margin: 15px 0 15px 0">Once you have signed on to the Customer Portal click the button in the upper right with your name and select “Training/info” from the drop-down menu.</p>
                                <p style="margin: 15px 0 15px 0">This will bring up a short video tutorial on how to make a reservation and navigate the site.</p>
                                <p style="margin: 10px 0 15px 0">Enjoy!  And thank you for becoming a Tuxedo Air America member!</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-family: arial, verdana; font-size: 14px">
                                <p style="line-height: 15px; margin-bottom: 12px; padding-top: 15px;">
                                    <p style="padding: 0 0 5px 0; margin:0"><?php echo $data['senderTitle']; ?></p>
                                    <p style="padding: 0 0 5px 0; margin:0"><strong>Email:</strong> <?php echo $data['senderEmail']; ?></p>
                                    <p style="padding: 0 0 5px 0; margin:0"><strong>Phone:</strong> <?php echo $data['senderPhone']; ?></p>
                                    <p style="padding: 0 0 5px 0; margin:0"><strong>Address:</strong> <?php echo $data['senderAddress']; ?></p>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="middle" style="font-size: 12px; color: #646464; background: #f4f4f4; padding: 20px">
                    <div>
                        <a href="https://tuxedoair.com" target="_blank" style="display: inline-block">
                            <?php echo $this->Html->image("tuxedo-logo.png", ['fullBase' => true, 'alt' => 'Tuxedo Air America', 'title' => 'Tuxedo Air America', 'style' => "max-width: 215px; width: 100%; height: auto;"]); ?>
                        </a>
                    </div>
                    <div style="border: 1px solid #000; color: #000; font-size: 18px; font-weight: bold; display: inline-block; padding: 8px 15px; margin-top: 20px;letter-spacing: 1px;">
                        918-298-3718
                    </div>
                </td>
            </tr>
            <tr>
                <td style="background: #1c1c1c; padding: 8px 0;color:#fff;font-size: 12px;">
                    Copyright &copy;<?php echo date('Y'); ?> Tuxedo Air America, LLC.
                </td>
            </tr>
        </table>
    </body>
</html>