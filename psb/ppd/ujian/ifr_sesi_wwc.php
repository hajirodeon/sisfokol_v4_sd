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
require("../../../inc/config.php");
require("../../../inc/fungsi.php");
$tpl = LoadTpl("../../../template/ifr_sesi_wwc.html"); 

nocache;

//nilai
$s = $_REQUEST['s'];
$wwcsesi = $_SESSION['wwc_sesi'];
$filenya = "ifr_sesi_wwc.php";
$nilai = 1; //per detik refresh

//deteksi
if ($s == "baru")
	{
	if (empty($_SESSION['wwc_sesi']))
		{
		session_register("wwc_sesi");
		$_SESSION['wwc_sesi'] = $nilai;
		}
	else
		{
		$_SESSION['wwc_sesi'] = $_SESSION['wwc_sesi'] + $nilai;
		}
	
	echo "<script>location.href='$filenya'</script>";
	}




//isi *START
ob_start();



echo 'Detik Ke-'.$wwcsesi.'
<script>
setTimeout("location.href=\''.$filenya.'?s=baru\'", '.$nilai.'000);
</script>';


//isi
$isi = ob_get_contents();
ob_end_clean();


require("../../../inc/niltpl.php");


//null-kan
exit();
?>