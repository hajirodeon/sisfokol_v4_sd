<?php
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
/////// SISFOKOL_SD_v4.0_(NyurungBAN)                           ///////
/////// (Sistem Informasi Sekolah untuk SD)                     ///////
///////////////////////////////////////////////////////////////////////
/////// Dibuat oleh :                                           ///////
/////// Agus Muhajir, S.Kom                                     ///////
/////// URL 	:                                               ///////
///////     * http://sisfokol.wordpress.com/                    ///////
///////     * http://hajirodeon.wordpress.com/                  ///////
///////     * http://yahoogroup.com/groups/sisfokol/            ///////
///////     * http://yahoogroup.com/groups/linuxbiasawae/       ///////
/////// E-Mail	:                                               ///////
///////     * hajirodeon@yahoo.com                              ///////
///////     * hajirodeon@gmail.com                              ///////
/////// HP/SMS	: 081-829-88-54                                 ///////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////


 






session_start();

require("../../../inc/config.php"); 
require("../../../inc/fungsi.php"); 
require("../../../inc/koneksi.php"); 
require("../../../inc/class/paging.php");
require("../../../inc/cek/psb_adm.php"); 
$tpl = LoadTpl("../../../template/index.html"); 

nocache;

//nilai
$filenya = "diterima.php";
$judul = "Calon Yang Diterima";
$judulku = "[$adm_session] ==> $judul";
$judulx = $judul;
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}



//isi *START
ob_start();



//pemberian nomor urut rangking
$qpnu = mysql_query("SELECT * FROM psb_siswa_calon_rangking ".
						"ORDER BY round(total_rata) DESC");
$rpnu = mysql_fetch_assoc($qpnu);
$tpnu = mysql_num_rows($qpnu);

do
	{
	//nilai
	$nomex= $nomex + 1;
	$pnu_kd = nosql($rpnu['kd']);
	
	//update
	mysql_query("UPDATE psb_siswa_calon_rangking SET no = '$nomex' ".
					"WHERE kd = '$pnu_kd'");	
	}
while ($rpnu = mysql_fetch_assoc($qpnu));





//js
require("../../../inc/js/jumpmenu.js");
require("../../../inc/js/swap.js"); 
require("../../../inc/menu/psb_adm.php"); 
xheadline($judul);

//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form action="'.$filenya.'" method="post" name="formx">';

//terpilih
$qtpx = mysql_query("SELECT * FROM psb_m_kelas");
$rowtpx = mysql_fetch_assoc($qtpx);
$tpx_kd = nosql($rowtpx['kd']);
$tpx_kea = balikin($rowtpx['kelas']);
$tpx_tampung = nosql($rowtpx['daya_tampung']);


//query data
$p = new Pager();
$start = $p->findStart($limit);
	
$sqlcount = "SELECT psb_siswa_calon.*, psb_siswa_calon.kd AS sckd, ".
				"psb_siswa_calon.nama AS scnama, psb_siswa_calon_rangking.* ".
				"FROM psb_siswa_calon, psb_siswa_calon_rangking ".
				"WHERE psb_siswa_calon.kd = psb_siswa_calon_rangking.kd_siswa_calon ".
				"AND psb_siswa_calon_rangking.nil_mapel <> '0' ".
				"AND psb_siswa_calon_rangking.nil_wwc <> '0' ".
				"AND psb_siswa_calon_rangking.nil_un <> '0' ".
				"AND psb_siswa_calon_rangking.nil_prestasi <> '0' ".
				"AND psb_siswa_calon.status_daftar = 'true' ".
				"AND round(psb_siswa_calon_rangking.total_rata) > '$skor_min' ".
				"ORDER BY round(psb_siswa_calon_rangking.no) ASC";
$sqlresult = $sqlcount;
				
$count = mysql_num_rows(mysql_query($sqlcount));
$pages = $p->findPages($count, $limit);
$limit = $tpx_tampung; //jml. data page maksimal sesuai dengan daya tampung yang ada
$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
$pagelist = $p->pageList($_GET['page'], $pages, $target);
$data = mysql_fetch_array($result);

if ($count != 0)
	{
	echo '[Daya Tampung : <strong>'.$tpx_tampung.'</strong>].
	<table width="900" border="1" cellspacing="0" cellpadding="3">
	<tr align="center" bgcolor="'.$warnaheader.'">
	<td width="1"><strong><font color="'.$warnatext.'">No.</font></strong></td>
	<td width="50"><strong><font color="'.$warnatext.'">No.Daftar</font></strong></td>
	<td><strong><font color="'.$warnatext.'">Nama</font></strong></td>
	<td width="100"><strong><font color="'.$warnatext.'">Skor Ujian Mapel</font></strong></td>
	<td width="100"><strong><font color="'.$warnatext.'">Skor Wawancara</font></strong></td>
	<td width="100"><strong><font color="'.$warnatext.'">Skor Nilai UN</font></strong></td>
	<td width="100"><strong><font color="'.$warnatext.'">Skor Nilai Prestasi</font></strong></td>
	<td width="100"><strong><font color="'.$warnatext.'">Skor Nilai US</font></strong></td>
	<td width="50"><strong><font color="'.$warnatext.'">Total Rata-Rata</font></strong></td>
	</tr>';
	
	do 
		{ 
		if ($warna_set ==0)
			{
			$warna = $warna01;
			$warna_set = 1;	
			}
		else
			{
			$warna = $warna02;
			$warna_set = 0;
			}
			
		$d_kd = nosql($data['sckd']);
		$d_no = nosql($data['no']);
		$d_noreg = nosql($data['no_daftar']);
		$d_nama = balikin($data['scnama']);
			
		$d_nil_mapel = nosql($data['nil_mapel']);
		$d_nil_wwc = nosql($data['nil_wwc']);
		$d_nil_un = nosql($data['nil_un']);
		$d_nil_prestasi = nosql($data['nil_prestasi']);
		$d_nil_us = nosql($data['nil_us']);
		$d_total_rata = nosql($data['total_rata']);
			
			
			
			
			
		//set diterima //////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//netralkan dulu
		mysql_query("UPDATE psb_siswa_calon SET status_diterima = 'false' ".
						"WHERE kd = '$d_kd'");
		
		//diterima
		mysql_query("UPDATE psb_siswa_calon SET status_diterima = 'true' ".
						"WHERE kd = '$d_kd'");
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			
		
		echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
		echo '<td>'.$d_no.'.</td>
		<td>'.$d_noreg.'</td>
			
		<td>'.$d_nama.'</td>

		<td>'.$d_nil_mapel.'</td>
		
		<td>'.$d_nil_wwc.'</td>
	
		<td>'.$d_nil_un.'</td>
	
		<td>'.$d_nil_prestasi.'</td>
		
		<td>'.$d_nil_us.'</td>
	
		<td>'.$d_total_rata.'</td>
			
        </tr>';				
		} 
	while ($data = mysql_fetch_assoc($result)); 
	
	echo '</table>
	<table width="900" border="0" cellspacing="0" cellpadding="3">
	<tr> 
	<td align="right">
	[<a href="diterima_prt.php" title="Print Laporan Calon Yang Diterima"><img src="'.$sumber.'/img/print.gif" width="16" height="16" border="0"></a>] 
	'.$pagelist.' <strong><font color="#FF0000">'.$count.'</font></strong> Data.</td>
	</tr>
	</table>';
	}
else
	{
	echo '<font color="red"><strong>TIDAK ADA DATA CALON YANG DITERIMA</strong></font>';
	}

echo '</form>
<br>
<br>
<br>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../../inc/niltpl.php");



//diskonek
xclose($koneksi);
exit();
?>