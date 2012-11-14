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


require_once('../lib/CommonStaticValue.php');
require_once('../mdl/ModelCommon.php');
class m_user_master extends ModelCommon{
/*******************************************************************************
	クラス名：m_user_master.php
	処理概要：ユーザーマスタモデル
*******************************************************************************/
	private $r_view_rec;					// ビューから取得したレコード
	private $r_col_name;					// カラムのコメント一覧
	private $r_table_rec;					// テーブルレコード
	private $r_where;						// where句配列
	private $r_ordery_by;					// order by句配列
	private $r_group_by;					// group by句配列
	private $c_column_info;					// カラム情報クラス
	
	private $set_view_name	= 'USERS_V';	// ビュー名
	private $set_table_name	= 'USERS';		// テーブル名
	//※table_nameはModelCommonで定義されています
	
	private $primary_key_col = 'USER_ID';	// 主キーの項目
	
	public	$htmlspchar_flag = 'Y';			// htmlspecialchars適用フラグ
	public	$shortname_size  = 8;			// 短縮名の文字サイズ
	public	$shortname_size_REMARKS = 25;	// 備考用短縮名の文字サイズ
	
	private $debug_mode = 0;
/*============================================================================
	コンストラクタ
	引数:
				$p_rec_get_flag				レコード取得フラグ(Y:取得,N:取得しない)
				$pr_where					where句配列
				$pr_ordery_by				order by句配列
				$pr_group_by				group by句配列
  ============================================================================*/
	function __construct($p_rec_get_flag = 'N', $pr_where = '', $pr_ordery_by = '', $pr_group_by = ''){
		// 継承元のコンストラクタを起動
		ModelCommon::__construct($this->set_view_name);		// ビュー名を指定
		if($this->debug_mode==1){print("Step-__construct継承元のコンストラクタを起動");print "<br>";}
		
		// カラム情報を取得
		require_once('../mdl/m_column_info.php');
		$this->c_column_info = new ColumnInfo($this->set_view_name);		// ビュー名を指定
		if($this->debug_mode==1){print("Step-__constructカラム情報を取得");print "<br>";}
		
		// レコード取得用配列セット
		$this->r_where			= $pr_where;
		$this->r_ordery_by		= $pr_ordery_by;
		$this->r_group_by		= $pr_group_by;
		
		// レコード取得
		if($p_rec_get_flag == 'Y'){
			$this->queryDBRecord();
		}
		if($this->debug_mode==1){print("Step-__constructレコード取得");print "<br>";}
		
	}
	
/*============================================================================
	レコード取得
  ============================================================================*/
	function queryDBRecord(){
		$lr_result_rec		= "";
		$lr_view_rec		= array();
		$lr_comment_rec		= array();
		
		// where セット
		$l_where_phrase		= $this->setWherePhrase($this->r_where);
		// order by セット
		$l_orderby_phrase	= $this->setOrderbyPhrase($this->r_ordery_by);
		// group by セット
		$l_groupby_phrase	= $this->setGroupbyPhrase($this->r_group_by);
		
		// レコード取得
		$lr_result_rec		= $this->getRecord();
		if($this->debug_mode==1){print("Step-queryDBRecordレコード取得");print "<br>";}
		
		// レコードが返ってきた場合は戻り値をセットする
		if(count($lr_result_rec)>0){
			$l_loop_cnt = 0;
			foreach($lr_result_rec as $key => $value){
				$l_loop_cnt++;
				foreach($value as $item_key => $item_value){
					if(ereg("^[0-9]+$",$item_key) ){
						// 配列キーが数値の部分は無視
					} else {
						//print "item_key:".$item_key." item_value:".$item_value."<br>";
						if($this->htmlspchar_flag == 'Y'){
							// htmlspecialchars適用
							if($item_value != ''){
								$lr_view_rec[$l_loop_cnt][$item_key] = htmlspecialchars($item_value);		// レコードの先頭番号は1
							}else{
								// 空の項目は不正な値がセットされてしまう為、適用しない
								$lr_view_rec[$l_loop_cnt][$item_key] = $item_value;		// レコードの先頭番号は1
							}
							// レコード番号1のときのみカラムコメント一覧作成
							if($l_loop_cnt == 1){
								$lr_comment_rec[$item_key] = htmlspecialchars($this->c_column_info->getColumnNameJ($item_key));
							}
							
							// 氏名、会社名、グループ名、備考は表示用の短縮名を作成する
							if($item_key=="NAME" || $item_key=="COMPANY_NAME" || $item_key=="GROUP_NAME"){
								$l_strlen = mb_strlen($lr_view_rec[$l_loop_cnt][$item_key]);
								if($l_strlen > $this->shortname_size){
									$lr_view_rec[$l_loop_cnt][$item_key."_SHORT"] = mb_substr($lr_view_rec[$l_loop_cnt][$item_key], 0, $this->shortname_size)."...";
								}else{
									$lr_view_rec[$l_loop_cnt][$item_key."_SHORT"] = $lr_view_rec[$l_loop_cnt][$item_key];
								}
							}else if($item_key=="REMARKS"){
								$l_strlen = mb_strlen($lr_view_rec[$l_loop_cnt][$item_key]);
								if($l_strlen > $this->shortname_size_REMARKS){
									$lr_view_rec[$l_loop_cnt][$item_key."_SHORT"] = mb_substr($lr_view_rec[$l_loop_cnt][$item_key], 0, $this->shortname_size_REMARKS)."...";
								}else{
									$lr_view_rec[$l_loop_cnt][$item_key."_SHORT"] = $lr_view_rec[$l_loop_cnt][$item_key];
								}
							}
						}else{
							// htmlspecialchars非適用
							$lr_view_rec[$l_loop_cnt][$item_key] = $item_value;		// レコードの先頭番号は1
							// レコード番号1のときのみカラムコメント一覧作成
							if($l_loop_cnt == 1){
								$lr_comment_rec[$item_key] = $this->c_column_info->getColumnNameJ($item_key);
							}
						}
					}
				}
			}
		}
		
		// レコードをセット
		$this->r_view_rec		= $lr_view_rec;
		$this->r_col_name		= $lr_comment_rec;
		if($this->debug_mode==1){print("Step-queryDBRecordレコードをセット");print "<br>";}
		
		// 条件初期化
		$this->resetCondition();
		if($this->debug_mode==1){print("Step-queryDBRecord条件初期化");print "<br>";}
	}

/*============================================================================
	特定カラムデータ取得
	特定カラムのデータ一覧を取得する
	引数:			$p_item						取得対象項目
					$p_obtain_duplicate_flag	重複値削除フラグ(Y:する,N:しない)
  ============================================================================*/
	function getColumnValueAll($p_item, $p_obtain_duplicate_flag = 'Y'){
		if($this->debug_mode==1){print("Step-getColumnValueAll開始");print "<br>";}
		$lr_return_value = "";
		
		// 項目指定がない場合は終了
		if(is_null($p_item) || $p_item == ''){
			return false;
		}
		
		// レコードが0件の場合は終了
		if(count($this->r_view_rec) == 0){
			return false;
		}
		
		// レコードを検索し、指定の項目のみ取得
		$lr_value = "";
		foreach($this->r_view_rec as $l_rec_num => $l_rec_data){
			$lr_value[$l_rec_num] = $l_rec_data[$p_item];
		}
		
		// 重複削除
		if($p_obtain_duplicate_flag == 'Y'){
			$lr_return_value = array_unique($lr_value);
		}else{
			$lr_return_value = $lr_value;
		}
		
		// 並べ替え
		sort ($lr_return_value);
		
		if($this->debug_mode==1){print("Step-getColumnValueAll終了");print "<br>";}
		return $lr_return_value;
	}
	
/*============================================================================
	新規登録処理
	処理概要：INSERT処理を行う
			$pr_data						更新するカラムと値の連想配列(文字列は''で囲む事)
			$p_update_user_key				更新者キー値
  ============================================================================*/
	function execInsert($pr_data, $p_update_user_key = ''){
		// レコードが設定されていない場合は処理しない
		if(count($this->r_table_rec) == 0){
			return false;
		}
		
		// SQL生成のインクルード
		require_once('../lib/crudSQL.php');
		$lc_create_sql = new crudSQL();
		$lr_sql = $lc_create_sql->crudSQLString($this->set_table_name, "INSERT", $pr_data , $p_update_user_key);
		
		//print $lr_sql."<br>";
		
		// SQL実行クラスインスタンス作成
		require_once('../mdl/CommonExecution.php');
		$lc_cex = new CommonExecution();
		
		// SQL実行
		$l_rtcode = $lc_cex->execSilentSQL($lr_sql);
		if($this->debug_mode==1){print("Step-__insertRecord SQL実行");print "<br>";}
		
		if($l_rtcode != RETURN_NOMAL){
			// Duplicate entry
			if(mb_strpos($l_rtcode, STATE_DUPLICATE_ENTRY) == 0 && $l_rtcode != RETURN_NOMAL){
				require_once('../lib/CommonFormatCheck.php');
				$lc_ccnm = new CommonFormatCheck();
				print "入力された「名前」又は「コード」は既に使用されています。"."\n";
			}
			return false;
		}
		return true;
	}
	
/*============================================================================
	更新処理実行
	引数:
			$pr_data						更新するカラムと値の連想配列
			$p_key_value					更新対象キー値
			$p_update_user_key				更新者キー値
			$p_where_phrase					where句
  ============================================================================*/
	function execUpdate($pr_data, $p_key_value = '', $p_update_user_key = '', $p_where_phrase = ''){
		
		// レコードが空の場合は何もしない
		if(count($pr_data) == 0){
			return false;
		}
		
		// SQL生成のインクルード
		require_once('../lib/crudSQL.php');
		$lc_create_sql = new crudSQL();
		$lr_sql = $lc_create_sql->crudSQLString($this->set_table_name, "UPDATE", $pr_data, $p_update_user_key, $p_key_value, $p_where_phrase);
		
		//print $lr_sql."<br>";
		//return true;
		
		// SQL実行クラスインスタンス作成
		require_once('../mdl/CommonExecution.php');
		$lc_cex = new CommonExecution();
		
		// SQL実行
		$l_rtcode = $lc_cex->execSilentSQL($lr_sql);
		if($this->debug_mode==1){print("Step-__updateRecord SQL実行");print "<br>";}
		
		if($l_rtcode != RETURN_NOMAL){
			// Duplicate entry
			if(mb_strpos($l_rtcode, STATE_DUPLICATE_ENTRY) == 0 && $l_rtcode != RETURN_NOMAL){
				require_once('../lib/CommonFormatCheck.php');
				$lc_ccnm = new CommonFormatCheck();
				print "入力された「名前」又は「コード」は既に使用されています。"."\n";
			}
			return false;
		}
		return true;
	}
	
/*============================================================================
	新規登録処理
	処理概要：
				r_table_recにセットされたレコードを新規登録処理を行う
	引数:
			$p_update_user_key				更新者ユーザーID
  ============================================================================*/
	function insertRecord($p_update_user_key){
		
		foreach($this->r_table_rec as $l_recnum => $lr_record){
			if(!$this->execInsert($lr_record, $p_update_user_key)){
				// エラーとなるレコードが発生した時点で終了
				return false;
			}
		}
		return true;
	}
	
/*============================================================================
	更新処理
	処理概要：
				r_table_recにセットされたレコードをuser_idをキーとして更新処理
				を行う
	引数:
  ============================================================================*/
	function updateRecord($p_update_user_key){
		//var_dump($this->r_table_rec);return true;
		
		foreach($this->r_table_rec as $l_recnum => $lr_record){
			//var_dump($lr_record[$this->primary_key_col]);
			//var_dump(is_null($lr_record[$this->primary_key_col]) || $lr_record[$this->primary_key_col] == '');return true;
			if(is_null($lr_record[$this->primary_key_col]) || $lr_record[$this->primary_key_col] == ''){
				// 主キーの設定が無い場合
				return false;
			}else{
				if(!$this->execUpdate($lr_record, $lr_record[$this->primary_key_col], $p_update_user_key)){
					// エラーとなるレコードが発生した時点で終了
					return false;
				}
			}
		}
		return true;
		
	}
	
/*============================================================================
	データチェック
  ============================================================================*/
	function checkData(){
	//	print "count->\n";
	//	print count($this->r_table_rec)."\n";
	//	print "r_table_rec->\n";
	//	print var_dump($this->r_table_rec)."\n";
		if(count($this->r_table_rec) > 0){
			
			require_once('../lib/CommonFormatCheck.php');
			$lc_ccnm = new CommonFormatCheck();
			
			foreach($this->r_table_rec as $l_key => $lr_data_rec){
				/*--------------------
				   標準チェック
				  --------------------*/
				$lr_check_res[$l_key] = $lc_ccnm->checkValue($lr_data_rec, $this->set_table_name);
				
				/*--------------------
				   個別チェック
				  --------------------*/
				// ユーザーコード
				if(!is_null($lr_data_rec['USER_CODE']) && strlen($lr_data_rec['USER_CODE']) > 0){
					if(!preg_match('/^[a-zA-Z0-9\-\_]{1,50}+$/',$lr_data_rec['USER_CODE'])){
						$lr_check_res[$l_key]['USER_CODE']['STATUS'] = 2;
						$lr_check_res[$l_key]['USER_CODE']['MESSAGE'] .= "「".$this->c_column_info->getColumnNameMS('USER_CODE')."」は半角英数字と「-」、「_」で入力して下さい。"."\n";
					}
				}
				
				// フリガナ
				if(!is_null($lr_data_rec['KANA']) && strlen($lr_data_rec['KANA']) > 0){
					if(!preg_match('/^[ァ-ヾ｡-ﾟ\　\ ]+$/u',$lr_data_rec['KANA'])){
						$lr_check_res[$l_key]['KANA']['STATUS'] = 2;
						$lr_check_res[$l_key]['KANA']['MESSAGE'] .= "「".$this->c_column_info->getColumnNameMS('KANA')."」はカナで入力して下さい。"."\n";
					}
				}
				
				// パスワード
				if(!is_null($lr_data_rec['PASSWORD']) && strlen($lr_data_rec['PASSWORD']) > 0){
					if(!preg_match('/^[a-zA-Z0-9\!\#\$\%\&\(\)\*\+\,\-\.\_\/]{1,50}+$/',$lr_data_rec['PASSWORD'])){
						$lr_check_res[$l_key]['PASSWORD']['STATUS'] = 2;
						$lr_check_res[$l_key]['PASSWORD']['MESSAGE'] .= "「".$this->c_column_info->getColumnNameMS('PASSWORD')."」は〔半角英数字と記号［!#$%&()*/+-_,.］〕で入力して下さい。"."\n";
					}
				}
				
				// 郵便番号
				if(!is_null($lr_data_rec['ZIP_CODE']) && strlen($lr_data_rec['ZIP_CODE']) > 0){
					if(!$lc_ccnm->checkZipCode($lr_data_rec['ZIP_CODE'])){
						$lr_check_res[$l_key]['ZIP_CODE']['STATUS'] = 2;
						$lr_check_res[$l_key]['ZIP_CODE']['MESSAGE'] .= "「".$this->c_column_info->getColumnNameMS('ZIP_CODE')."」はnnn-nnnn形式〔半角数字〕で入力して下さい。"."\n";
					}
				}
				
				// 自宅電話番号
				if(!is_null($lr_data_rec['HOME_PHONE']) && strlen($lr_data_rec['HOME_PHONE']) > 0){
					if(!$lc_ccnm->checkPhone($lr_data_rec['HOME_PHONE'])){
						$lr_check_res[$l_key]['HOME_PHONE']['STATUS'] = 2;
						$lr_check_res[$l_key]['HOME_PHONE']['MESSAGE'] .= "「".$this->c_column_info->getColumnNameMS('HOME_PHONE')."」は○○-○○-○○形式〔半角数字〕で入力して下さい。(各○○は5桁以内)"."\n";
					}
				}
				
				// 自宅メールアドレス
				if(!is_null($lr_data_rec['HOME_MAIL']) && strlen($lr_data_rec['HOME_MAIL']) > 0){
					if(!$lc_ccnm->checkMailAddress($lr_data_rec['HOME_MAIL'])){
						$lr_check_res[$l_key]['HOME_MAIL']['STATUS'] = 2;
						$lr_check_res[$l_key]['HOME_MAIL']['MESSAGE'] .= "「".$this->c_column_info->getColumnNameMS('HOME_MAIL')."」はxxxx@xx形式〔半角英数字〕で入力して下さい。"."\n";
					}
				}
				
				// 携帯電話番号
				if(!is_null($lr_data_rec['MOBILE_PHONE']) && strlen($lr_data_rec['MOBILE_PHONE']) > 0){
					if(!$lc_ccnm->checkMobilePhone($lr_data_rec['MOBILE_PHONE'])){
						$lr_check_res[$l_key]['MOBILE_PHONE']['STATUS'] = 2;
						$lr_check_res[$l_key]['MOBILE_PHONE']['MESSAGE'] .= "「".$this->c_column_info->getColumnNameMS('MOBILE_PHONE')."」はnnn-nnnn-nnnn形式〔半角数字〕で入力して下さい。"."\n";
					}
				}
				
				// 携帯メールアドレス
				if(!is_null($lr_data_rec['MOBILE_PHONE_MAIL']) && strlen($lr_data_rec['MOBILE_PHONE_MAIL']) > 0){
					if(!$lc_ccnm->checkMailAddress($lr_data_rec['MOBILE_PHONE_MAIL'])){
						$lr_check_res[$l_key]['MOBILE_PHONE_MAIL']['STATUS'] = 2;
						$lr_check_res[$l_key]['MOBILE_PHONE_MAIL']['MESSAGE'] .= "「".$this->c_column_info->getColumnNameMS('MOBILE_PHONE_MAIL')."」はxxxx@xx形式〔半角英数字〕で入力して下さい。"."\n";
					}
				}
				
				// 口座番号
				if(!is_null($lr_data_rec['ACCOUNT_NUMBER']) && strlen($lr_data_rec['ACCOUNT_NUMBER']) > 0){
					if(!preg_match('/^\d{5,7}$/',$lr_data_rec['ACCOUNT_NUMBER'])){
						$lr_check_res[$l_key]['ACCOUNT_NUMBER']['STATUS'] = 2;
						$lr_check_res[$l_key]['ACCOUNT_NUMBER']['MESSAGE'] .= "「".$this->c_column_info->getColumnNameMS('ACCOUNT_NUMBER')."」はnnnnnnn〔半角数字〕で入力して下さい。"."\n";
					}
				}
			}
			//print var_dump($lr_check_res)."<br>";
			return $lr_check_res;
		}else{
			return false;
		}
	}
	
/*============================================================================
	getter
  ============================================================================*/
	// レコード全て
	function getViewRecord(){
		return $this->r_view_rec;
	}
	// カラムのコメント(日本語名)一覧取得
	function getColumnComment(){
		return $this->r_col_name;
	}
	
/*============================================================================
	setter
  ============================================================================*/
	// Where
	function setWhereArray($pr_data){
		if(count($pr_data) > 0){
			$this->r_where			= $pr_data;
		}
	}
	// Order by
	function setOrderyBy($pr_data){
		if(count($pr_data) > 0){
			$this->r_ordery_by		= $pr_data;
		}
	}
	// Group by
	function setGroupBy($pr_data){
		if(count($pr_data) > 0){
			$this->r_group_by		= $pr_data;
		}
	}
	// 保存用レコード
	function setSaveRecord($pr_data){
		
	//	print_r($pr_data);
	//	echo count($pr_data);
		
		if(count($pr_data) > 0){
			$this->r_table_rec = "";
			$l_rec_cnt = 0;
			$l_single_flag = 0;
			
			foreach($pr_data as $l_key => $l_value){
				if(is_array($l_value)){
					// 複数レコード場合
					$l_single_flag = 0;
				}else{
					// 単一レコードの場合
					$l_single_flag = 1;
				}
			}
			
			if($l_single_flag == 0){
				// 複数レコード場合
				$this->r_table_rec = $pr_data;
			}else{
				// 単一レコードの場合
				$this->r_table_rec[0] = $pr_data;
			}
			
			
			/*
			if(count(array_slice($pr_data,0,1)) > 1){
				// 複数レコード場合
				$this->r_table_rec = $pr_data;
			}else{
				// 単一レコードの場合
				$this->r_table_rec[0] = $pr_data;
			}
			*/
		}
		
	//	echo "r_table_recの中<br>\n";
	//	print_r( $this->r_table_rec);
	//	echo "<br>\n";
		
	}
}
?>