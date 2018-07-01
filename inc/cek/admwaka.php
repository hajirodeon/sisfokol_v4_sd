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


///cek session //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$kd9_session = nosql($_SESSION['kd9_session']);
$no9_session = nosql($_SESSION['no9_session']);
$nip9_session = nosql($_SESSION['nip9_session']);
$nm9_session = balikin2($_SESSION['nm9_session']);
$username9_session = nosql($_SESSION['username9_session']);
$waka_session = nosql($_SESSION['waka_session']);
$pass9_session = nosql($_SESSION['pass9_session']);
$hajirobe_session = nosql($_SESSION['hajirobe_session']);

$qbw = mysql_query("SELECT admin_waka.kd ".
			"FROM admin_waka, m_pegawai ".
			"WHERE admin_waka.kd_pegawai = m_pegawai.kd ".
			"AND m_pegawai.kd = '$kd9_session' ".
			"AND m_pegawai.usernamex = '$username9_session' ".
			"AND m_pegawai.passwordx = '$pass9_session'");
$rbw = mysql_fetch_assoc($qbw);
$tbw = mysql_num_rows($qbw);

if (($tbw == 0) OR (empty($kd9_session))
	OR (empty($username9_session))
	OR (empty($pass9_session))
	OR (empty($waka_session))
	OR (empty($hajirobe_session)))
	{
	//diskonek
	xfree($qbw);
	xclose($koneksi);

	//re-direct
	$pesan = "ANDA BELUM LOGIN. SILAHKAN LOGIN DAHULU...!!!";
	pekem($pesan, $sumber);
	exit();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>