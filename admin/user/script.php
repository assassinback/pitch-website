<?php
require_once("../../config.php");

	$aColumns = array( 'name' );

	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".( $_GET['iDisplayStart'] ).", ".
			( $_GET['iDisplayLength'] );
	}
	
	/*
	 * Ordering
	 */
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i < intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	if ( $_GET['sSearch'] != "" )
	{
		$sWhere = "and ( first_name LIKE '%".$_GET['sSearch']."%' )";
	}
	
		
	/*
	 * SQL queries
	 * Get data to display
	 */
	 
	if($sOrder=='')
	{
		$sOrder=" order by id desc";
	}		
	 
	 
	$sQuery = "SELECT * FROM " . $dbPrefix . "user where 1 $sWhere";
    
    /* Total data set length */
    $aResultTotal = $db->query($sQuery);
	$iTotal = $aResultTotal->num_rows();
	
    $sQuery .= " $sOrder $sLimit";
    
	$rResult = $db->query($sQuery);
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iTotal,
		"aaData" => array()
	);
	
    foreach ($rResult->result_array() as $aRow)
	{
		$row = array();		
		$id = $aRow['id'];
        
        if($aRow['user_type'] == 2) 
        { 
            $usertype ="Coach";
        } 
        else if($aRow['user_type'] == 3)
        { 
            $usertype ="Scout";
        }
        else
        {
            $usertype ="Player";
        }            
            
        
        $row[] = '<input type="checkbox" name="delete[]" value="'.$aRow["id"].'">';
        $row[] = $aRow['first_name']." ". $aRow['last_name'];
        $row[] = $usertype;
        $row[] = ($aRow['status']) ? 'Active' : 'Inactive';				
		$row[] = '<a href="' . getAdminLink('edituserprofile', 'id=' . $id) . '" class="btn btn-primary">Modify</a><a href="' . getAdminLink('userinfo', 'id=' . $id) . '" class="btn btn-primary">View</a>';
		            
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>