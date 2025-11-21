<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/*
 * Configure paths required to find CakePHP + general filepath constants
 */
require __DIR__ . DIRECTORY_SEPARATOR . 'paths.php';

/*
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorTrap;
use Cake\Error\ExceptionTrap;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Cake\Database\TypeFactory;

/**
 * Load global functions.
 */
require CAKE . 'functions.php';

/*
 * See https://github.com/josegonzalez/php-dotenv for API details.
 *
 * Uncomment block of code below if you want to use `.env` file during development.
 * You should copy `config/.env.example` to `config/.env` and set/modify the
 * variables as required.
 *
 * The purpose of the .env file is to emulate the presence of the environment
 * variables like they would be present in production.
 *
 * If you use .env files, be careful to not commit them to source control to avoid
 * security risks. See https://github.com/josegonzalez/php-dotenv#general-security-information
 * for more information for recommended practices.
*/
 if (!env('APP_NAME') && file_exists(CONFIG . '.env')) {
     $dotenv = new \josegonzalez\Dotenv\Loader([CONFIG . '.env']);
     $dotenv->parse()
         ->putenv()
         ->toEnv()
         ->toServer();
}

/*
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

/*
 * Load an environment local configuration file to provide overrides to your configuration.
 * Notice: For security reasons app_local.php **should not** be included in your git repo.
 */
if (file_exists(CONFIG . 'app_local.php')) {
    Configure::load('app_local', 'default');
}

/*
 * When debug = true the metadata cache should only last
 * for a short time.
 */
if (Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+2 minutes');
    Configure::write('Cache._cake_translations_.duration', '+2 minutes');
    // disable router cache during development
    Configure::write('Cache._cake_routes_.duration', '+2 seconds');
}

/*
 * Set the default server timezone. Using UTC makes time calculations / conversions easier.
 * Check https://php.net/manual/en/timezones.php for list of valid timezone strings.
 */
date_default_timezone_set(Configure::read('App.defaultTimezone'));

/*
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/*
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

/*
 * Register application error and exception handlers.
 */
(new ErrorTrap(Configure::read('Error')))->register();
(new ExceptionTrap(Configure::read('Error')))->register();

/*
 * Include the CLI bootstrap overrides.
 */
if (PHP_SAPI === 'cli') {
    require CONFIG . 'bootstrap_cli.php';
}

/*
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 */
$fullBaseUrl = Configure::read('App.fullBaseUrl');
if (!$fullBaseUrl) {
    /*
     * When using proxies or load balancers, SSL/TLS connections might
     * get terminated before reaching the server. If you trust the proxy,
     * you can enable `$trustProxy` to rely on the `X-Forwarded-Proto`
     * header to determine whether to generate URLs using `https`.
     *
     * See also https://book.cakephp.org/5/en/controllers/request-response.html#trusting-proxy-headers
     */
    $trustProxy = false;

    $s = null;
    if (env('HTTPS') || ($trustProxy && env('HTTP_X_FORWARDED_PROTO') === 'https')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if ($httpHost) {
        $fullBaseUrl = 'http' . $s . '://' . $httpHost;
    }
    unset($httpHost, $s);
}
if ($fullBaseUrl) {
    Router::fullBaseUrl($fullBaseUrl);
}
unset($fullBaseUrl);

Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
TransportFactory::setConfig(Configure::consume('EmailTransport'));
Mailer::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));

/*
 * Setup detectors for mobile and tablet.
 * If you don't use these checks you can safely remove this code
 * and the mobiledetect package from composer.json.
 */
ServerRequest::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isMobile();
});
ServerRequest::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isTablet();
});

TypeFactory::build('date')->useLocaleParser()->setLocaleFormat('MM-dd-yyyy');
TypeFactory::build('datetime')->useLocaleParser()->setLocaleFormat('MM-dd-yyyy');
//TypeFactory::build('datetime')->useLocaleParser()->setLocaleFormat('MM/dd/yyyy');
/*
 * You can enable default locale format parsing by adding calls
 * to `useLocaleParser()`. This enables the automatic conversion of
 * locale specific date formats. For details see
 * @link https://book.cakephp.org/5/en/core-libraries/internationalization-and-localization.html#parsing-localized-datetime-data
 */
 /*TypeFactory::build('time')
    ->useLocaleParser();
 TypeFactory::build('date')
    ->useLocaleParser();
 TypeFactory::build('datetime')
    ->useLocaleParser();
 TypeFactory::build('timestamp')
    ->useLocaleParser();
TypeFactory::build('datetimefractional')
->useLocaleParser();
TypeFactory::build('timestampfractional')
->useLocaleParser();
TypeFactory::build('datetimetimezone')
->useLocaleParser();
TypeFactory::build('timestamptimezone')
->useLocaleParser();*/

/*
 * Custom Inflector rules, can be set to correctly pluralize or singularize
 * table, model, controller names or whatever other string is passed to the
 * inflection functions.
 */
//Inflector::rules('plural', ['/^(inflect)or$/i' => '\1ables']);
//Inflector::rules('irregular', ['red' => 'redlings']);
//Inflector::rules('uninflected', ['dontinflectme']);

// set a custom date and time format
// see https://book.cakephp.org/5/en/core-libraries/time.html#setting-the-default-locale-and-format-string
// and https://unicode-org.github.io/icu/userguide/format_parse/datetime/#datetime-format-syntax
//\Cake\I18n\Date::setToStringFormat('dd.MM.yyyy');
//\Cake\I18n\Time::setToStringFormat('dd.MM.yyyy HH:mm');

/*Constants used in application*/
define("ADMIN_EMAIL", env('ADMIN_EMAIL', null));

/*Constants used in welcome user email*/
define("ADMIN_SENDER_EMAIL", env('ADMIN_SENDER_EMAIL', null));
define("ADMIN_SENDER_NAME", env('ADMIN_SENDER_NAME', null));
define("ADMIN_SENDER_PHONE", env('ADMIN_SENDER_PHONE', null));
define("ADMIN_SENDER_ADDRESS", env('ADMIN_SENDER_ADDRESS', null));
// set variable for subscription renew alert time
define('TICKET_NO', 150000);
define('INVOICE_NO', 8000000);

define('PAGINATION_LIMIT', 25);
define('FORGOT_PASSWORD_EMAIL_SEND_SUCCESSFULLY', 'You password reset details successfully sent to your email address, if you do not see it, please check your junk mail or contact '. env('ADMIN_SENDER_EMAIL').'.');

#Role Names
define("ROLE_ADMIN", env('ROLE_ADMIN'));
#define("ROLE_CORPORATE_ADMIN", env('ROLE_CORPORATE_ADMIN'));
#define("ROLE_CORPORATE_USER", env('ROLE_CORPORATE_USER'));
#define("ROLE_INDIVIDUAL", env('ROLE_INDIVIDUAL'));

define("SUBMITING", env('SUBMITING'));

define('UNAUTHORIZED_ADMIN_USER', 'You need Admin Role to access the Admin Portal, please call us at +1-918-298-3718 or write to us at '. env('ADMIN_SENDER_EMAIL').'.');
define('UNAUTHORIZED_PILOT_USER', 'You need Pilot Role to access the Pilot Portal, please call us at +1-918-298-3718 or write to us at '. env('ADMIN_SENDER_EMAIL').'.');

//bcc email list for all outgoing emails
define('BCC_LIST_FOR_ALL_OUTGOING_MAILS', env('BCC_LIST_FOR_ALL_OUTGOING_MAILS'));

//Page size for US
define('PAGE_SIZE', env('PAGE_SIZE'));

define('RESTRICTED_EVONTECH_ADMIN_FUNCTIONALITIES', env('RESTRICTED_EVONTECH_ADMIN_FUNCTIONALITIES'));
define('EVONTECH_ADMIN_EMAIL', env('EVONTECH_ADMIN_EMAIL'));
define('RESTRICTION_MESSAGE', env('RESTRICTION_MESSAGE'));

define('APPLICATION_DEPLOYED_ON_SERVER', env('APPLICATION_DEPLOYED_ON_SERVER'));
define('BCC_LIST_FOR_USER_WELCOME_EMAIL', env('BCC_LIST_FOR_USER_WELCOME_EMAIL'));

define('ROLE_PILOTS', env('ROLE_PILOTS'));
define('PILOTS_PREFIX', env('PILOTS_PREFIX'));
define('PILOTS_ID', env('PILOTS_ID'));

//Role ID to give permissions
define('PERMISSION_ROLE_ID', env('PERMISSION_ROLE_ID'));
define('PILOT_ROLE_ID', env('PILOT_ROLE_ID'));

#Full permission access User ID
define('FULL_PERMISSION_ACCESS_ID', env('FULL_PERMISSION_ACCESS_ID'));
#Captcha site key
define('CAPTCHA_SECRET_KEY', env('CAPTCHA_SECRET_KEY'));
define('CAPTCHA_SITE_KEY', env('CAPTCHA_SITE_KEY'));

define('CC_LIST_FOR_USER_WELCOME_EMAIL', env('CC_LIST_FOR_USER_WELCOME_EMAIL'));
define('CC_LIST_FOR_UPDATE_BOOKING_EMAIL', env('CC_LIST_FOR_UPDATE_BOOKING_EMAIL'));
define('ROLE_PERMISSION_CONFIRM_MESSAGE', getenv('ROLE_PERMISSION_CONFIRM_MESSAGE'));

$partclassification = ['1'=>'Standard', '2'=>'Serialized'];
define('PARTS_CLASSIFICATION', serialize($partclassification));

$invItemType = ['1'=>'Consumable', '2'=>'Expandable', '3'=>'Rotable', '4'=>'Tool'];
define('INVENTORY_ITEM_TYPE', serialize($invItemType));

$defaultUOM = ['1'=>'Each', '2'=>'LBS', '3'=>'FT', '4'=>'IN', '5'=>'Quarts', '6'=>'Gallons'];
define('DEFAULT_UOM', serialize($defaultUOM));

$inventoryStatus = ['1'=>'Active', '11'=>'Requested', '3'=>'Consumed', '5'=>'Damaged', '4'=>'Discarded', '14'=>'Inactive', '2'=>'Installed', '9'=>'In-Transit', '13'=>'Needs Repair', '7'=>'Out for Repair', '12'=>'Quarantine', '10'=>'Shipped', '6'=>'Unavailable', '8'=>'Unrepairable'];
define('INVENTORY_STATUS', serialize($inventoryStatus));

$currency = [1=>"USD"];
define('CURRENCY', serialize($currency));

//$invConditions = ['1'=>'Altered', '2'=>'Inspected', '3'=>'Modified', '4'=>'New', '5'=>'Other / Not Specified', '6'=>'Overhauled', '7'=>'Rebuilt', '8'=>'Repaired', '9'=>'Serviced'];
$invConditions = ['1'=>'AR', '2'=>'BER', '3'=>'FN', '4'=>'NE', '5'=>'NS', '6'=>'OH', '7'=>'RD', '8'=>'RJ', '9'=>'RP', '10'=>'RP/Core', '11'=>'SV'];
define('INVENTORY_CONDITION', serialize($invConditions));

$invLocationStatus = ['1'=>'Normal', '2'=>'Offsite', '3'=>'Quarantine'];
define('INVENTORY_LOCATION_STATUS', serialize($invLocationStatus));

$urgency = ['1'=>'AOG', '2'=>'Urgent', '3'=>'Medium', '4'=>'Low'];
define('URGENCY', serialize($urgency));

$inventoryrequeststatus = ['1'=>'Approved', '2'=>'Canceled', '4'=>'Closed', '3'=>'Denied', '0'=>'Pending Review', '5'=>'PO Created', '6'=>'Ordered', '7'=>'Received', '8'=>'On Hold'];
define('INVENTORY_REQUEST_STATUS', serialize($inventoryrequeststatus));

$invPurchaseOrderType = ['1'=>'Standard', '2'=>'Exchange'];
define('INVENTORY_PURCHASE_TYPE', serialize($invPurchaseOrderType));

$invPurchaseOrderStatus = ['3'=>'Canceled', '4'=>'Closed', '0'=>'Draft', '1'=>'Partial Received', '2'=>'Send to Vendor'];
define('INVENTORY_PURCHASE_STATUS', serialize($invPurchaseOrderStatus));

$invPOExchangeStatus = ['1'=>'None', '2'=>'Open', '3'=>'Returned'];
define('INVENTORY_PURCHASE_EXCHANGE_STATUS', serialize($invPOExchangeStatus));

$shipVia = ['1'=>'DHL', '2'=>'FedEx Early AM', '3'=>'UPS', '4'=>'USPS'];
define('SHIP_VIA', serialize($shipVia));

$shipViaTrackURL = ['1'=>'http://www.dhl.com/en/express/tracking.html?AWB=', '2'=>'https://www.fedex.com/fedextrack/?action=track&trackingnumber=', '3'=>'https://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=', '4'=>'https://tools.usps.com/go/TrackConfirmAction_input?qtc_tLabels1='];
define('SHIP_VIA_TRACKING_URL', serialize($shipViaTrackURL));

$repairReceivedStatus = ['1'=>'Repaired', '2'=>'Replaced', '3'=>'Unrepairable'];
define('REPAIR_RECEIVED_STATUS', serialize($repairReceivedStatus));

$repairOrderStatus = ['0'=>'Draft', '1'=>'Partial Received', '2'=>'Shipped To Vendor', '3'=>'Canceled', '4'=>'Closed', '5'=>'Shipped from Vendor'];
define('REPAIR_ORDER_STATUS', serialize($repairOrderStatus));

$shippingOrderStatus = ['0'=>'Draft', '1'=>'Partial Received', '2'=>'Shipped', '3'=>'Canceled', '4'=>'Received', '5'=>'Pending Shipment'];
define('SHIPPING_ORDER_STATUS', serialize($shippingOrderStatus));

$shippingOrderDestination = ['1'=>'Internal', '2'=>'Vendor', '3'=>'Third Party'];
define('SHIPPING_ORDER_DESTINATION', serialize($shippingOrderDestination));

$transactionactionlist = [
    ['id'=>'1', 'name'=>'Activated From Equipment'],
    ['id'=>'2', 'name'=>'Adjust'],
    ['id'=>'3', 'name'=>'Allocate'],
    ['id'=>'4', 'name'=>'Cancel'],
    ['id'=>'5', 'name'=>'Capital Change'],
    ['id'=>'6', 'name'=>'Consumed'],
    ['id'=>'7', 'name'=>'Control Change'],
    ['id'=>'8', 'name'=>'Cost Adjustment'],
    ['id'=>'9', 'name'=>'Deactivated From Equipment'],
    ['id'=>'10', 'name'=>'Deallocate'],
    ['id'=>'11', 'name'=>'Dispose'],
    ['id'=>'12', 'name'=>'Division Change'],
    ['id'=>'13', 'name'=>'Error Correct'],
    ['id'=>'14', 'name'=>'Exchange Cost Adjustment'],
    ['id'=>'15', 'name'=>'Exchange Price Change'],
    ['id'=>'16', 'name'=>'Exchange Receive'],
    ['id'=>'17', 'name'=>'Import'],
    ['id'=>'18', 'name'=>'Install'],
    ['id'=>'19', 'name'=>'Install (Historical)'],
    ['id'=>'20', 'name'=>'Internal Ship'],
    ['id'=>'21', 'name'=>'Manual Entry'],
    ['id'=>'22', 'name'=>'Part Type Change'],
    ['id'=>'23', 'name'=>'Price Change'],
    ['id'=>'24', 'name'=>'Quarantined'],
    ['id'=>'25', 'name'=>'Receive'],
    ['id'=>'26', 'name'=>'Shipped'],
    ['id'=>'27', 'name'=>'Transfer'],
    ['id'=>'28', 'name'=>'Uninstall'],
    ['id'=>'29', 'name'=>'Uninstall (Historical)'],
    ['id'=>'30', 'name'=>'Unquarantined']
];

define('TRANSACTION_ACTION_LIST', serialize($transactionactionlist));

//sms send credential

define('TWILIO_ACCOUNT_SID', 'AC84a7787cd25a51be3e361e61afcc73be');
define('TWILIO_AUTH_TOKEN', '4929379927bc0ed5fe97fc99800808fa');
define('TWILIO_PHONE_NUMBER', '+18556435694');
define('OTP_MESSAGE_TEXT', 'You opt code for reset password is ');

$customerterms = array('1'=>'ACH', '2'=>'COD', '3'=>'Credit Card', '4'=>'NET 30', '5'=>'NET 45', '6'=>'NET 60', '7'=>'WIRE');
define('CUSTOMERTERMS', serialize($customerterms));

$currencyoverride = array('1'=>'Dollor USD');
define('CURRENCYOVERRIDE', serialize($currencyoverride));

$defaultPaymentMethod = array('1'=>'Credit Card', '2'=>'Cash', '3'=>'COD', '4'=>'Account');
define('DEFAULTPAYMENTMETHOD', serialize($defaultPaymentMethod));

$customerOTCClass = array('1'=>'Charter', '2'=>'Maintenance');
define('CUSTOMEROTCCLASS', serialize($customerOTCClass));

$typeOfOTCCreditCard = array('1'=>'American Express', '2'=>'Discover', '3'=>'Master Card', '4'=>'Visa', '5'=>'Wire', '6'=>'Cheque');
define('TYPEOFOTCCREDITCARD', serialize($typeOfOTCCreditCard));

$defaultOTCShippingMethod = array('1'=>'FedEx 2-Day', '2'=>'FedEx Freight', '3'=>'FedEx Ground', '4'=>'FedEx Overnight P1', '5'=>'Local Delivery', '6'=>'Pick Up', '7'=>'UPS 3-day Select', '8'=>'UPS Blue', '9'=>'UPS Ground', '10'=>'UPS Red', '11'=>'UPS Server', '12'=>'USPS', '13'=>'USPS Express', '14'=>'USPS First Class', '15'=>'USPS Priority');
define('DEFAULTOTCSHIPPINGMETHOD', serialize($defaultOTCShippingMethod));

$OTCInvoiceStatus = array('1'=>'Open', '2'=>'Waiting For Parts', '3'=>'Review', '4'=>'Processed', '5'=>'Void');
define('OTCINVOICESTATUS', serialize($OTCInvoiceStatus));

$aircraftEngineType = ['1'=>'Single', '2'=>'Twin', '3'=>'Turbine', '4'=>'Jet', '5'=>'Helicopter'];
define('AIRCRAFTENGINETYPE', serialize($aircraftEngineType));

$aircraftRate = ['1'=>'Avionics', '2'=>'Mechanic'];
define('AIRCRAFTRATE', serialize($aircraftRate));

$contractRateDepartment = ['1'=>'100-Maintenance', '2'=>'200-Avionic', '3'=>'300-Paint', '4'=>'400-Interior', '5'=>'500-Outside Sale', '6'=>'600-Brazos', '7'=>'Airframe', '8'=>'Engine', '9'=>'Flights', '10'=>'SHOP'];
define('CONTRACT_RATE_DEPARTMENT', serialize($contractRateDepartment));

$aircraftWOStatus = ['1'=>'Open', '2'=>'Pending', '3'=>'Review', '4'=>'Billing', '5'=>'Tracking', '6'=>'Completed', '7'=>'Void'];
define('AIRCRAFT_WORKORDER_STATUS', serialize($aircraftWOStatus));

$aircraftWOItemStatus = ['1'=>'Open', '2'=>'Insp. Required', '3'=>'Finished', '4'=>'Rejected', '5'=>'Continued'];
define('AIRCRAFT_WORKORDER_ITEMSTATUS', serialize($aircraftWOItemStatus));

$aircraftWOCategory = ['1'=>'100-Maintenance', '2'=>'200-Avionics', '3'=>'300-Paint', '4'=>'Interior', '5'=>'Brazos', '6'=>'Airframe Recommended', '7'=>'Airworthy Airframe', '8'=>'Airworthy Appliance', '9'=>'Airworthy Engine', '10'=>'Airworthy Prop', '11'=>'Avionics Estimate', '12'=>'Avionics Recommended', '13'=>'Customer Recommended', '14'=>'Customer Rejected', '15'=>'Engine Recommended', '16'=>'GENERAL', '17'=>'Hidden Damage Inspection', '18'=>'Outside Labor', '19'=>'Pitot Static', '20'=>'RENTAL', '21'=>'REPAIR', '22'=>'SHOP', '23'=>'Shop Order', '24'=>'STOCK'];
define('AIRCRAFT_WORKORDER_CATEGORY', serialize($aircraftWOCategory));


$aircraftWOLogBookCategory = ['1'=>'Airframe', '2'=>'Avionics', '3'=>'Engine 1', '7'=>'Engine 2', '4'=>'Engine 1 (And Airframe)', '8'=>'Engine 2 (And Airframe)', '5'=>'Prop 1', '9'=>'Prop 2', '6'=>'Prop 1 (And Airframe)', '10'=>'Prop 2 (And Airframe)'];
define('AIRCRAFT_WORKORDER_LOGBOOK_CATEGORY', serialize($aircraftWOLogBookCategory));

$aircraftWOWayofBilling = ['1'=>'Hourly', '2'=>'Flat', '3'=>'No Charge'];
define('AIRCRAFT_WORKORDER_WAYOF_BILLING', serialize($aircraftWOWayofBilling));

$outsideRepairInfoCond = ['1'=>'1', '2'=>'18', '3'=>'9', '4'=>'AE', '5'=>'AR', '6'=>'As Removed', '7'=>'FN', '8'=>'N', '9'=>'N/E', '10'=>'NE', '11'=>'New', '12'=>'NS', '13'=>'OH', '14'=>'Overhouled', '15'=>'RD', '16'=>'RJ', '17'=>'RP', '18'=>'SV', '19'=>'VLM2-207 (C)', '20'=>'Yellow Tagged'];
define('OUTSIDE_REPAIR_INFO_CONDITION', serialize($outsideRepairInfoCond));

$aircraftComplianceType = ['1'=>'SL', '2'=>'OH', '3'=>'OC'];
define('AIRCRAFT_COMPLIANCE_TYPE', serialize($aircraftComplianceType));

$woOSRVendorClass = ['1'=>'Avionics', '2'=>'OSR', '3'=>'Parts'];
define('WO_OSR_VENDOR_CLASS', serialize($woOSRVendorClass));

$defaultShipTo = ['1'=>'Billing Address', '2'=>'Shipping Address', '3'=>'Other'];
define('DEFAULT_SHIP_TO', serialize($defaultShipTo));

//Aircrft work order osr purchase order status
$aircraftWOOSRPOStatus = ['1'=>'Open', '2'=>'Approval Needed', '3'=>'Approved', '4'=>'Ordered', '5'=>'Received', '6'=>'Processing', '7'=>'Completed', '8'=>'Return', '9'=>'Void'];
define('AIRCRAFT_WO_OSR_PO_STATUS', serialize($aircraftWOOSRPOStatus));

$aircraftWOOSRPOInitials = ['1'=>'AC', '2'=>'DCG', '3'=>'JM', '4'=>'KA', '5'=>'KC', '6'=>'LC', '7'=>'part', '8'=>'RSL'];
define('AIRCRAFT_WO_OSR_PO_INITIALS', serialize($aircraftWOOSRPOInitials));

$woPaymentMethod = array('1'=>'Cash', '2'=>'Check', '3'=>'Credit Card', '4'=>'Other');
define('WOPAYMENTMETHOD', serialize($woPaymentMethod));

$woOptionMiscChargeMethod = array('1'=>'Manual', '2'=>'% Labor');
define('WOOPTIONMISCCHARGEMETHOD', serialize($woOptionMiscChargeMethod));

$woOptionPercentageOfLabor = array('1'=>'Use Standard %', '2'=>'Use Fluctuating %', '3'=>'Use Dual Rate %');
define('WOOPTIONPERCENTAGEOFLABOR', serialize($woOptionPercentageOfLabor));

$woOptTaxMethod = ['1'=>'Standard', '2'=>'Fluctating', '3'=>'Dual Rate'];
define('WOOPTIONTAXMETHOD', serialize($woOptTaxMethod));

$billingRateMethod = ['1'=>'Technician Rate', '2'=>'Aircraft Rate'];
define('WOOPTIONBILLINGRATEMETHOD', serialize($billingRateMethod));

$woOptBillingType = ['1'=>'QuickBooks'];
define('WOOPTIONBILLINGTYPE', serialize($woOptBillingType));

$woOptAircraftMethod = ['1'=>'Avionics', '2'=>'Mechanic'];
define('WOOPTIONAIRCRAFTMETHOD', serialize($woOptAircraftMethod));

$woItemOverviewWarranty = ['1'=>'Garmin', '2'=>'Piaggio America'];
define('WOITEMOVERVIEWWARRANTY', serialize($woItemOverviewWarranty));

$woItemSignOff = ['1'=>'Final Inspection', '2'=>'Function Check', '3'=>'Function Check as applicable', '4'=>'Hidden Damage', '5'=>'Hidden Damage as applicable', '6'=>'In Process', '7'=>'In Process as applicable', '8'=>'Inspector Code', '10'=>'Prelim Inspection', '11'=>'RII', '13'=>'Technician 01', '14'=>'Technician 02', '15'=>'Technician 03', '16'=>'Technician 04', '17'=>'Technician 05', '18'=>'Technician 06', '19'=>'Technician 07', '20'=>'Technician 08', '21'=>'Technician 09', '22'=>'Technician 10'];
define('WOITEMSIGNOFF', serialize($woItemSignOff));

$toolCertification = ['1'=>'Certified', '2'=>'Reference Only'];
define('TOOLCERTIFICATION', serialize($toolCertification));

$calibrationStatus = ['1'=>'In Service', '2'=>'Quarantined', '3'=>'Out for Calibration'];
define('CALIBRATIONSTATUS', serialize($calibrationStatus));

$estimated_rate = '135.00';
define('ESTIMATEDRATE', $estimated_rate);

$technicianBillingStyle = ['1'=>'Standard Rate', '2'=>'Override Rate an Hour', '3'=>'Flat Technician Amount'];
define('TECHNICIANBILLINGSTYLE', serialize($technicianBillingStyle));

$repairOrderRates = ['1'=>'Maintenance'];
define('REPAIRORDERRATES', serialize($repairOrderRates));

$woReportOption = ['1'=>'Address Envelope Insert', '2'=>'Avionic Estimate', '3'=>'Core Invoice', '4'=>'Core (Credit) Invoice', '5'=>'Customer Estimate', '6'=>'Customer Estimate (Hide OSR)', '7'=>'Customer Estimate (No Grouping)', '8'=>'Customer Estimate (No Part Numbers)', '9'=>'Customer Estimate (Show Notes)', '10'=>'Customer Estimate (Sort by Category)', '11'=>'Customer Estimate (Specific Category)', '12'=>'Customer Invoice', '13'=>'Customer Invoice (Hide All Items)', '14'=>'Customer Invoice (Hide All Items & Top Total)', '15'=>'Customer Invoice (Hide OSR)', '16'=>'Customer Invoice (Hide Parts)', '17'=>'Customer Invoice (Hide Top Total Amount)', '18'=>'Customer Invoice (No Amounts)', '19'=>'Customer Invoice (No Amounts, No Times)', '20'=>'Customer Invoice (No Grouping)', '21'=>'Customer Invoice (Show Notes)', '22'=>'Customer Invoice (Sort by Category)', '24'=>'Discrepancy Action Report', '25'=>'Discrepancy Action Report (Show Notes)', '26'=>'Discrepancy List', '27'=>'Discrepancy List (No Page Breaks)', '28'=>'Items By Group (All Items)', '29'=>'Items By Group (All Items - Hide Details)', '30'=>'Items By Group (One Group)', '31'=>'Items By Group (One Group - Hide Details)', '32'=>'Items By Group (One Group)', '33'=>'Items By Group (One Group - Hide Details)', '34'=>'Items By Group (Multiple Groups)', '35'=>'Items By Group (Multiple Groups - Hide Details)', '36'=>'Invoice Adjustment', '37'=>'Log Book Labels', '38'=>'Log Book Labels - Export', '39'=>'Maintenance Printout', '40'=>'Maintenance Printout #', '41'=>'Maintenance Printout (By Task)', '42'=>'Maintenance Printout (By Task #)', '43'=>'OSR Profit Report', '44'=>'OSR Repair Report', '45'=>'Pitot Static Invoice', '46'=>'Non-Rejected Items Only', '47'=>'Rejected Items Only', '48'=>'Service List', '49'=>'Service List (All Items, Particular Signoff)', '50'=>'Service List (By Category)', '51'=>'Service List (By Grouping)', '52'=>'Service List (Department Only)', '53'=>'Service List (Item Status)', '54'=>'Service List (Item Status - Hide Details)', '55'=>'Service List (Open Only)', '56'=>'Service List (Missing Sign-off)', '57'=>'Service List (Requires RII Only)', '58'=>'Signoffs', '59'=>'Technicians on Items', '60'=>'Technicians on Items #', '61'=>'Time Report (All)', '62'=>'Time Report (Cost)', '63'=>'Time Report (Item Adjustment)', '64'=>'Time Report (Item, All Tech Logs)', '65'=>'Time Report (Item, All Tech Logs) Export', '66'=>'Time Report (Item,All Tech Logs, With Notes)', '67'=>'Time Report (Item #, All Tech Logs)', '68'=>'Time Report (Overrun)', '69'=>'Time Report (Technician)', '70'=>'Time Report (Technician, By Date)', '71'=>'Warranty Estimate', '72'=>'Warranty Invoice', '73'=>'Warranty Invoice (No Grouping)', '74'=>'Warranty Invoice (Show Notes)', '75'=>'Work Request Form', '76'=>'Export Customer Estimate', '77'=>'Export Customer Invoice', '78'=>'Export Warranty Invoice'];
define('WOREPORTOPTION', serialize($woReportOption));

$dayofTheWeeks = ['1'=>'Monday', '2'=>'Tuesday', '3'=>'Wednesday', '4'=>'Thursday', '5'=>'Friday', '6'=>'Saturday', '7'=>'Sunday'];
define('DAYOFWEEKS', serialize($dayofTheWeeks));

$ptoToUse = ['1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8'];
define('PTOTOUSE', serialize($ptoToUse));

$ptoAccrualRate = ['1'=>'10 Days(3.08 hours per pay period)', '2'=>'15 Days(4.62 hours per pay period)', '3'=>'20 Days(6.15 hours per pay period)', '4'=>'25 Days(7.69 hours per pay period)'];
define('PTOACCRUALRATE', serialize($ptoAccrualRate));

$warrantyPartPricingOptions = ['1'=>'Regular Part Pricing', '2'=>'Part Cost', '3'=>'Part % Over Cost', '4'=>'Parts Exchange'];
define('WARRANTYPARTPRICINGOPTIONS', serialize($warrantyPartPricingOptions));

$barcodeApiURL = 'https://quickchart.io/chart?cht=qr&';
define('BARCODEAPIURL', $barcodeApiURL);

$techPublMenuList = ['HR', 'Maintenance', 'Information Technology', 'Inventory', 'Flight Operations', 'Fuel System'];
define('TECHNICALPUBLICATIONMENU', serialize($techPublMenuList));

$historyReports = [
                'user_histories'=>'User', 
                'role_histories'=>'Role', 
                'user_time_clock_histories'=>'User Time Clock', 
                'user_pto_request_histories'=>'PTO Request',
                'user_department_histories'=>'Department/Job Title',
                'technical_publication_histories'=>'Technical Publication',
                'plane_histories'=>'Aircraft', 
                'airframe_component_histories'=>'Airframe Components', 
                'sub_component_histories'=>'Sub Components', 
                'airframe_component_time_histories'=>'Airframe Component Times', 
                'airframe_component_part_histories'=>'Airframe Component Parts', 
                'ata_code_histories'=>'ATA Codes', 
                'disposition_histories'=>'Disposition', 
                'adsb_status_histories'=>'AD/SB Class', 
                'aircraft_discrepancy_histories'=>'Aircraft Discrepancies', 
                'pilot_histories'=>'Pilot', 
                'flightlog_histories'=>'Flight Schedule',
                'inventory_item_histories'=>'Inventory Catalog', 
                'inventory_histories'=>'Inventory', 
                'inventory_location_histories'=>'Inventory Location', 
                'inventory_request_histories'=>'Inventory Requests', 
                'inventory_purchase_order_histories'=>'Inventory Purchase Orders', 
                'inventory_repair_order_histories'=>'Inventory Repair Orders', 
                'inventory_shipping_order_histories'=>'Inventory Shipping Orders'
            ];
define('HISTORYREPORTS', serialize($historyReports));

$searchDropDown = ['all'=>'ALL', 'plane'=>'Aircraft', 'airframe_component'=>'Airframe Components', 'sub_component'=>'Sub Components', 'airframe_component_time'=>'Airframe Component Times', 'airframe_component_part'=>'Airframe Component Parts', 'ata_code'=>'ATA Codes', 'disposition'=>'Disposition', 'adsb_status'=>'AD/SB Class', 'pilot'=>'Pilot', 'inventory_item'=>'Inventory Catalog', 'inventory'=>'Inventory', 'inventory_location'=>'Inventory Location', 'inventory_request'=>'Inventory Requests', 'inventory_purchase_order'=>'Inventory Purchase Orders', 'inventory_repair_order'=>'Inventory Repair Orders', 'inventory_shipping_order'=>'Inventory Shipping Orders', 'customer_otc'=>'Customer/OTC', 'inventory_vendor'=>'Inventory Vendor', 'inventory_manufacturer'=>'Inventory Manufacturers', 'work_order'=>'Work Order'];
define('SEARCHDROPDOWN', serialize($searchDropDown));