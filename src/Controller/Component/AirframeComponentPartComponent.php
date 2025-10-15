<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\ConnectionManager;
use Cake\Routing\Router;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

class AirframeComponentPartComponent extends Component {
	public array $components = ['Timezone', 'Authentication.Authentication'];

	protected \App\Model\Table\UsersTable $Users;
	protected \App\Model\Table\AirframeComponentPartsTable $AirframeComponentParts;

	public function initialize(array $config): void
    {
        parent::initialize($config);
    }
    /**
     * GetAirframeComponentParts method
     * This function is used to get list of all parts.
     *
     * @return array.
     */
    public function getAirCompParts() {
	  	$airCompPartModel = $this->getController()->fetchTable('AirframeComponentParts');
	  	$airCompParts = $airCompPartModel->find('list', array (
			'keyField' => 'id', 
			'valueField' => 'ata_code'
	  	))->toArray();
	  	return $airCompParts;
    }

    //Item Type
    public function getItemTypes()
    {
        /*$itemTypes = [
                    'AD'=>'Airworthiness Directive',
                    'INSPECTION'=>'INSPECTION',
                    'PART'=>'PART',
                    'SB'=>'Service Bulletin',
                    'Replace/Overhaul'=>'Replace/Overhaul',
                    'On Condition'=>'On Condition',
                    'Permanent'=>'Permanent',
                    'Overhaul'=>'Overhaul',
                    'Retire'=>'Retire',
                    'Service Letter'=>'Service Letter',
                    'Misc'=>'Misc.'
                ];*/

		$airframeItemTypesTable = TableRegistry::getTableLocator()->get('AirframeItemTypes');

		$itemTypes = $airframeItemTypesTable->find('list', [
												'keyField' => 'id',
												'valueField' => 'title'
											])
											->where(['status' => 'active'])
											->orderAsc('title')
											->toArray();

        return $itemTypes;
    }

    //AD/SB status
    public function getADSBStatus()
    {
        $resStatus = [
                    'Complied With'=>'Complied With',
                    'N/A'=>'N/A',
                    'None'=>'None',
                    'Open'=>'Open',
                    'Recurring'=>'Recurring',
                    'Superseded'=>'Superseded',
                    'Terminated'=>'Terminated'
                ];
        return $resStatus;
    }

    //Authority
    public function getAuthority()
    {
        /*$resAuthority = [
                    'ANAC'=>'ANAC',
                    'CASA'=>'CASA',
                    'DGAC-IND'=>'DGAC-IND',
                    'EASA'=>'Service Bulletin',
                    'FAA'=>'FAA',
                    'Other'=>'Other',
                    'TRANSPORT CANADA'=>'TRANSPORT CANADA'
                ];*/

		$airframeIssuingAuthoritiesTable = TableRegistry::getTableLocator()->get('AirframeIssuingAuthorities');

		$resAuthority = $airframeIssuingAuthoritiesTable->find('list', [
														'keyField' => 'id',
														'valueField' => 'title'
													])
													->where(['status' => 'active'])
													->orderAsc('title')
													->toArray();
		
        return $resAuthority;
    }

    //Requirement Type
    public function getRequirementTypes()
    {
        /*$reqTypes = [
                    'Expiration'=>'Expiration',
                    'Life Limited'=>'Life Limited',
                    'None'=>'None',
                    'Overhaul'=>'Overhaul',
                ];*/

		$airframeRequirementTypesTable = TableRegistry::getTableLocator()->get('AirframeRequirementTypes');

		$reqTypes = $airframeRequirementTypesTable->find('list', [
														'keyField' => 'id',
														'valueField' => 'title'
													])
													->where(['status' => 'active'])
													->orderAsc('title')
													->toArray();

        return $reqTypes;
    }

	//Requirement Source
    public function getRequirementSources()
    {
		$airframeRequirementSourcesTable = TableRegistry::getTableLocator()->get('AirframeRequirementSources');

		$reqSources = $airframeRequirementSourcesTable->find('list', [
														'keyField' => 'id',
														'valueField' => 'title'
													])
													->where(['status' => 'active'])
													->orderAsc('title')
													->toArray();

        return $reqSources;
    }

	//MOC
    public function getMocs()
    {
		$airframeMocsTable = TableRegistry::getTableLocator()->get('AirframeMocs');

		$mocs = $airframeMocsTable->find('list', [
										'keyField' => 'id',
										'valueField' => 'title'
									])
									->where(['status' => 'active'])
									->orderAsc('title')
									->toArray();

        return $mocs;
    }

    public function getInstallStatus()
    {
    	$installStatus = [
                        'Altered'=>'[A] Altered',
                        'Inspected'=>'[I] Inspected',
                        'Modified'=>'[M] Modified',
                        'New'=>'[N] New',
                        'Not Specified'=>'[NS] Not Specified',
                        'Overhauled'=>'[O] Overhauled',
                        'Other'=>'[OT] Other (See Notes)',
                        'Repaired'=>'[R] Repaired',
                        'Rebuilt'=>'[RE] Rebuilt',
                        'Serviceable'=>'[S] Serviceable'
                    ];
        return $installStatus;
    }

    //Removal Reason
    public function getRemovalReason()
    {
	  $reasonArray = array(
				'Convenience'=>'[C] Convenience',
				'Exces Oil Consumption'=>'[E] Exces Oil Consumption',
				'[F]Failed'=>'[F] Failed',
				'Metal In Oil'=>'[M] Metal In Oil',
				'Other'=>'[N] Other (See Notes)',
				'Not Specified'=>'[NS] Not Specified',
				'Scheduled'=>'[S] Scheduled',
				'Temp Limit'=>'[T] Temp Limit',
				'Unscheduled'=>'[U] Unscheduled',
				'Vibration'=>'[V] Vibration',
				'Worn To Limit'=>'[W] Worn To Limit',
				'ACTIVATED'=>'ACTIVATED',
				'ACTIVE'=>'ACTIVE',
				'ARCED'=>'ARCED',
				'BACKEDOUT'=>'BACKEDOUT',
				'BATTERED'=>'BATTERED',
				'BENT'=>'BENT',
				'BINDING'=>'BINDING',
				'BINDS'=>'BINDS',
				'BLEWOUT'=>'BLEWOUT',
				'BLISTERED'=>'BLISTERED',
				'BLOCKED'=>'BLOCKED',
				'BLOWN'=>'BLOWN',
				'BOWED'=>'BOWED',
				'BRINELLED'=>'BRINELLED',
				'BROKEN'=>'BROKEN',
				'BUCKLED'=>'BUCKLED',
				'BULGED'=>'BULGED',
				'BURNED'=>'BURNED',
				'BURNEDOUT'=>'BURNEDOUT',
				'BURRED'=>'BURRED',
				'BURST'=>'BURST',
				'BYPASSING'=>'BYPASSING',
				'CALIBRATION'=>'CALIBRATION',
				'CANNING'=>'CANNING',
				'CARBONED'=>'CARBONED',
				'CHAFED'=>'CHAFED',
				'CHATTERING'=>'CHATTERING',
				'CHIPPED'=>'CHIPPED',
				'CLOGGED'=>'CLOGGED',
				'CLOSED'=>'CLOSED',
				'COCKED'=>'COCKED',
				'COKED'=>'COKED',
				'COLDSOLDERJT'=>'COLDSOLDERJT',
				'COLLAPSED'=>'COLLAPSED',
				'CONTAMINATED'=>'CONTAMINATED',
				'CORRODED'=>'CORRODED',
				'CRACKED'=>'CRACKED',
				'CRAZED'=>'CRAZED',
				'CRIMPED'=>'CRIMPED',
				'CROSSED'=>'CROSSED',
				'CRUSHED'=>'CRUSHED',
				'CUT'=>'CUT',
				'DAMAGED'=>'DAMAGED',
				'DEACTIVATED'=>'DEACTIVATED',
				'DEBONDED'=>'DEBONDED',
				'DEFECTIVE'=>'DEFECTIVE',
				'DEFLATED'=>'DEFLATED',
				'DEFORMED'=>'DEFORMED',
				'DENTED'=>'DENTED',
				'DEPARTED'=>'DEPARTED',
				'DEPLOYED'=>'DEPLOYED',
				'DESTROYED'=>'DESTROYED',
				'DETACHED'=>'DETACHED',
				'DETECTED'=>'DETECTED',
				'DETERIORATED'=>'DETERIORATED',
				'DIRTY'=>'DIRTY',
				'DISCHARGED'=>'DISCHARGED',
				'DISCONNECTED'=>'DISCONNECTED',
				'DISCOVERED'=>'DISCOVERED',
				'DISENGAGED'=>'DISENGAGED',
				'DISINTEGRATED'=>'DISINTEGRATED',
				'DISLODGED'=>'DISLODGED',
				'DISPLACED'=>'DISPLACED',
				'DISSOLVED'=>'DISSOLVED',
				'DISTORTED'=>'DISTORTED',
				'DLAMINATED'=>'DLAMINATED',
				'DRAGGING'=>'DRAGGING',
				'DRY'=>'DRY',
				'ELONGATED'=>'ELONGATED',
				'ERODED'=>'ERODED',
				'ERRATIC'=>'ERRATIC',
				'EXCESSPLAY'=>'EXCESSPLAY',
				'EXPLODED'=>'EXPLODED',
				'EXPOSED'=>'EXPOSED',
				'EXTGUISHED'=>'EXTGUISHED',
				'FAILED'=>'FAILED',
				'FALSEACTIVATION'=>'FALSEACTIVATION',
				'FALSEINDICATION'=>'FALSEINDICATION',
				'FATIGUED'=>'FATIGUED',
				'FAULTY'=>'FAULTY',
				'FIRE'=>'FIRE',
				'FIREWARNING'=>'FIREWARNING',
				'FLAKING'=>'FLAKING',
				'FLAMEDOUT'=>'FLAMEDOUT',
				'FLAT'=>'FLAT',
				'FLATTENED'=>'FLATTENED',
				'FLUCTUATES'=>'FLUCTUATES',
				'FLUTTER'=>'FLUTTER',
				'FOD'=>'FOD',
				'FOULED'=>'FOULED',
				'FRACTURED'=>'FRACTURED',
				'FRAYED'=>'FRAYED',
				'FRETTED'=>'FRETTED',
				'FROZEN'=>'FROZEN',
				'GALLED'=>'GALLED',
				'GASSING'=>'GASSING',
				'GLAZED'=>'GLAZED',
				'GOUGED'=>'GOUGED',
				'GROOVED'=>'GROOVED',
				'GROUNDED'=>'GROUNDED',
				'HOTSTART'=>'HOTSTART',
				'HUNGUP'=>'HUNGUP',
				'ICED'=>'ICED',
				'ILLUMINATED'=>'ILLUMINATED',
				'IMBALANCED'=>'IMBALANCED',
				'IMPROPER'=>'IMPROPER',
				'IMPROPERPART'=>'IMPROPERPART',
				'INACCURATE'=>'INACCURATE',
				'INACTIVE'=>'INACTIVE',
				'INADEQUATE'=>'INADEQUATE',
				'INCORRECT'=>'INCORRECT',
				'INOPERATIVE'=>'INOPERATIVE',
				'INSTALLED'=>'INSTALLED',
				'INTERFERENCE'=>'INTERFERENCE',
				'INTERMITTNT'=>'INTERMITTNT',
				'JAMMED'=>'JAMMED',
				'KINKED'=>'KINKED',
				'LACKOFLUBE'=>'LACKOFLUBE',
				'LEAKING'=>'LEAKING',
				'LOANER'=>'LOANER',
				'LOOSE'=>'LOOSE',
				'LOW'=>'LOW',
				'MAKINGMETAL'=>'MAKINGMETAL',
				'MALADJUSTMENT'=>'MALADJUSTMENT',
				'MALFUNCTIONED'=>'MALFUNCTIONED',
				'MELTED'=>'MELTED',
				'MISALIGNED'=>'MISALIGNED',
				'MISDRILLED'=>'MISDRILLED',
				'MISINSTALLED'=>'MISINSTALLED',
				'MISLOCATED'=>'MISLOCATED',
				'MISMANUFACTURED'=>'MISMANUFACTURED',
				'MISMARKED'=>'MISMARKED',
				'MISOVERHAULED'=>'MISOVERHAULED',
				'MISPINNED'=>'MISPINNED',
				'MISREPAIRED'=>'MISREPAIRED',
				'MISRIGGED'=>'MISRIGGED',
				'MISROUTED'=>'MISROUTED',
				'MISSING'=>'MISSING',
				'MISUSED'=>'MISUSED',
				'MISWIRED'=>'MISWIRED',
				'MUSHROOMED'=>'MUSHROOMED',
				'NICKED'=>'NICKED',
				'NOISY'=>'NOISY',
				'NOTBONDED'=>'NOTBONDED',
				'NOTCLOSED'=>'NOTCLOSED',
				'NOTGROUNDED'=>'NOTGROUNDED',
				'NOTOPENED'=>'NOTOPENED',
				'NOTSEATED'=>'NOTSEATED',
				'OBSTRUCTED'=>'OBSTRUCTED',
				'ODOR'=>'ODOR',
				'OPEN'=>'OPEN',
				'OSCILLATES'=>'OSCILLATES',
				'OUOFADJUST'=>'OUOFADJUST',
				'OUTOFBALANCE'=>'OUTOFBALANCE',
				'OUTOFPOSITION'=>'OUTOFPOSITION',
				'OUTOFRIG'=>'OUTOFRIG',
				'OUTOFTOLERANCE'=>'OUTOFTOLERANCE',
				'OVERHEATED'=>'OVERHEATED',
				'OVERSERVICED'=>'OVERSERVICED',
				'OVERSIZED'=>'OVERSIZED',
				'OVERSPEED'=>'OVERSPEED',
				'OVERTEMP'=>'OVERTEMP',
				'OVERTORQUED'=>'OVERTORQUED',
				'OVERWEIGHT'=>'OVERWEIGHT',
				'PEELING'=>'PEELING',
				'PERFORATED'=>'PERFORATED',
				'PINCHED'=>'PINCHED',
				'PINHOLE'=>'PINHOLE',
				'PLUGGED'=>'PLUGGED',
				'POPPED'=>'POPPED',
				'POROUS'=>'POROUS',
				'POWERLOSS'=>'POWERLOSS',
				'PRECESSES'=>'PRECESSES',
				'PULLED'=>'PULLED',
				'PUNCTURED'=>'PUNCTURED',
				'RATCHETING'=>'RATCHETING',
				'READSHIGH'=>'READSHIGH',
				'READSLOW'=>'READSLOW',
				'RESTRICTED'=>'RESTRICTED',
				'REVERSED'=>'REVERSED',
				'ROTATED'=>'ROTATED',
				'ROTTED'=>'ROTTED',
				'ROUGH'=>'ROUGH',
				'RUPTURED'=>'RUPTURED',
				'RUSTED'=>'RUSTED',
				'SATURATED'=>'SATURATED',
				'SCORED'=>'SCORED',
				'RCRATCHED'=>'RCRATCHED',
				'SEIZED'=>'SEIZED',
				'SEPARATED'=>'SEPARATED',
				'SEVERED'=>'SEVERED',
				'SHEARED'=>'SHEARED',
				'SHIFTED'=>'SHIFTED',
				'SHINGLED'=>'SHINGLED',
				'SHORTED'=>'SHORTED',
				'SHUTDOWN'=>'SHUTDOWN',
				'SIPHONING'=>'SIPHONING',
				'SLIPPED'=>'SLIPPED',
				'SLOW'=>'SLOW',
				'SLUGGISH'=>'SLUGGISH',
				'SMOKE'=>'SMOKE',
				'SPALLED'=>'SPALLED',
				'SPARKS'=>'SPARKS',
				'SPINNING'=>'SPINNING',
				'SPLIT'=>'SPLIT',
				'SPUN'=>'SPUN',
				'STALLED'=>'STALLED',
				'STICKING'=>'STICKING',
				'STICKS'=>'STICKS',
				'STIFF'=>'STIFF',
				'STOPPED'=>'STOPPED',
				'STRETCHED'=>'STRETCHED',
				'STRIPPED'=>'STRIPPED',
				'STUCK'=>'STUCK',
				'SURGES'=>'SURGES',
				'SWAPPED'=>'SWAPPED',
				'SWOLLEN'=>'SWOLLEN',
				'THERMALRUNAWAY'=>'THERMALRUNAWAY',
				'TIRE CHANGE'=>'TIRE CHANGE',
				'TIRE WORN'=>'TIRE WORN',
				'TOOLDAMAGE'=>'TOOLDAMAGE',
				'TORN'=>'TORN',
				'TRANSPOSED'=>'TRANSPOSED',
				'TRIPPED'=>'TRIPPED',
				'TROUBLSHOOTING'=>'TROUBLSHOOTING',
				'TUMBLES'=>'TUMBLES',
				'TWISTED'=>'TWISTED',
				'UNAPPROVEDPART'=>'UNAPPROVEDPART',
				'UNBONDED'=>'UNBONDED',
				'UNCALIBRATED'=>'UNCALIBRATED',
				'UNCONTROLLABLE'=>'UNCONTROLLABLE',
				'UNDERSERVICED'=>'UNDERSERVICED',
				'UNDERSIZE'=>'UNDERSIZE',
				'UNDERTORQUED'=>'UNDERTORQUED',
				'UNLATCHED'=>'UNLATCHED',
				'UNLOCKED'=>'UNLOCKED',
				'UNRELIABLE'=>'UNRELIABLE',
				'UNSAFETIED'=>'UNSAFETIED',
				'UNSCREWED'=>'UNSCREWED',
				'UNSECURE'=>'UNSECURE',
				'UNSERVICEABLE'=>'UNSERVICEABLE',
				'UNSTABLE'=>'UNSTABLE',
				'UNSTAKED'=>'UNSTAKED',
				'UNWANTED'=>'UNWANTED',
				'UNWANTEDEXTEND'=>'UNWANTEDEXTEND',
				'VAPORLOCK'=>'VAPORLOCK',
				'VIBRATES'=>'VIBRATES',
				'VIBRATION'=>'VIBRATION',
				'WARPED'=>'WARPED',
				'WEAK'=>'WEAK',
				'WILLNOTBALANCE'=>'WILLNOTBALANCE',
				'WILLNOTTEST'=>'WILLNOTTEST',
				'WORN'=>'WORN',
				'WRINKLED'=>'WRINKLED',
				'WRONGPART'=>'WRONGPART'
			  );
	  return $reasonArray;
    }

    //Post data correction and nextdue calculation to save/update in database
    public function postDataProcess($postData)
    {
	  	$result = array();
	  	if(!empty($postData)) {
			$mos = $hrs = $afl = $msc = '';
			if(!empty($postData['override']) && $postData['override'] == 1) {
			    $mos = !empty($postData['next_due_date']) ? $this->changeFormat($postData['next_due_date']) : '';
			    $hrs = !empty($postData['next_due_hrs']) ? $postData['next_due_hrs'] : '';
			    $afl = !empty($postData['next_due_afl']) ? $postData['next_due_afl'] : '';
			} else {
			    //Start Next due
			    if(empty($postData['adjustment_mos']) || !is_numeric($postData['adjustment_mos'])) {
				  	$postData['adjustment_mos'] = 0;
			    }

			    if(empty($postData['adjustment_days']) || !is_numeric($postData['adjustment_days'])) {
				  	$postData['adjustment_days'] = 0;
			    }

			    if(empty($postData['adjustment_hrs']) || !is_numeric($postData['adjustment_hrs'])) {
				  	$postData['adjustment_hrs'] = 0;
			    }

			    if(empty($postData['adjustment_afl']) || !is_numeric($postData['adjustment_afl'])) {
				  	$postData['adjustment_afl'] = 0;
			    }

			    if(empty($postData['last_cw_hrs'])) {
					$postData['last_cw_hrs'] = 0;
				}
				
				if(empty($postData['last_cw_afl'])) {
					$postData['last_cw_afl'] = 0;
				}

			    $date = !empty($postData['last_cw_date']) ? $this->changeFormat($postData['last_cw_date']) : '';
			    $hrs = !empty($postData['required_frequency_hrs']) ? $postData['required_frequency_hrs'] + $postData['last_cw_hrs'] + $postData['adjustment_hrs'] : '';
			    $afl = !empty($postData['required_frequency_afl']) ? $postData['required_frequency_afl'] + $postData['last_cw_afl'] + $postData['adjustment_afl'] : '';

			    //Get mos
			    $mos = $this->getMos($postData, $date);

			    //If End of adjustment is selected
			    if(!empty($mos) && @$postData['eom'] == 1) {
				  	$mos = strtoupper(date('Y-m-t', strtotime($mos)));
			    }
			}

			if(!empty($mos)) {
			    $postData['next_due_date'] = $mos;
			}

			if(!empty($hrs)) {
			    $postData['next_due_hrs'] = $hrs;
			}
			
			if(!empty($afl)) {
			    $postData['next_due_afl'] = $afl;
			}
			
			if(!empty($msc)) {
			    $postData['next_due_msc'] = $msc;
			}

			$result = $postData;
			$result['mos'] = $mos;
			$result['hrs'] = $hrs;
			$result['afl'] = $afl;
			$result['msc'] = $msc;
			return $result;
	  	}
    }

    //Get data correction and nextdue calculation to display data
    public function getDataProcess($params, $report=null)
    {
	  	$result = array();
	  	if(!empty($params)) {
		
			if(empty($params['intvAdjMos']) || !is_numeric($params['intvAdjMos'])) {
			    $params['intvAdjMos'] = 0;
			}

			if(empty($params['intvAdjDay']) || !is_numeric($params['intvAdjDay'])) {
			    $params['intvAdjDay'] = 0;
			}

			if(empty($params['intvAdjHrs']) || !is_numeric($params['intvAdjHrs'])) {
			    $params['intvAdjHrs'] = 0;
			}

			if(empty($params['intvAdjAfl']) || !is_numeric($params['intvAdjAfl'])) {
			    $params['intvAdjAfl'] = 0;
			}

			if(empty($params['lastCwHrs']) || !is_numeric($params['intvAdjMos'])) {
				$params['lastCwHrs'] = 0;
			}
			
			if(empty($params['lastCwAfl']) || !is_numeric($params['intvAdjMos'])) {
				$params['lastCwAfl'] = 0;
			}

			$mos = $hrs = $afl = '';
			if(!empty($params['isOverride']) && $params['isOverride'] == 'true') {
			    if(!empty($report['airframe_component_last_cw'][0]['override']) && $report['airframe_component_last_cw'][0]['override'] == 1) {
				  $mos = !empty($report['airframe_component_last_cw'][0]['next_due_date']) ? $this->changeFormat($report['airframe_component_last_cw'][0]['next_due_date']):'';
				  $hrs = !empty($report['airframe_component_last_cw'][0]['next_due_hrs']) ? $report['airframe_component_last_cw'][0]['next_due_hrs']:'';
				  $afl = !empty($report['airframe_component_last_cw'][0]['next_due_afl']) ? $report['airframe_component_last_cw'][0]['next_due_afl']:'';

			    } elseif(!empty($params['mos']) || !empty($params['hrs']) || !empty($params['afl'])) {
				  $mos = $params['mos'];
				  $hrs = $params['hrs'];
				  $afl = $params['afl'];

			    } elseif(empty($params['mos']) && empty($params['hrs']) && empty($params['afl'])) {
				  $date = !empty($params['lastCwMos']) ? $this->changeFormat($params['lastCwMos']) : '';
				  $hrs = !empty($params['intvHrs']) ? $params['intvHrs'] + $params['lastCwHrs'] + $params['intvAdjHrs'] : '';
				  $afl = !empty($params['intvAfl']) ? $params['intvAfl'] + $params['lastCwAfl'] + $params['intvAdjAfl'] : '';

				  //Get mos
				  $mos = $this->getJqMos($params, $date);

				  if(!empty($mos) && $params['isEom'] == 'true') {
					$mos = strtoupper(date('t-M-Y', strtotime($mos)));
				  }
			    }              
			} else {
			    $date = !empty($params['lastCwMos']) ? $this->changeFormat($params['lastCwMos']) : '';
			    $hrs = !empty($params['intvHrs']) ? $params['intvHrs'] + $params['lastCwHrs'] + $params['intvAdjHrs'] : '';
			    $afl = !empty($params['intvAfl']) ? $params['intvAfl'] + $params['lastCwAfl'] + $params['intvAdjAfl'] : '';
			    
			    //Get mos
			    $mos = $this->getJqMos($params, $date);
			    
			    if(!empty($mos) && (!empty($params['isEom']) && $params['isEom'] == 'true')) {
				  	$mos = strtoupper(date('t-M-Y', strtotime($mos)));
			    }
			}

			$result['mos'] = $mos;
			$result['hrs'] = $hrs;
			$result['afl'] = $afl;
			return $result;
	  	}
    }

    //Calculate months(mos)
    public function getMos($postData, $date)
    {
	  	if(!empty($postData)) {
			//Set adjustment days sign(Add or Remove days)
			$addDSign = "";
			if(!empty($postData['adjustment_days']) &&  is_numeric($postData['adjustment_days']) && $postData['adjustment_days'] >= 0) {
			    $postData['adjustment_days'] = (int)$postData['adjustment_days'];
			    $addDSign = " + ";
			} elseif(!empty($postData['adjustment_days']) && is_numeric($postData['adjustment_days']) && $postData['adjustment_days'] < 0) {
			    $postData['adjustment_days'] = (int)$postData['adjustment_days'];
			    $addDSign = "";
			} else {
			    $postData['adjustment_days'] = 0;
			    $addDSign = "";
			}

			//Add months in date
			$adjustMos = $mos = '';
			if(!empty($postData['required_frequency_mos']) && !empty($postData['adjustment_mos'])) {
			    $adjustMos = $postData['required_frequency_mos'] + $postData['adjustment_mos'];
			    $mos = (!empty($adjustMos) && !empty($date)) ? strtoupper(date('m/d/Y', strtotime("+".$adjustMos." months", strtotime($date)))) : '';
			} elseif (!empty($postData['required_frequency_mos']) && empty($postData['adjustment_mos'])) {
			    $adjustMos = $postData['required_frequency_mos'];
			    $mos = (!empty($adjustMos) && !empty($date)) ? strtoupper(date('m/d/Y', strtotime("+".$adjustMos." months", strtotime($date)))) : '';
			}

			//Add days in date
			$adjustDays = '';
			if(!empty($postData['required_frequency_days']) && !empty($postData['adjustment_days']) && !empty($mos)) {
			    $adjustDays = $postData['required_frequency_days'] + $postData['adjustment_days'];
			    $mos = strtoupper(date('m/d/Y', strtotime($mos.$addDSign.$adjustDays." days")));

			} elseif (!empty($postData['required_frequency_days']) && !empty($postData['adjustment_days']) && empty($mos)) {
			    $adjustDays = $postData['required_frequency_days'] + $postData['adjustment_days'];
			    $mos = !empty($date) ? strtoupper(date('m/d/Y', strtotime($addDSign.$adjustDays." days", strtotime($date)))) : '';

			} elseif (!empty($postData['required_frequency_days']) && empty($postData['adjustment_days']) && !empty($mos)) {
			    $adjustDays = $postData['required_frequency_days'];
			    $mos = strtoupper(date('m/d/Y', strtotime($mos.$addDSign.$adjustDays." days")));

			} elseif (!empty($postData['required_frequency_days']) && empty($postData['adjustment_days']) && empty($mos)) {
			    $adjustDays = $postData['required_frequency_days'];
			    $mos = !empty($date) ? strtoupper(date('m/d/Y', strtotime($addDSign.$adjustDays." days", strtotime($date)))) : '';

			} elseif(empty($postData['required_frequency_days']) && !empty($postData['adjustment_days']) && !empty($mos)) {
			    $adjustDays = $postData['adjustment_days'];
			    $mos = strtoupper(date('m/d/Y', strtotime($mos.$addDSign.$adjustDays." days")));

			} elseif(empty($postData['required_frequency_days']) && !empty($postData['adjustment_days']) && empty($mos)) {
			    $adjustDays = $postData['adjustment_days'];
			    $mos = !empty($date) ? strtoupper(date('m/d/Y', strtotime($addDSign.$adjustDays." days", strtotime($date)))) : '';
			}
			//End to add days in date

			return $mos;
	  	}
    }

    //Calculate months(mos)-For ajax request fields name are different
    public function getJqMos($params, $date)
    {
	  
	  	if(!empty($params)) {
			//Set adjustment days sign(Add or Remove days)
			$addDSign = "";
			if(!empty($params['intvAdjDay']) &&  is_numeric($params['intvAdjDay']) && $params['intvAdjDay'] >= 0) {
			    $params['intvAdjDay'] = (int)$params['intvAdjDay'];
			    $addDSign = " + ";
			} elseif(!empty($params['intvAdjDay']) && is_numeric($params['intvAdjDay']) && $params['intvAdjDay'] < 0) {
			    $params['intvAdjDay'] = (int)$params['intvAdjDay'];
			    $addDSign = "";
			} else {
			    $params['intvAdjDay'] = 0;
			    $addDSign = "";
			}

			//Add months in date
			$adjustMos = $mos = '';
			if(!empty($params['intvMos']) && !empty($params['intvAdjMos'])) {
			    $adjustMos = $params['intvMos'] + $params['intvAdjMos'];
			    $mos = (!empty($adjustMos) && !empty($date)) ? strtoupper(date('m/d/Y', strtotime("+".$adjustMos." months", strtotime($date)))) : '';
			} elseif (!empty($params['intvMos']) && empty($params['intvAdjMos'])) {
			    $adjustMos = $params['intvMos'];
			    $mos = (!empty($adjustMos) && !empty($date)) ? strtoupper(date('m/d/Y', strtotime("+".$adjustMos." months", strtotime($date)))) : '';
			}

			//Add days in date
			$adjustDays = '';
			if(!empty($params['intvDay']) && !empty($params['intvAdjDay']) && !empty($mos)) {
			    $adjustDays = $params['intvDay'] + $params['intvAdjDay'];
			    $mos = strtoupper(date('m/d/Y', strtotime($mos.$addDSign.$adjustDays." days")));

			} elseif (!empty($params['intvDay']) && !empty($params['intvAdjDay']) && empty($mos)) {
			    $adjustDays = $params['intvDay'] + $params['intvAdjDay'];
			    $mos = !empty($date) ? strtoupper(date('m/d/Y', strtotime($addDSign.$adjustDays." days", strtotime($date)))) : '';

			} elseif (!empty($params['intvDay']) && empty($params['intvAdjDay']) && !empty($mos)) {
			    $adjustDays = $params['intvDay'];
			    $mos = strtoupper(date('m/d/Y', strtotime($mos.$addDSign.$adjustDays." days")));

			} elseif (!empty($params['intvDay']) && empty($params['intvAdjDay']) && empty($mos)) {
			    $adjustDays = $params['intvDay'];
			    $mos = !empty($date) ? strtoupper(date('m/d/Y', strtotime($addDSign.$adjustDays." days", strtotime($date)))) : '';

			} elseif(empty($params['intvDay']) && !empty($params['intvAdjDay']) && !empty($mos)) {
			    $adjustDays = $params['intvAdjDay'];
			    $mos = strtoupper(date('m/d/Y', strtotime($mos.$addDSign.$adjustDays." days")));

			} elseif(empty($params['intvDay']) && !empty($params['intvAdjDay']) && empty($mos)) {
			    $adjustDays = $params['intvAdjDay'];
			    $mos = !empty($date) ? strtoupper(date('m/d/Y', strtotime($addDSign.$adjustDays." days", strtotime($date)))) : '';
			}
			//End to add days in date

			return $mos;
	  	}
    }

    //Change date format to 'm-d-Y'
    public function changeFormat($dateString) 
    {
        $dateString = str_replace('-', '/', $dateString);
        return date('m/d/Y', strtotime($dateString));
    }

    //Change date format to 'm-d-Y'
    public function dateFormat($dateString) 
    {
        $dateString = str_replace('-', '/', $dateString);
        return date('m-d-Y', strtotime($dateString));
    }

    //Get user name
    public function getUserName($userId) 
    {
    	if(!empty($userId)) {
    		$userModel = $this->getController()->fetchTable('Users');
    		$result = $userModel->find('all')->where(['id'=>$userId])->select(['full_name'])->first();
    		return $result['full_name'];
    	}
    	
    }

	public function uploadComponentPartFilesToServer($postData, $filelocation, $foldername, $tableName=''){
        ini_set('post_max_size', '100M');
        ini_set('upload_max_filesize', '100M');
		
        $authUserData = $this->Authentication->getResult()->getData();

        $attachment = $postData['file_name']; 
		$name = $attachment->getClientFilename();
		$type = $attachment->getClientMediaType();
		$size = $attachment->getSize();
		$temp = $attachment->getStream()->getMetadata('uri');
        $ext = substr(strrchr($name , '.'), 1);
        
        $iconcss = '';
        if($ext == 'pdf'){
            $iconcss = 'icon-pdf';
        }else if($ext == 'doc' || $ext == 'docx'){
            $iconcss = 'icon-doc';
        }else if($ext == 'xls' || $ext == 'xlsx'){
            $iconcss = 'icon-excel';
        }else if($ext == 'txt'){
            $iconcss = 'icon-text';
        }else{
            $iconcss = 'icon-generic';
        }
        
        $tblrow = '';
        if(move_uploaded_file($temp, $filelocation)) {
            $filesize = ($size/1000).' KB';
            $tblrow = '<tr>
                <td class="document-name"><span class="document-management-icon '.$iconcss.'"></span><a href="'.Router::url('/', true).$foldername.'/' . $name.'" download="'.$name.'">'.$name.'</a>
                <input type="hidden" name="filenames[]" value="'.$name.'">
                <input type="hidden" name="filesize[]" value="'.$filesize.'">
                </td>
                <td><i class="fa fa-times delete_comp_part_attachment" title="Remove File"></i></td>
            </tr>';
            if(!empty($tableName)){
                $connection = ConnectionManager::get('default');

                $componentpartfiles = $connection
                        ->execute(
                            'SELECT max(position) as positions FROM '.$tableName.' WHERE airframe_component_part_id = :airframe_component_part_id and status = "1" limit 1',
                            ['airframe_component_part_id' => $postData['airframe_component_part_id']],
                            ['created' => 'datetime']
                        )
                        ->fetch('assoc');
                
                $position = !empty($componentpartfiles) ? $componentpartfiles['positions']+1 : '1';
                
                $attachmentdata = [
									'plane_id'=>$postData['plane_id'],
                                    'airframe_component_part_id'=>$postData['airframe_component_part_id'],
                                    'file_name' => $name,
                                    'file_size' => $filesize,
                                    'position'=>$position,
                                    'added_by' => $authUserData['id'],
                                    'created_at'=>date("Y-m-d H:i:s")
                                ];

                $resp = $connection->insert($tableName, $attachmentdata, ['created' => 'datetime']);
                $inserted_id = $resp->lastInsertId($tableName);
				
                $responseArr = $this->getAirframeComponentPartAttachments($postData['airframe_component_part_id']);
				$tblrow = $responseArr['tblrow'];
            }
        }

        return $tblrow;
    }

	public function getAirframeComponentPartAttachments($airframe_component_part_id){
		$connection = ConnectionManager::get('default');

		$componentpartfiles = $connection
                        ->execute(
                            'SELECT pf.id, pf.file_name, pf.file_size, pf.`created_at`, u.full_name FROM `airframe_component_part_files` pf join users u on pf.added_by=u.id WHERE u.suspended="0" and pf.airframe_component_part_id=:airframe_component_part_id',
							['airframe_component_part_id' => $airframe_component_part_id],
                            ['created' => 'datetime']
                        )
                        ->fetchAll('assoc');
		
		$trclassName = 'airframe-component-part-file';
		
		$tblrow = '';
		$attachmentcounts = 0;
		foreach($componentpartfiles as $partfiles){
			$attachmentcounts++;
			$ext = substr(strrchr($partfiles['file_name'] , '.'), 1);
        
			$iconcss = '';
			if($ext == 'pdf'){
				$iconcss = 'icon-pdf';
			}else if($ext == 'doc' || $ext == 'docx'){
				$iconcss = 'icon-doc';
			}else if($ext == 'xls' || $ext == 'xlsx'){
				$iconcss = 'icon-excel';
			}else if($ext == 'txt'){
				$iconcss = 'icon-text';
			}else{
				$iconcss = 'icon-generic';
			}
			
			$tblrow .= '<tr class="airframe-component-part-file" data-val="'.$partfiles['id'].'">
				<td class="document-name"><span class="document-management-icon '.$iconcss.'"></span><a href="'.Router::url('/', true).'airframe_component_parts/' . $partfiles['file_name'].'" download="'.$partfiles['file_name'].'">'.$partfiles['file_name'].'</a>
				</td>
				<td>'.$partfiles['file_size'].'</td>
				<td>'.date('m-d-Y', strtotime($partfiles['created_at'])).'</td>
				<td>'.$partfiles['full_name'].'</td>
				<td><i class="fa fa-times delete_comp_part_attachment" title="Remove File" data-val="'.$partfiles['id'].'"></i></td>
			</tr>';
		}

		$returnArr = ['tblrow'=>$tblrow, 'attachmentcounts'=>$attachmentcounts];
		return $returnArr;
	}

	public function deleteAirframeComponentPartAttachments($id){
        $connection = ConnectionManager::get('default');
        $resp = $connection->delete('airframe_component_part_files', ['id' => $id]);

        return $resp;
    }

	public function getItemTypesById($itemTypeId)
    {
		$airframeItemTypesTable = TableRegistry::getTableLocator()->get('AirframeItemTypes');

		$itemTypes = $airframeItemTypesTable->get($itemTypeId);

        return $itemTypes;
    }

	public function getItemTypeDetByTitle($title)
    {
		$airframeItemTypesTable = TableRegistry::getTableLocator()->get('AirframeItemTypes');
		$itemTypes = [];
		if(!empty($title)){
			$itemTypes = $airframeItemTypesTable->find()
												->where([
													'OR' => [
														'LOWER(AirframeItemTypes.title) =' => strtolower($title),
														'LOWER(AirframeItemTypes.sort_title) =' => strtolower($title)
													]
												])
												->select(['AirframeItemTypes.id', 'AirframeItemTypes.sort_title'])
												->first();

		}

        return $itemTypes;
    }

	public function getRequirementTypesById($requirementTypeId)
    {
		$airframeRequirementTypesTable = TableRegistry::getTableLocator()->get('AirframeRequirementTypes');

		$reqTypes = $airframeRequirementTypesTable->get($requirementTypeId);

        return $reqTypes;
    }

	public function getIssuingAuthorityById($authorityId)
    {
		$airframeIssuingAuthoritiesTable = TableRegistry::getTableLocator()->get('AirframeIssuingAuthorities');

		$resAuthority = $airframeIssuingAuthoritiesTable->get($authorityId);
		
        return $resAuthority;
    }
}