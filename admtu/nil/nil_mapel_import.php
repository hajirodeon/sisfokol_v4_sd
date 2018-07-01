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
$filenya = "nil_mapel_import.php";
$judul = "Import Nilai";
$judulku = "[$tu_session : $nip5_session.$nm5_session] ==> $judul";
$juduly = $judul;
$tapelkd = nosql($_REQUEST['tapelkd']);
$smtkd = nosql($_REQUEST['smtkd']);
$kelkd = nosql($_REQUEST['kelkd']);
$rukd = nosql($_REQUEST['rukd']);
$mapelkd = nosql($_REQUEST['mapelkd']);




//PROSES //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//fungsi baca file excel
function parseExcel($excel_file_name_with_path)
	{
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('CP1251');
	$data->read($excel_file_name_with_path);


	for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++)
		{
		for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++)
			{
			$product[$i-1][$j-1]=$data->sheets[0]['cells'][$i][$j];
			}
		}

	return $product;
	}





//batal
if ($_POST['btnBTL'])
	{
	//nilai
	$tapelkd = nosql($_POST['tapelkd']);
	$smtkd = nosql($_POST['smtkd']);
	$kelkd = nosql($_POST['kelkd']);
	$rukd = nosql($_POST['rukd']);
	$mapelkd = nosql($_POST['mapelkd']);
	$filex_namex = $_POST['filex_namex'];


	//hapus file
	$path3 = "../../filebox/excel/$filex_namex";
	chmod($path3,0777);
	unlink ($path3);

	//re-direct
	$ke = "nil_mapel.php?tapelkd=$tapelkd&smtkd=$smtkd&kelkd=$kelkd&".
		"rukd=$rukd&mapelkd=$mapelkd";
	xloc($ke);
	exit();
	}





//import sekarang
if ($_POST['btnIMx'])
	{
	//nilai
	$tapelkd = nosql($_POST['tapelkd']);
	$smtkd = nosql($_POST['smtkd']);
	$kelkd = nosql($_POST['kelkd']);
	$rukd = nosql($_POST['rukd']);
	$mapelkd = nosql($_POST['mapelkd']);
	$filex_namex = $_POST['filex_namex'];

	//nek null
	if (empty($filex_namex))
		{
		//null-kan
		xclose($koneksi);

		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diulangi...!!";
		$ke = "nil_mapel.php?tapelkd=$tapelkd&smtkd=$smtkd&kelkd=$kelkd&".
				"rukd=$rukd&mapelkd=$mapelkd&s=import";
		pekem($pesan,$ke);
		exit();
		}
	else
		{
		//deteksi .xls
		$ext_filex = substr($filex_namex, -4);

		if ($ext_filex == ".xls")
			{
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


			//nilai
			$path1 = "../../filebox/excel";

			//file-nya...
			$uploadfile = "$path1/$filex_namex";

			//require
			require_once '../../inc/class/excel/excel.php';

			$prod=parseExcel($uploadfile);
			$cprod = count($prod);

			$datax = new Spreadsheet_Excel_Reader();
			$datax->setOutputEncoding('CP1251');
			$datax->read($uploadfile);
			$jml_kolom = $datax->sheets[0]['numCols'];


			for($i=0;$i<$cprod;$i++)
				{
				$i_xyz = md5("$x$i");
				$i_no = addslashes($prod[$i][-1]);
				$i_nis = addslashes($prod[$i][0]);
				$i_nama = addslashes($prod[$i][1]);


				//ke mysql
				$qcc = mysql_query("SELECT m_siswa.*, siswa_kelas.*, siswa_kelas.kd AS skkd ".
							"FROM m_siswa, siswa_kelas ".
							"WHERE siswa_kelas.kd_siswa = m_siswa.kd ".
							"AND siswa_kelas.kd_tapel = '$tapelkd' ".
							"AND siswa_kelas.kd_kelas = '$kelkd' ".
							"AND siswa_kelas.kd_ruang = '$rukd' ".
							"AND m_siswa.nis = '$i_nis'");
				$rcc = mysql_fetch_assoc($qcc);
				$tcc = mysql_num_rows($qcc);
				$cc_skkd = nosql($rcc['skkd']);


				//masukkan data
				for ($k=2;$k<=$jml_kolom;$k++)
					{
					//kode / nama kolom
					$i_kode1 = addslashes($prod[0][$k]);
					$no_sk = substr($i_kode1,0,1);

					//jika NS
					if (substr($i_kode1,0,2) == "NS")
						{
						//nilainya...
						$no_sk = substr($i_kode1,-1);
						$i_kode2 = addslashes($prod[$i][$k]);

						//cek
						$qcc = mysql_query("SELECT * FROM siswa_nilai_kompetensi2 ".
									"WHERE kd_siswa_kelas = '$cc_skkd' ".
									"AND kd_smt = '$smtkd' ".
									"AND kd_mapel = '$mapelkd' ".
									"AND sk = '$no_sk'");
						$rcc = mysql_fetch_assoc($qcc);
						$tcc = mysql_num_rows($qcc);

						//jika ada, update
						if ($tcc != 0)
							{
							//update
							mysql_query("UPDATE siswa_nilai_kompetensi2 SET nil_ns = '$i_kode2' ".
									"WHERE kd_siswa_kelas = '$cc_skkd' ".
									"AND kd_smt = '$smtkd' ".
									"AND kd_mapel = '$mapelkd' ".
									"AND sk = '$no_sk'");
							}
						else
							{
							//insert
							mysql_query("INSERT INTO siswa_nilai_kompetensi2 (kd, kd_siswa_kelas, kd_smt, ".
									"kd_mapel, sk, nil_ns) VALUES ".
									"('$i_xyz', '$cc_skkd', '$smtkd', ".
									"'$mapelkd', '$no_sk', '$i_kode2')");
							}
						}


					//jika NK
					else if (substr($i_kode1,0,2) == "NK")
						{
						//nilainya...
						$no_sk = substr($i_kode1,-1);
						$i_kode2 = addslashes($prod[$i][$k]);

						mysql_query("UPDATE siswa_nilai_kompetensi2 SET rata_nk = '$i_kode2' ".
								"WHERE kd_siswa_kelas = '$cc_skkd' ".
								"AND kd_smt = '$smtkd' ".
								"AND kd_mapel = '$mapelkd' ".
								"AND sk = '$no_sk'");
						}


					//jika NR
					else if (substr($i_kode1,0,2) == "NR")
						{
						//nilainya
						$i_kode2 = addslashes($prod[$i][$k]);

						//cek
						$qcc1 = mysql_query("SELECT * FROM siswa_nilai_raport ".
									"WHERE kd_siswa_kelas = '$cc_skkd' ".
									"AND kd_mapel = '$mapelkd' ".
									"AND kd_smt = '$smtkd'");
						$rcc1 = mysql_fetch_assoc($qcc1);
						$tcc1 = mysql_num_rows($qcc1);

						//jika ada, update
						if($tcc1 != 0)
							{
							mysql_query("UPDATE siswa_nilai_raport SET nil_raport = '$i_kode2' ".
									"WHERE kd_siswa_kelas = '$cc_skkd' ".
									"AND kd_mapel = '$mapelkd' ".
									"AND kd_smt = '$smtkd'");
							}
						else
							{
							mysql_query("INSERT INTO siswa_nilai_raport (kd, kd_siswa_kelas, ".
									"kd_smt, kd_mapel, nil_raport) VALUES ".
									"('$i_xyz', '$cc_skkd', '$smtkd', '$mapelkd', '$i_kode2')");
							}
						}


					//jika SKD
					else
						{
						//cari tahu, kd-nya...
						$qku2 = mysql_query("SELECT * FROM m_mapel_kompetensi ".
									"WHERE kd_mapel_kelas = '$hi_mpkd' ".
									"AND kode = '$i_kode1'");
						$rowku2 = mysql_fetch_assoc($qku2);
						$totalku2 = mysql_num_rows($qku2);
						$ku2_kd = nosql($rowku2['kd']);

						//nilainya...
						$i_kode2 = addslashes($prod[$i][$k]);

						//cek
						$qcc = mysql_query("SELECT * FROM siswa_nilai_kompetensi ".
									"WHERE kd_siswa_kelas = '$cc_skkd' ".
									"AND kd_smt = '$smtkd' ".
									"AND kd_mapel_kompetensi = '$ku2_kd'");
						$rcc = mysql_fetch_assoc($qcc);
						$tcc = mysql_num_rows($qcc);

						//jika ada, update
						if ($tcc != 0)
							{
							//update
							mysql_query("UPDATE siswa_nilai_kompetensi SET nil_nkd = '$i_kode2' ".
									"WHERE kd_siswa_kelas = '$i_skkd' ".
									"AND kd_smt = '$smtkd' ".
									"AND kd_mapel_kompetensi = '$ku2_kd'");
							}
						else
							{
							mysql_query("INSERT INTO siswa_nilai_kompetensi (kd, kd_siswa_kelas, kd_smt, ".
									"kd_mapel_kompetensi, nil_nkd) VALUES ".
									"('$i_xyz', '$cc_skkd', '$smtkd', ".
									"'$ku2_kd', '$i_kode2')");
							}
						}



					//ketahui NSK /////////////////////////////////////////////////////////////////////////
					$qcc5 = mysql_query("SELECT * FROM siswa_nilai_kompetensi2 ".
								"WHERE kd_siswa_kelas = '$i_skkd' ".
								"AND kd_smt = '$smtkd' ".
								"AND kd_mapel = '$mapelkd' ".
								"AND sk = '$no_sk'");
					$rcc5 = mysql_fetch_assoc($qcc5);
					$tcc5 = mysql_num_rows($qcc5);


					//jumlah
					$qcc5x = mysql_query("SELECT SUM(siswa_nilai_kompetensi.nil_nkd) AS jml_nkd ".
								"FROM m_mapel_kompetensi, siswa_nilai_kompetensi ".
								"WHERE m_mapel_kompetensi.kd = siswa_nilai_kompetensi.kd_mapel_kompetensi ".
								"AND right(m_mapel_kompetensi.kode,2) <> '.0' ".
								"AND left(m_mapel_kompetensi.kode,1) = '$no_sk' ".
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
								"AND sk = '$no_sk'");
						}
					else
						{
						//insert
						mysql_query("INSERT INTO siswa_nilai_kompetensi2(kd, kd_siswa_kelas, kd_smt, ".
								"kd_mapel, sk, rata_nsk) VALUES ".
								"('$i_xyz', '$i_skkd', '$smtkd', ".
								"'$mapelkd', '$no_sk', '$rata_nsk')");
						}
					}
				}


			//hapus file, jika telah import
			$path1 = "../../filebox/excel/$filex_namex";
			unlink ($path1);

			//null-kan
			xclose($koneksi);

			//re-direct
			$ke = "nil_mapel.php?tapelkd=$tapelkd&smtkd=$smtkd&kelkd=$kelkd&".
					"rukd=$rukd&mapelkd=$mapelkd";
			xloc($ke);
			exit();
			}
		else
			{
			//null-kan
			xclose($koneksi);

			//salah
			$pesan = "Bukan File .xls . Harap Diperhatikan...!!";
			$ke = "$filenya?tapelkd=$tapelkd&smtkd=$smtkd&kelkd=$kelkd&".
					"rukd=$rukd&mapelkd=$mapelkd&s=import";
			pekem($pesan,$ke);
			exit();
			}
		}
	}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//isi *START
ob_start();


//js
require("../../inc/js/jumpmenu.js");
require("../../inc/js/swap.js");
require("../../inc/menu/admtu.php");
xheadline($judul);


//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form name="formx" method="post" enctype="multipart/form-data" action="'.$filenya.'">
<table bgcolor="'.$warnaover.'" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>
Tahun Pelajaran : ';

//terpilih
$qtpx = mysql_query("SELECT * FROM m_tapel ".
			"WHERE kd = '$tapelkd'");
$rowtpx = mysql_fetch_assoc($qtpx);
$tpx_kd = nosql($rowtpx['kd']);
$tpx_thn1 = nosql($rowtpx['tahun1']);
$tpx_thn2 = nosql($rowtpx['tahun2']);

echo '<strong>'.$tpx_thn1.'/'.$tpx_thn2.'</strong>,

Kelas : ';

//terpilih
$qbtx = mysql_query("SELECT * FROM m_kelas ".
			"WHERE kd = '$kelkd'");
$rowbtx = mysql_fetch_assoc($qbtx);
$btxkd = nosql($rowbtx['kd']);
$btxno = nosql($rowbtx['no']);
$btxkelas = nosql($rowbtx['kelas']);

echo '<strong>'.$btxkelas.'</strong>,


Ruang : ';
//terpilih
$qrux = mysql_query("SELECT * FROM m_ruang ".
						"WHERE kd = '$rukd'");
$rowrux = mysql_fetch_assoc($qrux);

$ruxkd = nosql($rowrux['kd']);
$ruxruang = balikin($rowrux['ruang']);

echo '<b>'.$ruxruang.'</b>,


Semester : ';
//terpilih
$qstx = mysql_query("SELECT * FROM m_smt ".
			"WHERE kd = '$smtkd'");
$rowstx = mysql_fetch_assoc($qstx);
$stx_kd = nosql($rowstx['kd']);
$stx_no = nosql($rowstx['no']);
$stx_smt = nosql($rowstx['smt']);

echo '<strong>'.$stx_smt.'</strong>
</td>
</tr>
</table>

<table bgcolor="'.$warna02.'" width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<td>
Mata Pelajaran : ';
//terpilih
$qstdx = mysql_query("SELECT * FROM m_mapel ".
			"WHERE kd = '$mapelkd'");
$rowstdx = mysql_fetch_assoc($qstdx);
$stdx_kd = nosql($rowstdx['kd']);
$stdx_pel = balikin($rowstdx['pel']);


echo '<strong>'.$stdx_pel.'</strong>
</td>
</tr>
</table>';

$filex_namex = $_REQUEST['filex_namex'];

//nilai
$path1 = "../../filebox/excel/$filex_namex";

//file-nya...
$uploadfile = $path1;


echo '<p>
Nama File Yang di-Import : <strong>'.$filex_namex.'</strong>
<br>
<input name="filex_namex" type="hidden" value="'.$filex_namex.'">
<input name="tapelkd" type="hidden" value="'.$tapelkd.'">
<input name="kelkd" type="hidden" value="'.$kelkd.'">
<input name="rukd" type="hidden" value="'.$rukd.'">
<input name="mapelkd" type="hidden" value="'.$mapelkd.'">
<input name="smtkd" type="hidden" value="'.$smtkd.'">
<input name="btnBTL" type="submit" value="<< BATAL">
<input name="btnIMx" type="submit" value="IMPORT Sekarang>>">
</p>
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
xclose($koneksi);
exit();
?>