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

//fungsi - fungsi
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/class/paging.php");
require("../../inc/cek/admtu.php");
$tpl = LoadTpl("../../template/index.html");


nocache;

//nilai
$filenya = "rekap_kelas.php";
$judul = "Rekap ABsensi per Kelas Ruang";
$judulku = "[$tu_session : $nip5_session.$nm5_session] ==> $judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);
$tapelkd = nosql($_REQUEST['tapelkd']);
$kelkd = nosql($_REQUEST['kelkd']);
$rukd = nosql($_REQUEST['rukd']);
$ubln = nosql($_REQUEST['ubln']);
$uthn = nosql($_REQUEST['uthn']);

$ke = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd&rukd=$rukd&ubln=$ubln&uthn=$uthn";



//cacah tapel
$qtpel = mysql_query("SELECT * FROM m_tapel ".
						"WHERE kd = '$tapelkd'");
$rtpel = mysql_fetch_assoc($qtpel);
$tpel_thn1 = nosql($rtpel['tahun1']);
$tpel_thn2 = nosql($rtpel['tahun2']);






//focus...
if (empty($tapelkd))
	{
	$diload = "document.formx.tapel.focus();";
	}
else if (empty($kelkd))
	{
	$diload = "document.formx.kelas.focus();";
	}
else if (empty($rukd))
	{
	$diload = "document.formx.ruang.focus();";
	}
else if (empty($ubln))
	{
	$diload = "document.formx.ubln.focus();";
	}
else if (empty($uthn))
	{
	$diload = "document.formx.uthn.focus();";
	}








//isi *START
ob_start();

//js
require("../../inc/js/jumpmenu.js");
require("../../inc/js/swap.js");
require("../../inc/menu/admtu.php");
xheadline($judul);

//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form name="formx" method="post" action="'.$filenya.'">
<table bgcolor="'.$warnaover.'" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>
Tahun Pelajaran : ';

echo "<select name=\"tapel\" onChange=\"MM_jumpMenu('self',this,0)\">";

//terpilih
$qtpx = mysql_query("SELECT * FROM m_tapel ".
						"WHERE kd = '$tapelkd'");
$rowtpx = mysql_fetch_assoc($qtpx);
$tpx_kd = nosql($rowtpx['kd']);
$tpx_thn1 = nosql($rowtpx['tahun1']);
$tpx_thn2 = nosql($rowtpx['tahun2']);

echo '<option value="'.$tpx_kd.'">'.$tpx_thn1.'/'.$tpx_thn2.'</option>';

$qtp = mysql_query("SELECT * FROM m_tapel ".
						"WHERE kd <> '$tapelkd' ".
						"ORDER BY tahun1 ASC");
$rowtp = mysql_fetch_assoc($qtp);

do
	{
	$tpkd = nosql($rowtp['kd']);
	$tpth1 = nosql($rowtp['tahun1']);
	$tpth2 = nosql($rowtp['tahun2']);

	echo '<option value="'.$filenya.'?tapelkd='.$tpkd.'">'.$tpth1.'/'.$tpth2.'</option>';
	}
while ($rowtp = mysql_fetch_assoc($qtp));

echo '</select>,

Kelas : ';

echo "<select name=\"kelas\" onChange=\"MM_jumpMenu('self',this,0)\">";

//terpilih
$qbtx = mysql_query("SELECT * FROM m_kelas ".
						"WHERE kd = '$kelkd'");
$rowbtx = mysql_fetch_assoc($qbtx);
$btxkd = nosql($rowbtx['kd']);
$btxkelas = nosql($rowbtx['kelas']);

echo '<option value="'.$btxkd.'">'.$btxkelas.'</option>';

$qbt = mysql_query("SELECT * FROM m_kelas ".
						"WHERE kd <> '$kelkd' ".
						"ORDER BY no ASC");
$rowbt = mysql_fetch_assoc($qbt);

do
	{
	$btkd = nosql($rowbt['kd']);
	$btkelas = nosql($rowbt['kelas']);

	echo '<option value="'.$filenya.'?tapelkd='.$tapelkd.'&kelkd='.$btkd.'">'.$btkelas.'</option>';
	}
while ($rowbt = mysql_fetch_assoc($qbt));

echo '</select>,


Ruang : ';
echo "<select name=\"ruang\" onChange=\"MM_jumpMenu('self',this,0)\">";

//terpilih
$qrugx = mysql_query("SELECT * FROM m_ruang ".
						"WHERE kd = '$rukd'");
$rowrugx = mysql_fetch_assoc($qrugx);

$rugx_kd = nosql($rowrugx['kd']);
$rugx_ru = balikin($rowrugx['ruang']);

echo '<option value="'.$rugx_kd.'">'.$rugx_ru.'</option>';

$qru = mysql_query("SELECT * FROM m_ruang ".
						"WHERE kd <> '$rukd' ".
						"ORDER BY ruang ASC");
$rowru = mysql_fetch_assoc($qru);

do
	{
	$ru_kd = nosql($rowru['kd']);
	$ru_ru = balikin($rowru['ruang']);

	echo '<option value="'.$filenya.'?tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&rukd='.$ru_kd.'">'.$ru_ru.'</option>';
	}
while ($rowru = mysql_fetch_assoc($qru));

echo '</select>
</td>
</tr>
</table>

<table bgcolor="'.$warna02.'" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>
Bulan : ';
echo "<select name=\"ublnx\" onChange=\"MM_jumpMenu('self',this,0)\">";
echo '<option value="'.$ubln.''.$uthn.'" selected>'.$arrbln[$ubln].' '.$uthn.'</option>';
for ($i=1;$i<=12;$i++)
	{
	//nilainya
	if ($i<=6) //bulan juli sampai desember
		{
		$ibln = $i + 6;

		echo '<option value="'.$filenya.'?tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&'.
			'rukd='.$rukd.'&ubln='.$ibln.'&uthn='.$tpx_thn1.'">'.$arrbln[$ibln].' '.$tpx_thn1.'</option>';
		}

	else if ($i>6) //bulan januari sampai juni
		{
		$ibln = $i - 6;

		echo '<option value="'.$filenya.'?tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&'.
			'rukd='.$rukd.'&ubln='.$ibln.'&uthn='.$tpx_thn2.'">'.$arrbln[$ibln].' '.$tpx_thn2.'</option>';
		}
	}

echo '</select>




</td>
</tr>
</table>
<br>';


//nek blm dipilih
if (empty($tapelkd))
	{
	echo '<font color="#FF0000"><strong>TAHUN PELAJARAN Belum Dipilih...!</strong></font>';
	}
else if (empty($kelkd))
	{
	echo '<font color="#FF0000"><strong>KELAS Belum Dipilih...!</strong></font>';
	}
else if (empty($rukd))
	{
	echo '<font color="#FF0000"><strong>RUANG Belum Dipilih...!</strong></font>';
	}
else if (empty($_REQUEST['ubln']))
	{
	echo '<font color="#FF0000"><strong>BULAN Belum Dipilih...!</strong></font>';
	}
else if (empty($_REQUEST['uthn']))
	{
	echo '<font color="#FF0000"><strong>TAHUN Belum Dipilih...!</strong></font>';
	}
else
	{
	$p = new Pager();
	$start = $p->findStart($limit);

	$sqlcount = "SELECT DISTINCT(siswa_kelas.kd_siswa) AS swkd ".
					"FROM siswa_absensi, siswa_kelas, m_siswa ".
					"WHERE siswa_absensi.kd_siswa_kelas = siswa_kelas.kd ".
					"AND siswa_kelas.kd_siswa = m_siswa.kd ".
					"AND siswa_kelas.kd_tapel = '$tapelkd' ".
					"AND siswa_kelas.kd_kelas = '$kelkd' ".
					"AND siswa_kelas.kd_ruang = '$rukd' ".
					"AND round(DATE_FORMAT(siswa_absensi.tgl, '%m')) = '$ubln' ".
					"AND round(DATE_FORMAT(siswa_absensi.tgl, '%Y')) = '$uthn' ".
					"AND siswa_absensi.kd_absensi <> '' ".
					"AND round(TIME_FORMAT(siswa_absensi.jam, '%H')) <> '00' ".
					"ORDER BY round(m_siswa.nis) ASC";
	$sqlresult = $sqlcount;

	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$target = $ke;
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysql_fetch_array($result);


	echo '<table width="700" border="1" cellspacing="0" cellpadding="3">
	<tr bgcolor="'.$warnaheader.'">
	<td width="100"><strong>NIS</strong></td>
	<td width="150"><strong>Nama</strong></td>
	<td></td>
	</tr>';

	if ($count != 0)
		{
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

			//nilai
			$dtf_swkd = nosql($data['swkd']);

			//siswa
			$qnixu = mysql_query("SELECT * FROM m_siswa ".
									"WHERE kd = '$dtf_swkd'");
			$rnixu = mysql_fetch_assoc($qnixu);
			$nixu_nis = nosql($rnixu['nis']);
			$nixu_nm = balikin($rnixu['nama']);


			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>'.$nixu_nis.'</td>
			<td>'.$nixu_nm.'</td>
			<td>';
			//detail
			$qnitu = mysql_query("SELECT siswa_absensi.*, siswa_kelas.*, m_siswa.*,  ".
									"round(DATE_FORMAT(siswa_absensi.tgl, '%d')) AS abs_tgl ".
									"FROM siswa_absensi, siswa_kelas, m_siswa ".
									"WHERE siswa_absensi.kd_siswa_kelas = siswa_kelas.kd ".
									"AND siswa_kelas.kd_siswa = m_siswa.kd ".
									"AND siswa_kelas.kd_tapel = '$tapelkd' ".
									"AND siswa_kelas.kd_kelas = '$kelkd' ".
									"AND siswa_kelas.kd_ruang = '$rukd' ".
									"AND siswa_kelas.kd_siswa = '$dtf_swkd' ".
									"AND round(DATE_FORMAT(siswa_absensi.tgl, '%m')) = '$ubln' ".
									"AND round(DATE_FORMAT(siswa_absensi.tgl, '%Y')) = '$uthn' ".
									"AND siswa_absensi.kd_absensi <> '' ".
									"AND round(TIME_FORMAT(siswa_absensi.jam, '%H')) <> '00'");
			$rnitu = mysql_fetch_assoc($qnitu);

			do
				{
				//nilai
				$nitu_abs_kd = nosql($rnitu['kd_absensi']);
				$nitu_abs_tgl = nosql($rnitu['abs_tgl']);
				$nitu_jam_xjam = substr($rnitu['jam'],0,2);
				$nitu_jam_xmnt = substr($rnitu['jam'],3,2);
				$nitu_perlu = balikin($rnitu['keperluan']);


				//nek empty
				if ($nitu_jam_xjam == "00")
					{
					$nitu_jam_xjam = "";

					if ($nitu_jam_xmnt == "00")
						{
						$nitu_jam_xmnt = "";
						}
					}


				//absensinya
				$qbein = mysql_query("SELECT * FROM m_absensi ".
										"WHERE kd = '$nitu_abs_kd'");
				$rbein = mysql_fetch_assoc($qbein);
				$bein_kd = nosql($rbein['kd']);
				$bein_abs = balikin($rbein['absensi']);


				echo 'Tgl. : <strong>'.$nitu_abs_tgl.'</strong>,
				Jam : <strong>'.$nitu_jam_xjam.':'.$nitu_jam_xmnt.'</strong>
				<br>
				Ket. : <strong>'.$bein_abs.'</strong>
				<br>
				Keperluan. : <strong>'.$nitu_perlu.'</strong>
				<br>
				<br>';
				}
			while ($rnitu = mysql_fetch_assoc($qnitu));

			echo '</td>
			</tr>';
			}
		while ($data = mysql_fetch_assoc($result));
		}
	echo '</table>
	<table width="700" border="0" cellspacing="0" cellpadding="3">
  	<tr>
    <td align="right">'.$pagelist.'</td>
	</tr>
	</table>';
	}

echo '</form>
<br>
<br>
<br>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//isi
$isi = ob_get_contents();
ob_end_clean();


require("../../inc/niltpl.php");


//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>