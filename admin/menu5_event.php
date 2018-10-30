<?
$start_date = date("Y-m-d", time()-(86400*7));
$end_date = date("Y-m-d", time()-86400); 

$event1_query = "SELECT TOP 5 seq, name, email ".
				"FROM new_contactUs ".
				"WHERE submit_date >= '$start_date' AND submit_date <= '$end_date' ".
				"ORDER BY NEWID()";
$event1_query_result = mssql_query($event1_query, $conn_hannam);

$event2_query = "SELECT TOP 3 seq, email ".
				"FROM new_subscribe_member ".
				"WHERE status_date >= '$start_date' AND status = 1 ".
				"ORDER BY NEWID()";
$event2_query_result = mssql_query($event2_query, $conn_hannam);
?>

<div class="content_wrapper">
	<div class="h1_wrapper stripped">
		<h1>Event</h1>
	</div>

	<table class="table_admin" cellspacing="0" cellpadding="0">
		<tr height="30px"></tr>
		<tr>
			<td>
				<img src="../img/admin/detail_dot_red.gif">
				<span class="content_link">Event - Result</span>
			</td>
		</tr>

		<tr class="bb" style="padding-bottom:10px;">
			<td>
				<table width="100%">
					<tr class="bb">
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							Event 1:
						</td>
						<td>
							<?
							while($event1_row = mssql_fetch_array($event1_query_result)) {
								echo $event1_row['seq']." - ".Br_iconv($event1_row['name'])." (".$event1_row['email'].")<br><br>";
							}
							?>
						</td>
					</tr>
					<tr>
						<td width="100px;">
							<img src="../img/admin/detail_dot_grey.gif">
							Event 2:
						</td>
						<td style="padding-top:10px;">
							<?
							while($event2_row = mssql_fetch_array($event2_query_result)) {
								echo $event2_row['seq']." - ".$event2_row['email']."<br><br>";
							}
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<div style="margin-top:30px;">
		<div style="float:left;"><input type="button" class="btn" onClick="location.href='?menu=menu5'" value="목록"></div>
	</div>
</div>