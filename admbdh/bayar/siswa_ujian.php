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
require("../../inc/cek/admbdh.php");
$tpl = LoadTpl("../../template/index.html");


nocache;

//nilai
$filenya = "siswa_ujian.php";
$judul = "Keuangan Siswa : Uang Ujian";
$judulku = "[$bdh_session : $nip8_session. $nm8_session] ==> $judul";
$judulx = $judul;
$s = nosql($_REQUEST['s']);
$tapelkd = nosql($_REQUEST['tapelkd']);
$smtkd = nosql($_REQUEST['smtkd']);
$nis = nosql($_REQUEST['nis']);

$ke = "$filenya?tapelkd=$tapelkd&smtkd=$smtkd&nis=$nis";





//focus...
if (empty($tapelkd))
{
$diload = "document.formx.tapel.focus();isodatetime();";
}
else if (empty($smtkd))
{
$diload = "document.formx.smt.focus();isodatetime();";
}
else if (empty($nis))
{
$diload = "document.formx.nis.focus();isodatetime();";
}
else
{
$diload = "isodatetime();document.formx.nil_bayar.focus();";
}



//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//nek batal
if ($_POST['btnBTL'])
{
//nilai
$tapelkd = nosql($_POST['tapelkd']);
$kelkd = nosql($_POST['kelkd']);
$smtkd = nosql($_POST['smtkd']);

//re-direct
$ke = "$filenya?tapelkd=$tapelkd&smtkd=$smtkd";
xloc($ke);
exit();
}





//nek ok
if ($_POST['btnOK'])
{
//nilai
$tapelkd = nosql($_POST['tapelkd']);
$smtkd = nosql($_POST['smtkd']);
$nis = nosql($_POST['nis']);

//jika null
if (empty($nis))
{
//re-direct
$pesan = "Input Tidak Lengkap. Harap Diperhatikan...!!";
$ke = "$filenya?tapelkd=$tapelkd&smtkd=$smtkd";
pekem($pesan,$ke);
exit();
}
else
{
//re-direct
$ke = "$filenya?tapelkd=$tapelkd&smtkd=$smtkd&nis=$nis";
xloc($ke);
exit();
}

}





//jika simpan
if ($_POST['btnSMP'])
{
//nilai
$tapelkd = nosql($_POST['tapelkd']);
$smtkd = nosql($_POST['smtkd']);
$kelkd = nosql($_POST['kelkd']);
$nis = nosql($_POST['nis']);
$swkd = nosql($_POST['swkd']);
$nil_bayar = nosql($_POST['nil_bayar']);


//total uang ujian
$qpkl = mysql_query("SELECT * FROM m_uang_ujian ".
						"WHERE kd_tapel = '$tapelkd' ".
						"AND kd_kelas = '$kelkd'");
$rpkl = mysql_fetch_assoc($qpkl);
$pkl_nilai = nosql($rpkl['nilai']);



//cek
if (empty($nil_bayar))
{
//re-direct
$pesan = "Input Tidak Lengkap. Harap Diperhatikan...!!";
$ke = "$filenya?tapelkd=$tapelkd&smtkd=$smtkd&nis=$nis";
pekem($pesan,$ke);
exit();
}
else
{

//cek sudah bayar
$qcc = mysql_query("SELECT * FROM siswa_uang_ujian ".
						"WHERE kd_tapel = '$tapelkd' ".
						"AND kd_smt = '$smtkd' ".
						"AND kd_kelas = '$kelkd' ".
						"AND kd_siswa = '$swkd'");
$rcc = mysql_fetch_assoc($qcc);
$tcc = mysql_num_rows($qcc);

//jika ada
if ($tcc != 0)
	{
	//re-direct
	$ke = "siswa_ujian_prt.php?tapelkd=$tapelkd&smtkd=$smtkd&nis=$nis";
	xloc($ke);
	exit();
	}
else
	{
	mysql_query("INSERT INTO siswa_uang_ujian (kd, kd_tapel, kd_smt, kd_kelas, kd_siswa, tgl_bayar, nilai, postdate) VALUES ".
					"('$x', '$tapelkd', '$smtkd', '$kelkd', '$swkd', '$today', '$nil_bayar', '$today3')");

	//re-direct
	$ke = "siswa_ujian_prt.php?tapelkd=$tapelkd&smtkd=$smtkd&nis=$nis";
	xloc($ke);
	exit();
	}
}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




//isi *START
ob_start();

//js
require("../../inc/js/jumpmenu.js");
require("../../inc/js/swap.js");
require("../../inc/js/number.js");
require("../../inc/js/jam.js");
require("../../inc/menu/admbdh.php");
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

Semester : ';
echo "<select name=\"smt\" onChange=\"MM_jumpMenu('self',this,0)\">";

//terpilih
$qstx = mysql_query("SELECT * FROM m_smt ".
						"WHERE kd = '$smtkd'");
$rowstx = mysql_fetch_assoc($qstx);
$stx_kd = nosql($rowstx['kd']);
$stx_no = nosql($rowstx['no']);
$stx_smt = nosql($rowstx['smt']);

echo '<option value="'.$stx_kd.'">'.$stx_smt.'</option>';

$qst = mysql_query("SELECT * FROM m_smt ".
						"WHERE kd <> '$smtkd' ".
						"ORDER BY smt ASC");
$rowst = mysql_fetch_assoc($qst);

do
	{
	$st_kd = nosql($rowst['kd']);
	$st_smt = nosql($rowst['smt']);

	echo '<option value="'.$filenya.'?tapelkd='.$tapelkd.'&smtkd='.$st_kd.'">'.$st_smt.'</option>';
	}
while ($rowst = mysql_fetch_assoc($qst));

echo '</select>
</td>
</tr>
</table>';


//nek blm dipilih
if (empty($tapelkd))
{
echo '<p>
<font color="#FF0000"><strong>TAHUN PELAJARAN Belum Dipilih...!</strong></font>
</p>';
}

else if (empty($smtkd))
{
echo '<p>
<font color="#FF0000"><strong>SEMESTER Belum Dipilih...!</strong></font>
</p>';
}

else
{
echo '<p>
Hari/Tanggal/Jam :
<br>
<input name="display_tgl" type="text" size="25" value="'.$arrhari[$hari].', '.$tanggal.' '.$arrbln1[$bulan].' '.$tahun.'" class="input" readonly>
<input type="text" name="display_jam" size="5" style="text-align:right" class="input" readonly>
</p>

<p>
NIS :
<br>
<input name="nis"
type="text"
size="20"
value="'.$nis.'"
onKeyDown="var keyCode = event.keyCode;
if (keyCode == 13)
	{
	document.formx.btnOK.focus();
	document.formx.btnOK.submit();
	}"
onKeyPress="return numbersonly(this, event)">
<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
<input name="smtkd" type="hidden" value="'.$smtkd.'">
<input name="btnOK" type="submit" value=">>">
</p>';

if (!empty($nis))
{
//siswa
$qcc = mysql_query("SELECT * FROM m_siswa ".
						"WHERE nis = '$nis'");
$rcc = mysql_fetch_assoc($qcc);
$tcc = mysql_num_rows($qcc);
$cc_kd = nosql($rcc['kd']);
$cc_nama = balikin($rcc['nama']);


//ketahui kelasnya...
$qske = mysql_query("SELECT * FROM siswa_kelas ".
						"WHERE kd_tapel = '$tapelkd' ".
						"AND kd_siswa = '$cc_kd'");
$rske = mysql_fetch_assoc($qske);
$tske = mysql_num_rows($qske);
$ske_kelkd = nosql($rske['kd_kelas']);


//ketahui yang perlu dibayar...
$qnilx = mysql_query("SELECT * FROM m_uang_ujian ".
						"WHERE kd_tapel = '$tapelkd' ".
						"AND kd_smt = '$smtkd' ".
						"AND kd_kelas = '$ske_kelkd'");
$rnilx = mysql_fetch_assoc($qnilx);
$tnilx = mysql_num_rows($qnilx);
$nilx_nilai = nosql($rnilx['nilai']);



//cek sudah bayar
$qcc2 = mysql_query("SELECT * FROM siswa_uang_ujian ".
						"WHERE kd_tapel = '$tapelkd' ".
						"AND kd_smt = '$smtkd' ".
						"AND kd_kelas = '$ske_kelkd' ".
						"AND kd_siswa = '$cc_kd'");
$rcc2 = mysql_fetch_assoc($qcc2);
$tcc2 = mysql_num_rows($qcc2);

//jika sudah bayar
if ($tcc2 != 0)
	{
	$cc2_status = "<font color=\"red\"><strong>SUDAH BAYAR</strong></font>.";
	}
else
	{
	$cc2_status = "<font color=\"blue\"><strong>Belum Bayar</strong></font>.";
	}



//nek ada
if ($tcc != 0)
{
echo '<p>
Nama Siswa :
<br>
<input name="nama" type="text" value="'.$cc_nama.'" size="30" class="input" readonly>
</p>

<p>
Jumlah Uang Yang Dibayar :
<br>
Rp.
<input name="nil_bayar" type="text" size="10" value="'.$nilx_nilai.'" style="text-align:right" class="input" readonly>,00
</p>

<p>
['.$cc2_status.'].
<p>

<p>
<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
<input name="kelkd" type="hidden" value="'.$ske_kelkd.'">
<input name="smtkd" type="hidden" value="'.$smtkd.'">
<input name="swkd" type="hidden" value="'.$cc_kd.'">
<input name="btnSMP" type="submit" value="SIMPAN dan CETAK">
<input name="btnBTL" type="submit" value="RESET">
</p>';
}

else
{
echo '<p>
<font color="red">
<strong>NIS : '.$nis.', Tidak Ditemukan. Harap Diperhatikan...!!</strong>
<font>
</p>';
}

}
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