<?php

if($_GET['exportcsv'])
{

global $wpdb;

$querystring = "SELECT s.SID, f.FID, f.PID, f.flatterer_email, s.sentiment_text, s.sentiment_name, s.sentimentdate 
				FROM flatterers f
				INNER JOIN sentiments s ON f.FID  = s.FID  OR s.FID = 0
				WHERE f.invalid = 0
				UNION
				SELECT s.SID, s.FID, s.PID, s.sentiment_name, s.sentiment_text, s.sentiment_name, s.sentimentdate 
				FROM sentiments s
				WHERE  s.FID <= 0";

$orderrows = $wpdb->get_results($querystring);
	$table = '<table style="border:solid 1px black;width:75%;">';
	$table = $table . '<tr>';
		$table = $table . '<th style="border:solid 1px black;padding:5px;background-color:black;color:white;"><a href="?page=sentiments&sort=SID">SID</a></th>';
		$table = $table . '<th style="border:solid 1px black;padding:5px;background-color:black;color:white;"><a href="?page=sentiments&sort=FID">FID</a></th>';
		$table = $table . '<th style="border:solid 1px black;padding:5px;background-color:black;color:white;"><a href="?page=sentiments&sort=PID">PID</a></th>';
		$table = $table . '<th style="border:solid 1px black;padding:5px;background-color:black;color:white;"><a href="?page=sentiments&sort=flatterer_email">Email</a></th>';
		//$table = $table . '<th style="border:solid 1px black;padding:5px;background-color:black;color:white;"><a href="?page=sentiments&sort=flatterer_name">Name</a></th>';
		$table = $table . '<th style="border:solid 1px black;padding:5px;background-color:black;color:white;"><a href="?page=sentiments&sort=sentiment_text">Sentiment</a></th>';
		$table = $table . '<th style="border:solid 1px black;padding:5px;background-color:black;color:white;"><a href="?page=sentiments&sort=sentiment_name">Sentiment From</a></th>';
		$table = $table . '<th style="border:solid 1px black;padding:5px;background-color:black;color:white;"><a href="?page=sentiments&sort=sentimentdate">Date Entered</a></th>';
		//$table = $table . '<th style="border:solid 1px black;padding:5px;background-color:black;color:white;"><a href="?page=sentiments&sort=itemprice">Price</a></th>';
		//$table = $table . '<th style="border:solid 1px black;padding:5px;background-color:black;color:white;"><a href="?page=sentiments&sort=address">Address</a></th>';		

	$table = $table . '</tr>';

foreach ( $orderrows as $orderrow )
{
	$table = $table . '<tr>';

		$table = $table . '<td style="border:solid 1px black;padding:5px;">';
		$table = $table . '<a href="?page=sentiments&sid=' . $orderrow->SID . '">' . $orderrow->SID . '</a>';
		$table = $table . '</td>';

		$table = $table . '<td style="border:solid 1px black;padding:5px;">';
		$table = $table . '<a href="?page=sentiments&fid=' . $orderrow->FID . '">' . $orderrow->FID . '</a>';
		$table = $table . '</td>';

		$table = $table . '<td style="border:solid 1px black;padding:5px;">';
		$table = $table . '<a href="?page=sentiments&pid=' . $orderrow->PID . '">' . $orderrow->PID . '</a>';
		$table = $table . '</td>';

		$table = $table . '<td style="border:solid 1px black;padding:5px;">';
		$table = $table . $orderrow->flatterer_email;
		$table = $table . '</td>';

		$table = $table . '<td style="border:solid 1px black;padding:5px;">';
		$table = $table . $orderrow->flatterer_name;
		$table = $table . '</td>';

		$table = $table . '<td style="border:solid 1px black;padding:5px;">';
		$table = $table . $orderrow->sentiment_text;
		$table = $table . '</td>';

		$table = $table . '<td style="border:solid 1px black;padding:5px;">';
		$table = $table . $orderrow->sentiment_name;
		$table = $table . '</td>';

		$table = $table . '<td style="border:solid 1px black;padding:5px;">';
		$table = $table . $orderrow->sentimentdate;
		$table = $table . '</td>';

		//$table = $table . '<td style="border:solid 1px black;padding:5px;">';
		//$table = $table . '$'. $orderrow->itemprice . '.00';
		//$table = $table . '</td>';
		
		//$table = $table . '<td style="border:solid 1px black;padding:5px;">';
		//$table = $table . $orderrow->address . ' ' . $orderrow->address2 . '<br>' . $orderrow->city . ', ' . $orderrow->state . ' ' . $orderrow->zip;
		//$table = $table . '</td>';		



	$table = $table . '</tr>';
}

	$table = $table . '</table>';

//include 'C:\inetpub\wwwroot\wordpress\wp_jfcs\simple_html_dom.php';

$html = str_get_html($table);

$fileName="export.csv";
header('Content-type: application/ms-excel');
header("Content-Disposition: attachment; filename=$fileName");

$fp = fopen("php://output", "w");
$csvString="";

$html = str_get_html(trim($table));
foreach($html->find('tr') as $element)
{

    $td = array();
    foreach( $element->find('th') as $row)
    {
        $row->plaintext="\"$row->plaintext\"";
        $td [] = $row->plaintext;
    }
    $td=array_filter($td);
    $csvString.=implode(",", $td);

    $td = array();
    foreach( $element->find('td') as $row)
    {
        $row->plaintext="\"$row->plaintext\"";
        $td [] = $row->plaintext;
    }
    $td=array_filter($td);
    $csvString.=implode(",", $td)."\n";
}
echo $csvString;
fclose($fp);
exit;
}

?>


<?php session_start(); ?>
<?php
/** Step 2 (from text above). */
add_action( 'admin_menu', 'my_plugin_menu' );

/** Step 1. */
function my_plugin_menu() {
	add_menu_page( 'Manage Sentiments', 'Sentiments', 'manage_options', 'sentiments', 'my_plugin_options', '', '199.9' );
}

/** Step 3. */
function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) && !isset($_GET['pid']) && !isset($_GET['sid']) && !isset($_GET['fid']) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';

global $wpdb;

$querystring = "SELECT s.SID, f.FID, f.PID, f.flatterer_email, s.sentiment_text, s.sentiment_name, s.sentimentdate 
				FROM flatterers f
				INNER JOIN sentiments s ON f.FID  = s.FID OR s.FID = 0
				WHERE f.invalid = 0
				UNION
				SELECT s.SID, s.FID, s.PID, s.sentiment_name, s.sentiment_text, s.sentiment_name, s.sentimentdate 
				FROM sentiments s
				WHERE  s.FID <= 0 LIMIT 1000"; // Limit purposfully there

// For Sorting
$qstring = '';

if($_GET['pid'])
{
	$querystring = "SELECT s.SID, f.FID, f.PID, f.flatterer_email, s.sentiment_text, s.sentiment_name, s.sentimentdate 
					FROM flatterers f
					INNER JOIN sentiments s ON f.FID  = s.FID
					WHERE f.invalid = 0 AND f.PID = " . $_GET['pid'] . " AND s.PID = " . $_GET['pid'] . "
					UNION
					SELECT s.SID, s.FID, s.PID, s.sentiment_name, s.sentiment_text, s.sentiment_name, s.sentimentdate 
					FROM sentiments s
					WHERE s.PID = " . $_GET['pid'] . " AND s.FID <= 0";

	$qstring = '&pid=' . $_GET['pid'];
}

if($_GET['fid'])
{
	$querystring = "SELECT s.SID, f.FID, f.PID, f.flatterer_email, f.flatterer_name, s.sentiment_text, s.sentiment_name, s.sentimentdate 
					FROM flatterers f
					INNER JOIN sentiments s ON f.FID  = s.FID 
					WHERE f.invalid = 0 AND f.FID = " . $_GET['fid'] . " AND s.FID = " . $_GET['fid'] . "
					UNION
					SELECT s.SID, s.FID, s.PID, s.sentiment_name, s.sentiment_text, s.sentiment_name, s.sentimentdate 
					FROM sentiments s
					WHERE s.FID = " . $_GET['fid'] . " AND s.FID <= 0";

	$qstring = '&fid=' . $_GET['fid'];
}

if($_GET['sid'])
{
	$querystring = "SELECT s.SID, f.FID, f.PID, f.flatterer_email, s.sentiment_text, s.sentiment_name, s.sentimentdate 
					FROM flatterers f
					INNER JOIN sentiments s ON f.FID  = s.FID  OR s.FID = 0
					WHERE f.invalid = 0 AND s.SID = " . $_GET['sid'];

	$qstring = '&sid=' . $_GET['sid'];
}

// Sort Directions defaults

$sidsort = 'desc';
$fidsort = 'desc';
$pidsort = 'desc';
$emailsort = 'desc';
$sentimentsort = 'desc';
$sentimentfromsort = 'desc';
$datesort = 'asc';

if (isset($_SESSION['sentpg_sidsort'])) : $sidsort = $_SESSION['sentpg_sidsort']; else : $_SESSION['sentpg_sidsort'] = $sidsort; endif;
if (isset($_SESSION['sentpg_fidsort'])) : $fidsort = $_SESSION['sentpg_fidsort']; else : $_SESSION['sentpg_fidsort']= $fidsort; endif;
if (isset($_SESSION['sentpg_pidsort'])) : $pidsort = $_SESSION['sentpg_pidsort']; else : $_SESSION['sentpg_pidsort'] = $pidsort; endif;
if (isset($_SESSION['sentpg_emailsort'])) : $emailsort = $_SESSION['sentpg_emailsort']; else : $_SESSION['sentpg_emailsort'] = $emailsort; endif;
if (isset($_SESSION['sentpg_sentimentsort'])) : $sentimentsort = $_SESSION['sentpg_sentimentsort']; else : $_SESSION['sentpg_sentimentsort'] = $sentimentsort; endif;
if (isset($_SESSION['sentpg_sentimentfromsort'])) : $sentimentfromsort = $_SESSION['sentpg_sentimentfromsort']; else : $_SESSION['sentpg_sentimentfromsort'] = $sentimentfromsort; endif;
if (isset($_SESSION['sentpg_datesort'])) : $datesort = $_SESSION['sentpg_datesort']; else : $_SESSION['sentpg_datesort'] = $datesort; endif;

if($_GET['sort']) {

	$querystring = $querystring . " ORDER BY " . $_GET['sort'];

	if(!isset($_SESSION['sortdir'])) { $_SESSION['sortdir'] = "ASC"; }

	switch ($_GET['sort']) {
		case 'SID':
			if ($sidsort == 'asc') : $sidsort = 'desc'; else :$sidsort = 'asc'; endif;
			$_SESSION['sentpg_sidsort'] = $sidsort;
			$_SESSION['sortdir'] = strtoupper($sidsort);
			break;
		case 'FID':
			if ($fidsort == 'asc') : $fidsort = 'desc'; else :$fidsort = 'asc'; endif;
			$_SESSION['sentpg_fidsort'] = $fidsort;
			$_SESSION['sortdir'] = strtoupper($fidsort);
			break;
		case 'PID':
			if ($pidsort == 'asc') : $pidsort = 'desc'; else :$pidsort = 'asc'; endif;
			$_SESSION['sentpg_pidsort'] = $pidsort;
			$_SESSION['sortdir'] = strtoupper($pidsort);
			break;
		case 'flatterer_email':
			if ($emailsort == 'asc') : $emailsort = 'desc'; else :$emailsort = 'asc'; endif;
			$_SESSION['sentpg_emailsort'] = $emailsort;
			$_SESSION['sortdir'] = strtoupper($emailsort);
			break;
		case 'sentiment_text':
			if ($sentimentsort == 'asc') : $sentimentsort = 'desc'; else :$sentimentsort = 'asc'; endif;
			$_SESSION['sentpg_sentimentsort'] = $sentimentsort;
			$_SESSION['sortdir'] = strtoupper($sentimentsort);
			break;
		case 'sentiment_name':
			if ($sentimentfromsort == 'asc') : $sentimentfromsort = 'desc'; else :$sentimentfromsort = 'asc'; endif;
			$_SESSION['sentpg_sentimentfromsort'] = $sentimentfromsort;
			$_SESSION['sortdir'] = strtoupper($sentimentfromsort);
			break;
		case 'sentimentdate':
			if ($datesort == 'asc') : $datesort = 'desc'; else :$datesort = 'asc'; endif;
			$_SESSION['sentpg_datesort'] = $datesort;
			$_SESSION['sortdir'] = strtoupper($datesort);
			break;
	}

	$querystring = $querystring . " " . $_SESSION['sortdir'];
} else {
	$querystring = $querystring . " ORDER BY sentimentdate ASC";
	// Reset Sort
	$_SESSION['sentpg_sidsort'] = 'desc';
	$_SESSION['sentpg_fidsort'] = 'desc';
	$_SESSION['sentpg_pidsort'] = 'desc';
	$_SESSION['sentpg_emailsort'] = 'desc';
	$s_SESSION['sentpg_entimentsort'] = 'desc';
	$_SESSION['sentpg_sentimentfromsort'] = 'desc';
	$_SESSION['sentpg_datesort'] = 'asc';
}

$orderrows = $wpdb->get_results($querystring);
//echo $querystring;
	echo '<br/>';
	echo '<h1>'. $wpdb->num_rows .' Sentiments</h1>';
	echo '<style>
			/* OLD STYLES
			table {border:solid 1px black;width:95%;}
			th {border:solid 1px black;padding:5px;background-color:#6683a7;color:white;}
			th a { color:white; }
			th a:hover {background-color:white; color: #6683a7;}
			td { border:solid 1px black;padding:5px;}
			tr:nth-child(even) {background: #CCC;}
			tr:nth-child(odd) {background: #FFF;}
			*/
			th.identifier { width: 75px;}
		</style>';
	echo '<p>FID being "0" Means it was created by the Creator of the Flatterbox</p>';
	echo '<p>FID being "-1" Means it was created by the Unique URL for the Flatterbox</p>';
	//echo '<a href="https://www.iatspayments.com/login/login.html" class="button-primary">Go to iATS Transaction Portal</a>&nbsp;&nbsp;&nbsp;';
	//echo '<a href="?page=sentiments&exportcsv=1" class="button-primary">Export to CSV</a>';
	echo '<p>&nbsp;</p>';
	echo '<table class="wp-list-table widefat fixed striped posts">';
	echo '<thead><tr>';
		echo '<th class="identifier manage-column sortable '.$sidsort.'"><a href="?page=sentiments'.$qstring.'&sort=SID"><span>SID</span><span class="sorting-indicator"></span></a></th>';
		echo '<th class="identifier manage-column sortable '.$fidsort.'"><a href="?page=sentiments'.$qstring.'&sort=FID"><span>FID</span><span class="sorting-indicator"></span></a></th>';
		echo '<th class="identifier manage-column sortable '.$pidsort.'"><a href="?page=sentiments'.$qstring.'&sort=PID"><span>PID</span><span class="sorting-indicator"></span></a></th>';
		echo '<th class="manage-column column-email sortable '.$emailsort.'"><a href="?page=sentiments'.$qstring.'&sort=flatterer_email"><span>Email</span><span class="sorting-indicator"></span></a></th>';
		//echo '<th><a href="?page=sentiments&sort=flatterer_name">Name</a></th>';
		echo '<th class="manage-column sortable '.$sentimentsort.'"><a href="?page=sentiments'.$qstring.'&sort=sentiment_text"><span>Sentiment</span><span class="sorting-indicator"></span></a></th>';
		echo '<th class="manage-column column-author sortable '.$sentimentfromsort.'"><a href="?page=sentiments'.$qstring.'&sort=sentiment_name"><span>Sentiment From</span><span class="sorting-indicator"></span></a></th>';
		echo '<th class="manage-column column-date sortable '.$datesort.'"><a href="?page=sentiments'.$qstring.'&sort=sentimentdate"><span>Date Entered</span><span class="sorting-indicator"></span></a></th>';

	echo '</tr></thead><tbody>';

foreach ( $orderrows as $orderrow )
{
	echo '<tr>';

		echo '<td>';
		echo '<a href="?page=sentiments&sid=' . $orderrow->SID . '">' . $orderrow->SID . '</a>';
		echo '</td>';

		echo '<td>';
		echo '<a href="?page=sentiments&fid=' . $orderrow->FID . '">' . $orderrow->FID . '</a>';
		echo '</td>';

		echo '<td>';
		echo '<a href="?page=sentiments&pid=' . $orderrow->PID . '">' . $orderrow->PID . '</a>';
		echo '</td>';

		echo '<td>';
		echo $orderrow->flatterer_email;
		echo '</td>';

		//echo '<td>';
		//echo $orderrow->flatterer_name;
		//echo '</td>';

		echo '<td>';
		echo $orderrow->sentiment_text;
		echo '</td>';

		echo '<td>';
		echo $orderrow->sentiment_name;
		echo '</td>';

		echo '<td>';
		echo $orderrow->sentimentdate;
		echo '</td>';

		//echo '<td>';
		//echo '$'. $orderrow->itemprice . '.00';
		//echo '</td>';
		
		//echo '<td>';
		//echo $orderrow->address . ' ' . $orderrow->address2 . '<br>' . $orderrow->city . ', ' . $orderrow->state . ' ' . $orderrow->zip;
		//echo '</td>';			



	echo '</tr>';
}

	echo '</tbody></table>';

	echo '</div>';

}
?>