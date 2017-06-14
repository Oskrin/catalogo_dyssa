<?php 
	session_start();
	if (isset($_SESSION['user']['id'])) print 'authentified';
?>