<?php
    require_once '../../../lib/common.php';
    require_once '../../../lib/login.php';
    require_once '../../../lib/contents_list.php';

    // ↓↓　<2020/06/26> <VinhDao> <AWSの環境で「$WEBSERVER_FLG = 0」を変更する。>
        // common_files/webserver_flg.phpから値をとる。
        global $WEBSERVER_FLG;
    // ↑↑　<2020/06/26> <VinhDao> <AWSの環境で「$WEBSERVER_FLG = 0」を変更する。>

    // AWSの環境
    if ((strtotime(date("Y/m/d")) <= strtotime("2015/12/10")) && $WEBSERVER_FLG == 0) {
        if ($_GET["test"] == "") {
            header("/usersupport/levelup/nbk090400/");
        }
    }
    
    if (isset($_REQUEST['option'])) {
        if ($_REQUEST['option'] == 'manual') {
            execDownload("\downloadmanual_log_2015.txt", ",農業簿記9_平成27年 年末レベルアップ版（Ver9.05.00）,マニュアル,Ver151211", "download_files/bk9_05_00_lu_setup_m1.pdf", true);
        }
        execDownload("\download_log_2015.txt", ",農業簿記9_平成27年 年末レベルアップ版（Ver9.05.00）,プログラム,Ver151211,", $GLOBALS['PRG_DOWNLOAD_SERVER']."levelup/nbk9_05_00/BK9LU_H27Setup2.exe", true);
    }
    $folderRoot  = mb_substr(dirname(__DIR__), mb_strrpos(dirname(__DIR__), '/') + 1);
    $folderChild = mb_substr(dirname(__FILE__), mb_strrpos(dirname(__FILE__), '/') + 1);
    $link = "/usersupport/{$folderRoot}/{$folderChild}/index.php?option=";
?>
<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta name="robots" content="noindex,nofollow">
        <meta charset="shift_jis">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="format-detection" content="telephone=no" />
        <meta name="keywords" content="農業,簿記,新着情報,サポート" />
        <meta name="description" content="農業簿記9 平成27年 年末レベルアップ版 ダウンロード" />
        <title>農業簿記9&nbsp;平成27年 年末レベルアップ版&nbsp;ダウンロード｜そり蔵ネット</title>
        <script type="text/javascript" charset="utf-8" src="/common/js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" charset="utf-8" src="/common/js/jquery.easing.1.3.js"></script>
        <script type="text/javascript" charset="utf-8" src="/common/js/gloval.js"></script>
        <link rel="stylesheet" type="text/css" href="/common/css/gloval.css" media="all">
        <link rel="stylesheet" type="text/css" href="/common/css/style.css" media="all">
        <link rel="stylesheet" type="text/css" href="/common/css/size.css" media="all">
        <!--[if lt IE 9]>
        <script type="text/javascript" src="/common/js/html5shiv-printshiv.js"></script>
        <script type="text/javascript" src="/common/js/css3-mediaqueries.js"></script>
        <![endif]-->
        <script type="text/javascript" src="/js/dd.js"></script>
        <script type="text/javascript" src="/js/common.js"></script>
        <script type="text/javascript" src="/js/bbct/aes.js"></script>
        <script type="text/javascript" src="/js/bbct/aesprng.js"></script>
        <script type="text/javascript" src="/js/bbct/armour.js"></script>
        <script type="text/javascript" src="/js/bbct/entropy.js"></script>
        <script type="text/javascript" src="/js/bbct/lecuyer.js"></script>
        <script type="text/javascript" src="/js/bbct/md5.js"></script>
        <script type="text/javascript" src="/js/bbct/scramble.js"></script>
        <script type="text/javascript" src="/js/bbct/stegodict.js"></script>
        <script type="text/javascript" src="/js/bbct/utf-8.js"></script>
		<?php require_once __DIR__ . '/../../../lib/localstorage.php'; ?>
		<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
    </head>
    <body id="logout">
        <?php require_once '../../../lib/header_general.php'; ?>
        <article id="home" class="clearfix">
            <nav class="clearfix">
                <h1><a href="/index.php" onfocus="this.blur()">農業簿記9&nbsp;平成27年 年末レベルアップ版<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
                <?php require_once '../../../lib/nav_general.php'; ?>
            </nav>
            <section id="contents" class="program">
                <div class="box-contents">
                    <hgroup>
                        <p><a href="/program.php" onfocus="this.blur()">↑プログラム更新</a></p>
                        <h2>農業簿記9<br>平成27年 年末レベルアップ版<br>(Ver.9.05.00)&nbsp;ダウンロード</h2><br>
                        <dl class="clearfix">
                            <dt><span><a href="#downloadbox" onfocus="this.blur()"><img src="/common/images/pkg_nbk09_w316.gif" alt="農業簿記9"></a></span></dt>
                            <dd><span>「農業簿記9 平成27年 年末レベルアップ版(Ver.9.05.00)」のインストール方法、レベルアップ内容については [ マニュアル（PDF） ] をご覧ください。</span>
                            <span class="text-bold">マニュアルをご覧になるにはAdobe&nbsp;Reader&reg;が必要です。</span></dd>
                        </dl>
                    </hgroup><br>
                    <h3>レベルアップ項目（Ver.9.05.00 での対応）</h3>
                    <ul class="square">
                        <li><b>マイナンバー制度への対応</b></li>
                        <li><b>原則課税のリバースチャージに対応</b></li>
                        <li><b>Microsoft&reg; Windows&reg; 10 対応</b></li>
                        <li><b>お客様の声を反映した機能強化</b><br><br>詳しいレベルアップ内容や操作方法については、ページ最下部にあるマニュアルをご参照ください。</li>
                    </ul>
                    <blockquote>
                        <h3>本製品のサポートについて</h3>
                        <p>本ソフトウェアの操作方法に関するお問い合わせにつきましては、お電話およびFAXにて受け付けております。</p>
                        <h4>農業サポートセンター</h4>
                        <p>TEL：0258-31-5850　FAX：0258-31-5651<br>受付時間　10:00～12:00／13:00～17:00<br>[&nbsp;土・日・祝祭日・年末年始を除く&nbsp;]</p>
                        <p class="text-bold text-s">お問い合わせの際は、シリアルナンバーの確認をさせていただいておりますのでご協力をお願いいたします。</p>
                        <p class="text-bold text-s">申告前はサポートセンターが大変混み合いますので、ご不明な点がございましたらお早めにお問い合わせくださいますようお願い申し上げます。</p>
                    </blockquote><br>
                    <h3 id="downloadbox">農業簿記9&nbsp;平成27年 年末レベルアップ版<br>(Ver.9.05.00)&nbsp;ダウンロード</h3>
                    <p>形式：EXEファイル&nbsp;(404MB)&nbsp;<span class="text-bold">ADSL以上の回線を推奨</span></p>
                    <p class="btn-dl01"><a href="<?= $link ?>prg">ダウンロード</a></p>
                    <ul class="links">
                        <li><a href="<?= $link ?>manual" onfocus="this.blur()" target="_blank">PDFマニュアル&nbsp;(411KB)</a></li>
                        <li><a href="<?= $GLOBALS['AdobeReaderDL_URL'] ?>" onfocus="this.blur()" target="_blank">Adobe Reader&reg;</a></li>
                    </ul>
                    <p class="text-bold text-s">マニュアルをご覧になるにはAdobe Reader&reg;が必要です。</p>
                </div>
            </section>
        </article>
        <footer>
            <div id="linkBox" class="clearfix">
                <ul>
                    <li><a href="/index.php">HOME</a>&nbsp;&gt;&nbsp;<a href="/program.php">プログラム更新</a>&nbsp;&gt;&nbsp;農業簿記9&nbsp;平成27年 年末レベルアップ版</li>
                </ul>
            <?php require_once '../../../lib/footer_general.php'; ?>
        </footer>
        <p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
    </body>
</html>
