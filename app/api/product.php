<?php


	include_once("./include_setup.php");

	// Input variable
	$tel = JMC_GetInput("tel", METHOD);
	$session = JMC_GetInput("session", METHOD);
	$store = (JMC_GetInput("store", METHOD)) ? JMC_GetInput("store", METHOD) : "bby"; //Default: bby
	$language = (JMC_GetInput("language", METHOD)) ? JMC_GetInput("language", METHOD) : "korean"; //Default: korean
	$search_mode =  JMC_GetInput("search_mode", METHOD);
	$search_keyword = JMC_GetInput("search_keyword", METHOD);
	$search_index =  JMC_GetInput("search_index", METHOD);
	$search_plucategory = JMC_GetInput("search_plucategory", METHOD);
	$search_onsale = JMC_GetInput("search_onsale", METHOD);
	$search_store = JMC_GetInput("search_store", METHOD);
	$boardId = JMC_GetInput("boardId", METHOD);
	$page = (JMC_GetInput("page", METHOD)) ? JMC_GetInput("page", METHOD) : "1"; //Default: 1
	$mode = JMC_GetInput("mode", METHOD);

	// Check variable
	if($session && $tel && $mode) {
		try {
			// Select DB table for session ID
			mssql_select_db(HANNAM_DB_NAME, $conn_hannam);
			$query = "SELECT session_id, cardNo FROM tbl_app_member WHERE phoneNumber = '$tel'";
			$dbraw = mssql_query($query);
			$result = mssql_fetch_array($dbraw);

			// Authorize session ID
			if($result['session_id'] == $session) {

				//상품 정보 필터링
				//$PluRestrict = "and (prodKname not like '%".Br_dconv('풀무원')."%' and prodKname not like '%".Br_dconv('팔도면')."%')";
				//$PluRestrict = "and (prodKname not like '%".iconv('utf-8', 'euc-kr', '풀무원')."%')";

				//정육 검색 변경
				if($boardId == "9" || $boardId == "10" || $boardId == "53" || $boardId == "54" || $boardId == "67" || $boardId == "68" || $boardId == "86" || $boardId == "87") {
					$PluTerm = date("Y-m-d H:i:s", strtotime("-365 day"));
					$PluOnsale = "no";
				} else 				{
					if($search_mode) {
						$PluTerm = date("Y-m-d H:i:s", strtotime("-365 day"));
					} else {
						$PluTerm = date("Y-m-d H:i:s", strtotime("-7 day"));
					}
					$PluOnsale = "yes";
				}

				if($search_store && $store != $search_store) {
					$store = $search_store;
				}

				//전체 상품 검색
				if($boardId == "26" || $boardId == "37") {
					$plc_where = "";
				} else {
					if($search_plucategory) { //카테고리가 있는 경우
						$plc_where = "and prodType = '$search_plucategory'";
					} else { //카테고리가 없는 경우 - 상위 카테고리의 모든 하위 카테고리 보여준다.
						switch($boardId)
						{
							/* ---------------- Korean ---------------- */
							case "6" : { //매장 상품
								$plc_where = "and (ProdType = '02' or ProdType = '03' or ProdType = '04' or ProdType = '05' or ProdType = '06' or ProdType = '07' or ProdType = '08' or ProdType = '09' or ProdType = '10' or ProdType = '11' or ProdType = '12' or ProdType = '34' or ProdType = '35')";
								break;
							}
							case "7" : { //신선 청과
								$plc_where = "and (ProdType = '18' or ProdType = '19')";
								break;
							}
							case "8" : { //싱싱 수산
								$plc_where = "and ProdType = '25'";
								break;
							}
							case "9" : { //한남 정육
								$plc_where = "and (ProdType = '20' or ProdType = '21')";
								break;
							}
							case "10" : { //한남 반찬
								$plc_where = "and ProdType = '23'";
								break;
							}
							case "11" : { //하드 웨어
								$plc_where = "and (ProdType = '01' or ProdType = '13' or ProdType = '14' or ProdType = '36')";
								break;
							}
							/* ---------------- Korean ---------------- */

							/* ---------------- English ---------------- */
							case "50" : {
								$plc_where = "and (ProdType = '02' or ProdType = '03' or ProdType = '04' or ProdType = '05' or ProdType = '06' or ProdType = '07' or ProdType = '08' or ProdType = '09' or ProdType = '10' or ProdType = '11' or ProdType = '12' or ProdType = '34' or ProdType = '35')";
								break;
							}
							case "51" : { //신선 청과
								$plc_where = "and (ProdType = '18' or ProdType = '19')";
								break;
							}
							case "52" : { //싱싱 수산
								$plc_where = "and ProdType = '25'";
								break;
							}
							case "53" : { //한남 정육
								$plc_where = "and (ProdType = '20' or ProdType = '21')";
								break;
							}
							case "54" : { //한남 반찬
								$plc_where = "and ProdType = '23'";
								break;
							}
							case "55" : { //하드 웨어
								$plc_where = "and (ProdType = '01' or ProdType = '13' or ProdType = '14' or ProdType = '36')";
								break;
							}
							/* ---------------- English ---------------- */

							/* ---------------- Chinese ---------------- */
							case "64" : {
								$plc_where = "and (ProdType = '02' or ProdType = '03' or ProdType = '04' or ProdType = '05' or ProdType = '06' or ProdType = '07' or ProdType = '08' or ProdType = '09' or ProdType = '10' or ProdType = '11' or ProdType = '12' or ProdType = '34' or ProdType = '35')";
								break;
							}
							case "65" : { //신선 청과
								$plc_where = "and (ProdType = '18' or ProdType = '19')";
								break;
							}
							case "66" : { //싱싱 수산
								$plc_where = "and ProdType = '25'";
								break;
							}
							case "67" : { //싱싱 수산
								$plc_where = "and (ProdType = '20' or ProdType = '21')";
								break;
							}
							case "68" : { //한남 반찬
								$plc_where = "and ProdType = '23'";
								break;
							}
							case "69" : { //하드 웨어
								$plc_where = "and (ProdType = '01' or ProdType = '13' or ProdType = '14' or ProdType = '36')";
								break;
							}
							/* ---------------- Chinese ---------------- */

							/* ---------------- Japanese ---------------- */
							case "83" : {
								$plc_where = "and (ProdType = '02' or ProdType = '03' or ProdType = '04' or ProdType = '05' or ProdType = '06' or ProdType = '07' or ProdType = '08' or ProdType = '09' or ProdType = '10' or ProdType = '11' or ProdType = '12' or ProdType = '34' or ProdType = '35')";
								break;
							}
							case "84" : { //신선 청과
								$plc_where = "and (ProdType = '18' or ProdType = '19')";
								break;
							}
							case "85" : { //싱싱 수산
								$plc_where = "and ProdType = '25'";
								break;
							}
							case "86" : { //한남 정육
								$plc_where = "and (ProdType = '20' or ProdType = '21')";
								break;
							}
							case "87" : { //한남 반찬
								$plc_where = "and ProdType = '23'";
								break;
							}
							case "88" : { //하드 웨어
								$plc_where = "and (ProdType = '01' or ProdType = '13' or ProdType = '14' or ProdType = '36')";
								break;
							}
							/* ---------------- Japanese ---------------- */
						}
					}
				}

				$scale1 = 8; //page
				//$start_q1 = Page_View1($page, $scale1);
				//$Get_next = "main=$main&boardId=$boardId&page=$page&search_mode=$search_mode&search_plucategory=$search_plucategory&search_keyword=$search_keyword&search_index=$search_index&search_plucategory=$search_plucategory&search_onsale=$search_onsale";

				if($search_keyword)
				{
					if($search_index == "name")
					{
						$where_keyword = @iconv('utf-8', 'euc-kr', $search_keyword);
						$where_keyword = " and prodName like '%$where_keyword%'";
					}
					else if($search_index == "kname")
					{
						//검색 키워드 저장
						InsertSearchKeyword('', $search_keyword, $today);
						//키워드 삭제(한달 전꺼)
						DeleteSearchKeyword($today);

						//$where_keyword = @iconv('utf-8', 'euc-kr', $search_keyword);
						//$where_keyword = " and prodKname like '%$where_keyword%'";
						$where_keyword_temp = @iconv('utf-8', 'euc-kr', $search_keyword);
						$where_keyword_explode = explode(" ", $where_keyword_temp);
						$where_keyword = "";
						for($i = 0; $i < count($where_keyword_explode); $i++) {
							$where_keyword .= " and prodKname like '%$where_keyword_explode[$i]%'";
						}
					}
					else
					{
						$where_keyword = " and prodId = '$search_keyword'";
					}
					$where_onsale = "";
				}
				else
				{
					if($PluOnsale == "yes")
					{
						$where_onsale = " and prodPromo > '0' and promoSdate < '$today'  and promoEdate > '$today'";
					}
					else
					{
						$where_onsale = "";
					}
				}

				//세일 중인것 체크
				/*
				 if($search_onsale)
				 {
				$where_onsale = " and prodPromo > '0' and promoSdate < '$today'  and promoEdate > '$today'";
				}
				*/


				//조건절 : LastModeDate(마지막 팔린 날짜)가 3달 전인것, useYN == Y사용중인것,  prodBal 판매가 1개 이상인것
				//$year1date = date("Y-m-d H:i:s", strtotime("-30 day"));

				//소트절 : 검색 조건이 없는 경우(전체) - 많이 팔린 순으로,
				if($search_mode)
				{
					$IT_sort = "order by prodBal asc";
				}
				else
				{
					$IT_sort = "order by prodBal asc";
				}

				//제한 품목 표시
				if($search_mode)
				{
					$where_restricted = "";
				}
				else
				{
					$where_restricted = $PluRestrict;
				}

				// 기존 홈페이지 검색 쿼리
				//$IT_where = " MP.prodId != '' $plc_where $where_restricted $where_onsale and MP.useYN = 'Y' and MP.prodBal < 0 and MP.LastModDate > '$PluTerm' ".$where_keyword." and webview is null";
				//$IT_field_distinct = "distinct(MP.prodName), MP.prodId, MP.prodKname, MP.prodType, MP.prodOUprice, MP.prodBal, MP.prodTax, MP.prodUnit, MP.prodPromo, MP.promoPrice, MP.LastModDate, MP.LastModTime, MP.useYN, convert(varchar(20), MP.promoSdate, 120) as promoSdate, convert(varchar(20), MP.promoEdate, 120) as promoEdate";
				//$IT_field = "MP.prodId, MP.prodName, MP.prodKname, MP.prodType, MP.prodOUprice, MP.prodBal, MP.prodTax, MP.prodUnit, MP.prodPromo, MP.promoPrice, MP.LastModDate, MP.LastModTime, MP.useYN, convert(varchar(20), MP.promoSdate, 120) as promoSdate, convert(varchar(20), MP.promoEdate, 120) as promoEdate, MP.prodsize as ProdSize";

				// Query 수정 2012 - 10 - 17
				$IT_where = " MP.prodId != '' $plc_where $where_restricted $where_onsale and MP.useYN = 'Y' and MP.prodBal <> 0 ".$where_keyword." and webview is null ";

				$IT_field_distinct = "distinct(MP.prodName), MP.prodId, MP.prodKname, MP.prodType, MP.prodOUprice, MP.prodBal, MP.prodTax, MP.prodUnit, MP.prodPromo, MP.promoPrice, MP.LastModDate, MP.LastModTime, MP.useYN, convert(varchar(20), MP.promoSdate, 120) as promoSdate, convert(varchar(20), MP.promoEdate, 120) as promoEdate";

				$IT_field = "MP.prodId, MP.prodName, MP.prodKname, MP.prodType, MP.prodOUprice, MP.prodBal, MP.prodTax, MP.prodUnit, MP.prodPromo, MP.promoPrice, MP.LastModDate, MP.LastModTime, MP.useYN, convert(varchar(20), MP.promoSdate, 120) as promoSdate, convert(varchar(20), MP.promoEdate, 120) as promoEdate, MP.prodsize as ProdSize";

				//$IT_where = " MP.prodId != '' $plc_where $where_restricted $where_onsale and MP.useYN = 'Y' and MP.prodBal < 0 and MP.LastModDate > '$PluTerm' ".$where_keyword." and webview is null";
				//$IT_field_distinct = "distinct(MP.prodName), MP.prodId, MP.prodKname, MP.prodType, MP.prodOUprice, MP.prodBal, MP.prodTax, MP.prodUnit, MP.prodPromo, MP.promoPrice, MP.LastModDate, MP.LastModTime, MP.useYN, convert(varchar(20), MP.promoSdate, 120) as promoSdate, convert(varchar(20), MP.promoEdate, 120) as promoEdate";
				//$IT_field = "MP.prodId, MP.prodName, MP.prodKname, MP.prodType, MP.prodOUprice, MP.prodBal, MP.prodTax, MP.prodUnit, MP.prodPromo, MP.promoPrice, MP.LastModDate, MP.LastModTime, MP.useYN, convert(varchar(20), MP.promoSdate, 120) as promoSdate, convert(varchar(20), MP.promoEdate, 120) as promoEdate, GP.ProdSize as ProdSize";


				//상품 전체 수 구하는 쿼리
				$query = "select count(prodId) as row from mfProd MP where $IT_where";
				//$query = "select count(prodId) as row from mfProd MP left outer join tblGalProdMaster GP on prodId = GP.ProdBarCode where $IT_where";

				if($store == "sry")
				{
					mssql_select_db(SRY_DB_NAME, $conn_sry);
					$dbraw = mssql_query($query, $conn_sry);
				}
				else if($store == "bby")
				{
					mssql_select_db(BBY_DB_NAME, $conn_bby);
					$dbraw = mssql_query($query, $conn_bby);
				}

				$result = mssql_fetch_array($dbraw);
				$row = $result['row'];

				//페이지 인덱스 구하기
				$cpage_que = $page * $scale1;
				if($cpage_que == $scale1)
				{
					$cpage_que = 0;
				}
				else
				{
					$cpage_que = $cpage_que - $scale1;
				}

				//마지막 장 갯수 구하기
				$IT_top = $row - $cpage_que;
				if($IT_top > $scale1)
				{
					$IT_top = $scale1;
				}
				else
				{
					$IT_top = $IT_top;
				}

				$distint_subquery = "(select $IT_field_distinct from mfProd MP) SP";
				$query = "select top $IT_top $IT_field from mfProd MP where MP.prodId not in (select top $cpage_que prodId from mfProd MP WHERE $IT_where $IT_sort) and $IT_where $IT_sort";
				//$distint_subquery = "(select $IT_field_distinct from mfProd MP) SP";
				//$query = "select top $IT_top $IT_field from mfProd MP INNER JOIN (SELECT DISTINCT (ProdBarCode), ProdSize FROM tblGalProdMaster) GP on MP.prodId = GP.ProdBarCode where MP.prodId not in (select top $cpage_que prodId from mfProd MP, (SELECT DISTINCT (ProdBarCode), ProdSize FROM tblGalProdMaster) GP WHERE prodId = GP.ProdBarCode AND $IT_where $IT_sort) and $IT_where $IT_sort";

				//$query = "select * from  (select ROW_NUMBER() over($IT_sort) as ROWNUM, * from mfProd $IT_where) T where  T.ROWNUM BETWEEN ($page+1) AND ($page+$scale1)";
				//	echo $query;
				if($store == "sry")
				{
					mssql_select_db(SRY_DB_NAME, $conn_sry);
					$dbraw = mssql_query($query, $conn_sry);
				}
				else if($store == "bby")
				{
					mssql_select_db(BBY_DB_NAME, $conn_bby);
					$dbraw = mssql_query($query, $conn_bby);
				}

				if($mode == "list") {
					if($row > 0) {
						$i = 0;
						while($result = mssql_fetch_array($dbraw)) {
							unset($sub_data);
							//로드된 필드
							$prodId = @iconv('euc-kr', 'utf-8', $result['prodId']) or "Detected illegal character in this data";
							$prodType = @iconv('euc-kr', 'utf-8', $result['prodType']) or "Detected illegal character in this data";
							$prodKname = @iconv('euc-kr', 'utf-8', $result['prodKname']) or "Detected illegal character in this data";
							$prodName = @iconv('euc-kr', 'utf-8', $result['prodName']) or "Detected illegal character in this data";
							$prodOUprice = @iconv('euc-kr', 'utf-8', $result['prodOUprice']) or "Detected illegal character in this data";
							$prodUnit = @iconv('euc-kr', 'utf-8', $result['prodUnit']) or "Detected illegal character in this data";
							$prodPromo = @iconv('euc-kr', 'utf-8', $result['prodPromo']) or "Detected illegal character in this data";
							$promoSdate = @iconv('euc-kr', 'utf-8', $result['promoSdate']) or "Detected illegal character in this data";
							$promoEdate = @iconv('euc-kr', 'utf-8', $result['promoEdate']) or "Detected illegal character in this data";
							$promoPrice = @iconv('euc-kr', 'utf-8', $result['promoPrice']) or "Detected illegal character in this data";
							$ProdSize = ($result['ProdSize']!=" ") ? "[".@iconv('euc-kr', 'utf-8', $result['ProdSize'])."]" : "";

							if($prodPromo && $prodPromo > '0' && $promoSdate < $today && $promoEdate > $today) {//세일 중인것 yes
								$promo_check = "yes";

								//세일 갯수 붙이기.
								if($prodPromo > 1)
								{
									$prod_ouprice = $promoPrice."/".$prodPromo;
								}
								else
								{
									$prod_ouprice = $promoPrice;
								}
								$prod_ouprice_color = "#e10000";
							} else {
								$promo_check = "";
								$prod_ouprice = $prodOUprice;
								$prod_ouprice_color = "#333333";
							}

							//카테고리 가져오기
							$plu_category = ShowPRODCategory($prodType, $language);

							//이미지 가져오기
							$image_full = GetProductImageNameLocalFreesize($prodId, $store, '90');
							$sub_data['item_onsale'] = "false";
							if($promo_check == "yes") {
								$sub_data['item_onsale'] = "true";
							}

							if($image_full) {
								$sub_data['item_image'] = $image_full;
							} else {
								$sub_data['item_image'] = ADDRESS."/img_mobile/empty.png";
							}
							$sub_data['item_prodKname']=$prodKname;

							if($promo_check == "yes") {
								if(substr($prodId, 0, 1) == "2" && strlen($prodId) == "6") {
								} else {
									$sub_data['prodOUprice'] = $prodOUprice;
									$sub_data['prodUnit'] = $prodUnit;
									$sub_data['prod_ouprice'] = $prod_ouprice;
								}
							}
							$sub_data['prodName'] = Br_wordcut($prodName, 25);
							$sub_data['plu_category'] = $plu_category;
							$sub_data['ProdSize'] = $ProdSize;

							if(DEBUG) $sub_data['tel'] = $tel;
							if(DEBUG) $sub_data['session'] = $session;
							if(DEBUG) $sub_data['store'] = $store;
							if(DEBUG) $sub_data['language'] = $language;
							if(DEBUG) $sub_data['search_mode'] = $search_mode;
							if(DEBUG) $sub_data['search_keyword'] = $search_keyword;
							if(DEBUG) $sub_data['search_index'] = $search_index;
							if(DEBUG) $sub_data['search_plucategory'] = $search_plucategory;
							if(DEBUG) $sub_data['search_onsale'] = $search_onsale;
							if(DEBUG) $sub_data['search_store'] = $search_store;
							if(DEBUG) $sub_data['boardId'] = $boardId;
							if(DEBUG) $sub_data['page'] = $page;
							if(DEBUG) $sub_data['mode'] = $mode;
							if(DEBUG) $sub_data['prodId'] = @iconv('euc-kr', 'utf-8', $result['prodId']);

							//$data['item'.$i2] = $sub_data;
							$data[$i] = $sub_data;
							$i++;
							unset($sub_data);
							//JMC_PrintJson('product_item', $sub_data);
							//$data['process'] = true;
							//$data['message'] = "Found product data";
						}
						JMC_PrintLIstJson('product', $data);
						exit();
					} else {
						$data['process'] = false;
						$data['message'] = "Not found product data";

						if(DEBUG) $data['tel'] = $tel;
						if(DEBUG) $data['session'] = $session;
						if(DEBUG) $data['store'] = $store;
						if(DEBUG) $data['language'] = $language;
						if(DEBUG) $data['search_mode'] = $search_mode;
						if(DEBUG) $data['search_keyword'] = $search_keyword;
						if(DEBUG) $data['search_index'] = $search_index;
						if(DEBUG) $data['search_plucategory'] = $search_plucategory;
						if(DEBUG) $data['search_onsale'] = $search_onsale;
						if(DEBUG) $data['search_store'] = $search_store;
						if(DEBUG) $data['boardId'] = $boardId;
						if(DEBUG) $data['page'] = $page;
						if(DEBUG) $data['mode'] = $mode;
					}
				} elseif ($mode == "page") {
					//마지막 페이지 구하기
					$last_page = $row / $scale1;
					$prev_page = $page - 1;
					$next_page = $page + 1;

					$data['last_page'] = ($last_page > (int) $last_page) ? (int) $last_page+1 : (int) $last_page;
					$data['process'] = true;
					$data['message'] = "Count page of total product data";
				}
			} else {
				$data['process'] = false;
				$data['message'] = "Session failed";
			}
		} catch(Exception $e) {
			$data['process'] = false;
			$data['message'] = $e;
		}
	} else {
		$data['process'] = false;
		$data['message'] = "Empty parameter";
	}
	JMC_PrintJson('product', $data);
?>
