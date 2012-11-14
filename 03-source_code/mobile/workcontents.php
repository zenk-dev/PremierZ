<?php
/*******************************************************************************
*	PremierZ - The first product of ZENK
*
*	one line to give the program's name and an idea of what it does.
*
*	Copyright (C) 2012 ZENK Co., Ltd
*
*	This program is free software; you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
*
*
*	This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
*
*	You should have received a copy of the GNU Affero General Public License along with this program; If not, see <http://www.gnu.org/licenses/>.
*******************************************************************************/

/******************************************************************************
 ファイル名：contentslist.php
 処理概要  ：作業一覧画面(TOP)
 GET受領値：
             token                      トークン(必須)
******************************************************************************/
	require_once('../lib/CommonStaticValue.php');
	$l_debug_mode	= 0;						// デバッグモード(0:無効、1:有効、2:POST/GET表示)
	if($l_debug_mode == 1 || $l_debug_mode == 2){
		print_r($_GET);
		print "step1<br>";
	}
/*----------------------------------------------------------------------------
  変数定義
  ----------------------------------------------------------------------------*/
	$l_terminal			= "";					// 端末キャリア
	$l_model			= "";					// 端末モデル
	$lr_spdesc			= "";					// 端末固有のヘッダー記載情報
	$l_char_code		= "character_code";		// 文字コード
	$l_doctype			= "declaration";		// ドキュメントタイプ宣言
	$l_xmlns			= "xmlns";				// XML名前空間
	$l_token			= "";					// GETトークン
	$l_sess_token		= "";					// セッショントークン
	$l_err_flag			= true;					// エラーフラグ
	$l_show_rec_cnt		= 0;					// 表示項目カウント
	$l_bottom_menu_cnt	= 0;					// ハイパーリンクカウント
	$guid				= NULL;					//

/*----------------------------------------------------------------------------
  モバイル共通関数インスタンス作成
  ----------------------------------------------------------------------------*/
	require_once('../lib/MobileCommonFunctions.php');
	$lc_mcf = new MobileCommonFunctions();
	
/*==================================
  キャリア判別
  ==================================*/
	require_once('../lib/CommonMobiles.php');
	$lc_cm = new CommonMobiles();
	$l_connec_terminal = $lc_cm->checkMobiles();
	
	$l_terminal		= $l_connec_terminal['Terminal'];
	$l_model		= $l_connec_terminal['Model'];
	
	if($l_terminal == TERMINAL_DOCOMO){$guid = "&guid=ON";}
	
	// 端末固有設定
	$lr_spdesc = $lc_mcf->getSpecificDescription($l_terminal, $l_model);
	
	if($l_debug_mode==1){print("Step-キャリア判別");print "<br>";}
	
/*----------------------------------------------------------------------------
  例外定義
  ----------------------------------------------------------------------------*/
	function my_exception_complist(Exception $e){
		echo "例外が発生しました。";
		echo $e->getMessage();
		return;
    }
	set_exception_handler('my_exception_complist');

	if($l_debug_mode==1){print("Step-例外定義");print "<br>";}
	
/*----------------------------------------------------------------------------
  GET引数取得
  ----------------------------------------------------------------------------*/
	$l_token			= $_GET['token'];				// トークン

	if($l_debug_mode==1){print("Step-GET引数取得");print "<br>";}
  
/*----------------------------------------------------------------------------
  セッション確認
  ----------------------------------------------------------------------------*/
	// セッションチェック
	$lr_session = $lc_mcf->sessionCheck($l_token);
	if(!$lr_session){
		$l_err_flag = false;
	}
	
	// ユーザーID設定
	$l_user_id = $lr_session['USER_ID'];
	$l_authority = $lr_session['AUTHORITY_CODE'];
	
	if($l_debug_mode==1){
		print "セッションtoken ->".$l_sess_token."<br>";
		print "テーブルtoken ->".$lr_session['SESS_TOKEN']."<br>";
	}
	
	if($l_debug_mode==1){print("Step-セッション確認");print "<br>";}

/*----------------------------------------------------------------------------
  ここまででエラーが有る場合は不正アクセス画面を表示
  ----------------------------------------------------------------------------*/
	if(!$l_err_flag){
		$lc_mcf->showUnauthorizedAccessPage($lr_spdesc, $l_terminal, $l_model);
		return;
	}

/*----------------------------------------------------------------------------
  データ読込
  ----------------------------------------------------------------------------*/
	require_once('../mdl/m_workstaff.php');
	$lc_mwkst = new m_workstaff();

	$lr_workstaff = $lc_mwkst->getContentsList($l_user_id, 'WR', 'WC');
	
	if($l_debug_mode==1){
		print count($lr_workstaff)."<br>";
		print_r($lr_workstaff);
		print "<br>";
	}
	
	//print_r($lr_workstaff);
	
	if($l_debug_mode==1){print("Step-データ読込");print "<br>";}
	
/*----------------------------------------------------------------------------
  表示項目
  ----------------------------------------------------------------------------*/
	foreach($lr_workstaff as $key => $w_value){
		$l_show_rec_cnt++;
		// 承認状況に応じてステータスと絵文字をセット
		if($w_value[APPROVAL_DIVISION]=="AP"){
			$set_emoji = "";
			$set_status = $w_value[STAFF_STATUS_NAME];
		} else if($w_value[APPROVAL_DIVISION]=="NO"){
			$set_emoji = "";
			$set_status = "作業承認";
		} else {
			$set_emoji = "";
			$set_status = "作業承認";
		}
		
		$base = NULL;
		if(isset($w_value[WORK_BASE_NAME])){
			$base = "(".$w_value[WORK_BASE_NAME].")";
		}
		
		$linkurl = "<a href=\"workdetail.php?token=".$l_token."&gv_work_staff_id=".$w_value[WORK_STAFF_ID]."\">".$w_value[WORK_NAME_SHORT].$base."</a>";
		$lr_show_rec[$l_show_rec_cnt] = array("value" => $set_emoji.$w_value[WORK_DATE_SHORT]."&nbsp;".$set_status."<br>&nbsp;&nbsp;".$linkurl);
	}
	
//	print_r($lr_show_rec);
/*----------------------------------------------------------------------------
  Smarty設定
  ----------------------------------------------------------------------------*/
/*==================================
  smartyクラスインスタンス作成
  ==================================*/
	require_once('../Smarty/libs/Smarty.class.php');
	$lc_smarty = new Smarty();
	if(is_null($lc_smarty)){
		throw new Exception('Smartyクラスが作成できませんでした');
	}
	$lc_smarty->template_dir	= DIR_TEMPLATES;
	$lc_smarty->compile_dir		= DIR_TEMPLATES_C;
	$lc_smarty->config_dir		= DIR_CONFIGS;
	$lc_smarty->cache_dir		= DIR_CACHE;
	
	if($l_debug_mode==1){print("Step-smartyクラスインスタンス作成");print "<br>";}
	
/*==================================
  smartyアサイン
  ==================================*/
	// ヘッダー部
	$lc_smarty->assign("doctype",		$lr_spdesc[$l_doctype]);
	$lc_smarty->assign("char_code",		$lr_spdesc[$l_char_code]);
	$lc_smarty->assign("xmlns",			$lr_spdesc[$l_xmlns]);
	$lc_smarty->assign("terminal",		$l_terminal);
	$lc_smarty->assign("model",			$l_model);
	
	// タイトル
	$lc_smarty->assign("headtitle",		SCREEN_ZSMM0020);
	$lc_smarty->assign("headinfo",		"");
	
	// ロゴ
	$lc_smarty->assign("img_logo",		MOBILE_LOGO);
	
	// 作業内容一覧
	$lc_smarty->assign("ar_workstaff",	$lr_show_rec);
	$lc_smarty->assign("token",			$l_token);
	
	// 作業完了一覧
	$l_comp_html	=	"<a href=\"completionlist.php?token=".$l_token."\" ".$lc_mcf->getAccessKeyPhrase($l_terminal, $l_model, 1).">".SCREEN_ZSMM0060."</a>";
	$lc_smarty->assign("move_comp",	$l_comp_html);
	
	// ハイパーリンク
	$l_bottom_menu_cnt++;
	$lr_bottom_menu[$l_bottom_menu_cnt]	=	array(
												"link_url"	=> $_SERVER['PHP_SELF']."?token=".$l_token,
												"value"		=> "ページ更新",
												"key"		=> "5"
											);
	/* 設定情報
	$l_bottom_menu_cnt++;
	$lr_bottom_menu[$l_bottom_menu_cnt]	=	array(
												"link_url"	=> "userssetup.php?token=".$l_token.$guid,
												"value"		=> SCREEN_ZSMM0010,
												"key"		=> "7"
											);
	*/
	if($l_authority == AUTH_ADMI || $l_authority == AUTH_GEN1 || $l_authority == AUTH_GEN2 || $l_authority == AUTH_GENE || $l_authority == AUTH_GEN4){
		$l_bottom_menu_cnt++;
		$lr_bottom_menu[$l_bottom_menu_cnt]	=	array(
													"link_url"	=> "worksituation.php?token=".$l_token,
													"value"		=> SCREEN_ZSMM0040,
													"key"		=> "8"
												);
	}
	
	$l_bottom_menu_cnt++;
	$lr_bottom_menu[$l_bottom_menu_cnt]	=	array(
												"link_url"	=> "logout.php?token=".$l_token,
												"value"		=> SCREEN_ZSMMC002,
												"key"		=> "9"
											);
/*
	$l_bottom_menu_cnt++;
	$lr_bottom_menu[$l_bottom_menu_cnt]	=	array(
												"link_url"	=> DIR_MAN."index.php?token=".$l_token,
												"value"		=> "操作マニュアル",
												"key"		=> "#"
											);
*/
	$lc_smarty->assign("fmlink",	$lr_bottom_menu);
		
	// コピーライト
	$lc_smarty->assign("txt_copyright", $lc_mcf->getCopyRight());
	
	if($l_debug_mode==1){print("Step-smartyアサイン");print "<br>";}
/*==================================
  ページ表示
  ==================================*/
	$lc_smarty->display('MobileTemplateWorkContents.tpl');
	
	if($l_debug_mode==1){print("Step-ページ表示");print "<br>";}
?>