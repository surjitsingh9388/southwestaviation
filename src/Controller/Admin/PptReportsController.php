<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use Cake\Database\Expression\QueryExpression;
use Cake\Core\Configure;
require_once(ROOT . DS . 'vendor' . DS . 'autoload.php');

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Border;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Color as StyleColor;
use \PhpOffice\PhpPresentation\Slide\Background\Image;
use PhpOffice\PhpPresentation\Style\Fill;
use PhpOffice\PhpPresentation\Slide\Transition;
use PhpOffice\PhpPresentation\DocumentLayout;

/**
 * PptReports Controller
 */
class PptReportsController extends AppController
{
    private $planeObj;
    private $airCompPartObj;

    public $paginate = array(
        'limit' => PAGINATION_LIMIT
    );
    
    public function initialize() 
    {
        parent::initialize();
        $this->loadComponent('Plane');
        $this->loadComponent('Report');

        $this->planeObj = TableRegistry::get('Planes');
        $this->airCompPartObj = TableRegistry::get('AirframeComponentParts');
    }
    
    public function index()
    {
    	$params = $this->request->data;

    	//Sorting condition
        $sortOrder = ['-ata_code'=> 'DESC'];
        if(!empty($pids) && count($pids) == 1 && $this->Plane->orderType($params['airId']) == 'no') {
            $sortOrder = ['log_book'=>'ASC'];
        } elseif(!empty($pids) && count($pids) == 1 && $this->Plane->orderType($params['airId']) == 'yes') {
            $sortOrder = ['-ata_code'=>'DESC', 'mfg_code'=>'ASC'];
        }

        //To check parent child relation
        $childQuery = function ($q) { 
                        return $q->where(['AirframeComponentParts.parent_id !='=>0, 'AirframeComponentParts.id !='=>'ParentChildRelations.parent_id'])
                        ->select(['AirframeComponentParts.id', 'AirframeComponentParts.plane_id'])
                        ->contain([
                            'ParentChildRelations'=>[
                                'fields'=>['ParentChildRelations.airframe_component_part_id', 'ParentChildRelations.parent_id']
                            ]
                        ]);
                    };

    	$compAssociates = $this->Report->associateQuery();
        $reports = $this->airCompPartObj->find()
                    ->where(['AirframeComponentParts.plane_id'=>$params['airId']])
                    ->order($sortOrder)
                    ->contain([
                        'Planes'=>[
                            'fields'=>[
                                'Planes.id',
                                'Planes.plane_code'
                            ],
                            'AirframeComponentParts'=>$childQuery
                        ],
                        'AirframeComponents'=>$compAssociates,
                        'AirframeComponentLastCw'=>[
                            'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                        ],
                    ])
                    ->enableHydration(false)->toArray();
                    
        $results = $this->condRecords($reports, $params);
        //pr($results);die;

        $objPHPPresentation = new PhpPresentation();
        //$objPHPPresentation->getPresentationProperties()->setZoom(3);

		/******************************** First Slide *******************************/
		$slide0 = $objPHPPresentation->getActiveSlide();
		//$slide0 = $objPHPPresentation->createSlide();
		//$slide0->setIsVisible(true);
		
		// Background
        $oBackground = new Image();
        $oBackground->setPath(ROOT.DS.'webroot/images/swas.jpg');
        // Slide Background
        $slide0->setBackground($oBackground);

        // Create a shape (drawing)
		$shape = $slide0->createDrawingShape();
		$shape->setName('SWAS Logo')
		      ->setDescription('SWAS Logo')
		      ->setPath(ROOT.DS.'webroot/images/logo.png')
		      ->setHeight(150)
		      ->setWidth(400)
		      ->setOffsetX(10)
		      ->setOffsetY(600);
		$shape->getShadow()->setVisible(true)
		                   ->setDirection(45)
		                   ->setDistance(10);

		/*$oCell = new Transition();
		$oCell->setManualTrigger(false);
		$oCell->setTimeTrigger(true, 1000);
		$oCell->setTransitionType(Transition::TRANSITION_COVER_RIGHT);
		$slide0->setTransition($oCell);*/

		/******************************** Next Slides *******************************/
		if(!empty($results)) {
			$i=0;
			$j=1;
			$sld = 'slide';
			foreach ($results as $key=>$value) {
				//if(in_array($j, [1,5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80])) {
				if($j==1 || $j == $i*10) {
					$slide = $sld.$j;
					$slide = $objPHPPresentation->createSlide();
					$slide->setIsVisible(true);

					//Set slide width
					$objPHPPresentation->getLayout()->setDocumentLayout(['cx' => 1280, 'cy' => 700], true)
								        ->setCX(1280, DocumentLayout::UNIT_PIXEL)
								        ->setCY(700, DocumentLayout::UNIT_PIXEL);

					$shape = $slide->createTableShape(9);
					$shape->setHeight(200);
					$shape->setWidth(1245);
					$shape->setOffsetX(15);
					$shape->setOffsetY(15);

					//Dummy Row
					$row0 = $shape->createRow();
					/*$row0->getFill()->setFillType(Fill::FILL_SOLID)
								   ->setStartColor(new Color('FF000000'))
					               ->setEndColor(new Color('FF000000'));*/

					$cell0 = $row0->nextCell();
					$cell0->setWidth(100);
					$cell0->getBorders()->getTop()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getLeft()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getRight()->setColor(new Color(Color::COLOR_WHITE));
					
					$cell0 = $row0->nextCell();
					$cell0->setWidth(80);
					$cell0->getBorders()->getTop()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getLeft()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getRight()->setColor(new Color(Color::COLOR_WHITE));
					
					$cell0 = $row0->nextCell();
					$cell0->setWidth(140);
					$cell0->getBorders()->getTop()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getLeft()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getRight()->setColor(new Color(Color::COLOR_WHITE));

					$cell0 = $row0->nextCell();
					$cell0->setWidth(365);
					$cell0->getBorders()->getTop()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getLeft()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getRight()->setColor(new Color(Color::COLOR_WHITE));

					$cell0 = $row0->nextCell();
					$cell0->setWidth(120);
					$cell0->getBorders()->getTop()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getLeft()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getRight()->setColor(new Color(Color::COLOR_WHITE));

					$cell0 = $row0->nextCell();
					$cell0->setWidth(105);
					$cell0->getBorders()->getTop()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getLeft()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getRight()->setColor(new Color(Color::COLOR_WHITE));

					$cell0 = $row0->nextCell();
					$cell0->setWidth(105);
					$cell0->getBorders()->getTop()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getLeft()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getRight()->setColor(new Color(Color::COLOR_WHITE));

					$cell0 = $row0->nextCell();
					$cell0->setWidth(120);
					$cell0->getBorders()->getTop()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getLeft()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getRight()->setColor(new Color(Color::COLOR_WHITE));

					$cell0 = $row0->nextCell();
					$cell0->setWidth(110);
					$cell0->getBorders()->getTop()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getLeft()->setColor(new Color(Color::COLOR_WHITE));
					$cell0->getBorders()->getRight()->setColor(new Color(Color::COLOR_WHITE));
					//Dummy Row

					// Add row
					$row = $shape->createRow();
					$row->getFill()->setFillType(Fill::FILL_SOLID)
								   ->setStartColor(new Color('ead40e'))
					               ->setEndColor(new Color('ead40e'));
					$cell = $row->nextCell();
					$cell->setWidth();
					$cell->setColSpan(9);
					$cell->createTextRun('OVER DUE, CURRENT DUE AND PROJECTED DUE')->getFont()->setBold(true)->setSize(13);
					$cell->getBorders()->getBottom()->setLineWidth(1)
					                                ->setLineStyle(Border::LINE_SINGLE)
					                                ->setDashStyle(Border::DASH_DASH);
					$cell->getActiveParagraph()->getAlignment()
						->setMarginBottom(8)
						->setMarginTop(8)
						->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);

					// Add row
					$row = $shape->createRow();
					$row->getFill()->setFillType(Fill::FILL_SOLID)
								   ->setStartColor(new Color('FF0dba32'))
					               ->setEndColor(new Color('FF0dba32'));
					
					$oCell = $row->nextCell();
					$oCell->setWidth(100);
					$oCell->createTextRun('AIRCRAFT')->getFont()->setBold(true)->setSize(9);
					$oCell->getActiveParagraph()->getAlignment()
						->setMarginBottom(5)
						->setMarginTop(5)
						->setMarginRight(5)
						->setMarginLeft(5)
						->setVertical(Alignment::VERTICAL_CENTER)
						->setHorizontal(Alignment::HORIZONTAL_CENTER);

					$oCell = $row->nextCell();
					$oCell->setWidth(100);
					$oCell->createTextRun('ATA')->getFont()->setBold(true)->setSize(9);
					$oCell->getActiveParagraph()->getAlignment()
						->setMarginBottom(5)
						->setMarginTop(5)
						->setMarginRight(5)
						->setMarginLeft(5)
						->setVertical(Alignment::VERTICAL_CENTER)
						->setHorizontal(Alignment::HORIZONTAL_CENTER);

					$oCell = $row->nextCell();
					$oCell->setWidth(120);
					$oCell->createTextRun('REFERENCE')->getFont()->setBold(true)->setSize(9);
					$oCell->getActiveParagraph()->getAlignment()
						->setMarginBottom(5)
						->setMarginTop(5)
						->setMarginRight(5)
						->setMarginLeft(6)
						->setVertical(Alignment::VERTICAL_CENTER)
						->setHorizontal(Alignment::HORIZONTAL_CENTER);
					/*$oCell->createTextRun('    -----------------------  ')->getFont()->setBold(true)->setSize(11);
					$oCell->createTextRun('DESCRIPTION')->getFont()->setBold(true)->setSize(11);
					$oCell->getActiveParagraph()->getAlignment()
						->setMarginBottom(5)
						->setMarginTop(5)
						->setMarginRight(5)
						->setMarginLeft(5)
						->setVertical(Alignment::VERTICAL_CENTER)
						->setHorizontal(Alignment::HORIZONTAL_CENTER);*/

					$oCell = $row->nextCell();
					$oCell->setWidth(365);
					$oCell->createTextRun('DESCRIPTION')->getFont()->setBold(true)->setSize(9);
					$oCell->getActiveParagraph()->getAlignment()
						->setMarginBottom(5)
						->setMarginTop(5)
						->setMarginRight(5)
						->setMarginLeft(5)
						->setVertical(Alignment::VERTICAL_CENTER)
						->setHorizontal(Alignment::HORIZONTAL_CENTER);

					$oCell = $row->nextCell();
					$oCell->setWidth(120);
					$oCell->createTextRun('COMPLIANCE')->getFont()->setBold(true)->setSize(9);
					$oCell->getActiveParagraph()->getAlignment()
						->setMarginBottom(5)
						->setMarginTop(5)
						->setMarginRight(5)
						->setMarginLeft(5)
						->setVertical(Alignment::VERTICAL_CENTER)
						->setHorizontal(Alignment::HORIZONTAL_CENTER);

					$oCell = $row->nextCell();
					$oCell->setWidth(105);
					$oCell->createTextRun('INTERVAL')->getFont()->setBold(true)->setSize(9);
					$oCell->getActiveParagraph()->getAlignment()
						->setMarginBottom(5)
						->setMarginTop(5)
						->setMarginRight(5)
						->setMarginLeft(5)
						->setVertical(Alignment::VERTICAL_CENTER)
						->setHorizontal(Alignment::HORIZONTAL_CENTER);

					$oCell = $row->nextCell();
					$oCell->setWidth(105);
					$oCell->createTextRun('TOLERANCE')->getFont()->setBold(true)->setSize(9);
					$oCell->getActiveParagraph()->getAlignment()
						->setMarginBottom(5)
						->setMarginTop(5)
						->setMarginRight(5)
						->setMarginLeft(5)
						->setVertical(Alignment::VERTICAL_CENTER)
						->setHorizontal(Alignment::HORIZONTAL_CENTER);

					$oCell = $row->nextCell();
					$oCell->setWidth(120);
					$oCell->createTextRun('NEXT DUE')->getFont()->setBold(true)->setSize(9);
					$oCell->getActiveParagraph()->getAlignment()
						->setMarginBottom(5)
						->setMarginTop(5)
						->setMarginRight(5)
						->setMarginLeft(5)
						->setVertical(Alignment::VERTICAL_CENTER)
						->setHorizontal(Alignment::HORIZONTAL_CENTER);

					$oCell = $row->nextCell();
					$oCell->setWidth(110);
					$oCell->createTextRun('REMAINING')->getFont()->setBold(true)->setSize(9);
					$oCell->getActiveParagraph()->getAlignment()
						->setMarginBottom(5)
						->setMarginTop(5)
						->setMarginRight(5)
						->setMarginLeft(5)
						->setVertical(Alignment::VERTICAL_CENTER)
						->setHorizontal(Alignment::HORIZONTAL_CENTER);

					foreach ($row->getCells() as $cell) {
					    $cell->getBorders()->getTop()->setLineWidth(2)
					                                 ->setLineStyle(Border::LINE_SINGLE)
					                                 ->setDashStyle(Border::DASH_DASH);
					}
					$i++;
				}

				$refCompType = $value['ataCode']['reference'];

				//Current hours cycles 
		        $currHC = '';       
		        if(!empty($value['currHC']['currHCHrs'])) {
		            $currHC .= 'Hours: '.$value['currHC']['currHCHrs'].' ';
		        }
		        if(!empty($value['currHC']['currHCAfl'])) {
		            $currHC .= 'Cycles: '.$value['currHC']['currHCAfl'].' ';
		        }

		        //Last Compiled With
		        $lastCW = '';
		        if(!empty($value['lastCW']['lastCwMos'])) {
		            $lastCW .= $value['lastCW']['lastCwMos'].' ';
		        }
		        
		        if(!empty($value['lastCW']['lastCwHrs'])) {
		            $lastCW .= 'Hours: '.$value['lastCW']['lastCwHrs'].' ';
		        }

		        if(!empty($value['lastCW']['lastCwAfl'])) {
		            $lastCW .= 'Cycles: '.$value['lastCW']['lastCwAfl'].' ';
		        }

		        //Interval
		        $reqFeq = '';
		        if(!empty($value['reqFeq']['reqFreqMos'])) {
		            $reqFeq .= 'Months: '.$value['reqFeq']['reqFreqMos'].' ';
		        }

		        if(!empty($value['reqFeq']['reqFreqDays'])) {
		            $reqFeq .= 'Days: '.$value['reqFeq']['reqFreqDays'].' ';
		        }

		        if(!empty($value['reqFeq']['reqFreqHrs'])) {
		            $reqFeq .= 'Hours: '.$value['reqFeq']['reqFreqHrs'].' ';
		        }

		        if(!empty($value['reqFeq']['reqFreqAfl'])) {
		            $reqFeq .= 'Cycles: '.$value['reqFeq']['reqFreqAfl'].' ';
		        }

		        //Tolerance
		        $tolrData = '';
		        if(!empty($value['tolerance']['totalTlrD'])) {
		            $tolrData .= 'Days: '.$value['tolerance']['totalTlrD'].' ';
		        }

		        if(!empty($value['tolerance']['tolrHrs'])) {
		            $tolrData .= 'Hours: '.$value['tolerance']['tolrHrs'].' ';
		        }

		        if(!empty($value['tolerance']['tolrAfl'])) {
		            $tolrData .= 'Cycles: '.$value['tolerance']['tolrAfl'].' ';
		        }

		        //Next Due
		        $nextDue = '';
		        if(!empty($value['nextDue']['mos'])) {
		            $nextDue .= $value['nextDue']['mos'].' ';
		        }

		        if(!empty($value['nextDue']['hrs'])) {
		            $nextDue .= 'Hours: '.$value['nextDue']['hrs'].' ';
		        }

		        if(!empty($value['nextDue']['afl'])) {
		            $nextDue .= 'Cycles: '.$value['nextDue']['afl'].' ';
		        }

		        //Remaining
		        $remainingM = '';
		        $rcolorM = 'FF036a03';
		        if(!empty($value['remaining']['remMos'])) {
		        	$rcolorM = ltrim($value['alert']['altDColor'], 'color:#');
		            $remainingM = 'Months: '.$value['remaining']['remMos'].' ';
		        }

		        $remainingD = '';
		        $rcolorD = 'FF036a03';
		        if(!empty($value['remaining']['remDays'])) {
		            $rcolorD = ltrim($value['alert']['altDColor'], 'color:#');		            
		            $remainingD = 'Days: '.$value['remaining']['remDays'].' ';
		        }

		        $remainingH = '';
		        $rcolorH = 'FF036a03';
		        if(!empty($value['remaining']['remHrs'])) {
		            $rcolorH = ltrim($value['alert']['altHColor'], 'color:#');
		            $remainingH = 'Hours: '.round($value['remaining']['remHrs'],1).' ';
		        }

		        $remainingC = '';
		        $rcolorC = 'FF036a03';
		        if(!empty($value['remaining']['remAfl'])) {
		            $rcolorC = ltrim($value['alert']['altAColor'], 'color:#');
		            $remainingC = 'Cycles: '.$value['remaining']['remAfl'].' ';
		        }

				$row = $shape->createRow();
											
				$oCell = $row->nextCell();
				$oCell->createTextRun($value['plane']['plane_code'])->getFont()->setBold(false)->setSize(10);
				$oCell->getActiveParagraph()->getAlignment()
					->setMarginBottom(5)
					->setMarginTop(5)
					->setMarginRight(5)
					->setMarginLeft(5)
					->setVertical(Alignment::VERTICAL_CENTER)
					->setHorizontal(Alignment::HORIZONTAL_CENTER);

				$oCell = $row->nextCell();
				$oCell->createTextRun($value['ataCode']['ata_value'])->getFont()->setBold(false)->setSize(10);
				$oCell->getActiveParagraph()->getAlignment()
					->setMarginBottom(5)
					->setMarginTop(5)
					->setMarginRight(5)
					->setMarginLeft(5)
					->setVertical(Alignment::VERTICAL_CENTER)
					->setHorizontal(Alignment::HORIZONTAL_CENTER);

				$oCell = $row->nextCell();
				$oCell->createTextRun($refCompType)->getFont()->setBold(false)->setSize(10);
				$oCell->getActiveParagraph()->getAlignment()
					->setMarginBottom(1)
					->setMarginTop(1)
					->setMarginRight(1)
					->setMarginLeft(1)
					->setVertical(Alignment::VERTICAL_CENTER)
					->setHorizontal(Alignment::HORIZONTAL_CENTER);
				/*$oCell->createTextRun('     --------------------   ')->getFont()->setBold(true)->setSize(9);
				$oCell->createTextRun($value['partDet']['partDesc'])->getFont()->setBold(false)->setSize(9);
				$oCell->getActiveParagraph()->getAlignment()
					->setMarginBottom(1)
					->setMarginTop(1)
					->setMarginRight(1)
					->setMarginLeft(1)
					->setVertical(Alignment::VERTICAL_CENTER)
					->setHorizontal(Alignment::HORIZONTAL_CENTER);*/

				$oCell = $row->nextCell();
				$oCell->createTextRun($value['partDet']['partDesc'])->getFont()->setBold(false)->setSize(9);
				$oCell->getActiveParagraph()->getAlignment()
					->setMarginBottom(1)
					->setMarginTop(1)
					->setMarginRight(1)
					->setMarginLeft(1)
					->setVertical(Alignment::VERTICAL_CENTER)
					->setHorizontal(Alignment::HORIZONTAL_CENTER);

				$oCell = $row->nextCell();
				$oCell->createTextRun($lastCW)->getFont()->setBold(false)->setSize(10);
				$oCell->getActiveParagraph()->getAlignment()
					->setMarginBottom(5)
					->setMarginTop(5)
					->setMarginRight(5)
					->setMarginLeft(5)
					->setVertical(Alignment::VERTICAL_CENTER)
					->setHorizontal(Alignment::HORIZONTAL_CENTER);

				$oCell = $row->nextCell();
				$oCell->createTextRun($reqFeq)->getFont()->setBold(false)->setSize(10);
				$oCell->getActiveParagraph()->getAlignment()
					->setMarginBottom(5)
					->setMarginTop(5)
					->setMarginRight(5)
					->setMarginLeft(5)
					->setVertical(Alignment::VERTICAL_CENTER)
					->setHorizontal(Alignment::HORIZONTAL_CENTER);

				$oCell = $row->nextCell();
				$oCell->createTextRun($tolrData)->getFont()->setBold(false)->setSize(10);
				$oCell->getActiveParagraph()->getAlignment()
					->setMarginBottom(5)
					->setMarginTop(5)
					->setMarginRight(5)
					->setMarginLeft(5)
					->setVertical(Alignment::VERTICAL_CENTER)
					->setHorizontal(Alignment::HORIZONTAL_CENTER);

				$oCell = $row->nextCell();
				$oCell->createTextRun($nextDue)->getFont()->setBold(false)->setSize(10);
				$oCell->getActiveParagraph()->getAlignment()
					->setMarginBottom(5)
					->setMarginTop(5)
					->setMarginRight(5)
					->setMarginLeft(5)
					->setVertical(Alignment::VERTICAL_CENTER)
					->setHorizontal(Alignment::HORIZONTAL_CENTER);

				$oCell = $row->nextCell();
				$oCell->createTextRun($remainingM)->getFont()->setBold(false)->setSize(10)->setColor( new Color($rcolorM) );
				$oCell->createTextRun($remainingD)->getFont()->setBold(false)->setSize(10)->setColor( new Color($rcolorD) );
				$oCell->createTextRun($remainingH)->getFont()->setBold(false)->setSize(10)->setColor( new Color($rcolorH) );
				$oCell->createTextRun($remainingC)->getFont()->setBold(false)->setSize(10)->setColor( new Color($rcolorC) );
				//setColor( new Color($rcolor) )
				$oCell->getActiveParagraph()->getAlignment()
					->setMarginBottom(5)
					->setMarginTop(5)
					->setMarginRight(5)
					->setMarginLeft(5)
					->setVertical(Alignment::VERTICAL_CENTER)
					->setHorizontal(Alignment::HORIZONTAL_CENTER);
				
				$j++;

				$oCell = new Transition();
				$oCell->setManualTrigger(false);
				$oCell->setTimeTrigger(true, 5000);
				$oCell->setTransitionType(Transition::TRANSITION_COVER_RIGHT);
				$slide0->setTransition($oCell);
				$slide->setTransition($oCell);
			}

		} else {
			$row = $shape->createRow();
			$row->getFill()->setFillType(Fill::FILL_SOLID)
						   ->setStartColor(new Color('ffffff'))
			               ->setEndColor(new Color('ffffff'));
			$cell = $row->nextCell();
			$cell->setColSpan(9);
			$cell->createTextRun('No Record Found')->getFont()->setBold(true)->setSize(10);
			$cell->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_CENTER);
		}
		
		$oWriterPPTX = IOFactory::createWriter($objPHPPresentation, 'PowerPoint2007');
		$fileName = date('YmdHis').".pptx";
		$res = $oWriterPPTX->save(WWW_ROOT.PPT_DIR.$fileName);
		//Read pptx
		/*$pptReader = IOFactory::createReader($objPHPPresentation, 'PowerPoint2007');
		$res2 = $pptReader->load(WWW_ROOT.PPT_DIR.$fileName);
		$oTree = new PhpPptTree($res2);
		echo $oTree->display();*/

		//$this->set(compact(['fileName']));
		
        $result = array('status'=>'success', 'data'=>ROOT_DIR.PPT_DIR.$fileName, 'filename'=>$fileName);
        echo json_encode($result);die;
    }
}