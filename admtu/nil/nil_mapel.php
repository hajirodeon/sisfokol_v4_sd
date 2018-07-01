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
$filenya = "nil_mapel.php";
$judul = "Nilai per Mata Pelajaran";
$judulku = "[$tu_session : $nip5_session.$nm5_session] ==> $judul";
$juduly = $judul;
$tapelkd = nosql($_REQUEST['tapelkd']);
$smtkd = nosql($_REQUEST['smtkd']);
$kelkd = nosql($_REQUEST['kelkd']);
$rukd = nosql($_REQUEST['rukd']);
$mapelkd = nosql($_REQUEST['mapelkd']);
$s = nosql($_REQUEST['s']);
$page = nosql($_REQUEST['page']);

//page...
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}

$ke = "$filenya?tapelkd=$tapelkd&smtkd=$smtkd&kelkd=$kelkd&".
			"rukd=$rukd&mapelkd=$mapelkd&page=$page";





//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika reset
if ($_POST['btnRST'])
	{
	//nilai
	$tapelkd = nosql($_POST['tapelkd']);
	$smtkd = nosql($_POST['smtkd']);
	$kelkd = nosql($_POST['kelkd']);
	$rukd = nosql($_POST['rukd']);
	$mapelkd = nosql($_POST['mapelkd']);
	$page = nosql($_POST['page']);

	//page...
	if ((empty($page)) OR ($page == "0"))
		{
		$page = "1";
		}


	//query
	$p = new Pager();
	$start = $p->findStart($limit);

	$sqlcount = "SELECT m_siswa.*, m_siswa.kd AS mskd, ".
			"siswa_kelas.*, siswa_kelas.kd AS skkd ".
			"FROM m_siswa, siswa_kelas ".
			"WHERE siswa_kelas.kd_siswa = m_siswa.kd ".
			"AND siswa_kelas.kd_tapel = '$tapelkd' ".
			"AND siswa_kelas.kd_kelas = '$kelkd' ".
			"AND siswa_kelas.kd_ruang = '$rukd' ".
			"ORDER BY round(m_siswa.nis) ASC";
	$sqlresult = $sqlcount;

	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$pagelist = $p->pageList($_POST['page'], $pages, $target);
	$data = mysql_fetch_array($result);



	//kelas mata pelajaran
	$qhi = mysql_query("SELECT m_mapel_kelas.*, m_mapel_kelas.kd AS mpkd, ".
				"m_mapel.*, m_mapel.kd AS mkkd ".
				"FROM m_mapel_kelas, m_mapel ".
				"WHERE m_mapel_kelas.kd_mapel = m_mapel.kd ".
				"AND m_mapel_kelas.kd_kelas = '$kelkd' ".
				"AND m_mapel_kelas.kd_mapel = '$mapelkd'");
	$rowhi = mysql_fetch_assoc($qhi);
	$totalhi = mysql_num_rows($qhi);
	$hi_mpkd = nosql($rowhi['mpkd']);


	do
		{
		$nomerb = $nomerb + 1;
		$xyzb = md5("$x$nomerb");
		$i_kd = nosql($data['mskd']);
		$i_skkd = nosql($data['skkd']);


		//query-kategori
		$qku = mysql_query("SELECT DISTINCT(left(kode,1)) AS katkd ".
					"FROM m_mapel_kompetensi ".
					"WHERE kd_mapel_kelas = '$hi_mpkd' ".
					"AND right(kode,2) <> '.0' ".
					"ORDER BY kode ASC");
		$rowku = mysql_fetch_assoc($qku);
		$totalku = mysql_num_rows($qku);


		do
			{
			//nilai
			$nomera = $nomera + 1;
			$xyza = md5("$x$nomera");
			$ku_katkd = nosql($rowku['katkd']);


			//sub
			$qku2 = mysql_query("SELECT * FROM m_mapel_kompetensi ".
						"WHERE kd_mapel_kelas = '$hi_mpkd' ".
						"AND right(kode,2) <> '.0' ".
						"AND left(kode,1) = '$ku_katkd' ".
						"ORDER BY kode ASC");
			$rowku2 = mysql_fetch_assoc($qku2);
			$totalku2 = mysql_num_rows($qku2);


			do
				{
				//nilai
				$nomer = $nomer + 1;
				$xyz = md5("$x$nomer");
				$ku2_kd = nosql($rowku2['kd']);
				$ku2_kode = nosql($rowku2['kode']);


				//hapus
				mysql_query("DELETE FROM siswa_nilai_kompetensi ".
						"WHERE kd_siswa_kelas = '$i_skkd' ".
						"AND kd_smt = '$smtkd' ".
						"AND kd_mapel_kompetensi = '$ku2_kd'");
				}
			while ($rowku2 = mysql_fetch_assoc($qku2));



			//ketahui NS //////////////////////////////////////////////////////////////////////////////////
			//hapus
			mysql_query("DELETE FROM siswa_nilai_kompetensi2 ".
					"WHERE kd_siswa_kelas = '$i_skkd' ".
					"AND kd_smt = '$smtkd' ".
					"AND kd_mapel = '$mapelkd' ".
					"AND sk = '$ku_katkd'");
			}
		while ($rowku = mysql_fetch_assoc($qku));


		mysql_query("DELETE FROM siswa_nilai_raport ".
				"WHERE kd_siswa_kelas = '$i_skkd' ".
				"AND kd_mapel = '$mapelkd' ".
				"AND kd_smt = '$smtkd'");
		}
	while ($data = mysql_fetch_assoc($result));



	//re-direct
	$ke = "$filenya?tapelkd=$tapelkd&smtkd=$smtkd&kelkd=$kelkd&".
		"rukd=$rukd&page=$page";
	xloc($ke);
	exit();
	}





//export
if ($_POST['btnEX'])
	{
	//nilai
	$tapelkd = nosql($_POST['tapelkd']);
	$smtkd = nosql($_POST['smtkd']);
	$kelkd = nosql($_POST['kelkd']);
	$rukd = nosql($_POST['rukd']);
	$mapelkd = nosql($_POST['mapelkd']);


	//re-direct
	$ke = "nil_mapel_xls.php?tapelkd=$tapelkd&smtkd=$smtkd&kelkd=$kelkd&".
		"rukd=$rukd&mapelkd=$mapelkd";
	xloc($ke);
	exit();
	}







//ke import
if ($_POST['btnIM'])
	{
	//nilai
	$tapelkd = nosql($_POST['tapelkd']);
	$smtkd = nosql($_POST['smtkd']);
	$kelkd = nosql($_POST['kelkd']);
	$rukd = nosql($_POST['rukd']);
	$mapelkd = nosql($_POST['mapelkd']);


	//re-direct
	$ke = "$filenya?tapelkd=$tapelkd&smtkd=$smtkd&kelkd=$kelkd&".
		"rukd=$rukd&mapelkd=$mapelkd&s=import";
	xloc($ke);
	exit();
	}





//import
if ($_POST['btnIM2'])
	{
	//nilai
	$tapelkd = nosql($_POST['tapelkd']);
	$smtkd = nosql($_POST['smtkd']);
	$kelkd = nosql($_POST['kelkd']);
	$rukd = nosql($_POST['rukd']);
	$mapelkd = nosql($_POST['mapelkd']);
	$filex_namex = strip(strtolower($_FILES['filex_xls']['name']));

	//nek null
	if (empty($filex_namex))
		{
		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diulangi...!!";
		$ke = "$filenya?tapelkd=$tapelkd&smtkd=$smtkd&kelkd=$kelkd&".
				"rukd=$rukd&mapelkd=$mapelkd&s=import";
		pekem($pesan,$ke);
		}
	else
		{
		//deteksi .jpg
		$ext_filex = substr($filex_namex, -4);

		if ($ext_filex == ".xls")
			{
			//nilai
			$path1 = "../../filebox";
			$path2 = "../../filebox/excel";
			chmod($path1,0777);
			chmod($path2,0777);


			//mengkopi file
			copy($_FILES['filex_xls']['tmp_name'],"../../filebox/excel/$filex_namex");

			//chmod
                        $path3 = "../../filebox/excel/$filex_namex";
			chmod($path1,0755);
			chmod($path2,0755);
			chmod($path3,0755);

			//re-direct
			$ke = "nil_mapel_import.php?tapelkd=$tapelkd&smtkd=$smtkd&kelkd=$kelkd&".
					"rukd=$rukd&mapelkd=$mapelkd&filex_namex=$filex_namex";
			xloc($ke);
			exit();
			}
		else
			{
			//re-direct
			$pesan = "Bukan File .xls . Harap Diperhatikan...!!";
			$ke = "$filenya?tapelkd=$tapelkd&smtkd=$smtkd&kelkd=$kelkd&".
					"rukd=$rukd&mapelkd=$mapelkd&s=import";
			pekem($pesan,$ke);
			exit();
			}
		}
	}






//simpan
if ($_POST['btnSMP'])
	{
	//nilai
	$tapelkd = nosql($_POST['tapelkd']);
	$smtkd = nosql($_POST['smtkd']);
	$kelkd = nosql($_POST['kelkd']);
	$rukd = nosql($_POST['rukd']);
	$mapelkd = nosql($_POST['mapelkd']);
	$page = nosql($_POST['page']);

	//page...
	if ((empty($page)) OR ($page == "0"))
		{
		$page = "1";
		}


	//query
	$p = new Pager();
	$start = $p->findStart($limit);

	$sqlcount = "SELECT m_siswa.*, m_siswa.kd AS mskd, ".
			"siswa_kelas.*, siswa_kelas.kd AS skkd ".
			"FROM m_siswa, siswa_kelas ".
			"WHERE siswa_kelas.kd_siswa = m_siswa.kd ".
			"AND siswa_kelas.kd_tapel = '$tapelkd' ".
			"AND siswa_kelas.kd_kelas = '$kelkd' ".
			"AND siswa_kelas.kd_ruang = '$rukd' ".
			"ORDER BY round(m_siswa.nis) ASC";
	$sqlresult = $sqlcount;

	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$pagelist = $p->pageList($_POST['page'], $pages, $target);
	$data = mysql_fetch_array($result);



	//kelas
	$qhi = mysql_query("SELECT m_mapel_kelas.*, m_mapel_kelas.kd AS mpkd, ".
				"m_mapel.*, m_mapel.kd AS mkkd ".
				"FROM m_mapel_kelas, m_mapel ".
				"WHERE m_mapel_kelas.kd_mapel = m_mapel.kd ".
				"AND m_mapel_kelas.kd_kelas = '$kelkd' ".
				"AND m_mapel_kelas.kd_mapel = '$mapelkd'");
	$rowhi = mysql_fetch_assoc($qhi);
	$totalhi = mysql_num_rows($qhi);
	$hi_mpkd = nosql($rowhi['mpkd']);


	do
		{
		$nomerb = $nomerb + 1;
		$xyzb = md5("$x$nomerb");
		//$i_kd = nosql($data['mskd']);
		$i_skkd = nosql($data['skkd']);


		//query-kategori
		$qku = mysql_query("SELECT DISTINCT(left(kode,1)) AS katkd ".
					"FROM m_mapel_kompetensi ".
					"WHERE kd_mapel_kelas = '$hi_mpkd' ".
					"AND right(kode,2) <> '.0' ".
					"ORDER BY kode ASC");
		$rowku = mysql_fetch_assoc($qku);
		$totalku = mysql_num_rows($qku);


		do
			{
			//nilai
			$nomera = $nomera + 1;
			$xyza = md5("$x$nomera");
			$ku_katkd = nosql($rowku['katkd']);


			//sub
			$qku2 = mysql_query("SELECT * FROM m_mapel_kompetensi ".
						"WHERE kd_mapel_kelas = '$hi_mpkd' ".
						"AND right(kode,2) <> '.0' ".
						"AND left(kode,1) = '$ku_katkd' ".
						"ORDER BY kode ASC");
			$rowku2 = mysql_fetch_assoc($qku2);
			$totalku2 = mysql_num_rows($qku2);


			do
				{
				//nilai
				$nomer = $nomer + 1;
				$xyz = md5("$x$nomer");
				$ku2_kd = nosql($rowku2['kd']);
				$ku2_kode = nosql($rowku2['kode']);



				//ambil nilai
				$xnh = "nkd";
				$xnh1 = "$i_skkd$xnh$ku2_kd";
				$xnhxx = nosql($_POST["$xnh1"]);

				//nek se-digit
				if (strlen($xnhxx) == 1)
					{
					$xnhxx = "0$xnhxx";
					}

				//nek lebih dari 100
				if ($xnhxx > 100)
					{
					$xnhxx = "00";
					}



				//cek
				$qcc = mysql_query("SELECT * FROM siswa_nilai_kompetensi ".
							"WHERE kd_siswa_kelas = '$i_skkd' ".
							"AND kd_smt = '$smtkd' ".
							"AND kd_mapel_kompetensi = '$ku2_kd'");
				$rcc = mysql_fetch_assoc($qcc);
				$tcc = mysql_num_rows($qcc);

				//jika ada, update
				if ($tcc != 0)
					{
					//update
					mysql_query("UPDATE siswa_nilai_kompetensi SET nil_nkd = '$xnhxx' ".
							"WHERE kd_siswa_kelas = '$i_skkd' ".
							"AND kd_smt = '$smtkd' ".
							"AND kd_mapel_kompetensi = '$ku2_kd'");
					}
				else
					{
					//insert
					mysql_query("INSERT INTO siswa_nilai_kompetensi(kd, kd_siswa_kelas, kd_smt, ".
							"kd_mapel_kompetensi, nil_nkd) VALUES ".
							"('$xyz', '$i_skkd', '$smtkd', ".
							"'$ku2_kd', '$xnhxx')");
					}
				}
			while ($rowku2 = mysql_fetch_assoc($qku2));



			//ketahui NS //////////////////////////////////////////////////////////////////////////////////
			//ambil nilai
			$xnh2 = "ns";
			$xnh21 = "$i_skkd$xnh2$ku_katkd";
			$xnh2xx = nosql($_POST["$xnh21"]);


			//nek se-digit
			if (strlen($xnh2xx) == 1)
				{
				$xnh2xx = "0$xnh2xx";
				}

			//nek lebih dari 100
			if ($xnh2xx > 100)
				{
				$xnh2xx = "00";
				}


			//cek
			$qcc = mysql_query("SELECT * FROM siswa_nilai_kompetensi2 ".
						"WHERE kd_siswa_kelas = '$i_skkd' ".
						"AND kd_smt = '$smtkd' ".
						"AND kd_mapel = '$mapelkd' ".
						"AND sk = '$ku_katkd'");
			$rcc = mysql_fetch_assoc($qcc);
			$tcc = mysql_num_rows($qcc);

			//jika ada, update
			if ($tcc != 0)
				{
				//update
				mysql_query("UPDATE siswa_nilai_kompetensi2 SET nil_ns = '$xnh2xx' ".
						"WHERE kd_siswa_kelas = '$i_skkd' ".
						"AND kd_smt = '$smtkd' ".
						"AND kd_mapel = '$mapelkd' ".
						"AND sk = '$ku_katkd'");
				}
			else
				{
				//insert
				mysql_query("INSERT INTO siswa_nilai_kompetensi2(kd, kd_siswa_kelas, kd_smt, ".
						"kd_mapel, sk, nil_ns) VALUES ".
						"('$xyza', '$i_skkd', '$smtkd', ".
						"'$mapelkd', '$ku_katkd', '$xnh2xx')");
				}



			//ketahui NSK /////////////////////////////////////////////////////////////////////////
			$qcc5 = mysql_query("SELECT * FROM siswa_nilai_kompetensi2 ".
						"WHERE kd_siswa_kelas = '$i_skkd' ".
						"AND kd_smt = '$smtkd' ".
						"AND kd_mapel = '$mapelkd' ".
						"AND sk = '$ku_katkd'");
			$rcc5 = mysql_fetch_assoc($qcc5);
			$tcc5 = mysql_num_rows($qcc5);


			//jumlah
			$qcc5x = mysql_query("SELECT SUM(siswa_nilai_kompetensi.nil_nkd) AS jml_nkd ".
						"FROM m_mapel_kompetensi, siswa_nilai_kompetensi ".
						"WHERE m_mapel_kompetensi.kd = siswa_nilai_kompetensi.kd_mapel_kompetensi ".
						"AND right(m_mapel_kompetensi.kode,2) <> '.0' ".
						"AND left(m_mapel_kompetensi.kode,1) = '$ku_katkd' ".
						"AND siswa_nilai_kompetensi.kd_siswa_kelas = '$i_skkd' ".
						"AND siswa_nilai_kompetensi.kd_smt = '$smtkd'");
			$rcc5x = mysql_fetch_assoc($qcc5x);
			$tcc5x = mysql_num_rows($qcc5x);
			$cc5x_nkd = nosql($rcc5x['jml_nkd']);

			//rata2
			$rata_nsk = round($cc5x_nkd/$tcc5,2);


			//jika ada, update
			if ($tcc5 != 0)
				{
				//update
				mysql_query("UPDATE siswa_nilai_kompetensi2 SET rata_nsk = '$rata_nsk' ".
						"WHERE kd_siswa_kelas = '$i_skkd' ".
						"AND kd_smt = '$smtkd' ".
						"AND kd_mapel = '$mapelkd' ".
						"AND sk = '$ku_katkd'");
				}
			else
				{
				//insert
				mysql_query("INSERT INTO siswa_nilai_kompetensi2(kd, kd_siswa_kelas, kd_smt, ".
						"kd_mapel, sk, rata_nsk) VALUES ".
						"('$xyza', '$i_skkd', '$smtkd', ".
						"'$mapelkd', '$ku_katkd', '$rata_nsk')");
				}



			//ketahui rata NK ////////////////////////////////////////////////////////////////////
			//jika NS : null
			if (empty($xnh2xx))
				{
				$rata_nk = round(($rata_nsk * 3) / 5,2);
				}

			//jika NSK : null
			else if ((empty($tcc5)) OR (empty($cc5x_nkd)))
				{
				$rata_nk = round(($xnh2xx * 2) / 5,2);
				}

			//jika null
			else if (((empty($tcc5)) OR (empty($cc5x_nkd))) AND (empty($xnh2xx)))
				{
				$rata_nk = "0";
				}

			else
				{
				$rata_nk = round((($rata_nsk * 3) + ($xnh2xx * 2)) / 5,2);
				}


			//update
			mysql_query("UPDATE siswa_nilai_kompetensi2 SET rata_nk = '$rata_nk' ".
					"WHERE kd_siswa_kelas = '$i_skkd' ".
					"AND kd_smt = '$smtkd' ".
					"AND kd_mapel = '$mapelkd' ".
					"AND sk = '$ku_katkd'");
			}
		while ($rowku = mysql_fetch_assoc($qku));



		//rata2 raport
		$qktu = mysql_query("SELECT AVG(rata_nk) AS nr ".
					"FROM siswa_nilai_kompetensi2 ".
					"WHERE kd_siswa_kelas = '$i_skkd' ".
					"AND kd_mapel = '$mapelkd' ".
					"AND kd_smt = '$smtkd'");
		$rktu = mysql_fetch_assoc($qktu);
		$ktu_nr = round(nosql($rktu['nr']));



		//cek
		$qcc1 = mysql_query("SELECT * FROM siswa_nilai_raport ".
					"WHERE kd_siswa_kelas = '$i_skkd' ".
					"AND kd_mapel = '$mapelkd' ".
					"AND kd_smt = '$smtkd'");
		$rcc1 = mysql_fetch_assoc($qcc1);
		$tcc1 = mysql_num_rows($qcc1);

		//jika ada, update
		if($tcc1 != 0)
			{
			mysql_query("UPDATE siswa_nilai_raport SET nil_raport = '$ktu_nr' ".
					"WHERE kd_siswa_kelas = '$i_skkd' ".
					"AND kd_mapel = '$mapelkd' ".
					"AND kd_smt = '$smtkd'");
			}
		else
			{
			mysql_query("INSERT INTO siswa_nilai_raport (kd, kd_siswa_kelas, ".
					"kd_smt, kd_mapel, nil_raport) VALUES ".
					"('$xyzb', '$i_skkd', '$smtkd', '$mapelkd', '$ktu_nr')");
			}



		//rangking //////////////////////////////////////////////////////////////////////////////////////////////////////
		//total_kognitif
		$qjum = mysql_query("SELECT SUM(nil_raport) AS total_kognitif ".
					"FROM siswa_nilai_raport ".
					"WHERE kd_siswa_kelas = '$i_skkd' ".
					"AND kd_smt = '$smtkd'");
		$rjum = mysql_fetch_assoc($qjum);
		$tjum = mysql_num_rows($qjum);
		$total_kognitif = round(nosql($rjum['total_kognitif']));


		//rata_kognitif
		$qjum2 = mysql_query("SELECT AVG(nil_raport) AS rata_kognitif ".
					"FROM siswa_nilai_mapel ".
					"WHERE kd_siswa_kelas = '$i_skkd' ".
					"AND kd_smt = '$smtkd'");
		$rjum2 = mysql_fetch_assoc($qjum2);
		$tjum2 = mysql_num_rows($qjum2);
		$rata_kognitif = round(nosql($rjum2['rata_kognitif']));


		//total
		$total_nilai = round($total_kognitif);


		//cek
		$qgk = mysql_query("SELECT * FROM siswa_rangking ".
					"WHERE kd_tapel = '$tapelkd' ".
					"AND kd_kelas = '$kelkd' ".
					"AND kd_siswa_kelas = '$i_skkd' ".
					"AND kd_smt = '$smtkd'");
		$rgk = mysql_fetch_assoc($qgk);
		$tgk = mysql_num_rows($qgk);

		//jika ada
		if ($tgk != 0)
			{
			//update
			mysql_query("UPDATE siswa_rangking SET total = '$total_kognitif', ".
					"rata = '$rata_kognitif' ".
					"WHERE kd_siswa_kelas = '$i_skkd' ".
					"AND kd_smt = '$smtkd'");
			}
		else
			{
			//insert
			mysql_query("INSERT INTO siswa_rangking(kd, kd_tapel, kd_kelas, kd_ruang, ".
					"kd_siswa_kelas, kd_smt, total, rata) VALUES ".
					"('$xyz', '$tapelkd', '$kelkd', '$rukd', ".
					"'$i_skkd', '$smtkd', '$total_kognitif', '$rata_kognitif')");
			}
		}
	while ($data = mysql_fetch_assoc($result));






	//pemberian rangking............................................................................................
	$qgki = mysql_query("SELECT * FROM siswa_rangking ".
				"WHERE kd_tapel = '$tapelkd' ".
				"AND kd_kelas = '$kelkd' ".
				"AND kd_smt = '$smtkd' ".
				"ORDER BY round(total) DESC");
	$rgki = mysql_fetch_assoc($qgki);
	$tgki = mysql_num_rows($qgki);

	//nek ada
	if ($tgki != 0)
		{
		do
			{
			//nilai
			$nox = $nox + 1;
			$gki_kd = nosql($rgki['kd']);

			mysql_query("UPDATE siswa_rangking SET rangking = '$nox' ".
					"WHERE kd = '$gki_kd'");
			}
		while ($rgki = mysql_fetch_assoc($qgki));
		}
	//rangking //////////////////////////////////////////////////////////////////////////////////////////////////////




	//re-direct
	$ke = "$filenya?tapelkd=$tapelkd&smtkd=$smtkd&kelkd=$kelkd&".
		"rukd=$rukd&mapelkd=$mapelkd&page=$page";
	xloc($ke);
	exit();
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////










//focus....focus...
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
else if (empty($smtkd))
	{
	$diload = "document.formx.smt.focus();";
	}







//isi *START
ob_start();

//js
require("../../inc/js/jumpmenu.js");
require("../../inc/js/swap.js");
require("../../inc/js/checkall.js");
require("../../inc/js/number.js");
require("../../inc/menu/admtu.php");
xheadline($judul);

//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form name="formx" method="post" action="'.$filenya.'" enctype="multipart/form-data">
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
$btxno = nosql($rowbtx['no']);
$btxkelas = nosql($rowbtx['kelas']);

echo '<option value="'.$btxkd.'">'.$btxkelas.'</option>';

$qbt = mysql_query("SELECT * FROM m_kelas ".
						"WHERE kd <> '$kelkd' ".
						"ORDER BY round(no) ASC");
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
$qrux = mysql_query("SELECT * FROM m_ruang ".
						"WHERE kd = '$rukd'");
$rowrux = mysql_fetch_assoc($qrux);

$ruxkd = nosql($rowrux['kd']);
$ruxruang = balikin($rowrux['ruang']);

echo '<option value="'.$ruxkd.'">'.$ruxruang.'</option>';

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

	echo '<option value="'.$filenya.'?tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&rukd='.$rukd.'&smtkd='.$st_kd.'">'.$st_smt.'</option>';
	}
while ($rowst = mysql_fetch_assoc($qst));

echo '</select>
</td>
</tr>
</table>
<br>

Nama Mata Pelajaran : ';
echo "<select name=\"mapel\" onChange=\"MM_jumpMenu('self',this,0)\">";
//terpilih
$qstdx = mysql_query("SELECT * FROM m_mapel ".
						"WHERE kd = '$mapelkd'");
$rowstdx = mysql_fetch_assoc($qstdx);
$stdx_kd = nosql($rowstdx['kd']);
$stdx_mulo = nosql($rowstdx['mulo']);
$stdx_pel = balikin($rowstdx['pel']);

//nek mulo
if ($stdx_mulo == "true")
	{
	$mulo_ket = "Muatan Lokal --> $stdx_pel";
	}
else
	{
	$mulo_ket = $stdx_pel;
	}

echo '<option value="'.$stdx_kd.'" selected>'.$mulo_ket.'</option>';


//list
$qstd = mysql_query("SELECT m_mapel.*, m_mapel.kd AS mpkd, m_mapel_kelas.* ".
						"FROM m_mapel, m_mapel_kelas ".
						"WHERE m_mapel_kelas.kd_mapel = m_mapel.kd ".
						"AND m_mapel_kelas.kd_kelas = '$kelkd' ".
						"AND m_mapel.kd <> '$mapelkd' ".
						"ORDER BY round(m_mapel.no) ASC, ".
						"round(m_mapel.no_sub) ASC");
$rowstd = mysql_fetch_assoc($qstd);

do
	{
	$std_kd = nosql($rowstd['mpkd']);
	$std_mulo = nosql($rowstd['mulo']);
	$std_pel = balikin($rowstd['pel']);

	//nek mulo
	if ($std_mulo == "true")
		{
		$mulo_ket = "Muatan Lokal --> $std_pel";
		}
	else
		{
		$mulo_ket = $std_pel;
		}

	echo '<option value="'.$filenya.'?tapelkd='.$tapelkd.'&kelkd='.$kelkd.'&rukd='.$rukd.'&smtkd='.$smtkd.'&mapelkd='.$std_kd.'">'.$mulo_ket.'</option>';
	}
while ($rowstd = mysql_fetch_assoc($qstd));

echo '</select>
<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
<input name="rukd" type="hidden" value="'.$rukd.'">
<input name="kelkd" type="hidden" value="'.$kelkd.'">
<input name="smtkd" type="hidden" value="'.$smtkd.'">
<input name="mapelkd" type="hidden" value="'.$mapelkd.'">
<br>';

//nek drg
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

else if (empty($smtkd))
	{
	echo '<font color="#FF0000"><strong>SEMESTER Belum Dipilih...!</strong></font>';
	}

else if (empty($mapelkd))
	{
	echo '<font color="#FF0000"><strong>MATA PELAJARAN Belum Dipilih...!</strong></font>';
	}
else
	{
	//jika import
	if ($s == "import")
		{
		echo '<p>
		Silahkan Masukkan File yang akan Di-Import :
		<br>
		<input name="filex_xls" type="file" size="30">
		<br>
		<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
		<input name="kelkd" type="hidden" value="'.$kelkd.'">
		<input name="rukd" type="hidden" value="'.$rukd.'">
		<input name="mapelkd" type="hidden" value="'.$mapelkd.'">
		<input name="smtkd" type="hidden" value="'.$smtkd.'">
		<input name="s" type="hidden" value="'.$s.'">
		<input name="btnBTL" type="submit" value="BATAL">
		<input name="btnIM2" type="submit" value="IMPORT >>">
		</p>';
		}
	else
		{
		//query
		$p = new Pager();
		$start = $p->findStart($limit);

		$sqlcount = "SELECT m_siswa.*, m_siswa.kd AS mskd, ".
				"siswa_kelas.*, siswa_kelas.kd AS skkd ".
				"FROM m_siswa, siswa_kelas ".
				"WHERE siswa_kelas.kd_siswa = m_siswa.kd ".
				"AND siswa_kelas.kd_tapel = '$tapelkd' ".
				"AND siswa_kelas.kd_kelas = '$kelkd' ".
				"AND siswa_kelas.kd_ruang = '$rukd' ".
				"ORDER BY round(m_siswa.nis) ASC";
		$sqlresult = $sqlcount;

		$count = mysql_num_rows(mysql_query($sqlcount));
		$pages = $p->findPages($count, $limit);
		$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
		$target = "$filenya?tapelkd=$tapelkd&kelkd=$kelkd&rukd=$rukd&smtkd=$smtkd";
		$pagelist = $p->pageList($_GET['page'], $pages, $target);
		$data = mysql_fetch_array($result);


		//kelas mapel
		$qhi = mysql_query("SELECT m_mapel_kelas.*, m_mapel_kelas.kd AS mpkd, ".
					"m_mapel.*, m_mapel.kd AS mkkd ".
					"FROM m_mapel_kelas, m_mapel ".
					"WHERE m_mapel_kelas.kd_mapel = m_mapel.kd ".
					"AND m_mapel_kelas.kd_kelas = '$kelkd' ".
					"AND m_mapel_kelas.kd_mapel = '$mapelkd'");
		$rowhi = mysql_fetch_assoc($qhi);
		$totalhi = mysql_num_rows($qhi);
		$hi_mpkd = nosql($rowhi['mpkd']);


		//jumlah kompetensi
		$qku4xu = mysql_query("SELECT * FROM m_mapel_kompetensi ".
					"WHERE kd_mapel_kelas = '$hi_mpkd' ".
					"AND right(kode,2) <> '.0'");
		$rowku4xu = mysql_fetch_assoc($qku4xu);
		$totalku4xu = mysql_num_rows($qku4xu);



		//nek ada
		if ($count != 0)
			{
			//kelas mapel
			$qhi = mysql_query("SELECT m_mapel_kelas.*, m_mapel_kelas.kd AS mpkd, ".
						"m_mapel.*, m_mapel.kd AS mkkd ".
						"FROM m_mapel_kelas, m_mapel ".
						"WHERE m_mapel_kelas.kd_mapel = m_mapel.kd ".
						"AND m_mapel_kelas.kd_kelas = '$kelkd' ".
						"AND m_mapel_kelas.kd_mapel = '$mapelkd'");
			$rowhi = mysql_fetch_assoc($qhi);
			$totalhi = mysql_num_rows($qhi);
			$hi_mpkd = nosql($rowhi['mpkd']);


			echo '<input name="btnIM" type="submit" value="IMPORT">
			<input name="btnEX" type="submit" value="EXPORT">
			<table border="1" cellpadding="3" cellspacing="0">
			<tr bgcolor="'.$warnaheader.'">
			<td width="50" ROWSPAN="2"><strong>NIS</strong></td>
			<td width="150" ROWSPAN="2"><strong>Nama</strong></td>';

			//query-kategori
			$qku = mysql_query("SELECT DISTINCT(left(kode,1)) AS katkd ".
						"FROM m_mapel_kompetensi ".
						"WHERE kd_mapel_kelas = '$hi_mpkd' ".
						"AND right(kode,2) <> '.0' ".
						"ORDER BY kode ASC");
			$rowku = mysql_fetch_assoc($qku);
			$totalku = mysql_num_rows($qku);


			do
				{
				//nilai
				$ku_katkd= nosql($rowku['katkd']);


				//sub
				$qku2 = mysql_query("SELECT * FROM m_mapel_kompetensi ".
							"WHERE kd_mapel_kelas = '$hi_mpkd' ".
							"AND right(kode,2) <> '.0' ".
							"AND left(kode,1) = '$ku_katkd' ".
							"ORDER BY kode ASC");
				$rowku2 = mysql_fetch_assoc($qku2);
				$totalku2 = mysql_num_rows($qku2);
				$jml_kolom = $totalku2 + 2;


				echo '<td width="50" COLSPAN="'.$jml_kolom.'" align="center"><strong>Standar Kompetensi '.$ku_katkd.'</strong></td>';
				}
			while ($rowku = mysql_fetch_assoc($qku));

			echo '<td width="10" align="center" ROWSPAN="2"><strong>NR</strong></td>
			</tr>
			<tr bgcolor="'.$warnaheader.'">';


			//query-kategori
			$qku = mysql_query("SELECT DISTINCT(left(kode,1)) AS katkd ".
						"FROM m_mapel_kompetensi ".
						"WHERE kd_mapel_kelas = '$hi_mpkd' ".
						"AND right(kode,2) <> '.0' ".
						"ORDER BY kode ASC");
			$rowku = mysql_fetch_assoc($qku);
			$totalku = mysql_num_rows($qku);


			do
				{
				//nilai
				$ku_katkd= nosql($rowku['katkd']);


				//sub
				$qku2 = mysql_query("SELECT * FROM m_mapel_kompetensi ".
							"WHERE kd_mapel_kelas = '$hi_mpkd' ".
							"AND right(kode,2) <> '.0' ".
							"AND left(kode,1) = '$ku_katkd' ".
							"ORDER BY kode ASC");
				$rowku2 = mysql_fetch_assoc($qku2);
				$totalku2 = mysql_num_rows($qku2);


				do
					{
					//nilai
					$ku2_kode = nosql($rowku2['kode']);

					echo '<td align="center"><strong>NKD <br> '.$ku2_kode.'</strong></td>';
					}
				while ($rowku2 = mysql_fetch_assoc($qku2));

				echo '<td width="10" align="center"><strong>NS'.$ku_katkd.'</strong></td>
				<td width="10" align="center"><strong>NK'.$ku_katkd.'</strong></td>';
				}
			while ($rowku = mysql_fetch_assoc($qku));


			echo '</tr>';

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

				$nomer = $nomer + 1;
				$i_kd = nosql($data['mskd']);
				$i_skkd = nosql($data['skkd']);
				$i_nis = nosql($data['nis']);
				$i_nama = balikin($data['nama']);


				echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
				echo '<td valign="top">
				'.$i_nis.'
				</td>
				<td valign="top">
				'.$i_nama.'
				</td>';


				//query-kategori
				$qku = mysql_query("SELECT DISTINCT(left(kode,1)) AS katkd ".
							"FROM m_mapel_kompetensi ".
							"WHERE kd_mapel_kelas = '$hi_mpkd' ".
							"AND right(kode,2) <> '.0' ".
							"ORDER BY kode ASC");
				$rowku = mysql_fetch_assoc($qku);
				$totalku = mysql_num_rows($qku);


				do
					{
					//nilai
					$nomerku = $nomerku + 2;
					$ku_katkd = nosql($rowku['katkd']);


					//sub
					$qku2 = mysql_query("SELECT * FROM m_mapel_kompetensi ".
								"WHERE kd_mapel_kelas = '$hi_mpkd' ".
								"AND right(kode,2) <> '.0' ".
								"AND left(kode,1) = '$ku_katkd' ".
								"ORDER BY kode ASC");
					$rowku2 = mysql_fetch_assoc($qku2);
					$totalku2 = mysql_num_rows($qku2);


					do
						{
						//nilai
						$ku2_kd = nosql($rowku2['kd']);
						$ku2_kode = nosql($rowku2['kode']);


						//nilainya
						$qdtu = mysql_query("SELECT * FROM siswa_nilai_kompetensi ".
									"WHERE kd_siswa_kelas = '$i_skkd' ".
									"AND kd_smt = '$smtkd' ".
									"AND kd_mapel_kompetensi = '$ku2_kd'");
						$rdtu = mysql_fetch_assoc($qdtu);
						$tdtu = mysql_num_rows($qdtu);
						$dtu_nkd = nosql($rdtu['nil_nkd']);

						echo '<td>
						<input name="'.$i_skkd.'nkd'.$ku2_kd.'" type="text" value="'.$dtu_nkd.'" size="3" maxlength="5">
						</td>';
						}
					while ($rowku2 = mysql_fetch_assoc($qku2));



					//nilainya
					$qdtu2 = mysql_query("SELECT * FROM siswa_nilai_kompetensi2 ".
								"WHERE kd_siswa_kelas = '$i_skkd' ".
								"AND kd_smt = '$smtkd' ".
								"AND kd_mapel = '$mapelkd' ".
								"AND sk = '$ku_katkd'");
					$rdtu2 = mysql_fetch_assoc($qdtu2);
					$tdtu2 = mysql_num_rows($qdtu2);
					$dtu2_ns = nosql($rdtu2['nil_ns']);
					$dtu2_nk = nosql($rdtu2['rata_nk']);


					echo '<td>
					<input name="'.$i_skkd.'ns'.$ku_katkd.'" type="text" value="'.$dtu2_ns.'" size="3" maxlength="5">
					</td>
					<td>
					<input name="'.$i_skkd.'nk'.$ku_katkd.'" type="text" value="'.$dtu2_nk.'" size="3" maxlength="5" class="input" readonly>
					</td>';
					}
				while ($rowku = mysql_fetch_assoc($qku));


				//nilainya
				$qdtu21 = mysql_query("SELECT * FROM siswa_nilai_raport ".
							"WHERE kd_siswa_kelas = '$i_skkd' ".
							"AND kd_mapel = '$mapelkd' ".
							"AND kd_smt = '$smtkd'");
				$rdtu21 = mysql_fetch_assoc($qdtu21);
				$tdtu21 = mysql_num_rows($qdtu21);
				$dtu21_raport = nosql($rdtu21['nil_raport']);

				echo '<td width="10">
				<input name="'.$i_skkd.'raport" type="text" value="'.$dtu21_raport.'" size="2" maxlength="2" class="input" readonly>
				</td>
				</tr>';
				}
			while ($data = mysql_fetch_assoc($result));

			echo '</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			<td width="250">
			<input name="btnSMP" type="submit" value="SIMPAN">
			<input name="btnRST" type="submit" value="RESET">
			<input name="jml" type="hidden" value="'.$limit.'">
			<input name="s" type="hidden" value="'.$s.'">
			<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
			<input name="kelkd" type="hidden" value="'.$kelkd.'">
			<input name="rukd" type="hidden" value="'.$rukd.'">
			<input name="smtkd" type="hidden" value="'.$smtkd.'">
			<input name="mapelkd" type="hidden" value="'.$mapelkd.'">
			<input name="page" type="hidden" value="'.$page.'">
			<input name="total" type="hidden" value="'.$count.'">
			<font color="#FF0000"><strong>'.$count.'</strong></font> Data.
			</tr>
			</table>';
			}
		else
			{
			echo '<p>
			<font color="red">
			<strong>TIDAK ADA DATA.</strong>
			</font>
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