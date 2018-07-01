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


//cek user SISWA
$qswa = mysql_query("SELECT * FROM m_user ".
			"WHERE kd = '$kd1_session' ".
			"AND tipe = 'SISWA'");
$rswa = mysql_fetch_assoc($qswa);
$tswa = mysql_num_rows($qswa);

//jika bukan
if ($tswa == 0)
	{
	//re-direct
	$pesan = "Anda Bukan Seorang Siswa. Terima Kasih.";
	$ke = "$sumber/janissari/index.php";
	pekem($pesan,$ke);
	exit();
	}
?>