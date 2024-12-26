<?php class Report_library extends MX_Controller{
	var $table='pmon__m_project_setup';
	var $id_col='PROJECT_ID';
	function __construct(){
      	 parent::__construct();
	}
	protected function getSessionID($Month, $Year){
		//$startDate = $Year."-".$Month."- "
		if($Month <= 3){			
			$startYear = $Year-1;
			$endYear = $Year;
		}else{
			$startYear = $Year;
			$endYear = $Year+1;	
		}
		//echo $startYear .'-'. $endYear;
		$this->db->select('SESSION_ID');		
		$arrWhere = array('SESSION_START_YEAR'=>$startYear, 'SESSION_END_YEAR'=>$endYear);
		$recs  = $this->db->get_where('__sessions', $arrWhere);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			return $rec->SESSION_ID;
		}
		return 0;
	}
	protected function getFinancialMonthFromMonth($month){
		return (($month<4) ? ($month+9) :($month-3));
	}
	//
	protected function getProjectIDs($searchData){
		$where = array();
		//showArrayValues($searchData);
		if($searchData['CE_ID']>0) array_push($where, " AND p.OFFICE_CE_ID = " .$searchData['CE_ID']);
		if($searchData['SE_ID']>0) array_push($where, " AND p.OFFICE_SE_ID = " .$searchData['SE_ID']);
		if($searchData['EE_ID']>0) array_push($where, " AND p.OFFICE_EE_ID = " .$searchData['EE_ID']);
		//if($searchData['SDO_ID']>0) array_push($where, " AND p.SDO_ID = " .$searchData['SDO_ID']);
		$where = ((count($where)>0) ? implode('', $where): '');
		//FIRST - get all the project id
		$strSQL = 'SELECT DISTINCT p.PROJECT_ID
			FROM pmon__v_projectlist_with_lock as p
				WHERE SETUP_LOCK=1 
					AND PROJECT_TYPE_ID='.$this->session->userData('PROJECT_TYPE_ID').'
					'.$where.
				' ORDER BY p.PROJECT_ID ';
		$recs = $this->db->query($strSQL);
		$projectIDs = array();
		//echo $this->db->last_query().'<br />';
		if($recs && $recs->num_rows()){
			foreach ($recs->result() as $rec){
				array_push($projectIDs, $rec->PROJECT_ID);
			}
		}
		//showArrayValues($projectIDs);
		//echo count($projectIDs). ' ';
		return $projectIDs;
	}
	protected function getOnGoingProjectIDsStartOfSession($sessionID, $searchData){
		$ids = $this->getProjectIDs($searchData);
		//showArrayValues($ids);
		//echo 'Tango1:'.count($ids).'<BR/>C prj:';

		if (count($ids)==0) return $ids;
		
		$sYear = $searchData['YEAR'];
		if($searchData['MONTH']<4)	$sYear--;
		$searchDate = $sYear.'-04-01';
		
		$pSessionID = $sessionID - 1;
		$pWhere = '.PROJECT_ID IN ('.implode(',', $ids).')';
		//get project which is completed before the report month
		$strSQL = 'SELECT mc.PROJECT_ID FROM pmon__t_monthlydata as mc 
			WHERE mc'.$pWhere . '
				AND mc.MONTH_DATE<"'.$searchDate.'" 
				AND mc.PROJECT_STATUS=5 ';
		$recs = $this->db->query($strSQL);
		$completedProjectIDs = array();
		//echo $this->db->last_query();
		if($result){
			if( $recs->num_rows()>0 ){ 
				foreach ($recs->result() as $rec){
					array_push($completedProjectIDs, $rec->PROJECT_ID);
				}
			}
		}
		/*$endtime = microtime(true);
		echo $endtime-$starttime.'<br/>Diff:';
		$starttime = microtime(true);*/
		$diff_ids = array_diff($ids, $completedProjectIDs);
		/*$endtime = microtime(true);
		echo $endtime-$starttime.'<br/>SQL :';
		$starttime = microtime(true);*/
		return $diff_ids;
	}
	protected function getOnGoingOrNewProjectID($searchData){
		$ids = $this->getProjectIDs($searchData);
		
		//showArrayValues($ids);
		//echo 'Tango1:'.count($ids).'<BR/>C prj:';
		if (count($ids)==0) return $ids;
		//$starttime = microtime(true) ;
		///
		$pSessionID = $searchData['SESSION_ID'] - 1;
		$pWhere = '.PROJECT_ID IN ('.implode(',', $ids).')';
		//get project which is completed before the report month
      //
      $strSQL = 'SELECT mc.PROJECT_ID FROM pmon__t_locks as mc 
			WHERE mc'.$pWhere . '
				AND mc.MONTH_LOCK<"'.$searchData['REPORT_DATE'].'" 
				AND mc.IS_COMPLETED=1 AND SE_COMPLETION=1 ';
		$recs = $this->db->query($strSQL);
		$completedProjectIDs = array();
		//echo $this->db->last_query();
		if($recs && $recs->num_rows()){ 
			foreach ($recs->result() as $rec){
				array_push($completedProjectIDs, $rec->PROJECT_ID);
			}
		}
        $strSQL = 'SELECT p.PROJECT_ID FROM pmon__v_projectlist_with_lock as p 
              WHERE PROJECT_STATUS=6 AND SE_COMPLETION=1 ';
          $recs = $this->db->query($strSQL);
          $droppedProjectIDs = array();
          //echo $this->db->last_query();
          if($recs && $recs->num_rows()){ 
              foreach ($recs->result() as $rec){
                  array_push($droppedProjectIDs, $rec->PROJECT_ID);
              }
          }
		//showArrayValues($completedProjectIDs);
		/*$endtime = microtime(true);
		echo $endtime-$starttime.'<br/>Diff:';
		$starttime = microtime(true);*/
		$diff_ids = array_diff($ids, $completedProjectIDs);
      $diff_ids = array_diff($diff_ids, $droppedProjectIDs);
		/*$endtime = microtime(true);
		echo $endtime-$starttime.'<br/>SQL :';
		$starttime = microtime(true);*/
		return $diff_ids;
	}
	//
	protected function getProjectRecords($sessionID, $mCheckDate, $searchData){
		$projectIDs = $this->getOnGoingOrNewProjectID($searchData);
		//echo 'Ongoing';
		//showArrayValues($projectIDs);
		if(count($projectIDs)==0) return FALSE;
		$pWhere = '.PROJECT_ID IN ('.implode(',', $projectIDs).')';
		$strSQL = "SELECT DISTINCT p.PROJECT_ID, p.PROJECT_NAME, 
				p.PROJECT_TYPE_ID, p.OFFICE_EE_ID, 
				p.PROJECT_SUB_TYPE_ID, p.PROJECT_SUB_TYPE, 
				ps.AA_NO, ps.AA_DATE, ps.AA_AMOUNT, ps.PROJECT_COMPLETION_DATE, 
				p.OFFICE_EE_NAME AS OFFICE_NAME,
				p.OFFICE_CE_ID, p.OFFICE_SE_ID,
				p.OFFICE_CE_NAME, p.OFFICE_SE_NAME,
				estqty.EXPENDITURE_TOTAL, 
				estqty.LA_NO_NA, estqty.LA_HA_NA, 
				estqty.LA_COMPLETED_NO_NA, estqty.LA_COMPLETED_HA_NA, 
				estqty.FA_HA_NA, estqty.FA_COMPLETED_HA_NA, estqty.HEAD_WORKS_EARTHWORK_NA, 
				estqty.HEAD_WORKS_MASONRY_NA, estqty.CANAL_EARTHWORK_NA, 
				estqty.CANAL_STRUCTURES_NA, estqty.CANAL_LINING_NA,
				ps.GRANT_NO, 
				(CONCAT(bh.DEMAND_NO, '-', bh.MAJOR_HEAD, '-', bh.SUB_MAJOR_HEAD, '-', 
					bh.MINOR_HEAD, '-', bh.SEGMENT_CODE, '-', bh.SCHEME_CODE, '-', 
					bh.OBJECT_HEAD, '-', bh.DETAIL_HEAD))as MINOR_BUDGET_HEAD,
				(CONCAT(bhm.DEMAND_NO, '-', bhm.MAJOR_HEAD, '-', bhm.SUB_MAJOR_HEAD, '-', 
					bhm.MINOR_HEAD, '-', bhm.SEGMENT_CODE, '-', bhm.SCHEME_CODE, '-', 
					bhm.OBJECT_HEAD, '-', bhm.DETAIL_HEAD))as MAJOR_BUDGET_HEAD,
					raa.RAA_NO, raa.RAA_DATE, raa.RAA_AMOUNT 
			FROM pmon__v_projectlist_with_lock AS p 
				Inner Join pmon__m_project_setup AS ps ON p.PROJECT_ID = ps.PROJECT_ID 
				LEFT JOIN __budget_heads AS bh ON ps.BUDGET_MINOR_HEAD_ID = bh.ID 
				LEFT JOIN __budget_heads AS bhm ON ps.BUDGET_MAJOR_HEAD_ID = bhm.ID 
				LEFT JOIN(
					SELECT rp.PROJECT_ID, rp.RAA_NO, rp.RAA_DATE, rp.RAA_AMOUNT 
					FROM pmon__t_raa_project as rp 
						INNER JOIN (
							SELECT MAX(raa1.RAA_DATE),  raa1.RAA_PROJECT_ID, raa1.PROJECT_ID 
								FROM pmon__t_raa_project as raa1 
							WHERE raa1.RAA_DATE<='". $mCheckDate .
							"' AND raa1". $pWhere ." 
							GROUP BY PROJECT_ID
							ORDER BY PROJECT_ID
						)AS raamax ON(raamax.RAA_PROJECT_ID=rp.RAA_PROJECT_ID)
					ORDER BY PROJECT_ID 
				)as raa ON p.PROJECT_ID = raa.PROJECT_ID 
				LEFT JOIN pmon__t_estimated_qty AS estqty 
					ON (estqty.PROJECT_ID=p.PROJECT_ID) 
			WHERE p.SETUP_LOCK = 1 
				AND ps.AA_DATE<='". $mCheckDate ."' AND p". $pWhere .
			' ORDER BY OFFICE_EE_ID, OFFICE_SE_ID, OFFICE_CE_ID, OFFICE_NAME, PROJECT_NAME';
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query();		
		return (($recs->num_rows()>0) ? $recs->result() : false);
	} 
	//Physical Progress
	protected function getProjectRecordsForPP($searchData){
		$projectIDs = $this->getOnGoingOrNewProjectID($searchData);
		//echo 'Ongoing';
		//showArrayValues($projectIDs);
		if(count($projectIDs)==0) return FALSE;
		$pWhere = '.PROJECT_ID IN ('.implode(',', $projectIDs).')';
		$strSQL = "SELECT DISTINCT p.PROJECT_ID, p.PROJECT_NAME, 
				p.PROJECT_TYPE_ID, p.OFFICE_EE_ID, 
				p.PROJECT_SUB_TYPE_ID, p.PROJECT_SUB_TYPE, 
				ps.AA_NO, ps.AA_DATE, ps.AA_AMOUNT, ps.PROJECT_COMPLETION_DATE, 
				p.OFFICE_EE_NAME AS OFFICE_NAME,
				p.OFFICE_CE_ID, p.OFFICE_SE_ID,
				p.OFFICE_CE_NAME, p.OFFICE_SE_NAME, ps.PROJECT_SAVE_DATE, 
				estqty.LA_NA, estqty.FA_NA, 
				estqty.HEAD_WORKS_EARTHWORK_NA, estqty.HEAD_WORKS_MASONRY_NA, 
				estqty.STEEL_WORKS_NA, estqty.CANAL_EARTHWORK_NA, 
				estqty.CANAL_STRUCTURES_NA, estqty.CANAL_LINING_NA
			FROM pmon__v_projectlist_with_lock AS p 
				Inner Join pmon__m_project_setup AS ps ON p.PROJECT_ID = ps.PROJECT_ID 
				LEFT JOIN pmon__t_estimated_status AS estqty 
					ON (estqty.PROJECT_ID=p.PROJECT_ID) 
			WHERE p.SETUP_LOCK = 1 
				AND ps.AA_DATE<='". $searchData['REPORT_LAST_DATE'] ."' AND p". $pWhere .
			' ORDER BY OFFICE_EE_ID, OFFICE_SE_ID, OFFICE_CE_ID, OFFICE_NAME, PROJECT_NAME';
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query();		
		return (($recs && $recs->num_rows()) ? $recs->result() : false);
	}
	protected function projectTarget($sessionId, $arrProjectIDs){
		$strSQL = "SELECT yt.PROJECT_ID,
				SUM(yt.LA_NO) AS LA_NO,
				SUM(yt.LA_HA) AS LA_HA,
				(0.00) AS LA_COMPLETED_NO,
				(0.00) AS LA_COMPLETED_HA,
				(0.00) AS FA_COMPLETED_HA,
				SUM(yt.FA_HA) AS FA_HA,
				SUM(yt.HEAD_WORKS_EARTHWORK) AS HEAD_WORKS_EARTHWORK,
				SUM(yt.HEAD_WORKS_MASONRY) AS HEAD_WORKS_MASONRY,
				SUM(yt.STEEL_WORKS) AS STEEL_WORKS,
				SUM(yt.CANAL_EARTHWORK) AS CANAL_EARTHWORK,
				SUM(yt.CANAL_LINING) AS CANAL_LINING, 
				SUM(yt.CANAL_STRUCTURES) AS CANAL_STRUCTURES, 
				SUM(yt.CANAL_MASONRY) AS CANAL_MASONRY, 
				SUM(yt.ROAD_WORKS) AS ROAD_WORKS, 
				SUM(yt.IRRIGATION_POTENTIAL) AS IRRIGATION_POTENTIAL,
				SUM(yt.EXPENDITURE) AS EXPENDITURE, 
				SUM(yt.BUDGET_AMOUNT) AS BUDGET_AMOUNT
			FROM pmon__t_yearlytargets AS yt
				INNER JOIN pmon__t_locks AS pt
					ON(pt.PROJECT_ID=yt.PROJECT_ID 
						AND yt.SESSION_ID<=pt.TARGET_LOCK_SESSION_ID)
			WHERE yt.SESSION_ID = ".$sessionId.'
				AND yt.PROJECT_ID IN ('. implode(',', $arrProjectIDs).')
			GROUP BY yt.PROJECT_ID ';
		$recs = $this->db->query($strSQL);
		return (($recs && $recs->num_rows()) ? $recs->result() : false);
	}
	protected function TargetInMonth($searchData, $arrProjectIDs){
		$strSQL = 'SELECT yt.PROJECT_ID,
				SUM(yt.LA_NO)AS LA_NO, 
				SUM(yt.LA_HA)AS LA_HA, 
				SUM(yt.FA_HA)AS FA_HA, 
				(0) as LA_COMPLETED_NO,
				(0.0) as LA_COMPLETED_HA,
				(0.0) as FA_COMPLETED_HA,
				SUM(yt.HEAD_WORKS_EARTHWORK)AS HEAD_WORKS_EARTHWORK,
				SUM(yt.HEAD_WORKS_MASONRY)AS HEAD_WORKS_MASONRY, 
				SUM(yt.STEEL_WORKS)AS STEEL_WORKS, 
				SUM(yt.CANAL_EARTHWORK)AS CANAL_EARTHWORK, 
				SUM(yt.CANAL_LINING)AS CANAL_LINING, 
				SUM(yt.CANAL_STRUCTURES)AS CANAL_STRUCTURES, 
				SUM(yt.CANAL_MASONRY) AS CANAL_MASONRY, 
				SUM(yt.ROAD_WORKS) AS ROAD_WORKS, 
				SUM(yt.IRRIGATION_POTENTIAL)AS IRRIGATION_POTENTIAL, 
				SUM(yt.EXPENDITURE)AS EXPENDITURE
			FROM pmon__t_yearlytargets AS yt
				INNER JOIN pmon__t_locks AS pt
					ON(pt.PROJECT_ID=yt.PROJECT_ID 
						AND yt.SESSION_ID<=pt.TARGET_LOCK_SESSION_ID)
			WHERE yt.SESSION_ID='.$searchData['SESSION_ID'].
				' AND yt.FINANCIAL_MONTH<='.$searchData['FINANCIAL_MONTH']. '
				AND yt.PROJECT_ID IN ('. implode(',', $arrProjectIDs).')
			GROUP BY yt.PROJECT_ID ';
		$recs = $this->db->query($strSQL);
		return (($recs && $recs->num_rows()) ? $recs->result() : false);
	}
	protected function getMonthlyProgress($searchData, $arrProjectIDs){
		$strSQL = 'SELECT pmm.PROGRESS, pmm.PROJECT_ID 
			FROM pmon__t_progress AS pmm 
				INNER JOIN ( 
					SELECT MAX(px.PROGRESS_DATE) AS MXPM, px.PROJECT_ID 
						FROM pmon__t_progress AS px 
						INNER JOIN pmon__t_locks AS pt 
							ON(pt.PROJECT_ID=px.PROJECT_ID AND px.PROGRESS_DATE<=pt.MONTH_LOCK) 
							WHERE px.PROJECT_ID IN ('. implode(',', $arrProjectIDs).')
							 AND px.PROGRESS_DATE <= "'.$searchData['REPORT_DATE'].'"
					 GROUP BY PROJECT_ID 
				)AS maxMonth ON (pmm.PROJECT_ID=maxMonth.PROJECT_ID and maxMonth.MXPM=pmm.PROGRESS_DATE)';
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query();
		return (($recs && $recs->num_rows()) ? $recs->result() : false);
	}
	protected function getEstimatedStatusRecord($arrProjectIDs){
		if(!count($arrProjectIDs)) return false;
		$this->db->order_by('PROJECT_ID', 'ASC');
		$this->db->where_in('PROJECT_ID', $arrProjectIDs);
		$recs = $this->db->get('pmon__t_estimated_status');
		$data = array();
		if($recs && $recs->num_rows()){
			foreach($recs->result() as $rec){
				$data[$rec->PROJECT_ID] = array(
					'LA_NA'=>$rec->LA_NA, 
					'FA_NA'=>$rec->FA_NA, 
					'HEAD_WORKS_EARTHWORK_NA'=>$rec->HEAD_WORKS_EARTHWORK_NA, 
					'HEAD_WORKS_MASONRY_NA'=>$rec->HEAD_WORKS_MASONRY_NA, 
					'STEEL_WORKS_NA'=>$rec->STEEL_WORKS_NA, 
					'CANAL_EARTHWORK_NA'=>$rec->CANAL_EARTHWORK_NA, 
					'CANAL_STRUCTURES_NA'=>$rec->CANAL_STRUCTURES_NA, 
					'CANAL_LINING_NA'=>$rec->CANAL_LINING_NA,
					'ROAD_WORKS_NA'=>$rec->ROAD_WORKS_NA,
					'IRRIGATION_POTENTIAL_NA'=>$rec->IRRIGATION_POTENTIAL_NA
				);
			}
		}
		return $data;
	}
	protected function getEstimatedRecord($searchData, $arrProjectIDs){
		if(!count($arrProjectIDs)) return false;
		$where = array();
		if($searchData['CE_ID']>0) array_push($where, " AND p.OFFICE_CE_ID = " .$searchData['CE_ID']);
		if($searchData['SE_ID']>0) array_push($where, " AND p.OFFICE_SE_ID = " .$searchData['SE_ID']);
		if($searchData['EE_ID']>0) array_push($where, " AND p.OFFICE_EE_ID = " .$searchData['EE_ID']);
		//if($searchData['SDO_ID']>0) array_push($where, " AND p.OFFICE_SDO_ID = " .$searchData['SDO_ID']);
		array_push($where, ' AND p.PROJECT_ID IN ('. implode(',', $arrProjectIDs).')');
		$strSQL = 'SELECT p.PROJECT_ID, p.OFFICE_EE_ID, 
				e.LA_NO, e.LA_HA, e.LA_COMPLETED_NO, e.LA_COMPLETED_HA, 
				e.FA_HA, e.FA_COMPLETED_HA, e.HEAD_WORKS_EARTHWORK, 
				e.HEAD_WORKS_MASONRY, e.STEEL_WORKS, e.CANAL_EARTHWORK, e.CANAL_LINING, 
				e.CANAL_STRUCTURES, e.CANAL_MASONRY, e.ROAD_WORKS, e.IRRIGATION_POTENTIAL 
			FROM pmon__v_projectlist_with_lock as p 
				LEFT Join (
					SELECT eq.PROJECT_ID, eq.LA_NO, eq.LA_HA, eq.LA_COMPLETED_NO, 
						eq.LA_COMPLETED_HA, 
						eq.FA_HA, eq.FA_COMPLETED_HA, eq.HEAD_WORKS_EARTHWORK, 
						eq.HEAD_WORKS_MASONRY, eq.STEEL_WORKS, eq.CANAL_EARTHWORK, eq.CANAL_LINING, 
						eq.CANAL_STRUCTURES, eq.CANAL_MASONRY, eq.ROAD_WORKS, eq.IRRIGATION_POTENTIAL 
					FROM pmon__t_estimated_qty AS eq
					INNER JOIN (
						SELECT MAX(e.RAA_ID)as RAA_ID, e.ESTIMATED_QTY_ID, e.PROJECT_ID FROM pmon__t_estimated_qty as e
							INNER JOIN pmon__t_raa_project as ra ON(e.PROJECT_ID=ra.PROJECT_ID and e.RAA_ID=ra.RAA_PROJECT_ID)
							WHERE e.PROJECT_ID IN ('. implode(',', $arrProjectIDs).') 
							GROUP BY e.PROJECT_ID
					)AS eq2 ON(eq.PROJECT_ID=eq2.PROJECT_ID and eq.ESTIMATED_QTY_ID=eq2.ESTIMATED_QTY_ID)
				)AS e ON(p.PROJECT_ID=e.PROJECT_ID) 
			WHERE p.SETUP_LOCK=1 
				'.((count($where)>0) ? implode('', $where): '').' 
			GROUP BY p.PROJECT_ID, p.OFFICE_EE_ID 
				ORDER BY PROJECT_ID';
		$recs = $this->db->query($strSQL);
		// echo $this->db->last_query();
		return (($recs && $recs->num_rows()) ? $recs->result() : false);
	}
	protected function getAchievementEndOfLastFY($searchData, $arrProjectIDs){
		if(!count($arrProjectIDs)) return false;
		$where = array();
		if($searchData['CE_ID']) array_push($where, " AND p.OFFICE_CE_ID = " .$searchData['CE_ID']);
		if($searchData['SE_ID']) array_push($where, " AND p.OFFICE_SE_ID = " .$searchData['SE_ID']);
		if($searchData['EE_ID']) array_push($where, " AND p.OFFICE_EE_ID = " .$searchData['EE_ID']);
		//if($searchData['SDO_ID']>0) array_push($where, " AND p.OFFICE_SDO_ID = " .$searchData['SDO_ID']);
		array_push($where, ' AND p.PROJECT_ID IN ('. implode(',', $arrProjectIDs).')');
		$strSQL = 'SELECT p.PROJECT_ID, p.OFFICE_EE_ID,
				sum(m.LA_NO) as LA_NO, 
				sum(m.LA_HA) as LA_HA, 
				sum(m.LA_COMPLETED_NO) as LA_COMPLETED_NO, 
				sum(m.LA_COMPLETED_HA) as LA_COMPLETED_HA,
				sum(m.FA_HA)as FA_HA,
				sum( m.FA_COMPLETED_HA) as FA_COMPLETED_HA,
				sum(m.HEAD_WORKS_EARTHWORK)as HEAD_WORKS_EARTHWORK,	
				sum(m.HEAD_WORKS_MASONRY) as HEAD_WORKS_MASONRY,
				sum(m.STEEL_WORKS) as STEEL_WORKS,
				sum(m.CANAL_EARTHWORK) as CANAL_EARTHWORK,
				sum(m.CANAL_LINING) as CANAL_LINING, 
				sum(m.CANAL_STRUCTURES) as CANAL_STRUCTURES,
				SUM(m.CANAL_MASONRY) AS CANAL_MASONRY, 
				SUM(m.ROAD_WORKS) AS ROAD_WORKS, 
				sum(m.IRRIGATION_POTENTIAL) as IRRIGATION_POTENTIAL
			FROM pmon__v_projectlist_with_lock as p
				LEFT Join pmon__t_achievements as m ON (p.PROJECT_ID = m.PROJECT_ID)
			WHERE p.SETUP_LOCK = 1
				AND m.SESSION_ID <='.($searchData['SESSION_ID']-1).
				((count($where)>0) ? implode('', $where): '').'
			GROUP BY p.PROJECT_ID, p.OFFICE_EE_ID
			ORDER BY PROJECT_ID';	
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query();
		//showArrayValues($result);
		return (($recs && $recs->num_rows()) ? $recs->result() : false);
	}
	protected function achivementEndOfMonth($arrProjectIDs, $monthDate, $sessionID){
		if(count($arrProjectIDs)==0) return false;
		$strSQL = 'SELECT m.PROJECT_ID,
					SUM(m.LA_NO) AS LA_NO,
					SUM(m.LA_HA) AS LA_HA,
					SUM(m.LA_COMPLETED_NO) AS LA_COMPLETED_NO,
					SUM(m.LA_COMPLETED_HA) AS LA_COMPLETED_HA,
					SUM(m.FA_HA) AS FA_HA,
					SUM(m.FA_COMPLETED_HA) AS FA_COMPLETED_HA,
					SUM(m.HEAD_WORKS_EARTHWORK) AS HEAD_WORKS_EARTHWORK,
					SUM(m.HEAD_WORKS_MASONRY) AS HEAD_WORKS_MASONRY,
					SUM(m.STEEL_WORKS) AS STEEL_WORKS,
					SUM(m.CANAL_EARTHWORK) AS CANAL_EARTHWORK,
					SUM(m.CANAL_LINING) AS CANAL_LINING,
					SUM(m.CANAL_STRUCTURES) AS CANAL_STRUCTURES,
					SUM(m.CANAL_MASONRY) AS CANAL_MASONRY, 
				SUM(m.ROAD_WORKS) AS ROAD_WORKS, 
					SUM(m.IRRIGATION_POTENTIAL) AS IRRIGATION_POTENTIAL 
				FROM pmon__t_monthlydata AS m 
				WHERE m.PROJECT_ID IN ('. implode(',', $arrProjectIDs).') 
					AND m.SESSION_ID='.$sessionID.' 
					AND m.MONTH_DATE<="'.$monthDate.'" 
					AND m.LOCKED=1 
		 		GROUP BY PROJECT_ID 
				ORDER BY PROJECT_ID';
		//echo $strSQL ;
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query();
		return ($recs->num_rows()>0) ? $recs->result() : false;
	}
	//FINANCIAL PROGRESS
	protected function getTargetFinancial($arrProjectIDs, $sessionID, $tDate){
		//get target till date & target in session
		$strSQL = 'SELECT yt.PROJECT_ID, SUM(yt.EXPENDITURE) AS ct10, (1) as transType
				FROM pmon__t_yearlytargets AS yt 
					INNER JOIN pmon__t_locks AS pt
						ON(pt.PROJECT_ID=yt.PROJECT_ID AND pt.TARGET_LOCK_SESSION_ID>='.$sessionID.')
				WHERE yt.PROJECT_ID IN('.implode(',', $arrProjectIDs).')
					AND yt.YEARLY_TARGET_DATE<="'.$tDate.'"
					AND yt.SESSION_ID='.$sessionID.'
					GROUP BY PROJECT_ID
			UNION ALL
				SELECT yt.PROJECT_ID, SUM(yt.EXPENDITURE) AS ct10, (2) as transType
				FROM pmon__t_yearlytargets AS yt 
					INNER JOIN pmon__t_locks AS pt
						ON(pt.PROJECT_ID=yt.PROJECT_ID AND pt.TARGET_LOCK_SESSION_ID>='.$sessionID.')
				WHERE yt.SESSION_ID=pt.TARGET_LOCK_SESSION_ID
					AND yt.PROJECT_ID IN('.implode(',', $arrProjectIDs).')
					AND yt.YEARLY_TARGET_DATE<="'.$tDate.'"
					GROUP BY PROJECT_ID
			UNION ALL
				SELECT yt.PROJECT_ID, yt.BUDGET_AMOUNT AS ct10, (3) as transType
				FROM pmon__t_yearlytargets AS yt 
					INNER JOIN pmon__t_locks AS pt
						ON(pt.PROJECT_ID=yt.PROJECT_ID AND pt.TARGET_LOCK_SESSION_ID>='.$sessionID.')
				WHERE yt.SESSION_ID='.$sessionID.' 
					AND yt.FINANCIAL_MONTH=12 
					AND yt.PROJECT_ID IN('.implode(',', $arrProjectIDs).')
				GROUP BY PROJECT_ID
			ORDER BY PROJECT_ID';
			//'.$sessionID.'
		//echo $strSQL;
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query();
		return ($recs->num_rows()>0) ? $recs->result(): false;
	}
	protected function achievedFinancial($arrProjectIDs, $sessionID, $searchData, $monthDate){
		//1.total expenditure last year 2.total exp. in current year
		$strSQL = 'SELECT a.PROJECT_ID, SUM(a.EXPENDITURE_TOTAL) AS ac1, (1) as transType 
			FROM pmon__t_achievements as a 
				INNER JOIN pmon__t_locks AS pt 
						ON(pt.PROJECT_ID=a.PROJECT_ID AND pt.TARGET_LOCK_SESSION_ID>='.$sessionID.') 
				WHERE a.SESSION_ID<'.$sessionID.
				' AND a.PROJECT_ID IN('.implode(',', $arrProjectIDs).') 
				 GROUP BY PROJECT_ID 
			UNION ALL 
				SELECT a.PROJECT_ID, SUM(a.EXPENDITURE_TOTAL) AS ac1, (2) as transType 
				FROM pmon__t_monthlydata as a 
					INNER JOIN pmon__t_locks AS pt 
						ON(pt.PROJECT_ID=a.PROJECT_ID AND pt.MONTH_LOCK>="'.$monthDate.'") 
				WHERE a.SESSION_ID='.$sessionID.
					' AND a.MONTH_DATE<="'.$monthDate.'" 
					AND a.PROJECT_ID IN('.implode(',', $arrProjectIDs).') '.
				' GROUP BY PROJECT_ID 
			 ORDER BY PROJECT_ID';
		//echo $strSQL;
		$recs = $this->db->query($strSQL);
		// echo $this->db->last_query();
		return ($recs->num_rows()>0) ? $recs->result(): false;
	}
	//
	protected function getLaggingLandmarks($sessionID, $searchData, &$projectIDs){
		$projectIDs = $this->getOnGoingOrNewProjectID($searchData);
		//echo 'Ongoing';
		//showArrayValues($projectIDs);
		if(count($projectIDs)==0) 
			return FALSE;
		$where = array();
		array_push($where, ' WHERE 1 ');
		if($searchData['CE_ID']>0) array_push($where, " AND p.OFFICE_CE_ID = " .$searchData['CE_ID']);
		if($searchData['SE_ID']>0) array_push($where, " AND p.OFFICE_SE_ID = " .$searchData['SE_ID']);
		if($searchData['EE_ID']>0) array_push($where, " AND p.OFFICE_EE_ID = " .$searchData['EE_ID']);
		//if($searchData['SDO_ID']>0) array_push($where, " AND p.SDO_ID = " .$searchData['SDO_ID']);

		array_push($where, ' AND p.PROJECT_ID IN (' .implode(',', $projectIDs).')');
		$where = ((count($where)>0) ? implode('', $where): '');
		$searchDate = date("Y-m-t", strtotime(
			$searchData['YEAR'].'-'.str_pad($searchData['MONTH'], 2, '0', STR_PAD_LEFT).'-01'));

		$strSQL = 'SELECT p.PROJECT_ID,	p.PROJECT_CODE,
			p.PROJECT_TYPE_ID, p.PROJECT_COMPLETION_DATE,
			p.PROJECT_NAME,
			t.LA_TARGET_DATE,
			t.SPILLWAY_TARGET_DATE,
			t.FLANKS_TARGET_DATE,
			t.SLUICES_TARGET_DATE,
			t.NALLA_CLOSURE_TARGET_DATE,
			t.CANAL_EARTHWORK_TARGET_DATE,
			t.CANAL_STRUCTURES_TARGET_DATE,
			t.CANAL_LINING_TARGET_DATE,
			p.OFFICE_EE_NAME AS OFFICE_NAME,
			p.OFFICE_CE_NAME,
			p.OFFICE_SE_NAME,
			p.OFFICE_EE_ID,
			p.OFFICE_SE_ID,
			p.OFFICE_CE_ID,
			estimated.IRRIGATION_POTENTIAL,
			monthly.ENTRY_MONTH, 
			monthly.ENTRY_YEAR,
			IFNULL(monthly.LA_CASES_STATUS, 0) AS LA_CASES_STATUS,
			IFNULL(monthly.SPILLWAY_STATUS, 0) AS SPILLWAY_STATUS, 
			IFNULL(monthly.FLANK_STATUS, 0) AS FLANK_STATUS,
			IFNULL(monthly.SLUICES_STATUS, 0) AS SLUICES_STATUS, 
			IFNULL(monthly.NALLA_CLOSURE_STATUS, 0) AS NALLA_CLOSURE_STATUS, 
			IFNULL(monthly.CANAL_EARTH_WORK_STATUS, 0) AS CANAL_EARTH_WORK_STATUS,
			IFNULL(monthly.MONTHLY_DATA_ID, 0) AS MONTHLY_DATA_ID,
			monthly.CANAL_STRUCTURE_STATUS, 
			monthly.CANAL_LINING_STATUS,
			(monthly.PROJECT_STATUS)AS MONTHLY_PROJECT_STATUS
		FROM pmon__v_projectlist_with_lock AS p
			LEFT Join pmon__t_target_date_completion AS t ON p.PROJECT_ID = t.PROJECT_ID
			LEFT JOIN(
				(
					SELECT ESTIMATED_QTY_ID, PROJECT_ID, IRRIGATION_POTENTIAL 
						FROM pmon__t_estimated_qty
					WHERE ESTIMATED_QTY_ID IN (
						SELECT MAX(a.ESTIMATED_QTY_ID) 
							FROM pmon__t_estimated_qty AS a
						WHERE a.PROJECT_ID IN('.implode(',', $projectIDs).') 
						GROUP BY a.PROJECT_ID
					)
					GROUP BY PROJECT_ID
						ORDER BY PROJECT_ID 
				) AS estimated
			) ON estimated.PROJECT_ID = p.PROJECT_ID 
			LEFT JOIN (
				SELECT m.PROJECT_ID, m.MONTHLY_DATA_ID, m.ENTRY_MONTH, m.ENTRY_YEAR,
					m.LA_CASES_STATUS, m.SPILLWAY_STATUS, m.FLANK_STATUS,
					m.SLUICES_STATUS, m.NALLA_CLOSURE_STATUS, m.CANAL_EARTH_WORK_STATUS,
					m.CANAL_STRUCTURE_STATUS, m.CANAL_LINING_STATUS,
					m.SUBMISSION_DATE AS SUBMISSION_DATE, m.PROJECT_STATUS 
				FROM pmon__t_monthlydata AS m 
					INNER JOIN(
						SELECT MAX(MONTHLY_DATA_ID) AS MONTHLY_DATA_ID 
							FROM pmon__t_monthlydata 
						WHERE PROJECT_ID IN('.implode(',', $projectIDs).') 
							AND MONTH_DATE<="'.$searchDate.'"
						GROUP BY PROJECT_ID 
						ORDER BY PROJECT_ID 
					) AS monthlyLast ON monthlyLast.MONTHLY_DATA_ID = m.MONTHLY_DATA_ID 
				ORDER BY PROJECT_ID
			)AS monthly ON(monthly.PROJECT_ID=p.PROJECT_ID ) 
			'.
		$where.
		' GROUP BY OFFICE_CE_NAME, OFFICE_SE_NAME, OFFICE_NAME, PROJECT_ID
				ORDER BY OFFICE_CE_NAME, OFFICE_SE_NAME, OFFICE_NAME, PROJECT_NAME';
		//echo $strSQL;
		$recs = $this->db->query($strSQL);
		return ($recs->num_rows()>0) ? $recs->result() : false;
	}
	/** OK Not used delete it after checking */
	protected function getLastMonthStatus($arrProjectIDs){
		$strSQL = 'SELECT m.ENTRY_MONTH, m.ENTRY_YEAR,
			m.LA_CASES_STATUS, m.SPILLWAY_STATUS, m.FLANK_STATUS,
			m.SLUICES_STATUS, m.NALLA_CLOSURE_STATUS, m.CANAL_EARTH_WORK_STATUS,
			m.CANAL_STRUCTURE_STATUS, m.CANAL_LINING_STATUS,
			m.SUBMISSION_DATE AS SUBMISSION_DATE
		FROM pmon__t_monthlydata AS m
			INNER JOIN(
				SELECT MAX(MONTHLY_DATA_ID) AS MONTHLY_DATA_ID
					FROM pmon__t_monthlydata 
				WHERE PROJECT_ID IN('.implode(',', $arrProjectIDs).') 
				GROUP BY PROJECT_ID
				ORDER BY PROJECT_ID 
			) AS monthlyLast ON monthlyLast.MONTHLY_DATA_ID = m.MONTHLY_DATA_ID
		WHERE PROJECT_ID = '.$PROJECT_ID.'
		ORDER BY PROJECT_ID';
		return $this->db->query($strSQL)->row();
	}
	//
	protected function getProjectDataForIP($sessionID, $searchData, &$projectIDs){
		//echo 'ssess:'.$sessionID;
		$projectIDs = $this->getOnGoingOrNewProjectID($searchData);
		//showArrayValues($projectIDs);
		//No record found!
		if(count($projectIDs)==0){
			return FALSE;
		}
		$mFinancialMonth = $this->getFinancialMonthFromMonth($searchData['MONTH']);
		//SECOND - filter projects whose status is ongoing at the start of FY 
		//			means Last Months status ongoing or 
		//			status ongoing if setup in this session
		//THIRD - filter project which got AA in this year
		//FOURTH - 
		$strSQL = 'SELECT p.PROJECT_NAME, p.PROJECT_ID,
					p.PROJECT_COMPLETION_DATE, p.PROJECT_TYPE_ID, 
					p.AA_NO, p.AA_DATE, p.AA_AMOUNT, 
					raa.RAA_NO, raa.RAA_DATE, raa.RAA_AMOUNT, 
					p.OFFICE_EE_ID, p.OFFICE_EE_NAME as OFFICE_NAME,
					IFNULL(eqty.IRRIGATION_POTENTIAL, 0) AS AA_IP,
					IFNULL(raa.IRRIGATION_POTENTIAL, 0)AS RAA_IP,
					p.OFFICE_EE_NAME AS OFFICE_NAME,
					p.OFFICE_CE_NAME,
					p.OFFICE_SE_NAME,
					p.OFFICE_EE_ID,
					p.OFFICE_SE_ID,
					p.OFFICE_CE_ID
				FROM pmon__v_projectlist_with_lock as p
					LEFT JOIN pmon__t_estimated_qty as eqty ON (eqty.PROJECT_ID=p.PROJECT_ID AND eqty.RAA_ID=0)
					LEFT JOIN(
						(
							SELECT rp.PROJECT_ID, rp.RAA_NO, rp.RAA_DATE, 
								rp.RAA_AMOUNT, raaqty.IRRIGATION_POTENTIAL
							FROM pmon__t_raa_project as rp
								LEFT JOIN pmon__t_estimated_qty as raaqty 
									ON (raaqty.PROJECT_ID=rp.PROJECT_ID) 
								INNER JOIN(
									SELECT MAX(maxraa.RAA_DATE) AS RAA_DATE, 
										maxraa.RAA_PROJECT_ID, 
										maxraa.PROJECT_ID
									FROM pmon__t_raa_project as maxraa
										WHERE maxraa.PROJECT_ID IN('.implode(',', $projectIDs).') 
									GROUP BY PROJECT_ID 
									ORDER BY PROJECT_ID 
								)AS mraa ON(mraa.RAA_PROJECT_ID=raaqty.RAA_ID )
							ORDER BY rp.PROJECT_ID
						)as raa 
					)ON p.PROJECT_ID = raa.PROJECT_ID 
				WHERE 1 
					AND p.PROJECT_ID IN ('.implode(',', $projectIDs).') '.
				' GROUP BY OFFICE_CE_NAME, OFFICE_SE_NAME, OFFICE_NAME, PROJECT_ID
				ORDER BY OFFICE_CE_NAME, OFFICE_SE_NAME, OFFICE_NAME, PROJECT_NAME';
		$recs = $this->db->query($strSQL);
		// echo $this->db->last_query();
		return ($recs->num_rows() > 0 ) ? $recs->result() : false;
	}
	protected function getIrrigationPotential($sessionID, $searchData, $arrProjectIDs){
		if(!count($arrProjectIDs)) return false;
		/*$where = array();
		if($searchData['CE_ID']>0) array_push($where, " AND p.CE_ID = " .$searchData['CE_ID']);
		if($searchData['SE_ID']>0) array_push($where, " AND p.SE_ID = " .$searchData['SE_ID']);
		if($searchData['EE_ID']>0) array_push($where, " AND p.EE_ID = " .$searchData['EE_ID']);
		if($searchData['SDO_ID']>0) array_push($where, " AND p.SDO_ID = " .$searchData['SDO_ID']);
		array_push($where, ' p.PROJECT_ID IN ('. implode(',', $arrProjectIDs).')');
		*/
		$mFinancialMonth = $this->getFinancialMonthFromMonth($searchData['MONTH']);
		//in below union 
		//1st part - setup in current session
		//2nd part - setup in other than current session
		$strSQL = 'SELECT p.PROJECT_ID, 
				la.IRRIGATION_POTENTIAL AS LY_IRRIGATION_POTENTIAL,
				mm.IRRIGATION_POTENTIAL AS CY_IRRIGATION_POTENTIAL
			FROM pmon__m_project_setup AS p 
				LEFT JOIN pmon__t_achievements as la ON (p.PROJECT_ID=la.PROJECT_ID)
				LEFT JOIN (
						SELECT SUM(IRRIGATION_POTENTIAL) AS IRRIGATION_POTENTIAL, m.PROJECT_ID
						 FROM pmon__t_monthlydata as m 
						 WHERE m.PROJECT_ID IN ('. implode(',', $arrProjectIDs).')
						 	AND m.SESSION_ID='.$sessionID.' AND m.FINANCIAL_MONTH<='.$mFinancialMonth .'
						GROUP BY m.PROJECT_ID
					)AS mm
				ON mm.PROJECT_ID = p.PROJECT_ID
			WHERE p.PROJECT_ID IN ('. implode(',', $arrProjectIDs).')
				AND p.SESSION_ID='.$sessionID .
		' UNION ALL
			SELECT p.PROJECT_ID, 
				m1.IRRIGATION_POTENTIAL AS LY_IRRIGATION_POTENTIAL,
				mm.IRRIGATION_POTENTIAL AS CY_IRRIGATION_POTENTIAL
			FROM pmon__m_project_setup AS p
				LEFT JOIN (
					SELECT SUM(my.IRRIGATION_POTENTIAL) AS IRRIGATION_POTENTIAL, 
						(IFNULL(my.PROJECT_ID, 0))as PROJECT_ID
					 FROM pmon__t_monthlydata as my
					 WHERE my.PROJECT_ID IN ('. implode(',', $arrProjectIDs).')
						AND my.SESSION_ID='.$sessionID.' AND my.FINANCIAL_MONTH<='.$mFinancialMonth .'
					GROUP BY my.PROJECT_ID
				)AS mm ON (mm.PROJECT_ID = p.PROJECT_ID)
				LEFT JOIN (
					SELECT mx.IRRIGATION_POTENTIAL_T AS IRRIGATION_POTENTIAL, 
						(IFNULL(mx.PROJECT_ID, 0))as PROJECT_ID
					 FROM pmon__t_monthlydata as mx
					 WHERE mx.PROJECT_ID IN ('. implode(',', $arrProjectIDs).')
						AND mx.SESSION_ID='.($sessionID-1).' AND mx.FINANCIAL_MONTH=12
				)AS m1 ON (m1.PROJECT_ID=p.PROJECT_ID)
			WHERE p.PROJECT_ID IN ('. implode(',', $arrProjectIDs).')
				AND p.SESSION_ID<>'.$sessionID.
			' ORDER BY PROJECT_ID';
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query();
		return ($recs->num_rows()>0) ? $recs->result() : false;
	}
	protected function getTargetForIP($sessionID, $searchData, $arrProjectIDs){
		if(!count($arrProjectIDs)) return false;
		/*$where = array();
		if($searchData['CE_ID']>0) array_push($where, " AND p.CE_ID = " .$searchData['CE_ID']);
		if($searchData['SE_ID']>0) array_push($where, " AND p.SE_ID = " .$searchData['SE_ID']);
		if($searchData['EE_ID']>0) array_push($where, " AND p.EE_ID = " .$searchData['EE_ID']);
		if($searchData['SDO_ID']>0) array_push($where, " AND p.SDO_ID = " .$searchData['SDO_ID']);
		array_push($where, ' p.PROJECT_ID IN ('. implode(',', $arrProjectIDs).')');
		*/
		$mFinancialMonth = $this->getFinancialMonthFromMonth($searchData['MONTH']);
		$strSQL = 'SELECT t.PROJECT_ID, 
				SUM(t.IRRIGATION_POTENTIAL) AS IRRIGATION_POTENTIAL
			FROM pmon__t_yearlytargets t
			WHERE t.PROJECT_ID IN ('. implode(',', $arrProjectIDs).')
				AND t.SESSION_ID='.$sessionID .
				' AND FINANCIAL_MONTH<='.$mFinancialMonth.
			' ORDER BY PROJECT_ID';
		$recs = $this->db->query($strSQL);
		// echo $this->db->last_query();
		return ($recs->num_rows()>0) ? $recs->result() : false;
	}
	//
	protected function getCompletedProjects($sessionID, $searchData){
		$projectIDs = $this->getProjectIDs($searchData);
		//showArrayValues($projectIDs);
		//No record found!
		if(count($projectIDs)==0)
			return $projectIDs;
			
		$MONTH_DATE = $searchData['YEAR'].'-'.str_pad($searchData['MONTH'], 2, '0', STR_PAD_LEFT).'-01';
		$strSQL = 'SELECT PROJECT_ID 
			FROM pmon__t_monthlydata 
				WHERE PROJECT_ID IN ('.implode(',', $projectIDs).') 
					AND PROJECT_STATUS=5 
					AND MONTH_DATE <="'.$MONTH_DATE  .'"
					 ORDER BY PROJECT_ID';
		$recs = $this->db->query($strSQL);
		$projectIDs = array();
		// echo $this->db->last_query();
		if($result){
			if( $recs->num_rows()>0 ){ 
				foreach ($recs->result() as $rec){
					array_push($projectIDs, $rec->PROJECT_ID);
				}
			}
		}
		//showArrayValues($projectIDs);
		if(count($projectIDs)==0) return $projectIDs;
		// p.PROJECT_TYPE,
		$strSQL = 'SELECT p.PROJECT_ID, p.PROJECT_NAME, p.PROJECT_CODE, 
				p.OFFICE_EE_ID,	p.OFFICE_EE_NAME as OFFICE_NAME, 
				p.OFFICE_SE_ID,	p.OFFICE_SE_NAME, 
				p.OFFICE_CE_ID,	p.OFFICE_CE_NAME, 
				p.DISTRICT_NAME, 
				p.AA_NO, p.AA_DATE, p.AA_AMOUNT, p.EXPENDITURE_TOTAL, 
				p.PROJECT_COMPLETION_DATE, p.LIVE_STORAGE, 
				(GROUP_CONCAT(DISTINCT pb.BLOCK_NAME ORDER BY pb.BLOCK_NAME SEPARATOR ", "))as BLOCK_NAME,
				(GROUP_CONCAT(DISTINCT ab.ASSEMBLY_NAME ORDER BY ab.ASSEMBLY_NAME SEPARATOR ", "))as ASSEMBLY_NAME,
				IFNULL(eqty.IRRIGATION_POTENTIAL, 0) AS AA_IP,
				raa.RAA_NO, raa.RAA_DATE, raa.RAA_AMOUNT, 
				IFNULL(raa.IRRIGATION_POTENTIAL, 0)AS RAA_IP,
				IFNULL(mn.IRRIGATION_POTENTIAL_T, 0)AS D_IP
			FROM pmon__v_projectlist_details_with_lock as p 
				LEFT JOIN pmon__t_estimated_qty as eqty 
					ON (eqty.PROJECT_ID=p.PROJECT_ID AND eqty.RAA_ID=0)
				LEFT JOIN(
					SELECT rp.PROJECT_ID, rp.RAA_NO, rp.RAA_DATE, 
						rp.RAA_AMOUNT, raaqty.IRRIGATION_POTENTIAL
					FROM pmon__t_raa_project as rp
						LEFT JOIN pmon__t_estimated_qty as raaqty ON (raaqty.PROJECT_ID=rp.PROJECT_ID) 
					ORDER BY RAA_PROJECT_ID DESC LIMIT 0, 1
				)as raa ON p.PROJECT_ID = raa.PROJECT_ID 
				LEFT JOIN(
					SELECT deposit__projects_block_served.PROJECT_ID, b.BLOCK_NAME
					FROM deposit__projects_block_served 
						INNER JOIN __blocks as b ON deposit__projects_block_served.BLOCK_ID = b.BLOCK_ID 
				) AS pb ON pb.PROJECT_ID = p.PROJECT_ID
				LEFT JOIN(
					SELECT deposit__projects_assembly_const_served.PROJECT_ID, b.ASSEMBLY_NAME
					FROM deposit__projects_assembly_const_served
						INNER JOIN __m_assembly_constituency as b
							ON __projects_assembly_const_served.ASSEMBLY_ID = b.ASSEMBLY_ID 
				) AS ab ON ab.PROJECT_ID = p.PROJECT_ID  
				LEFT JOIN(
					SELECT PROJECT_ID, IRRIGATION_POTENTIAL_T
						FROM pmon__t_monthlydata
							WHERE PROJECT_ID IN ('.implode(',', $projectIDs).')
								AND PROJECT_STATUS=5
								AND ENTRY_MONTH ='.$searchData['MONTH'].'
								AND ENTRY_YEAR ='.$searchData['YEAR'].
							' ORDER BY PROJECT_ID
				) AS mn ON mn.PROJECT_ID = p.PROJECT_ID 
			WHERE p.PROJECT_ID IN ('.implode(',', $projectIDs).') 
				GROUP BY OFFICE_CE_NAME, OFFICE_SE_NAME, OFFICE_NAME, PROJECT_ID
				ORDER BY OFFICE_CE_ID, OFFICE_SE_ID, OFFICE_NAME, PROJECT_NAME';
		$recs = $this->db->query($strSQL);
		//showArrayValues($recs->result());
		 //echo $this->db->last_query();
		return ($recs->num_rows()>0) ? $recs->result() : false;
	}
	//
	protected function raaReceived($sessionID, $searchData){
		$projectIDs = $this->getProjectIDs($searchData);
		//showArrayValues($projectIDs);
		//No record found!
		if(count($projectIDs)==0)
			return $projectIDs;
		
		if($sessionID==9999){
			$where = ''; //no filter
		}else if($sessionID==0){
			$sDate = $searchData['YEAR'].'-'.str_pad($searchData['MONTH'], 2, '0', STR_PAD_LEFT).'-01';
			$sDate = date("Y-m-t", strtotime($sDate));
			$where = ' AND raa.RAA_DATE="'.$sDate.'"';
		}else{
			$x = $this->getSessionDate($sessionID);
			$where = ' AND raa.RAA_DATE>="'.$x['START_DATE'] . '" '.
				' AND raa.RAA_DATE<="'.$x['END_DATE'].'"';
		}

		$strSQL = 'SELECT p.PROJECT_ID, p.PROJECT_NAME, p.PROJECT_CODE, 
				p.OFFICE_EE_NAME as OFFICE_NAME, 
				__districts.DISTRICT_NAME, p.LIVE_STORAGE, 
				(GROUP_CONCAT(DISTINCT pb.BLOCK_NAME ORDER BY pb.BLOCK_NAME SEPARATOR ", "))as BLOCK_NAME,
				(GROUP_CONCAT(DISTINCT pa.ASSEMBLY_NAME ORDER BY pa.ASSEMBLY_NAME SEPARATOR ", "))as ASSEMBLY_NAME,
				raa.RAA_NO, raa.RAA_DATE, raa.RAA_AMOUNT, 
				IFNULL(raa.IRRIGATION_POTENTIAL, 0)AS RAA_IP
			FROM pmon__v_projectlist_with_lock as p 
				INNER JOIN __districts ON(p.HEAD_WORK_DISTRICT_ID=__districts.DISTRICT_ID)
				LEFT JOIN(
					SELECT rp.PROJECT_ID, rp.RAA_NO, rp.RAA_DATE, 
						rp.RAA_AMOUNT, raaqty.IRRIGATION_POTENTIAL
					FROM pmon__t_raa_project as rp
						INNER JOIN(
							SELECT MAX(maxraa.RAA_DATE) AS RAA_DATE, 
								maxraa.RAA_PROJECT_ID, 
								maxraa.PROJECT_ID
							FROM pmon__t_raa_project as maxraa
								WHERE maxraa.PROJECT_ID IN('.implode(',', $projectIDs).') 
							GROUP BY PROJECT_ID 
							ORDER BY PROJECT_ID 
						)AS mraa ON(mraa.RAA_PROJECT_ID=rp.RAA_PROJECT_ID )
						LEFT JOIN pmon__t_estimated_qty as raaqty 
							ON (raaqty.RAA_ID=rp.RAA_PROJECT_ID) 
					ORDER BY rp.PROJECT_ID
				)as raa ON p.PROJECT_ID = raa.PROJECT_ID 
				LEFT JOIN(
					SELECT deposit__projects_block_served.PROJECT_ID, b.BLOCK_NAME
					FROM deposit__projects_block_served 
						INNER JOIN __blocks as b ON deposit__projects_block_served.BLOCK_ID = b.BLOCK_ID 
				) AS pb ON pb.PROJECT_ID = p.PROJECT_ID 
				LEFT JOIN(
					SELECT deposit__projects_assembly_const_served.PROJECT_ID, b.ASSEMBLY_NAME
					FROM deposit__projects_assembly_const_served 
						INNER JOIN __m_assembly_constituency as b ON deposit__projects_assembly_const_served.ASSEMBLY_ID = b.ASSEMBLY_ID 
				) AS pa ON pa.PROJECT_ID = p.PROJECT_ID 
			WHERE p.PROJECT_ID IN ('.implode(',', $projectIDs).') 
				'.$where.' 
			GROUP BY p.PROJECT_ID
				ORDER BY PROJECT_NAME';
		//echo $strSQL;
		/*INNER JOIN pmon__t_raa_project as raa ON p.PROJECT_ID = raa.PROJECT_ID 
					INNER JOIN pmon__t_estimated_qty as raaqty 
						ON (raaqty.RAA_ID=raa.RAA_PROJECT_ID AND raaqty.RAA_ID<>0) */
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query();
		return ($recs->num_rows()>0) ? $recs->result() : false;
	}
	protected function raaReceivedAll($searchData){
		$projectIDs = $this->getProjectIDs(0, $searchData);
		//showArrayValues($projectIDs);
		//No record found!
		if(count($projectIDs)==0)
			return $projectIDs;
		
		$strSQL = 'SELECT p.PROJECT_ID, p.PROJECT_NAME, p.PROJECT_CODE, 
				p.OFFICE_EE_NAME as OFFICE_NAME, 
				__districts.DISTRICT_NAME, p.LIVE_STORAGE, 
				(GROUP_CONCAT(DISTINCT pb.BLOCK_NAME ORDER BY pb.BLOCK_NAME SEPARATOR ", "))as BLOCK_NAME,
				(GROUP_CONCAT(DISTINCT pa.ASSEMBLY_NAME ORDER BY pa.ASSEMBLY_NAME SEPARATOR ", "))as ASSEMBLY_NAME,
				raa.RAA_NO, raa.RAA_DATE, raa.RAA_AMOUNT, 
				IFNULL(raaqty.IRRIGATION_POTENTIAL, 0)AS RAA_IP
			FROM pmon__v_projectlist_with_lock as p 
				INNER JOIN __districts ON(p.HEAD_WORK_DISTRICT_ID=__districts.DISTRICT_ID)
				INNER JOIN pmon__t_raa_project as raa ON p.PROJECT_ID = raa.PROJECT_ID 
				LEFT JOIN pmon__t_estimated_qty as raaqty 
							ON (raaqty.RAA_ID=raa.RAA_PROJECT_ID) 
				LEFT JOIN(
					SELECT deposit__projects_block_served.PROJECT_ID, b.BLOCK_NAME
					FROM deposit__projects_block_served 
						INNER JOIN __blocks as b ON deposit__projects_block_served.BLOCK_ID = b.BLOCK_ID 
				) AS pb ON pb.PROJECT_ID = p.PROJECT_ID 
				LEFT JOIN(
					SELECT deposit__projects_assembly_const_served.PROJECT_ID, b.ASSEMBLY_NAME
					FROM deposit__projects_assembly_const_served 
						INNER JOIN __m_assembly_constituency as b ON deposit__projects_assembly_const_served.ASSEMBLY_ID = b.ASSEMBLY_ID 
				) AS pa ON pa.PROJECT_ID = p.PROJECT_ID 
			WHERE p.PROJECT_ID IN ('.implode(',', $projectIDs).') 
			GROUP BY p.PROJECT_ID
				ORDER BY PROJECT_NAME';
		//echo $strSQL;
		/*INNER JOIN pmon__t_raa_project as raa ON p.PROJECT_ID = raa.PROJECT_ID 
					INNER JOIN pmon__t_estimated_qty as raaqty 
						ON (raaqty.RAA_ID=raa.RAA_PROJECT_ID AND raaqty.RAA_ID<>0) */
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query();
		return ($recs->num_rows()>0) ? $recs->result() : false;
	}
	protected function getSessionDate($id){
		$rec = $this->db->get_where('__sessions', array('SESSION_ID' => $id));
		if($rec){
			if($rec->num_rows()==1){
				$row = $rec->row(); 
				return array('START_DATE'=> $row->START_DATE,
					'END_DATE'=> $row->END_DATE);
			}
		}
		return array('START_DATE'=>0, 'END_DATE'=>0);
	}
	//
	protected function aaReceived($sessionID, $searchData){
		$projectIDs = $this->getOnGoingOrNewProjectID($searchData);
echo count($projectIDs);
		//$projectIDs = $this->getProjectIDs($searchData);
		//showArrayValues($projectIDs);
		//No record found!
		if(count($projectIDs)==0)
			return $projectIDs;

		if($sessionID==9999){
			$where ='';
		}else if($sessionID==0){
			$sDate = $searchData['YEAR'].'-'.str_pad($searchData['MONTH'], 2, '0', STR_PAD_LEFT).'-01';
			$sDate = date("Y-m-t", strtotime($sDate));
			$where = ' AND AA_DATE="'.$sDate.'"';
		}else{
			$x = $this->getSessionDate($sessionID);
			$where = ' AND AA_DATE>="'.$x['START_DATE'] . '" '.
				' AND AA_DATE<="'.$x['END_DATE'].'"';
		}
		$strSQL = 'SELECT p.PROJECT_ID, p.PROJECT_NAME, p.PROJECT_CODE, 
				p.OFFICE_EE_NAME as OFFICE_NAME, 
				__districts.DISTRICT_NAME, p.LIVE_STORAGE, 
				(GROUP_CONCAT(DISTINCT pb.BLOCK_NAME ORDER BY pb.BLOCK_NAME SEPARATOR ", "))as BLOCK_NAME,
				(GROUP_CONCAT(DISTINCT pa.ASSEMBLY_NAME ORDER BY pa.ASSEMBLY_NAME SEPARATOR ", "))as ASSEMBLY_NAME,
				p.AA_NO, p.AA_DATE, p.AA_AMOUNT, (0)AS AA_IP
			FROM pmon__v_projectlist_with_lock as p 
				INNER JOIN __districts ON(p.HEAD_WORK_DISTRICT_ID=__districts.DISTRICT_ID)
				LEFT JOIN(
					SELECT deposit__projects_block_served.PROJECT_ID, b.BLOCK_NAME
					FROM deposit__projects_block_served 
						INNER JOIN __blocks as b ON deposit__projects_block_served.BLOCK_ID = b.BLOCK_ID 
				) AS pb ON pb.PROJECT_ID = p.PROJECT_ID 
				LEFT JOIN(
					SELECT deposit__projects_assembly_const_served.PROJECT_ID, b.ASSEMBLY_NAME
					FROM deposit__projects_assembly_const_served 
						INNER JOIN __m_assembly_constituency as b ON deposit__projects_assembly_const_served.ASSEMBLY_ID = b.ASSEMBLY_ID 
				) AS pa ON pa.PROJECT_ID = p.PROJECT_ID 
			WHERE p.PROJECT_ID IN ('.implode(',', $projectIDs).') 
				'.$where.' 
			GROUP BY PROJECT_ID 
				ORDER BY AA_DATE, PROJECT_NAME';
				//AND YEAR(p.AA_DATE)=2013'.'
				//AND MONTH(p.AA_DATE) ='.$searchData['MONTH'].'
				//AND YEAR(p.AA_DATE) ='.$searchData['YEAR'].'
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query();
		return ($recs->num_rows()>0) ? $recs->result() : false;
	}
	//
	protected function getAbstractReport($sessionID, $searchData){
		//$arrProjectIDs = $this->getProjectID($sessionID, $searchData);
		$arrProjectIDs = array_unique(  $this->getOnGoingProjectIDsStartOfSession($sessionID, $searchData) );
		$arrOngoingProjectIDs = array_unique( $this->getOnGoingOrNewProjectID($searchData) );
		if(count($arrProjectIDs)==0) return FALSE;
		$strProjectIDs = 'PROJECT_ID IN ('.implode(',', $arrProjectIDs).')';
		//echo 'Projects :'.count($arrProjectIDs). ' -- ';
		//echo 'Ongoing:'.count($arrOngoingProjectIDs);
		$strOngoingProjectIDs = 'PROJECT_ID IN ('.implode(',', $arrOngoingProjectIDs).')';
		//echo $strProjectIDs ;
		$YEAR = $searchData['YEAR'];
		$MONTH = $searchData['MONTH'];
		$searchYear = $YEAR;
		$startYear = $YEAR;
		if($MONTH<4) $startYear--;
		
		$startDate = $startYear.'-04-01';
		$tillDate = date("Y-m-t", strtotime( $YEAR.'-'.str_pad($searchData['MONTH'], 2,"0", STR_PAD_LEFT).'-01'));
		
		$reportDate = $searchData['YEAR'].'-'. str_pad($searchData['MONTH'], 2,"0", STR_PAD_LEFT).'-01';
		$strSQL = 'SELECT pro_count.OFFICE_EE_ID, pro_count.OFFICE_EE_NAME AS OFFICE_NAME,
			pro_count.TOTAL_PROJ,
			AA_SUM.AA_AMOUNT_TOTAL, 
			RAA_SUM.RAA_AMOUNT,
			trg_bugt.BUDGET_AMOUNT, 
			trg_bugt_fin.EXPENDITURE_TARGET,
			mnth_exp_tot.EXPENDITURE_TOTAL_END_OF_MONTH,
			pro_cmp_tot.TO_BE_COMPLETED,
			IFNULL(pro_cmp_sess.COMPLETED_PROJECTS, 0)AS COMPLETED_PROJECTS,
			(pprogress.CC80)as PA80, 
			(pprogress.CC5080)as PROJECT_COUNT_50_80, 
			(pprogress.CC50)as PROJECT_COUNT_BELOW_50, 
			trgt_creation.IRR_POT_CREATION,
			trgt_created.IRR_POT_CREATED
		FROM pmon__v_projectlist_with_lock as p '.
			//Total Project Count
			'LEFT Join(
				SELECT distinct ps.PROJECT_ID, COUNT(distinct ps.PROJECT_ID) AS TOTAL_PROJ, 
					ps.OFFICE_EE_ID, ps.OFFICE_EE_NAME
				FROM pmon__v_projectlist_with_lock as ps
					WHERE ps.SETUP_LOCK=1
						AND ps.'.$strProjectIDs.'
					GROUP BY ps.OFFICE_EE_ID
			) AS pro_count ON p.OFFICE_EE_ID = pro_count.OFFICE_EE_ID '.
				// AA Amount 
			'LEFT Join(
				SELECT pv.OFFICE_EE_ID, pv.PROJECT_ID,
					IFNULL(SUM(pv.AA_AMOUNT), 0) AS AA_AMOUNT_TOTAL
					FROM pmon__v_projectlist_with_lock as pv
						LEFT JOIN pmon__t_estimated_qty as ps 
						ON (pv.PROJECT_ID=ps.PROJECT_ID AND ps.RAA_ID=0)
					WHERE pv.'.$strProjectIDs.'
					GROUP BY pv.OFFICE_EE_ID 
				) AS AA_SUM
			 ON p.OFFICE_EE_ID =  AA_SUM.OFFICE_EE_ID '.
			// RAA Amount 
			'LEFT Join(
				SELECT pv.OFFICE_EE_ID AS OFFICE_EE_ID, SUM(raa.RAA_AMOUNT) AS RAA_AMOUNT
					FROM pmon__v_projectlist_with_lock as pv
						INNER JOIN pmon__t_raa_project AS raa
							ON (pv.PROJECT_ID=raa.PROJECT_ID)
					GROUP BY pv.OFFICE_EE_ID
			) AS RAA_SUM ON p.OFFICE_EE_ID = RAA_SUM.OFFICE_EE_ID'.
			// BUDGET Amount 
			' LEFT Join (
				SELECT pv.OFFICE_EE_ID AS OFFICE_EE_ID, 
					SUM(y.BUDGET_AMOUNT) AS BUDGET_AMOUNT										
					FROM pmon__v_projectlist_with_lock as pv
						LEFT Join pmon__t_yearlytargets AS y
							ON (pv.PROJECT_ID = y.PROJECT_ID 
								AND y.SESSION_ID='.$sessionID.'
								AND y.FINANCIAL_MONTH=12
							)
					GROUP BY pv.OFFICE_EE_ID
			) AS trg_bugt ON p.OFFICE_EE_ID = trg_bugt.OFFICE_EE_ID '.
			// Financial Amount
			' LEFT Join (
				SELECT SUM(pro_trgt_fin.EXPENDITURE) AS EXPENDITURE_TARGET, pv.OFFICE_EE_ID AS OFFICE_EE_ID
					FROM pmon__v_projectlist_with_lock as pv
						LEFT Join pmon__t_yearlytargets AS pro_trgt_fin
							ON (pv.PROJECT_ID = pro_trgt_fin.PROJECT_ID
								AND pro_trgt_fin.YEARLY_TARGET_DATE<="'.$reportDate.
							'" AND pro_trgt_fin.SESSION_ID='.$sessionID.')
					WHERE pv.'.$strProjectIDs.' GROUP BY pv.OFFICE_EE_ID 
			) AS trg_bugt_fin ON p.OFFICE_EE_ID = trg_bugt_fin.OFFICE_EE_ID '.
			// Expenditure Total Amount 
			' LEFT Join (
				SELECT SUM(mm.EXPENDITURE_TOTAL) AS EXPENDITURE_TOTAL_END_OF_MONTH, 
					pv.OFFICE_EE_ID AS OFFICE_EE_ID
					FROM  pmon__v_projectlist_with_lock as pv
						INNER JOIN pmon__t_monthlydata AS mm
							ON( pv.PROJECT_ID = mm.PROJECT_ID
								AND mm.FINANCIAL_MONTH<="'.$reportDate.'"
							 AND mm.SESSION_ID='.$sessionID.'
							)
					WHERE pv.'.$strProjectIDs.'  
					GROUP BY pv.OFFICE_EE_ID					
			) AS mnth_exp_tot ON p.OFFICE_EE_ID = mnth_exp_tot.OFFICE_EE_ID  '.
			// Number of Schemes to be completed in this Fin.year to end of month
			' LEFT Join (
				SELECT IFNULL(COUNT(PROJECT_ID), 0) AS TO_BE_COMPLETED, 
					pv.OFFICE_EE_ID AS OFFICE_EE_ID								
					FROM pmon__v_projectlist_with_lock as pv
						WHERE pv.PROJECT_COMPLETION_DATE>="'.$startDate.'" 
								AND pv.PROJECT_COMPLETION_DATE<="'.$tillDate.'" 
							AND pv.'.$strProjectIDs.'
				GROUP BY pv.OFFICE_EE_ID
			) AS pro_cmp_tot ON p.OFFICE_EE_ID =  pro_cmp_tot.OFFICE_EE_ID '.
			// Number of Schemes completed in this Fin.year   
			' LEFT Join(
				SELECT COUNT(pro_comp.NUM_PROJ_CMPLT_SESS) AS COMPLETED_PROJECTS, 
					pv.PROJECT_ID, 
					pv.OFFICE_EE_ID as OFFICE_EE_ID 
					FROM pmon__v_projectlist_with_lock as pv 
						LEFT Join (
							SELECT (m.PROJECT_ID) AS NUM_PROJ_CMPLT_SESS, m.PROJECT_ID, m.PROJECT_STATUS, m.SESSION_ID
								FROM pmon__t_monthlydata m
								WHERE m.MONTH_DATE>="'.$startDate.'" 
									AND m.MONTH_DATE<="'.$tillDate.'" 
								AND m.PROJECT_STATUS=5 
								AND m.'.$strProjectIDs.'
						) AS pro_comp ON pv.PROJECT_ID = pro_comp.PROJECT_ID
						GROUP BY pv.OFFICE_EE_ID
			) AS pro_cmp_sess ON p.OFFICE_EE_ID = pro_cmp_sess.OFFICE_EE_ID '.
			// No. of schemes having overall progress more than 80% 
			' LEFT JOIN( 
				SELECT SUM(pg.C80) AS CC80, SUM(pg.C5080) as CC5080, 
					SUM(pg.C50) as CC50, pg.EE_ID 
				FROM (
					SELECT pr.PROJECT_ID, mxM.PROGRESS_DATE, pr.EE_ID, pr.PROGRESS, 
						IF(pr.PROGRESS>80, 1,0) AS C80,
						IF(pr.PROGRESS>=50 AND pr.PROGRESS<=80, 1,0) AS C5080,
						IF(pr.PROGRESS<50, 1,0) AS C50 
					FROM pmon__t_progress AS pr 
						INNER JOIN ( 
							SELECT PROJECT_ID, MAX(PROGRESS_DATE) as PROGRESS_DATE, EE_ID 
							FROM pmon__t_progress 
								WHERE PROGRESS_DATE<="'.$reportDate.'" 
									AND '.$strOngoingProjectIDs.' 
							GROUP BY PROJECT_ID, EE_ID 
						)as mxM ON (
							mxM.PROJECT_ID=pr.PROJECT_ID AND mxM.PROGRESS_DATE=pr.PROGRESS_DATE
						) 
					GROUP BY PROJECT_ID, EE_ID
				)as pg 
				GROUP BY EE_ID 
			)AS pprogress ON p.OFFICE_EE_ID=pprogress.EE_ID '.
			// target for creation of irrigation potential during Fin.year upto end of month 
			' LEFT Join (
				SELECT pv.OFFICE_EE_ID AS OFFICE_EE_ID, 
					SUM(t.IRRIGATION_POTENTIAL) AS IRR_POT_CREATION 
				FROM pmon__v_projectlist_with_lock as pv 
					Left Join pmon__t_yearlytargets AS t 
						ON pv.PROJECT_ID = t.PROJECT_ID 
				WHERE TARGET_MONTH<>0 
					AND t.FINANCIAL_MONTH <='.$this->getFinancialMonthFromMonth($MONTH).' 
					AND t.SESSION_ID ='.$sessionID.' 
					AND t.'.$strProjectIDs.' 
				GROUP BY pv.OFFICE_EE_ID
			) AS trgt_creation ON p.OFFICE_EE_ID = trgt_creation.OFFICE_EE_ID'.
			// target for creation of irrigation potential during Fin.year upto end of month
			' LEFT Join (
				SELECT SUM(m.IRRIGATION_POTENTIAL) AS IRR_POT_CREATED, 
					pv.OFFICE_EE_ID AS OFFICE_EE_ID 
				FROM pmon__v_projectlist_with_lock as pv 
					LEFT Join pmon__t_monthlydata as m ON 
						(pv.PROJECT_ID = m.PROJECT_ID 
							AND ENTRY_MONTH<>0 
							AND m.FINANCIAL_MONTH<='.$this->getFinancialMonthFromMonth($MONTH).' 
							AND m.SESSION_ID='.$sessionID.' 
							AND m.'.$strProjectIDs.'
						)
					WHERE pv.'.$strProjectIDs.' 
					GROUP BY pv.OFFICE_EE_ID
			) AS trgt_created ON p.OFFICE_EE_ID = trgt_created.OFFICE_EE_ID '.
			' WHERE 1 AND  p.'.$strProjectIDs.' 
			GROUP BY OFFICE_NAME 
			ORDER BY OFFICE_NAME';
		/*if( $searchData['EE_ID']!='' and $searchData['EE_ID']!=0 ){
			$qry .= ' AND proj_view.EE_ID = '.$searchData['EE_ID'].' '; 
		}
		$qry .= ' AND proj_view.SESSION_ID <= '.$SESSION_ID.
				' GROUP BY proj_view.EE_ID ';
		*/
		//echo $strSQL;
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query();
		//showArrayValues($recs->result()); 
		return ($recs->num_rows()>0) ? $recs->result() : false;
	}
	//General Report
	protected function getGeneralReport($sessionID, $searchData){
		//showArrayValues($searchData);
		$where = array();
		if($searchData['CE_ID']>0) array_push($where, " AND p.OFFICE_CE_ID = " .$searchData['CE_ID']);
		if($searchData['SE_ID']>0) array_push($where, " AND p.OFFICE_SE_ID = " .$searchData['SE_ID']);
		if($searchData['EE_ID']>0) array_push($where, " AND p.OFFICE_EE_ID = " .$searchData['EE_ID']);
		//if($searchData['SDO_ID']>0) array_push($where, " AND proj_view.SDO_ID = " .$searchData['SDO_ID']);
		$strSQL = 'SELECT p.PROJECT_ID, p.PROJECT_CODE, 
				p.PROJECT_NAME, p.PROJECT_NAME_HINDI, 
				p.OFFICE_EE_NAME AS OFFICE_NAME,
				p.OFFICE_CE_ID, p.OFFICE_SE_ID, p.OFFICE_EE_ID,
				p.OFFICE_CE_NAME, p.OFFICE_SE_NAME,
				__districts.DISTRICT_NAME, p.PROJECT_SUB_TYPE, 
				p.PROJECT_COMPLETION_DATE,
				yt.BUDGET_AMOUNT,
				IFNULL(md.ENTRY_MONTH, mnth.ENTRY_MONTH) AS MONTH,
				IFNULL(md.ENTRY_YEAR, mnth.ENTRY_YEAR) AS YEAR,
				IFNULL(md.PROJECT_STATUS, mnth.PROJECT_STATUS) AS PROJECT_STATUS,
				md.ALLOCATED_BUDGET,
				IFNULL(md.SUBMISSION_DATE, mnth.SUBMISSION_DATE) AS SUBMISSION_DATE,
				td.LA_TARGET_DATE,
				td.SPILLWAY_TARGET_DATE,
				td.FLANKS_TARGET_DATE,
				td.NALLA_CLOSURE_TARGET_DATE,
				td.CANAL_EARTHWORK_TARGET_DATE,
				td.CANAL_STRUCTURES_TARGET_DATE,
				td.CANAL_LINING_TARGET_DATE 
			FROM pmon__v_projectlist_with_lock as p 
				LEFT Join __districts 
					ON p.HEAD_WORK_DISTRICT_ID = __districts.DISTRICT_ID 
				LEFT Join pmon__t_yearlytargets AS yt 
					ON p.PROJECT_ID = yt.PROJECT_ID 
					AND yt.TARGET_MONTH ='.$searchData['MONTH'].' 
					AND yt.SESSION_ID = '.$sessionID.' 
				LEFT Join pmon__t_monthlydata AS md
					ON p.PROJECT_ID = md.PROJECT_ID 
					AND md.ENTRY_MONTH = '.$searchData['MONTH'].'  
					AND md.SESSION_ID = '.$sessionID.' 
				LEFT JOIN( 
					SELECT DISTINCTROW mdata.PROJECT_ID, mdata.ENTRY_MONTH, 
						mdata.ENTRY_YEAR, mdata.PROJECT_STATUS, 
						mdata.SUBMISSION_DATE 
					FROM pmon__t_monthlydata AS mdata 
						INNER JOIN (
							SELECT MAX(MONTH_DATE)AS MONTH_DATE, PROJECT_ID 
								FROM pmon__t_monthlydata 
							GROUP BY PROJECT_ID 
						)AS maxmon ON(maxmon.MONTH_DATE=mdata.MONTH_DATE AND maxmon.PROJECT_ID=mdata.PROJECT_ID) 
				)as mnth ON p.PROJECT_ID = mnth.PROJECT_ID 
				LEFT Join pmon__t_target_date_completion AS td 
					ON p.PROJECT_ID = td.PROJECT_ID 
			WHERE p.PROJECT_ID <> 0 
				AND PROJECT_TYPE_ID='.$this->session->userData('PROJECT_TYPE_ID').' 
				AND  p.SETUP_LOCK = 1 
				AND p.PROJECT_STATUS<5 '.
				((count($where)>0) ? implode('', $where): '').
				' ORDER BY p.OFFICE_CE_ID, p.OFFICE_SE_ID, p.OFFICE_EE_ID, p.PROJECT_NAME ASC, md.SUBMISSION_DATE DESC';
		//echo $strSQL;
		$recs = $this->db->query($strSQL);
		//echo $this->db->last_query();
		return ($recs->num_rows()>0) ? $recs->result() : false;
	}
	//
	protected function getLockStatus($sessionID, $searchData){
		$ids = $this->getProjectIDs($searchData);
		$strSQL = 'SELECT DISTINCT p.PROJECT_ID, ps.PROJECT_NAME, t.*,
					CONCAT(SESSION_START_YEAR, "-", SESSION_END_YEAR) AS SESSION_YEAR
				FROM pmon__m_project_setup as p 
					INNER JOIN deposit__projects as ps ON (p.PROJECT_ID=ps.PROJECT_ID) 
					INNER JOIN pmon__t_locks AS t 
						ON (t.PROJECT_ID=p.PROJECT_ID) 
					LEFT JOIN __sessions as s ON (s.SESSION_ID=t.TARGET_LOCK_SESSION_ID)
				WHERE p.PROJECT_ID IN ('.implode(',', $ids).') 
				ORDER BY PROJECT_NAME ';
		//echo $sessionID;
		$recs = $this->db->query($strSQL);
		// echo $this->db->last_query();
		return (($result && $recs->num_rows()) ? $recs->result():FALSE);
	}
	protected function getLockRecords($projectIds){
		$strSQL = 'SELECT pt.MONTH_LOCK, pt.MONTHLY_EXISTS, 
			pt.IS_COMPLETED, pt.PROJECT_ID, pt.SETUP_LOCK, m.SUBMISSION_DATE 
		FROM pmon__t_locks AS pt 
			INNER JOIN pmon__t_monthlydata AS m 
				ON (pt.PROJECT_ID = m.PROJECT_ID AND pt.MONTH_LOCK = m.MONTH_DATE) 
		WHERE pt.PROJECT_ID IN('. implode(',', $projectIds).
			') ORDER BY PROJECT_ID ASC';
		$recs = $this->db->query($strSQL);
		// echo $this->db->last_query();
		return (($recs && $recs->num_rows())? $recs->result():FALSE);
	}
	protected function getProjectDataForOngoingProj($sessionID, $searchData, &$projectIDs,$group_by=''){
		//echo 'ssess:'.$sessionID;
		$projectIDs = $this->getOnGoingOrNewProjectID($searchData);
		//showArrayValues($projectIDs);
		//No record found!
		if(count($projectIDs)==0){
			return FALSE;
		}
		$mFinancialMonth = $this->getFinancialMonthFromMonth($searchData['MONTH']);
		//SECOND - filter projects whose status is ongoing at the start of FY 
		//			means Last Months status ongoing or 
		//			status ongoing if setup in this session
		//THIRD - filter project which got AA in this year
		//FOURTH - 
		
		$recs = $this->db->get_where('__sessions', array('SESSION_ID' => $sessionID-1), 1);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$prevSessionEndYear= $rec->SESSION_END_YEAR;
		}
		
		$recs = $this->db->get_where('__sessions', array('SESSION_ID' => $sessionID), 1);
		if($recs && $recs->num_rows()){
			$rec = $recs->row();
			$curSessionStartYear= $rec->SESSION_START_YEAR;
		}
		
		$oSql="";
		if($group_by==1){
			$oSql.= ", p.DISTRICT_NAME,p.PROJECT_TYPE, p.PROJECT_NAME ";
		}else if($group_by==2){
			$oSql.= ", p.TEHSIL_NAME,p.PROJECT_TYPE,p.PROJECT_NAME ";
		}else if($group_by==3){
			$oSql.= ", p.BLOCK_NAME,p.PROJECT_TYPE,p.PROJECT_NAME ";
		}else if($group_by==4){
			$oSql.= ", p.DISTRICT_NAME,p.TEHSIL_NAME,p.PROJECT_TYPE,p.PROJECT_NAME ";
		}else if($group_by==7){
			$oSql.= ", p.DISTRICT_NAME,p.BLOCK_NAME,p.PROJECT_TYPE,p.PROJECT_NAME ";
		}		
		else{
			$oSql.=", p.PROJECT_TYPE, p.PROJECT_NAME ";
		}
		
		$strSQL = 'SELECT p.PROJECT_NAME, p.PROJECT_ID,
					p.PROJECT_COMPLETION_DATE, p.PROJECT_TYPE_ID, 
					p.AA_NO, p.AA_DATE, p.AA_AMOUNT, 
					raa.RAA_NO, raa.RAA_DATE, raa.RAA_AMOUNT, 
					p.OFFICE_EE_ID, p.OFFICE_EE_NAME as OFFICE_NAME,
					IFNULL(eqty.IRRIGATION_POTENTIAL, 0) AS AA_IP,
					IFNULL(raa.IRRIGATION_POTENTIAL, 0)AS RAA_IP,
					p.OFFICE_EE_NAME AS OFFICE_NAME,
					p.OFFICE_CE_NAME,
					p.OFFICE_SE_NAME,
					p.OFFICE_EE_ID,
					p.OFFICE_SE_ID,
					p.OFFICE_CE_ID,
					
					projects__reservoir_data.LIVE_STORAGE_CAPACITY,
					p.DISTRICT_NAME,
					p.TEHSIL_NAME,
					p.BLOCK_NAME,
					p.PROJECT_TYPE_ID,p.PROJECT_SUB_TYPE_ID,
					p.PROJECT_TYPE, p.PROJECT_SUB_TYPE,
					
					budget_provisions.AMOUNT,
					prev_year_exp.EXP_WORK AS PREV_EXP_WORK,
					cur_year_exp.EXP_WORK AS CUR_EXP_WORK
					
				FROM pmon__v_projectlist_with_lock as p				
					LEFT JOIN
						projects__reservoir_data
					ON 
						projects__reservoir_data.PROJECT_ID = p.PROJECT_ID
					LEFT JOIN (
						(
							SELECT SESSION_ID, PROJECT_ID, AMOUNT 
							FROM projects__t_budget_provisions
							WHERE SESSION_ID='.$sessionID.'
						) AS budget_provisions
					)ON p.PROJECT_ID = budget_provisions.PROJECT_ID 
					
					LEFT JOIN (
						(
							SELECT ID,PROJECT_ID,EXP_WORK,EXP_MISC,EXP_DATE
							FROM ework__t_exp
							WHERE EXP_DATE= "'.$prevSessionEndYear.'-03-31"
						) AS prev_year_exp
					)ON p.PROJECT_ID = prev_year_exp.PROJECT_ID 
					
					LEFT JOIN (
						(
							SELECT ID,PROJECT_ID, sum(EXP_WORK) AS EXP_WORK ,sum(EXP_MISC) AS EXP_MISC
							FROM ework__t_exp
							WHERE 
								EXP_DATE BETWEEN "'.$curSessionStartYear.'-04-01" AND "'.date("Y-m-d").'"
							GROUP BY
								PROJECT_ID
						) AS cur_year_exp
					)ON p.PROJECT_ID = cur_year_exp.PROJECT_ID
				
					LEFT JOIN pmon__t_estimated_qty as eqty ON (eqty.PROJECT_ID=p.PROJECT_ID AND eqty.RAA_ID=0)
					LEFT JOIN(
						(
							SELECT rp.PROJECT_ID, rp.RAA_NO, rp.RAA_DATE, 
								rp.RAA_AMOUNT, raaqty.IRRIGATION_POTENTIAL
							FROM pmon__t_raa_project as rp
								LEFT JOIN pmon__t_estimated_qty as raaqty 
									ON (raaqty.PROJECT_ID=rp.PROJECT_ID) 
								INNER JOIN(
									SELECT MAX(maxraa.RAA_DATE) AS RAA_DATE, 
										maxraa.RAA_PROJECT_ID, 
										maxraa.PROJECT_ID
									FROM pmon__t_raa_project as maxraa
										WHERE maxraa.PROJECT_ID IN('.implode(',', $projectIDs).') 
									GROUP BY PROJECT_ID 
									ORDER BY PROJECT_ID 
								)AS mraa ON(mraa.RAA_PROJECT_ID=raaqty.RAA_ID )
							ORDER BY rp.PROJECT_ID
						)as raa 
					)ON p.PROJECT_ID = raa.PROJECT_ID 
				WHERE 1 
					AND p.PROJECT_ID IN ('.implode(',', $projectIDs).') '.
				' GROUP BY OFFICE_CE_NAME, OFFICE_SE_NAME, OFFICE_NAME, PROJECT_ID
				ORDER BY OFFICE_CE_NAME, OFFICE_SE_NAME, OFFICE_NAME  '.$oSql;
		$recs = $this->db->query($strSQL);
		// echo $this->db->last_query();
		// exit;
		return ($recs->num_rows() > 0 ) ? $recs->result() : false;
	}
}?>