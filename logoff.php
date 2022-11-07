<?php
if (session_id() == '') {
	session_cache_limiter('private');
	session_cache_expire(0);
	session_start();
}
// require_once 'lib/common.php';
//  require_once 'lib/login.php';
//  DeleteCookies();
session_destroy();
$_SESSION[] = [];
// header("Location: index.php");
?>

<script>
	sessionStorage.clear();
	localStorage.clear();
	location.replace('index.php');
</script>