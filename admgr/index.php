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

//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
require("../inc/cek/admgr.php");
$tpl = LoadTpl("../template/index.html");

nocache;

//nilai
$filenya = "index.php";
$judul = "Daftar Mata Pelajaran";
$judulku = "[$guru_session : $nip1_session.$nm1_session] ==> $judul";
$juduli = $judul;
$tapelkd = nosql($_REQUEST['tapelkd']);





//isi *START
ob_start();

//js
require("../inc/js/swap.js");
require("../inc/js/jumpmenu.js");
require("../inc/menu/admgr.php");
xheadline($judul);

//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<table bgcolor="'.$warnaover.'" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>
Tahun Pelajaran : ';
echo "<select name=\"tapel\" onChange=\"MM_jumpMenu('self',this,0)\">";

//terpilih
$qtpx = mysql_query("SELECT * FROM m_tapel ".
						"WHERE kd = '$tapelkd'");
$rowtpx = mysql_fetch_assoc($qtpx);

echo '<option value="'.nosql($rowtpx['kd']).'">'.nosql($rowtpx['tahun1']).'/'.nosql($rowtpx['tahun2']).'</option>';

$qtp = mysql_query("SELECT * FROM m_tapel ".
			"WHERE kd <> '$tapelkd' ".
			"ORDER BY tahun1 DESC");
$rowtp = mysql_fetch_assoc($qtp);

do
	{
	$tpkd = nosql($rowtp['kd']);
	$tpth1 = nosql($rowtp['tahun1']);
	$tpth2 = nosql($rowtp['tahun2']);

	echo '<option value="'.$filenya.'?tapelkd='.$tpkd.'">'.$tpth1.'/'.$tpth2.'</option>';
	}
while ($rowtp = mysql_fetch_assoc($qtp));

echo '</select>
</td>
</tr>
</table>';


//nek null
if (empty($tapelkd))
	{
	echo '<p>
	<font color="red">
	<strong>TAHUN PELAJARAN Belum Ditentukan...!!</strong>
	</font>
	</p>';
	}

else
	{
	//data ne
	$qdty = mysql_query("SELECT m_pegawai.*, m_guru.*, m_guru_mapel.*, m_guru_mapel.kd AS mgkd, ".
				"m_mapel.*, m_mapel.kd AS mpkd, ".
				"m_ruang.*, m_ruang.kd AS mrkd, ".
				"m_tapel.*, m_kelas.* ".
				"FROM m_pegawai, m_guru, m_guru_mapel, m_mapel, ".
				"m_ruang, m_tapel, m_kelas ".
				"WHERE m_guru_mapel.kd_mapel = m_mapel.kd ".
				"AND m_guru_mapel.kd_ruang = m_ruang.kd ".
				"AND m_guru_mapel.kd_guru = m_guru.kd ".
				"AND m_guru.kd_pegawai = m_pegawai.kd ".
				"AND m_guru.kd_tapel = m_tapel.kd ".
				"AND m_guru.kd_kelas = m_kelas.kd ".
				"AND m_pegawai.kd = '$kd1_session' ".
				"AND m_guru.kd_tapel = '$tapelkd' ".
				"ORDER BY m_kelas.kelas ASC, ".
				"m_ruang.ruang ASC");
	$rdty = mysql_fetch_assoc($qdty);
	$tdty = mysql_num_rows($qdty);


	echo '<p>
	<table width="900" border="1" cellspacing="0" cellpadding="3">
	<tr bgcolor="'.$warnaheader.'">
	<td width="50"><strong>Tahun Pelajaran</strong></td>
	<td width="50"><strong>Kelas</strong></td>
	<td width="50"><strong>Ruang</strong></td>
	<td width="300"><strong>Mata Pelajaran</strong></td>
	<td width="50"><strong>KKM</strong></td>
	<td width="50"><strong>Nilai</strong></td>
	<td width="50"><strong>Absensi</strong></td>
	</tr>';

	//nek gak null
	if ($tdty != 0)
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
			$dty_gurkd = nosql($rdty['mgkd']);
			$dty_kelkd = nosql($rdty['kd_kelas']);
			$dty_tapelkd = nosql($rdty['kd_tapel']);
			$dty_rukd = nosql($rdty['kd_ruang']);
			$dty_pelkd = nosql($rdty['kd_mapel']);
			$dty_ruang = balikin($rdty['ruang']);
			$dty_pel = balikin($rdty['pel']);

			//tapel
			$qytapel = mysql_query("SELECT * FROM m_tapel ".
									"WHERE kd = '$dty_tapelkd'");
			$rytapel = mysql_fetch_assoc($qytapel);
			$ytapel_thn1 = nosql($rytapel['tahun1']);
			$ytapel_thn2 = nosql($rytapel['tahun2']);


			//kelas
			$qykel = mysql_query("SELECT * FROM m_kelas ".
									"WHERE kd = '$dty_kelkd'");
			$rykel = mysql_fetch_assoc($qykel);
			$ykel_kelas = nosql($rykel['kelas']);




			//KKM-nya
			$qdt = mysql_query("SELECT * FROM m_mapel_kelas ".
						"WHERE kd_kelas = '$dty_kelkd' ".
						"AND kd_mapel = '$dty_pelkd'");
			$rdt = mysql_fetch_assoc($qdt);
			$dt_kkm = nosql($rdt['kkm']);




			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>'.$ytapel_thn1.'/'.$ytapel_thn2.'</td>
			<td>'.$ykel_kelas.'</td>
			<td>'.$dty_ruang.'</td>
			<td>'.$dty_pel.'</td>
			<td>
			<a href="ajar/kkm.php?tapelkd='.$dty_tapelkd.'&kelkd='.$dty_kelkd.'&rukd='.$dty_rukd.'&mapelkd='.$dty_pelkd.'"
			title="Kelas = '.$ykel_kelas.', Ruang = '.$dty_ruang.', Pelajaran = '.$dty_pel.'">
			<img src="'.$sumber.'/img/edit.gif" width="16" height="16" border="0"></a> ['.$dt_kkm.'].
			</td>
			<td>
			<a href="ajar/nil_raport.php?tapelkd='.$dty_tapelkd.'&kelkd='.$dty_kelkd.'&rukd='.$dty_rukd.'&mapelkd='.$dty_pelkd.'"
			title="Kelas = '.$ykel_kelas.', Ruang = '.$dty_ruang.', Pelajaran = '.$dty_pel.'">
			<img src="'.$sumber.'/img/edit.gif" width="16" height="16" border="0"></a>
			</td>
			<td>
			<a href="ajar/absensi.php?tapelkd='.$dty_tapelkd.'&kelkd='.$dty_kelkd.'&rukd='.$dty_rukd.'&mapelkd='.$dty_pelkd.'"
			title="Kelas = '.$ykel_kelas.', Ruang = '.$dty_ruang.', Pelajaran = '.$dty_pel.'">
			<img src="'.$sumber.'/img/edit.gif" width="16" height="16" border="0"></a>
			</td>
			</tr>';
			}
		while ($rdty = mysql_fetch_assoc($qdty));
		}

	echo '</table>
	</p>';
	}


echo '<br><br><br>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();

require("../inc/niltpl.php");



//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>