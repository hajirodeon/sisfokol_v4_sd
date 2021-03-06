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
require("../../../inc/config.php");
require("../../../inc/fungsi.php");
require("../../../inc/koneksi.php");
require("../../../inc/cek/janissari.php");
require("../../../inc/class/paging.php");
require("../../../inc/class/pagingx.php");
$tpl = LoadTpl("../../../template/janissari.html");

nocache;

//nilai
$filenya = "artikel.php";
$uskd = nosql($_REQUEST['uskd']);
$artkd = nosql($_REQUEST['artkd']);
$s = nosql($_REQUEST['s']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}

//dia...
$qtem = mysql_query("SELECT * FROM m_user ".
						"WHERE kd = '$uskd'");
$rtem = mysql_fetch_assoc($qtem);
$ttem = mysql_num_rows($qtem);
$tem_no = nosql($rtem['nomor']);
$tem_nama = balikin($rtem['nama']);
$tem_tipe = nosql($rtem['tipe']);

//jika tidak ada, kembali ke aku sendiri
if ((empty($uskd)) OR ($ttem == 0))
	{
	//re-direct
	$ke = "$sumber/janissari/k/profil/profil.php";
	xloc($ke);
	exit();
	}

//query
$qdt = mysql_query("SELECT * FROM user_blog_artikel ".
						"WHERE kd = '$artkd'");
$rdt = mysql_fetch_assoc($qdt);
$dt_judul = balikin($rdt['judul']);
$dt_rangkuman = balikin($rdt['rangkuman']);
$dt_isi = balikin($rdt['isi']);
$dt_katkd = nosql($rdt['kd_kategori']);
$dt_postdate = $rdt['postdate'];


//kat terpilih
$qkatx = mysql_query("SELECT * FROM user_blog_kategori ".
						"WHERE kd = '$dt_katkd'");
$rkatx = mysql_fetch_assoc($qkatx);
$katx_kd = nosql($rkatx['kd']);
$katx_kat = balikin($rkatx['kategori']);


//judul
$judul = $dt_judul;
$judulku = "Halaman : $tem_no.$tem_nama [$tem_tipe] --> $judul";
$juduli = $judul;




//isi *START
ob_start();



//js
require("../../../inc/menu/janissari.php");



//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<table bgcolor="#E9FFBB" width="100%" border="0" cellspacing="3" cellpadding="0">
<tr valign="top">
<td width="80%">
<p>
<font color="green"><big><strong>'.$dt_judul.'</strong></big></font>
<br>
[<em>Kategori : <strong>'.$katx_kat.'</strong></em>].
[<em>'.$dt_postdate.'</em>].
</p>

<p>
<em>'.$dt_rangkuman.'</em>
</p>

<p>
'.$dt_isi.'
</p>
<br>
<br>

<p>
<hr size="1">';

//daftar komentar ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$qdko = mysql_query("SELECT * FROM user_blog_artikel_msg ".
						"WHERE kd_user_blog_artikel = '$artkd' ".
						"ORDER BY postdate ASC");
$rdko = mysql_fetch_assoc($qdko);
$tdko = mysql_num_rows($qdko);

if ($tdko != 0)
	{
	do
		{
		//nilai
		$dko_kd = nosql($rdko['kd']);
		$dko_msg = balikin($rdko['msg']);
		$dko_dari = nosql($rdko['dari']);
		$dko_postdate = $rdko['postdate'];

		//user-nya
		$qtuse = mysql_query("SELECT m_user.*, m_user.kd AS uskd, ".
								"user_blog.* ".
								"FROM m_user, user_blog ".
								"WHERE user_blog.kd_user = m_user.kd ".
								"AND m_user.kd = '$dko_dari'");
		$rtuse = mysql_fetch_assoc($qtuse);
		$tuse_kd = nosql($rtuse['uskd']);
		$tuse_no = nosql($rtuse['nomor']);
		$tuse_nm = balikin($rtuse['nama']);
		$tuse_tipe = nosql($rtuse['tipe']);
		$tuse_foto_path = $rtuse['foto_path'];

		//nek null foto
		if (empty($tuse_foto_path))
			{
			$nilz_foto = "$sumber/img/foto_blank.jpg";
			}
		else
			{
			//gawe mini thumnail
			$nilz_foto = "$sumber/filebox/profil/$tuse_kd/thumb_$tuse_foto_path";
			}

		echo '<table width="100%" border="0" cellspacing="3" cellpadding="0">
		<tr valign="top">
		<td width="50" valign="top">
		<a href="'.$sumber.'/janissari/p/index.php?uskd='.$tuse_kd.'" title="('.$tuse_tipe.'). '.$tuse_no.'. '.$tuse_nm.'">
		<img src="'.$nilz_foto.'" align="left" alt="'.$tuse_nm.'" width="50" height="75" border="1">
		</a>
		</td>
		<td valign="top">
		<em>'.$dko_msg.'. <br>
		[Oleh : <strong><a href="'.$sumber.'/janissari/p/index.php?uskd='.$tuse_kd.'" title="('.$tuse_tipe.'). '.$tuse_no.'. '.$tuse_nm.'">('.$tuse_tipe.'). '.$tuse_no.'. '.$tuse_nm.'</a></strong>].
		['.$dko_postdate.'].</em>
		<br><br>
		</td>
		</tr>
		</table>
		<br>';
		}
	while ($rdko = mysql_fetch_assoc($qdko));
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





//simpan komentar ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($_POST['btnSMP'])
	{
	//nilai
	$uskd = nosql($_POST['uskd']);
	$artkd = nosql($_POST['artkd']);
	$bk_artikel = cegah($_POST['bk_artikel']);
	$page = nosql($_POST['page']);


	//insert
	mysql_query("INSERT INTO user_blog_artikel_msg(kd, kd_user_blog_artikel, dari, msg, postdate) VALUES ".
					"('$x', '$artkd', '$kd1_session', '$bk_artikel', '$today')");

	//re-direct
	$ke = "$filenya?uskd=$uskd&artkd=$artkd&page=$page";
	xloc($ke);
	exit();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo '</p>
<br>
<br>

<p>
Beri Komentar :
<br>
<textarea name="bk_artikel" cols="50" rows="5" wrap="virtual"></textarea>
<br>
<input name="artkd" type="hidden" value="'.$artkd.'">
<input name="uskd" type="hidden" value="'.$uskd.'">
<input name="page" type="hidden" value="'.$page.'">
<input name="btnSMP" type="submit" value="SIMPAN">
<input name="btnBTL" type="reset" value="BATAL">
</p>

<br>
<br>
<br>
<hr size="1">
<big><strong>Artikel Lainnya...</strong></big>
<br>';

//query
$a_p = new Pager();
$a_limit = 10;
$a_start = $a_p->findStart($a_limit);

$a_sqlcount = "SELECT * FROM user_blog_artikel ".
					"WHERE kd_user = '$uskd' ".
					"AND kd <> '$artkd' ".
					"ORDER BY postdate DESC";
$a_sqlresult = $a_sqlcount;

$a_count = mysql_num_rows(mysql_query($a_sqlcount));
$a_pages = $a_p->findPages($a_count, $a_limit);
$a_result = mysql_query("$a_sqlresult LIMIT ".$a_start.", ".$a_limit);
$a_target = "$filenya?uskd=$uskd&artkd=$artkd";
$a_pagelist = $a_p->pageList($_GET['page'], $a_pages, $a_target);
$a_data = mysql_fetch_array($a_result);

//jika ada
if ($a_count != 0)
	{
	echo '<table width="100%" border="0" cellpadding="3" cellspacing="0">';

	do
  		{
		$dt_kd = nosql($a_data['kd']);
		$dt_katkd = nosql($a_data['kd_kategori']);
		$dt_judul = balikin($a_data['judul']);
		$dt_postdate = $a_data['postdate'];

		//kategori
		$qkat = mysql_query("SELECT * FROM user_blog_kategori ".
								"WHERE kd = '$dt_katkd'");
		$rkat = mysql_fetch_assoc($qkat);
		$kat_kategori = balikin($rkat['kategori']);

		//jml komentar
		$qjko = mysql_query("SELECT * FROM user_blog_artikel_msg ".
								"WHERE kd_user_blog_artikel = '$dt_kd'");
		$rjko = mysql_fetch_assoc($qjko);
		$tjko = mysql_num_rows($qjko);

		echo "<tr valign=\"top\" bgcolor=\"$tk_warna\" onmouseover=\"this.bgColor='$tk_warnaover';\" onmouseout=\"this.bgColor='$tk_warna';\">";
		echo '<td valign="top">
		<UL>
		<LI>
		<strong>'.$dt_judul.'</strong>
		<br>
		[<em>Kategori : <strong>'.$kat_kategori.'</strong></em>].
		[<em>'.$dt_postdate.'</em>].
		[(<strong>'.$tjko.'</strong>) Komentar].
		[<a href="artikel.php?uskd='.$uskd.'&artkd='.$dt_kd.'&page='.$page.'" title="'.$dt_judul.'">...SELENGKAPNYA</a>]
		</LI>
		</UL>
		</td>
		</tr>';
  		}
	while ($a_data = mysql_fetch_assoc($a_result));

	echo '</table>
	'.$a_pagelist.'';
	}
else
	{
	echo '<font color="blue"><strong>BELUM ADA ARTIKEL LAINNYA.</strong></font>';
	}

echo '<br>
<br>
<br>
</td>

<td width="1%">
</td>

<td>';

//ambil sisi
require("../../../inc/menu/p_sisi.php");

echo '<br>
<br>
<br>
</td>
</tr>
</table>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../../inc/niltpl.php");



//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>