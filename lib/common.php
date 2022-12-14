<?php
    if (session_id() == "") {
        session_start();
    }
	ob_start();
    // require_once dirname(__FILE__)."/../../common_files/includes/debug/config.inc.php";	
    use function PHPSTORM_META\type;
	
    require_once dirname(__FILE__)."/../../common_files/webserver_flg.php";
    require_once dirname(__FILE__)."/../../common_files/connect_db.php";
    // Bellsoft Maruyama add 2020/02/10 from
    require_once dirname(__FILE__).'/../../common_files/smtp_mail.php';
    // Bellsoft Maruyama add 2020/02/10 end
//　↓↓　<2020/08/04> <VinhDao> <追加>
    require_once __DIR__ . '/../../common_files/STFSApiAccess.php';
//　↑↑　<2020/08/04> <VinhDao> <追加>
    require_once __DIR__ . '/../../common_files/security.php';
//　↓↓　＜2020/09/14＞　＜VinhDao＞　＜追加＞
    require_once __DIR__ . '/../../common_files/security/index.php';
//　↑↑　＜2020/09/14＞　＜VinhDao＞　＜追加＞
//　↓↓　<2020/10/30> <VinhDao> <追加>
    require_once __DIR__ . "/../../common_files/classes/redirect/redirect.php";
    use Redirect\Redirect as Redirect;
    require_once __DIR__ . '/functions.php';
//　↑↑　<2020/10/30> <VinhDao> <追加>
// ↓↓　<2022/08/25> <KhanhDinh> <追加>
	require_once 'login.php';
// ↑↑　<2022/08/25> <KhanhDinh> <追加>

    global $WEBSERVER_FLG;

    // IntendedProductsCode:ログインを許可する対象製品をシリアルの上4桁で指定します。
    // PossessedProductsCode:所有状況を確認する対象製品をシリアルの上4桁で指定します。
    // 1011:農業簿記7, 1012:農業簿記8, 1013:農業簿記9
    // 1014:農業簿記10, 1015:農業簿記11
    // 1051:農業簿記7JA, 1052:農業簿記8JA, 1053:農業簿記9JA
    // 1054:農業簿記10_JA, 1055:農業簿記11_JA
    // 1020:農業日誌V6, 1021:農業日誌V6プラス
    // 1031:漁業簿記2
    // 1080:れん太郎
    // 1200:クミカン, 1201:クミカンV2
    // 1430:ホクノウ
    // 2312:給料王17
    // 2322:販売王17
    // ※漁業簿記2のログインは2013年6月末にて削除
    // ※農業簿記7のログインは2011年12月末にて終了のため削除
    // PHP環境に合わせて配列を変更(Kentaro.Watanabe 2020/03/13)

    // ログイン許可製品リスト（配列）
    global $IntendedProductsCode;
    // $IntendedProductsCode = [
    //     "1014", "1015", "1054", "1055", 
    //     "1020", "1021", "1430"
    // ];
    $IntendedProductsCode = [
        "1015", "1055", "1430"
    ];

    // 所有状況を確認する対象製品（配列）
    global $PossessedProductsCode;
    // $PossessedProductsCode = [
    //     "1011", "1012", "1013", "1014", "1015", 
    //     "1051", "1052", "1053", "1054", "1055", 
    //     "1020", "1021", "1031", "1080", "1200", 
    //     "1201", "1430"
    // ];
    $PossessedProductsCode = [
        "1014", "1015", "1054", "1055", "1021", "1430"
    ];

    // [共通の値の設定]
    // ExtentionDays:ソリマチクラブ会員契約終了後、一定期間ログインできるようにします。
    // 契約終了日を見る際にこの値を合算して判別してください。
    define("ExtentionDays", 30);       // ソリクラ期間終了後ログインできる猶予日数
    define("CookiesSaveDays", 30);     // Cookieの保存期間（日数）

    define("LoginProduct", "LoginProduct");
    define("f_expires", "expires");
    define("f_serialno", "serialno");
    define("sorizo", "sorizo");
    define("f_url", "requested_url");
    define("f_userpref", "userpref");
    define("f_ky_next_kb", "ky_next_kb");

    // [共通の値の設定]
    // PresentLocation:自分のいるページを判別するための値です。
    // 値：  faq:FAQのページの場合は旧来の処理が多いため、この値で判断します
    global $PresentLocation, $FaqURL, $SplitFaqURL, $FaqPrdFolder, $MovieSupportFlg, $FaqCode;
    $PresentLocation = $FaqURL = $SplitFaqURL = $FaqPrdFolder = $MovieSupportFlg = $FaqCode = "";

    //  [共通の値の設定]
    //  製品コード、製品フラグ：製品別の値を示すためのコードです。
    //  ダイレクトメニューの関係で使用されています。
    // 製品コード
    define("NBK07_CODE", "100");           // 農業簿記7
    define("NBKJA07_CODE", "101");         // 農業簿記7 JAバージョン
    define("NSS07_CODE", "102");           // 農家の白色申告7
    define("FBK02_CODE", "200");           // 漁業簿記2
    define("NNS06PLUS_CODE", "150");       // 農業日誌V6プラス
    define("NBK08_CODE", "103");           // 農業簿記8
    define("NBKJA08_CODE", "803");         // 農業簿記8 JAバージョン
    define("NNS06_CODE", "151");           // 農業日誌6
    define("NBK09_CODE", "104");           // 農業簿記9
    define("NBKJA09_CODE", "804");         // 農業簿記9 JAバージョン
    define("NBK10_CODE", "105");           // 農業簿記10
    define("NBKJA10_CODE", "805");         // 農業簿記10 JAバージョン
    define("NBK11_CODE", "106");           // 農業簿記11
    define("NBKJA11_CODE", "806");         // 農業簿記11 JAバージョン

    // 製品フラグ
    define("NBK07_S_FLG", "10");           // 農業簿記7
    define("NBKJA07_S_FLG", "11");         // 農業簿記7 JAバージョン
    define("NSS07_S_FLG", "12");           // 農家の白色申告7
    define("FBK02_S_FLG", "20");           // 漁業簿記2
    define("NNS06PLUS_S_FLG", "50");       // 農業日誌V6プラス
    define("NBK08_S_FLG", "13");           // 農業簿記8
    define("NBKJA08_S_FLG", "83");         // 農業簿記8 JAバージョン
    define("NBK09_S_FLG", "14");           // 農業簿記9
    define("NBKJA09_S_FLG", "84");         // 農業簿記9 JAバージョン
    define("NBK10_S_FLG", "15");           // 農業簿記10
    define("NBKJA10_S_FLG", "85");         // 農業簿記10 JAバージョン
    define("ETAX2007_S_FLG", "301");       // e-Tax連携オプション2007
    define("KAKUTEI19_S_FLG", "401");      // みんなの確定申告19年度版
    define("ETAX2008_S_FLG", "310");       // e-Tax連携オプション2008
    define("KAKUTEI20_S_FLG", "410");      // みんなの確定申告20年度版
    define("ETAX2009_S_FLG", "320");       // e-Tax連携オプション2009
    define("KAKUTEI21_S_FLG", "420");      // みんなの確定申告21年度版
    define("ETAX2010_S_FLG", "330");       // e-Tax連携オプション2010
    define("KAKUTEI22_S_FLG", "430");      // みんなの確定申告22年度版
    define("ETAX2011_S_FLG", "340");       // e-Tax連携オプション2011
    define("KAKUTEI23_S_FLG", "440");      // みんなの確定申告23年度版
    define("ETAX2012_S_FLG", "350");       // e-Tax連携オプション2012
    define("KAKUTEI24_S_FLG", "450");      // みんなの確定申告24年度版
    define("ETAX2013_S_FLG", "360");       // e-Tax連携オプション2013
    define("KAKUTEI25_S_FLG", "460");      // みんなの確定申告25年分
    define("ETAX2014_S_FLG", "370");       // e-Tax連携オプション2014
    define("KAKUTEI26_S_FLG", "470");      // みんなの確定申告26年分
    define("ETAX2015_S_FLG", "380");       // e-Tax連携オプション2015
    define("KAKUTEI27_S_FLG", "480");      // みんなの確定申告27年分
    define("RENTARO_S_FLG", "60");         // ソリマチれん太郎(BK7)
    define("KUMIKAN_S_FLG", "70");         // クミカン(BK7)
    define("RENTARO8_S_FLG", "61");        // ソリマチれん太郎(BK8)
    define("KUMIKAN8_S_FLG", "71");        // クミカン(BK8)


    //**********************************************************************/
    // 全製品共通指定 外部ダウンロードサーバーを追加（2010/12/09 KWatanabe）         */
    //**********************************************************************/
    global $LEVELUP_DOWNLOAD_SERVER;
    $LEVELUP_DOWNLOAD_SERVER = "";
    global $KAKUTEI_DOWNLOAD_SERVER;
    $KAKUTEI_DOWNLOAD_SERVER = "";
    global $ETAX_DOWNLOAD_SERVER;
    $ETAX_DOWNLOAD_SERVER = "";
    global $GENSEN_DOWNLOAD_SERVER;
    $GENSEN_DOWNLOAD_SERVER = "";

    // （WebARENA移行後）
    global $SP_DOWNLOAD_SERVER;
    $SP_DOWNLOAD_SERVER = "http://www.sorimachi.on.arena.ne.jp/sp/";
    global $SP_DOWNLOAD_SERVER_AWS;
    $SP_DOWNLOAD_SERVER_AWS = "https://sorimachi-download.s3-ap-northeast-1.amazonaws.com/sp/";
    global $SP_DOWNLOAD_SERVER_DL1;
    $SP_DOWNLOAD_SERVER_DL1 = "https://sorimachi-download.s3-ap-northeast-1.amazonaws.com/sp/";
    global $PRG_DOWNLOAD_SERVER;
    $PRG_DOWNLOAD_SERVER = "http://www.sorimachi.on.arena.ne.jp/program/";
    global $PRG_DOWNLOAD_SERVER_AWS;
    $PRG_DOWNLOAD_SERVER_AWS = "https://sorimachi-download.s3-ap-northeast-1.amazonaws.com/prg/";
    global $PRG_DOWNLOAD_SERVER_DL1;
    $PRG_DOWNLOAD_SERVER_DL1 = "http://dl1.sorimachi.co.jp/prg/";
    global $PRG_DOWNLOAD_SERVER_RES;
    $PRG_DOWNLOAD_SERVER_RES = "https://res.sorimachi.co.jp/files_prg/";
    global $AdobeReaderDL_URL;
    $AdobeReaderDL_URL = "http://www.adobe.co.jp/products/acrobat/readstep2.html";

    // ソリマチホーム
    // IPアドレス追加（長岡からの閲覧用）
    // サーバーによって場合分け（20100225追加）
    // HTTPSに変更、テスト環境見直し（2020/03/13 Kentaro.Watanabe）
    // 本サーバーの場合 : "http://www.sorimachi.co.jp/"
    global $SORIMACHI_HOME;
    $SORIMACHI_HOME = ($WEBSERVER_FLG == 0) ? "https://www.sorimachi.co.jp/" : "https://www.sorimachi.co.jp/";

    // AGRI8ホーム
    // IPアドレス追加（長岡からの閲覧用）
    // サーバーによって場合分け（20100225追加）
    // 本サーバーの場合 : "http://www.agri8.jp/"
    global $AGRI8_HOME;
    $AGRI8_HOME = ($WEBSERVER_FLG == 0) ? "http://www.agri8.jp/" : "http://www.agri8.jp/";

    // ORDERホーム-ソリマチHPのSSLに移行
    // サーバーによって場合分け（20100225追加）
    // HTTPSに変更、テスト環境見直し（2020/03/13 Kentaro.Watanabe）
    // 本サーバーの場合 : "https://www.sorimachi.co.jp/"
    global $ORDER_HOME;
    $ORDER_HOME = ($WEBSERVER_FLG == 0) ? "https://www.sorimachi.co.jp/" : "https://www.sorimachi.co.jp/";

    // そり蔵ネットホームページ
    // IPアドレス追加（長岡からの閲覧用）
    // サーバーによって場合分け（20100225追加）
    // HTTPSに変更、テスト環境見直し（2020/03/13 Kentaro.Watanabe）
    // 本サーバーの場合 : "http://www.sorizo.net/"
    global $SORIZO_HOME;
    // $SORIZO_HOME = ($WEBSERVER_FLG == 0) ? "https://www.hp-sorizo.mym.sorimachi.biz" : "https://www.hp-sorizo.mym.sorimachi.biz";
    $SORIZO_HOME = ($WEBSERVER_FLG == 0) ? "https://www.sorizo.net/" : "https://www.sorizo.net/";
    // $SORIZO_HOME = $_SERVER['HTTP_HOST'];


    // FAQ用サポセンテストサーバー
    // 本サーバーの場合 : "http://www.sorizo.net/"
    // テストサーバー（OUJU-SV）の場合 : 長岡サポセンSV : "http://192.168.5.100:90/"
    //                             東京テストSV : "http://192.168.8.21:81/"
    // HTTPSに変更、テスト環境見直し（2020/03/13 Kentaro.Watanabe）
    global $SORIMACHISCWEB_HOME;
    $SORIMACHISCWEB_HOME = ($WEBSERVER_FLG == 0) ? "https://www.sorizo.net/" : "https://www.sorizo.net/";

    // ファイルディレクトリ
    // サーバーによって場合分け（20100806追加）
    // 本サーバーの場合 : "d:/SORIZOWEB"
    // テストサーバー（OUJU-SV）の場合 : "c:/SORIZO-9"
    // $SORIZOWEB_DIRECTORY = "c:/SORIZOWEB";
    global $SORIZOWEB_DIRECTORY;
    $SORIZOWEB_DIRECTORY = ($WEBSERVER_FLG == 0) ? "d:/SORIZOWEB" : "c:/SORIZO-9";

    // ログ保存用ディレクトリ
    global $LogFileDirectory;
    global $LGINLogFileDirectory;
    global $DLLogFileDirectory;

	// ajax call from lib/common.php
	if (!empty($_POST['store'])) {
		if(!empty($_POST['all'])){
			foreach (@$_POST['all'] as $key => $value) {
				$_SESSION['store'][$key] = urldecode($value);
				// $_SESSION['store'][$key] = ($value);
			}
		}

		switch (@$_POST['pathname']) {
			case '/':
			case '/index.php':
				$rs['path'] = 'index';
				echo json_encode($rs);
				break;
			case '/drm/loginchkdrm.php':
				$serial_no = GetLoginSerial();
				if ($serial_no == "") {
					$url = "/drm/loginchkdrm.php?auth=false";
				} else {

					$url = "/drm/" . @$_POST['url'] . ".php";
				}
				$rs['path'] = "drm";
				$rs['drm']['url'] = $url;
				// ob_end_clean();
				echo json_encode($rs);
				break;
			default:
				$rs['path'] = 'index';
				echo json_encode($rs);
				break;
		}
		die();
		// echo json_encode($_SESSION['store']);
		// die();
	}


    // 本サーバーの場合
    // 2020/02/26 t.maruyama 削除 ↓↓  AWS環境のログ出力先変更対応
    //    $LogFileDirectory = dirname(__FILE__)."/../../logs/";
    //    $LogFileDirectory = dirname(__FILE__)."/../../../data/logs/";
        $LogFileDirectory = __DIR__ . "/../../../data/logs/";
    // 2020/02/26 t.maruyama 削除 ↑↑
        $LGINLogFileDirectory = $LogFileDirectory . "www_sorizo/login/";
    //    $DLLogFileDirectory = $LogFileDirectory."www_sorizo/download/";
        $DLLogFileDirectory = $LogFileDirectory;

    // Function :デバッグ出力
    // Input    :デバッグ出力文
    // Return   :なし
    // Comment  :
    function Debug($str) {
        echo "<font size='-1'>DEBUG > " . $str . "</font><br />";
    }

    // Function :日付フォーマット
    // Input    :datTime    :日付
    //          :strFormat  :フォーマット
    // Return   :日付文字列
    // Comment  :フォーマット指定できる型について（日付型からの変換）
    // 　YYYY    西暦４桁
    // 　YY      西暦２桁
    // 　MM      月２桁
    // 　M       月１桁
    // 　DD      日２桁
    // 　D       日１桁
    // 　HH24    時２桁（２４時間）
    // 　H24     時１桁（２４時間）
    // 　HH      時２桁（１２時間）
    // 　H       時１桁（１２時間）
    // 　II      分２桁
    // 　I       分１桁
    // 　SS      秒２桁
    // 　S       秒１桁
    // 　XX      午前/午後
    function FormatDateTime($datetime, $strFormat = "YYYY/MM/DD") {
        $FormatType = explode("/", "YYYY/YY/MMM/MM/M/DDD/DD/D/HH24/H24/HH/H/II/I/SS/S/XX/ZZ");
        $tmpFormat = (string)($strFormat);
        $numType = count($FormatType);
        for ($i = 0; $i < $numType; $i++) {
            if (strpos($tmpFormat, $FormatType[$i]) !== false) {
                switch ($FormatType[$i]) {
                    case "HH24":
                        $tmpFormat = str_replace("HH24", date("H", strtotime($datetime)), $tmpFormat);
                        break;

                    case "H24":
                        $tmpFormat = str_replace("H24", date("G", strtotime($datetime)), $tmpFormat);
                        break;

                    case "HH":
                        $tmpFormat = str_replace("HH", date("h", strtotime($datetime)), $tmpFormat);
                        break;

                    case "H":
                        $tmpFormat = str_replace("H", date("g", strtotime($datetime)), $tmpFormat);
                        break;

                    case "II":
                        $tmpFormat = str_replace("II", date("i", strtotime($datetime)), $tmpFormat);
                        break;

                    case "I":
                        $tmpFormat = str_replace("I", (int)date("i", strtotime($datetime)), $tmpFormat);
                        break;

                    case "SS":
                        $tmpFormat = str_replace("SS", date("s", strtotime($datetime)), $tmpFormat);
                        break;

                    case "S":
                        $tmpFormat = str_replace("S", (int)date("s", strtotime($datetime)), $tmpFormat);
                        break;

                    case "YYYY":
                        $tmpFormat = str_replace("YYYY", date("Y", strtotime($datetime)), $tmpFormat);
                        break;

                    case "YY":
                        $tmpFormat = str_replace("YY", substr((string)(date("Y", strtotime($datetime))), -2), $tmpFormat);
                        break;

                    case "MMM":
                        $tmpFormat = str_replace("MMM", substr(date("F", strtotime($datetime)), 0, 3), $tmpFormat);
                        break;

                    case "MM":
                        $tmpFormat = str_replace("MM", date("m", strtotime($datetime)), $tmpFormat);
                        break;

                    case "M":
                        $tmpFormat = str_replace("M", (int)date("m", strtotime($datetime)), $tmpFormat);
                        break;

                    case "DDD":
                        $tmpFormat = str_replace("DDD", substr(date("l", strtotime($datetime)), 0, 3), $tmpFormat);
                        break;

                    case "DD":
                        $tmpFormat = str_replace("DD", date("d", strtotime($datetime)), $tmpFormat);
                        break;

                    case "D":
                        $tmpFormat = str_replace("D", (int)date("d", strtotime($datetime)), $tmpFormat);
                        break;

                    case "XX":
                        if (date("H", strtotime($datetime)) < 12) {
                            $tmpFormat = str_replace("XX", "午前", $tmpFormat);
                        }
                        else {
                            $tmpFormat = str_replace("XX", "午後", $tmpFormat);
                        }
                        break;

                    case "ZZ":
                        if (date("H", strtotime($datetime)) < 12) {
                            $tmpFormat = str_replace("ZZ", "AM", $tmpFormat);
                        }
                        else {
                            $tmpFormat = str_replace("ZZ", "PM", $tmpFormat);
                        }
                        break;
                }
            }
        }
        for($i = 0; $i < $numType; $i++) {
            $FormatType[$i] = $FormatType[$i]."/";
        }
        $tmpFormat = str_replace($FormatType, "", $tmpFormat);
        return (string)$tmpFormat;
    }

    // Function :契約期間参照
    // Input    :serial_no  :シリアルＮｏ
    // Return   :0          :期間内
    //          :-1         :エラー
    //          :-2         :期間外
    //          :-3         :契約期間が存在しない
    //          :-4         :保守契約が確認できないためログインできない
    // Comment  :
    function RefDateExtention($serial_no) {
        // API
        // シリアルNo.から顧客コード,顧客情報 を取得します。
        $json = '{
                    "sral":{
                        "data":[
                            {"name":"sral_no","value":"'.$serial_no.'","operator":"="}
                        ]
                    }
                }';
        $user = GetAPIData("users", $json, "GET");

    // ↓↓　<2020/04/08> <VinhDao> <userをチェックする>
        if ( empty( $user['users'] ) 
            or GetFirstByField($user, 'error') != '' ) {
            return -1;
        }
    // ↑↑　<2020/04/08> <VinhDao> <userをチェックする>
        $user_cd = $user["users"][0]["user_cd"];

        $json = '{
                    "ky":{
                        "data":[{"name":"user_cd","value":"' . $user_cd . '","operator":"="}],
                    },
                    "ky_prod":{},
                    "ky_his":{}
                }';
        $ky = GetAPIData("ky", $json, "GET");
    
    // ↓↓　<2020/04/08> <VinhDao> <kyをチェックする>
        if ( empty( $ky['ky'] ) 
            or GetFirstByField($ky, 'error') != ''
            or count( $ky['ky'] ) < 1 ) {
            return -3;
        }

        $ky = $ky["ky"];
        $numKy = count($ky);
        // if ($numKy < 1) {
        //     return -3;
        // }
    // ↑↑　<2020/04/08> <VinhDao> <kyをチェックする>

        $hasContract = false;
        $hasProduct = false;
        for ($i = 0; $i < $numKy; $i++) {
            if ( is_array($ky[$i]) 
            && is_array($ky[$i]["ky_prod"]) 
            && is_array($ky[$i]["ky_prod"][0]["sral"]) ) {
                $hasProduct = true;
                $numHis = count($ky[$i]["ky_his"]);
                $ky_e_ymd = 0;
                // 保有確認製品の内容にも上書きします。（複数持っている場合に備えて）
                for ($k = 0; $k < $numHis; $k++) {
                    $temp = (is_array($ky[$i]["ky_his"][$k])) ? $ky[$i]["ky_his"][$k]["ky_e_ymd"] : "";
                    if (strtotime($ky_e_ymd) < strtotime($temp)) {
                        $ky_e_ymd = $temp;
                    }
                }

                if (!is_null($ky_e_ymd) && $hasContract == false) {
                    $syuryo_date = date("Y/m/d", strtotime($ky_e_ymd));
                    $syuryo_date_extention = DateAdd($syuryo_date, ExtentionDays);
                    if (DateDiff(date("Y/m/d"), $syuryo_date_extention, true) >= 0) {
                        $hasContract = true;
                        break;
                    }
                }
            }
        }
        return ($hasProduct) ? (($hasContract) ? 0 : -2) : -4;
    }

    // Function :半角変換
    // Input    :var        :変換対象
    // Return   :変換後文字列
    // Comment  :BASP21を使用して半角変換
    function Han2Zen($var) {   
        $var = (is_null($var)) ?  "" : (string)$var;
        return mb_convert_kana($var, "RVa");
    }

    // Function :SQL文字列フォーマット
    // Input    :var        :値
    // Return   :Sql
    // Comment  :NULLはNULL, NULLでなければ''で囲んで返す
    function QuoteSqlChar($var) {
        return (is_null($var) || strlen($var) == 0) ? "NULL" : "'".$var."'";
    }

    // Function :HTML出力
    // Input    :str        :値
    // Return   :
    // Comment  :HTMLEncodeして返す
    function HTMLoutput($str) {
        return htmlentities($str);
    }

    // Function :HTML出力
    // Input    :str        :値
    // Return   :
    // Comment  :HTMLEncodeして返す
    function DeleteAFile($filespec) {
        if (file_exists($filespec)) {
            unlink($filespec);
        }
    }

    // Function :リダイレクト
    // Input    :url        :URL
    // Return   :なし
    // Comment  :HTMLEncodeして返す
    function SetRedirect($url) {
        $query = $_SERVER['QUERY_STRING'] ?? '';
        if ((strlen($query) > 0) && (strpos($url, "?") === false)) {
            $myStr = $url."?".$query;
        }
        elseif (strlen($query) > 0) {
            $myStr = $url."&".$query;
        }
        else {
            $myStr = $url;
        }
        $myStr = str_replace("&&", "", $myStr);
        $myStr = str_replace("??", "", $myStr);
        
    //　↓↓　＜2020/10/30＞　＜VinhDao＞　＜修正＞
        // header("Location: ".$myStr);
        Redirect::goto($myStr);
    //　↑↑　＜2020/10/30＞　＜VinhDao＞　＜修正＞
    }

    // Function :文字列操作 (NULL値変換)
    // Input    :I var          :対象
    //          :I replacestr   :変換文字列
    // Comment  :var が NULLの場合は、指定した置換文字列に置換
    function Nz($var, $replacestr) {
        // NULLの場合は、指定した置換文字列に置換
        return (is_null($var)) ? $replacestr : $var;
    }

    // Function :商品コードの取得
    // Input    :serial_no  :シリアルNo
    // Output   :goods_code :商品コード
    // Output   :pack_code  :PACK商品コード
    // Return   :0          :通常商品
    //          :1          :PACK商品
    //          :-1         :マスタ範囲外
    //          :-2         :ホストにて登録済み
    // Comment  :シリアルNo.から商品コードを取得します
    function GetGoodsCode($serial_no, &$goods_code, &$pack_code) {
        $json = '{
                    "prod":{},
                    "sral":{
                        "data":[{"name":"sral_no","value":"'.$serial_no.'","operator":"="}]
                    }
                }';
        $prod = GetAPIData("prod", $json, "GET");
    // ↓↓　<2020/04/08> <VinhDao> <prodをチェックする>
        if ( empty($prod["prod"]) 
            or GetFirstByField($prod, 'error') != '' ) {
            goto Result;
        }
    // ↑↑　<2020/04/08> <VinhDao> <prodをチェックする>

        $prod = $prod["prod"];
        if ( is_array($prod[0]) ) {
            $prod = $prod[0];
            $goods_code = $prod["sral"][0]["shin_cd"];
            $pack_code = $prod["pshin_cd"];
        }

        Result:
        return ($pack_code != "") ? 1 : 0;
    }

    // Function :products_idを変換
    // Input    :products_id :商品コード
    // Output   :serial_no   :シリアルＮｏ
    // Return   :0           :正常終了
    //          :-1          :異常終了
    function CnvertSerial($products_id, &$serial_no) {
        $serial_no = str_replace(array(" ", "-"), "", $products_id);
        return (strpos($serial_no, "-") === false) ? 0 : -1;
    }

    // Function :シリアル16桁対象製品かチェック
    // Input    :シリアルNo
    // Return   ：True Or False
    // Comment  :シリアル16桁対象製品でない場合 False
    function Check14($serial_no) {
        $goods_code = $pack_code = "";
        GetGoodsCode($serial_no, $goods_code, $pack_code);
        if ((int)substr($goods_code, 3, 2) >= 14) {
            // 商品コードの4・5桁目が14以上ならシリアル16桁対象製品
            return true;
        }
        return false;
    }

    // Function :セキュリティコードのチェック
    // Input    :シリアルNo16桁
    // Return   :
    // Comment  :
    function CheckSecurityCode($serial_no) {
        // セキュリティコードsCodeの算出
        $sCode = "";
        $rc = CalcSecurityCode($serial_no, $sCode);
        if ($rc != 0) {
            return -1;
        }
        return (substr($serial_no, -3) != $sCode) ? -1 : 0;
    }

    // Function :セキュリティコード算出
    // Input    :シリアルNo16桁 Or 13桁
    // Return   :セキュリティコード
    // Comment  :
    function CalcSecurityCode($serial_no, &$sCode) {
        // シリアルNoは数値かチェック
        if (!is_numeric($serial_no)) {
            return -1;
        }

        $buf1 = substr($serial_no, 0, 13);

        // 1.シリアル№13桁を除数999で割り、剰余を取り出す
        //（Modは桁あふれで使用できないので注意！）
        $buf2 = $buf1 - (int)( $buf1 / 999 ) * 999;
        $buf2 = round($buf2, 4);

        // 2.シリアル№7桁目(チェックディジットキー)と定数1を足した数値を取り出す
        $buf3 = (int)substr($serial_no, 6, 1) + 1;

        // 3.「1.」の数値と「2.」の数値を掛ける。結果の下3桁をセキュリティコードとする
        $buf4 = substr((string)($buf2 * $buf3), -3);

        // 4.「3.」の結果が3桁未満の場合、先頭に0を付けて3桁にする
        $sCode = substr("000".(string)$buf4, -3);
        return 0;
    }

    // Function :シリアルNo16桁表示用
    // Input    :シリアルNo13桁
    // Return   :シリアルNo16桁
    // Comment  :
    function CalcSerial_16($serial_no) {
        $tmp = $serial_no;

        // シリアルからハイフンを取る
        $serial_no1 = "";
        $rc = CnvertSerial($serial_no, $serial_no1);
        if ($rc == 0) {
            $serial_no = $serial_no1;
        }

        // 商品コードから14シリーズかどうかチェック
        if (Check14($serial_no)) {
            // セキュリティコードsCodeの算出
            $sCode = "";
            $rc = CalcSecurityCode($serial_no, $sCode);
            if ($rc != 0) {
            //　↓↓　＜2020/10/30＞　＜VinhDao＞　＜修正＞
                // header("Location: Err.php?no=20&id=".$serial_no."ng");
                // exit;
                Redirect::goto('Err.php?no=20&id=' . $serial_no . 'ng');
            //　↑↑　＜2020/10/30＞　＜VinhDao＞　＜修正＞
            }
            return $serial_no.$sCode;
        }
        return $tmp;
    }

    // Function :シリアルNo13桁ハイフン付加
    // Input    :serial_no  :シリアルNo13桁
    // Return   :product_id :チェック後の付随シリアルNo
    // Comment  :付随シリアルNoのハイフン付加
    function AddHyphenSerial13($serial_no) {
        return substr($serial_no, 0, 4)."-".substr($serial_no, 4, 4)."-".substr($serial_no, 8);
    }

    // Function :シリアルNo16桁ハイフン付加
    // Input    :serial_no  :シリアルNo16桁
    // Return   :product_id :チェック後の付随シリアルNo
    // Comment  :付随シリアルNoのハイフン付加
    function AddHyphenSerial16($serial_no) {
        return substr($serial_no, 0, 4)."-".substr($serial_no, 4, 4)."-".substr($serial_no, 8, 5)."-".substr($serial_no, -3);
    }

    // 2019/12/27 t.maruyama 追加 ↓↓ 変数の宣言が足りないので追加（仮実装）
    define("SKN_TOP_URL_JA", ""); // TODO 仮実装
    define("SKN_TOP_UR", ""); // TODO 仮実装
    // 2019/12/27 t.maruyama 追加 ↑↑
    function GetTopPage($products_code) {
        if ((string)($products_code) == (string)(NBKJA07_CODE)) {
            return SKN_TOP_URL_JA;
        }
        return SKN_TOP_UR;
    }

    function DateDiff($d1, $d2, $negative = false) {
        $date1 = new DateTime($d1);
        $date2 = new DateTime($d2);
        $diff = $date1->diff($date2);
        return ($negative) ? $diff->format("%r%a") : $diff->days;
    }

    function DateAdd($date, $add, $option = "d", $format = "Y/m/d") {
        switch ($option) {
            case "w":
                return date($format, strtotime($date."+".$add." weeks"));
            case "m":
                return date($format, strtotime($date."+".$add." months"));
            default:
                return date($format, strtotime($date."+".$add." days"));
        }
    }

	// Function :Cookies will be changed to session['store'] stored in localstorage
    // Input    :name      :key of session
    // Input    :field     :value you want to get
    // Return   :cookieArr :array contain value
	// Comment  : all session['store'] will be saved in localstorage
    function GetCookie($name, $field = '') {
        if ( empty($_SESSION['store'][$name]) ) {
            return $field == "" ? array() : '';
        }
    //　↓↓　＜2020/10/30＞　＜VinhDao＞　＜修正＞
        // $cookieArr = unserialize($_SESSION['store'][$name]);
        // return $field != "" ? $cookieArr[$field] : $cookieArr;

	// ↓↓　<2022/31/08> <KhanhDinh> <decode value>
		$cookieArr = json_decode($_SESSION['store'][$name],true);
	// ↑↑　<2022/31/08> <KhanhDinh> <decode value>

        if ( $field != '' ) {
	// ↓↓　<2022/31/08> <KhanhDinh> <create when logined and update expire when move tab>
			$expire = strtotime("now") + CookiesSaveDays * 86400;
			$expire =  $expire * 1000; // unit: milisecond => compare milisecond in js
			$_SESSION['store']['expire'] = $expire;
	// ↑↑　<2022/31/08> <KhanhDinh> <create when logined and update expire when move tab>
            return !isset($cookieArr[$field]) ? '' : $cookieArr[$field];
        }
        else {
            return $cookieArr;
        }
    //　↑↑　＜2020/10/30＞　＜VinhDao＞　＜修正＞
    }

// ↓↓　<2022/31/08> <KhanhDinh> <修正>
	// Function :Cookies will be changed to session['store'] stored in localstorage
    // Input    :name  :key of session
    // Input    :val   :value of session
	// Comment  : all session['store'] will be saved in localstorage
    function UpdateCookie($name, $val, $expire = CookiesSaveDays, $option = "day") {
		$_SESSION['store'][$name] = json_encode($val);
    //　↓↓　＜2020/10/30＞　＜VinhDao＞　＜修正＞
        // if ($expire == "none") {
        //     // setcookie($name, serialize($val), time() + 365 * 86400, '/');
        //     $expire = 365 * 86400;
        //     goto Update;
        // }
        // $expire = ($option == "day") ? $expire * 86400 : $expire;
        // Update:
        // setcookie($name, serialize($val), time() + $expire, '/');
    //　↑↑　＜2020/10/30＞　＜VinhDao＞　＜修正＞
		
    }
// ↑↑　<2022/31/08> <KhanhDinh> <修正>

    // ↓★★動作確認用
    function WriteLog2($is_drm = false) {
    }
    // ↑★★動作確認用
    function WriteLog($is_drm = false) {
        global $LGINLogFileDirectory;
        // ログインした際にサーバー側のCSVにログを書き込みます。
        $temp     = explode("/", $_SERVER["SCRIPT_NAME"]);
        $temp     = $temp[count($temp) - 1];
        $json = '{
                    "sral":{
                        "data":[
                            {"name":"sral_no","value":"' . GetLoginSerial() . '","operator":"="}
                        ]
                    }
                }';
        $user = GetAPIData("users", $json, "GET");
		
        $user_cd = "";
 
    // ↓↓　<2020/04/08> <VinhDao> <syntaxを変換する>
        if ( !empty( $user["users"] ) and GetFirstByField($user, 'error') == '' ) {
            $user_cd = $user["users"][0]["user_cd"];
        }
        // $user_cd = (is_array($user["users"][0])) ? $user["users"][0]["user_cd"] : "";
        // $csv_body = date('Y/m/d') . ", " . date("H:i:s") . ", " . $_SERVER["REMOTE_ADDR"] . ", " . GetCookie(LoginProduct, f_serialno) . ", " . $user_cd . ", View, ".(($is_drm == true) ? str_replace('.php', '',$temp) : $temp);
        
        $FileName = $LGINLogFileDirectory . 'sorizonet_' . ($is_drm ? "drmori" : "website") . date('\_Y\_m\.\t\x\t');
        file_put_contents(
            $FileName, 
            [
                'Date'    => date('Y/m/d\,'),
                'Time'    => date('H:i:s\,'),
            // ↓↓　＜2020/09/09＞　＜VinhDao＞　＜No.8とNo.9のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞
                // 'IP'      => $_SERVER["REMOTE_ADDR"] . ',',
                'IP'      => getClientIP() . ',',
            // ↑↑　＜2020/09/09＞　＜VinhDao＞　＜No.8とNo.9のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞
                'Serial'  => GetCookie(LoginProduct, f_serialno) . ',',
                'User_cd' => $user_cd . ',',
                'action'  => 'view' . ',',
                'page'    => $is_drm ? str_replace('.php', '', $temp) : $temp,
                'end'     => PHP_EOL
            ], 
            FILE_APPEND | LOCK_EX
        );
    // ↑↑　<2020/04/08> <VinhDao> <syntaxを変換する>
    }

    function execDownload($filename, $body, $redirect, $isEcho = true) {
        global $DLLogFileDirectory;
    // 2019/12/27 t.maruyama 修正 ↓↓ date関数とtime関数の使い方が間違っていたので修正(time関数は引数を取らない関数）
    //    $csv_body = date("Y/m/d").','.time("h:i:s").",".GetLoginSerial().$body;
        $csv_body = date("Y/m/d,h:i:s").",".GetLoginSerial().$body;
    // 2019/12/27 t.maruyama 修正 ↑↑

    // ↓↓　＜2020/09/11＞　＜VinhDao＞　＜No.12とNo.13のHP合同PRJ-www_sorizo-運用_20200911を修正する。＞
        // file_put_contents($DLLogFileDirectory . trim($filename, '\\'), $csv_body.PHP_EOL, FILE_APPEND | LOCK_EX);
        file_put_contents(
            $DLLogFileDirectory . 'www_sorizo/download/' . trim($filename, '\\'), 
            rtrim($csv_body, ', ') . ',' . getClientIP() . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    // ↑↑　＜2020/09/11＞　＜VinhDao＞　＜No.12とNo.13のHP合同PRJ-www_sorizo-運用_20200911を修正する。＞

    // 2020/02/26 t.maruyama 削除 ↓↓ 「プログラム更新 -> 簿記10 レベルアップ」メニューでダウンロードできない不具合の修正
    // AWS環境では、リダイレクト前に出力するとリダイレクト処理が動かないので削除。
    //    echo ($isEcho) ? $csv_body : "";
    // 2020/02/26 t.maruyama 削除 ↑↑

    //　↓↓　＜2020/10/30＞　＜VinhDao＞　＜修正＞
        // header("Location: ".$redirect);
        // exit;
        Redirect::goto($redirect);
    //　↑↑　＜2020/10/30＞　＜VinhDao＞　＜修正＞
    }

    // ↓↓　＜2021/06/15＞　＜YenNhi＞　＜write log into DB＞
    function execDownloadAndSaveDB($DownloadProgramName, $DownloadProgramYear, $DownloadProgramVersion, $redirect, $UserClassification = 1){
        $DownloadDate = date("Y/m/d H:i:s");
        $UserSerialNumber = GetLoginSerial();
        try {
            $conn = ConnectSorizo();
            $ipaddress = getClientIP();
            
            $query = "INSERT INTO Kakutei_Download (
                    DownloadDate, 
                    DownloadProgramName, 
                    DownloadProgramYear, 
                    DownloadProgramVersion, 
                    UserClassification, 
                    UserSerialNumber, 
                    UserIDPartner, 
                    AdminComment, 
                    AdminCommentDate, 
                    DeleteFlag,
                    DeleteFlagDate,
                    UserIPAddress
                ) 
                VALUES (
                    '{$DownloadDate}', 
                    '{$DownloadProgramName}', 
                    $DownloadProgramYear, 
                    '{$DownloadProgramVersion}', 
                    '{$UserClassification}', 
                    '{$UserSerialNumber}', 
                    NULL, 
                    NULL, 
                    NULL, 
                    NULL, 
                    NULL,
                    '$ipaddress'
                )";
            
            $result = mysqli_query($conn, $query);

            Redirect::goto($redirect);
            
        } catch (Exception $e){
            Redirect::goto($redirect);        
        }
    }
    // ↑↑　＜2021/06/15＜YenNhi＞　＜write log into DB＞

    /**
        Format string to insert hyphens
        @param: string $string
        @param: int $digit
        @return string
    **/
    function addHyphen( $string, $digit ) {
        $format = "";
        switch ( $digit ) {
            // 12 digits: 0000-000000-00
            case 12:
                $format .= substr( $string, 0, 4 );
                $format .= "-" . substr( $string, 4, 6 );
                $format .= "-" . substr( $string, 10 );
                break;

            // 13 digits: 0000-0000-00000
            case 13:
                $format .= substr( $string, 0, 4 );
                $format .= "-" . substr( $string, 4, 4 );
                $format .= "-" . substr( $string, 8 );
                break;

            // 16 digits: 0000-0000-00000-000
            case 16:
                $format .= substr( $string, 0, 4 );
                $format .= "-" . substr( $string, 4, 4 );
                $format .= "-" . substr( $string, 8, 5 );
                $format .= "-" . substr( $string, 13 );
                break;
        }
        return $format;
    }

    /**
        Get IP Address Client
        @return string IP 
    **/
    function getClientIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    // ダウンロードファイルパス取得
    function getDownloadFileUrl($service_product, $version) {

        $url = 'https://member.sorimachi.co.jp/api/check_download_file_path.php'; // API

        $debug_flg = false; // デバッグモード
        if ($debug_flg) {
            $url = 'https://member.hp-sorimachi.mym.sorimachi.biz/api/check_download_file_path.php';
        }
        
        $data = [
            'service_product' => $service_product,
            'version' => $version,
        ];
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => $data,
            //CURLOPT_HEADER => true,
            //CURLOPT_NOBODY => true,
            CURLOPT_RETURNTRANSFER => true,
            //CURLINFO_HEADER_OUT => true,
        ));
        
        // URL の内容を取得する
        $ret =  (string)curl_exec($curl);

        //var_dump($ret);
        curl_close($curl);
        return $ret;
    }

    // ファイルダウンロード
    function fileDownload($service_product, $version) {
        $url = getDownloadFileUrl($service_product, $version);
        $headers = get_headers($url);
        if( is_array($headers) && count($headers) > 0 ) {
            if( strpos($headers[0],'OK') ) {
                addHeader($url);
                readfile($url);
                return;
            } else {
                echo '"<script type="text/javascript">alert("ダウンロードに失敗しました。");</script>"';
            }
        } else {
            echo '"<script type="text/javascript">alert("ダウンロードに失敗しました。");</script>"';
        }
    }

    function addHeader($path) {
        // ウェブブラウザが独自にMIMEタイプを判断する処理を抑止する
        header('X-Content-Type-Options: nosniff');
        //header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($path));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        // header('Pragma: public');
        // header('Content-Length: ' . filesize($path));
        while (ob_get_level()) {
            ob_end_clean();
        }
    }

    // 2021/01/17 haruka-suganuma add
    // 元号の取得
    function getEra($year){
        /**
         * 元号情報の保持
         * 元号が始まる年と元号名を記載
         */
        $eras = [
            ['year' => 2018, 'name' => '令和'],
            ['year' => 1988, 'name' => '平成'],
        ];

        foreach ($eras as $era) {
            if ($year <= $era["year"]) continue;

            $era_year = $year - $era["year"];
            if ($era_year === 1) {
                return $era["name"]. "元年分";
            }
            return $era["name"]. $era_year. "年分";
        }
        return "";
    }
/* ↑↑　<2022/31/08> <KhanhDinh> <add> */
	/**
	* delete all session except key $except
	*
	* @param $except except key when delete session, delete all the session
	* 
	* @author Khanh
	*/ 
	function deleteExceptSession($except)		
	{
		foreach($_SESSION as $key => $val){
			if ($key !== $except){
				unset($_SESSION[$key]);
			}
		}
	}

?>
