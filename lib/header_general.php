<?php //WriteLog(); ?>
<header id="general">
<?php if (GetLoginSerial() != "") { ?>
    <dl class="on">
        <dt><a href="/logoff.php">ログアウトする</a></dt>
        <dd>ラッキー野菜は【&nbsp;<script type="text/javascript">dd();</script>&nbsp;】です☆</dd> 
    </dl>
<?php } else { ?>
	<form name="serial_login" method="post" action="/sorikuranet_callup.php">
        <dl class="off" style="display: flex; align-items: center;">
            <dt><input name="serial_key" id="serial_key" type="text" placeholder="シリアルNo."></dt>
            <dd><button name="" type="submit">ログインする</button></dd>
        </dl>
    </form>    
<?php
	}
    $error_message = "";
// 2020/01/14 t.maruyama 修正 ↓↓ PHP Notice: Undefined index が発生していたので修正
//    $error_code = @$_GET["err"];
    $error_code = "";
	if (!empty($_GET["err"])) {
		$error_code = @$_GET["err"];
		GetTopErrorMessage($error_code, $error_message);
?>
    <div id="headAlert"><p><?= $error_message ?></p></div>
<?php } ?>
</header>