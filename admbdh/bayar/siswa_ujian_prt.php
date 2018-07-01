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
require("../../inc/cek/admbdh.php");
$tpl = LoadTpl("../../template/window.html");


nocache;

//nilai
$filenya = "siswa_ujian_prt.php";
$judulku = "[$bdh_session : $nip8_session. $nm8_session] ==> $judul";
$judulku = $judul;
$judulx = $judul;
$tapelkd = nosql($_REQUEST['tapelkd']);
$smtkd = nosql($_REQUEST['smtkd']);
$nis = nosql($_REQUEST['nis']);



//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//re-direct print...
$ke = "siswa_ujian.php?tapelkd=$tapelkd&smtkd=$smtkd&nis=$nis";
$diload = "window.print();location.href='$ke'";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




//isi *START
ob_start();

//js
require("../../inc/js/swap.js");

//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form name="formx" method="post" action="'.$filenya.'">
<table width="500" border="1" cellspacing="0" cellpadding="3">
<tr valign="top">
<td valign="top" align="center">


<table width="500" border="0" cellspacing="0" cellpadding="3">
<tr valign="top">
<td valign="top" align="center">
<P>
<big>
<strong><u>BUKTI PEMBAYARAN UANG UJIAN</u></strong>
</big>
</P>
<P>
<big>
<strong><u>'.$sek_nama.'</u></strong>
</big>
</P>

<hr height="1">
</td>
</tr>
</table>
<table width="500" border="0" cellspacing="0" cellpadding="3">
<tr valign="top">
<td valign="top" width="200">
Hari, Tanggal
</td>
<td width="1">:</td>
<td>
<strong>'.$arrhari[$hari].', '.$tanggal.' '.$arrbln1[$bulan].' '.$tahun.'</strong>
</td>
</tr>

<tr valign="top">
<td valign="top" width="200">
Nomor Induk
</td>
<td width="1">:</td>
<td>
<strong>'.$nis.'</strong>
</td>
</tr>';

//cek
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
$ske_rukd = nosql($rske['kd_ruang']);

//ketahui kelas
$qkeli = mysql_query("SELECT * FROM m_kelas ".
								"WHERE kd = '$ske_kelkd'");
$rkeli = mysql_fetch_assoc($qkeli);
$keli_kelas = balikin($rkeli['kelas']);


//ketahui ruang
$qrui = mysql_query("SELECT * FROM m_ruang ".
								"WHERE kd = '$ske_rukd'");
$rrui = mysql_fetch_assoc($qrui);
$rui_ruang = balikin($rrui['ruang']);


//ketahui yang perlu dibayar...
$qnilx = mysql_query("SELECT * FROM m_uang_ujian ".
						"WHERE kd_tapel = '$tapelkd' ".
						"AND kd_smt = '$smtkd' ".
						"AND kd_kelas = '$ske_kelkd'");
$rnilx = mysql_fetch_assoc($qnilx);
$tnilx = mysql_num_rows($qnilx);
$nilx_nilai = nosql($rnilx['nilai']);


echo '<tr valign="top">
<td valign="top" width="200">
Nama Siswa
</td>
<td width="1">:</td>
<td>
<strong>'.$cc_nama.'</strong>
</td>
</tr>

<tr valign="top">
<td valign="top" width="200">
Kelas/Ruang
</td>
<td width="1">:</td>
<td>
<strong>'.$keli_kelas.'/'.$rui_ruang.'</strong>
</td>
</tr>

<tr valign="top">
<td valign="top" width="200">
Jumlah Uang Yang Dibayar
</td>
<td width="1">:</td>
<td>
<strong>'.xduit2($nilx_nilai).'</strong>
</td>
</tr>

</table>
<br>
<br>
<br>

<table width="500" border="0" cellspacing="0" cellpadding="3">
<tr valign="top">
<td valign="top" width="200" align="center">
</td>
<td valign="top" align="center">
<strong>jakarta, '.$tanggal.' '.$arrbln1[$bulan].' '.$tahun.'</strong>
<br>
<br>
<br>
<br>
<br>
(<strong>ADMINISTRATOR</strong>)
</td>
</tr>
<table>

<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
<input name="kelkd" type="hidden" value="'.$nil_kelkd.'">
<input name="smtkd" type="hidden" value="'.$smtkd.'">
<input name="swkd" type="hidden" value="'.$cc_kd.'">
<input name="nis" type="hidden" value="'.$nis.'">
</td>
</tr>
</table>


<br>
<br>

</td>
</tr>
</table>
<i>Code : '.$today3.'</i>
</form>
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