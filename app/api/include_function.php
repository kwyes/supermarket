<?php
	/**
	 * Function
	 * 
	 * @author Jeong, Munchang
	 * @since Create: 2012. 06. 30 / Update: 2012. 08. 08
	 */
	
	defined('_APP_PHPMODULE') or die('Access Error.');
	
	/**
	 * @author Jeong, Munchang
	 * JMC_PrintJson: Generate sigle array type of json data
	 * In: $dataname - Name of data, $data - Array type data
	 * Out: None
	 */
	function JMC_PrintJson($dataname, $data) {
		echo "{ "."\"".$dataname."\" : [";		
		echo json_encode($data);
		echo "] }";
	}
	
	/**
	 * @author Jeong, Munchang
	 * JMC_PrintListJson: Generate multi array type of json data
	 * In: $dataname - Name of data, $data - Array type data
	 * Out: None
	 */
	function JMC_PrintListJson($dataname, $data) {
		echo "{ "."\"".$dataname."\" : [";
		for($i = 0; $i < count($data); $i++) {
			echo (($i != 0) ? "," : "");
			echo json_encode($data[$i]);
		}
		echo "] }";
	}
	
	/**
	 * @author Jeong, Munchang
	 * JMC_CreateSession: Generate session ID - Mix data and randum number and encode to base64 code
	 * In: none
	 * Out: $new_session
	 */
	function JMC_CreateSession() {
		$new_session = base64_encode(date('y').mt_rand(10, 99).date('m').mt_rand(10, 99).date('d').mt_rand(10, 99).date('h').mt_rand(10, 99).date('i').mt_rand(10, 99).date('s').mt_rand(10, 99));
		return $new_session;
	}
	
	/**
	 * @author Jeong, Munchang
	 * JMC_GetInput: Inistailize input variable
	 * In: $name - Name of variable, $method - Type of method(GET, POST)
	 * Out: $result - string or false
	 */
	function JMC_GetInput($name, $method) {
		if($name) {
			if($method == "POST") {
				if(isset($_POST[$name])) {
					$result = htmlspecialchars($_POST[$name]);
				}
			} elseif ($method == "GET") {
				if(isset($_GET[$name])) {
					$result = htmlspecialchars($_GET[$name]);
				}
			}
			if ($result == "" || $result == " ") {
				return false;
			} else {
				return $result;
			}
		} else {
			return false;
		}
	}
	
	/*
	 * Page_View1
	 * In: $cpage1, $scale1
	 * Out: $start_q1
	 */
	function Page_View1($cpage1, $scale1)
	{
		$start_q1 = $scale1 * ($cpage1 - 1);
		return $start_q1;
	}
	
	/*
	 * Br_wordcut
	 * In: $String, $MaxLen, $ShortenStr)
	 * Out: $news_textt
	 */
	function Br_wordcut($String, $MaxLen, $ShortenStr="..")
	{
		$news_textt = $String;
		$str = $news_textt;
		//
		if(strlen($str) > $MaxLen)
		{
			//$str = preg_replace("/\s+/", ' ', preg_replace("/(\r\n|\r|\n)/", " ", $str));
			$str = preg_replace("/(\r\n|\r|\n)/", " ", $str);
			//
			if(strlen($str) >= $MaxLen)
			{
				//$words=explode(' ',preg_replace("/(\r\n|\r|\n)/"," ",$str));
				$words = preg_split('/( |-|=|_|,|\.)/i', $str, -1, PREG_SPLIT_DELIM_CAPTURE);
	
				$str = '';
				$i = 0;
				while(strlen($str) + strlen($words[$i]) < $MaxLen)
				{
	
					$str.=$words[$i];
					$i++;
				}
				//
				$news_textt = trim($str);
				$news_textt .= "..";
	
			}
		}
		return $news_textt;
	}
	
	/*
	 * GetProductImageNameLocalFreesize: Search product's image
	 * In: $prod_id - ID of product, $prod_svr, $size - Output size
	 * Out: $image_file - Image's address or false
	 */
	function GetProductImageNameLocalFreesize($prod_id, $prod_svr, $size)
	{
		global $conn_hannam;
		mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
		$query = "select top 1 piFolder, piImageName from plu_info where piPluId = '$prod_id' and piSvr = '$prod_svr'";
		$dbraw = mssql_query($query, $conn_hannam);
		$result = mssql_fetch_array($dbraw);
		$image_folder = iconv('euc-kr', 'utf-8', $result['piFolder']);
		$image_name = iconv('euc-kr', 'utf-8', $result['piImageName']);
	
		if($image_name == "" || $image_name == " ") {
			$image_file = ADDRESS."/img_mobile/empty.png";
			return $image_file;
		} elseif($image_folder && $image_name) {
			$image_file = "http://184.69.79.114:8000/plu/COM/".str_replace("+", "%20", urlencode($image_folder))."/".str_replace("+", "%20", urlencode($image_name));

			$ch = curl_init($image_file);
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_exec($ch);
			$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if($retcode != 200) {
				$image_file = false;
			}
			curl_close($ch);
			return $image_file;
		} else {
			return false;
		}
	}
	
	/*
	 * ShowPRODCategory: Print product's category
	 * In: $type, $language
	 * Out: $category - string or false
	 */
	function ShowPRODCategory($type, $language)
	{
		global $conn_hannam;
		mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
		$query="select top 1 plc_prodName_$language, plc_prodSub_$language from plu_category where plc_prodType = '$type'";
		$dbraw = mssql_query($query, $conn_hannam);
		$result = mssql_fetch_array($dbraw);
	
		$category = iconv('euc-kr', 'utf-8', $result['plc_prodName_'.$language])." > ".iconv('euc-kr', 'utf-8', $result['plc_prodSub_'.$language]);
	
		if ($category)
		{
			return $category;
		}
		return false;
	
	}
	
	/*
	 * InsertSearchKeyword
	 * In: $bsId, $bsKeyword, $bsDate
	 * Out: None
	 */
	function InsertSearchKeyword($bsId, $bsKeyword, $bsDate)
	{
		global $conn_hannam;
		$bsKeyword = @iconv('utf-8', 'euc-kr', $bsKeyword);
	
		//체크 같은 아이피 1분내 검색 결과
		$bsIpaddress = $_SERVER['REMOTE_ADDR'];
		$bdDate1 = date("Y-m-d H:i:s", strtotime("-1 minute", strtotime($bsDate)));
		$query = "select top 1 * from board_search_mobile where bsIpaddress = '$bsIpaddress' and bsKeyword = '$bsKeyword' and bsDate > '$bdDate1' order by bsDate desc";
		$dbraw = mssql_query($query, $conn_hannam);
		$result = mssql_fetch_array($dbraw);
		$result_data = $result['bsId'];
		
		if ($result_data == "")
		{
			$query = "INSERT INTO board_search_mobile (bsKeyword, bsDate, bsIpaddress, bsActive) VALUES ('$bsKeyword', '$bsDate', '$bsIpaddress', '1')";
			mssql_query($query, $conn_hannam);
		}
	}
	
	/*
	 * DeleteSearchKeyword
	 * In: $bsDate
	 * Out: None
	 */
	function DeleteSearchKeyword($bsDate)
	{
		global $conn_hannam;
		$bdDate1 = date("Y-m-d H:i:s", strtotime("-40 day", strtotime($bsDate)));
		$query = "delete from board_search_mobile where bsDate < '$bdDate1'";
		
		mssql_query($query, $conn_hannam);
	}
?>