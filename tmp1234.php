<?php
if ( isset($_GET['tID']) ) :
	//echo sftp_printer($_GET['tID'].'.xml');
	if ( strrpos(get_include_path(), "phpseclib0.3.0") === false ) :
		set_include_path(get_include_path() . PATH_SEPARATOR . './phpseclib0.3.0'); // Staging requires the ../ to be ./
	endif;

	include('Net/SFTP.php');

	$sftp = new Net_SFTP('ftp.tginc.com', 2222);
	if (!$sftp->login('flatterbox', 'T&!Fl@!!er')) :
		echo 'Login Failed';
	endif;
	echo $sftp->pwd();
	//$response = http_get("http://www.whatismyip.com/", array("timeout"=>1), $info);
	$externalContent = file_get_contents('http://checkip.dyndns.com/');
	preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
	$externalIp = $m[1];
	echo '<br/>'.$externalIp;
	//print_r($sftp);
else :
	echo 'The ring... it is gone...';
endif;

/* SFTP TO PRINTER */
function sftp_printer($xml_file) {

	if ( strrpos(get_include_path(), "phpseclib0.3.0") === false ) :
		set_include_path(get_include_path() . PATH_SEPARATOR . './phpseclib0.3.0'); // Staging requires the ../ to be ./
	endif;

	include('Net/SFTP.php');

	$rtn = false;

	$sftp = new Net_SFTP('ftp.tginc.com', 2222);
	if ( isset($_GET['tID']) ) : echo $sftp->login('flatterbox', 'T&!Fl@!!er'); endif;
	//if ( isset($_GET['tID']) ) : print_r( $sftp->getSupportedVersions() ); endif;
	if (!$sftp->login('flatterbox', 'T&!Fl@!!er') || true) :
		$rtn ='Login Failed';
	endif;

	$local_directory = './orderlist/'; // Set in previous function -- orderlist/';
	$remote_directory = '/incoming/';

	//echo $local_directory.'<br/>';
	//echo $remote_directory.'<br/>';

	$files_to_upload = array();

	if ( $xml_file != "." && $xml_file != ".." ) :
		$files_to_upload[] = $xml_file;
	endif;

	if( !empty($files_to_upload) && $rtn == false ) :
		foreach($files_to_upload as $file) :
			/*
			echo dirname(__FILE__).'<br/>';
			echo $file.'<br/>';
			echo $local_directory . $file.'<br/>';
			*/
			//$rtn = $sftp->put($remote_directory . $file, $local_directory . $file, NET_SFTP_LOCAL_FILE);

			$data = file_get_contents($local_directory . $file);
			if ( isset($_GET['tID']) ) : echo $data; endif;
			$rtn = $sftp->put($remote_directory . $file, $data);
		endforeach;
	endif;

	return $rtn;
/*
	$conn = ssh2_connect('ftp.tginc.com', 2222);
	ssh2_auth_password($conn, 'flatterbox', 'T&!Fl@!!er');

	// send a file
	return ssh2_scp_send($conn, $local_directory.$xml_file, $remote_directory.$xml_file, 0644);
*/

}

?>