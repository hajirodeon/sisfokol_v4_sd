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


//nilai
$maine = "$sumber/admbk/index.php";


//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<table bgcolor="#E4D6CC" width="100%" border="0" cellspacing="0" cellpadding="5">
<tr>
<td>';
//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





//home //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<a href="'.$maine.'" title="Home" class="menuku"><strong>Home</strong></a>&nbsp;&nbsp; | ';
//home //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





//setting ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<A href="#" class="menuku" data-flexmenu="flexmenu1"><strong>SETTING</strong></A>&nbsp;&nbsp; |
<UL id="flexmenu1" class="flexdropdownmenu">
<LI>
<a href="'.$sumber.'/admbk/s/pass.php" title="Ganti Password">Ganti Password</a>
</LI>
</UL>';
//setting ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////








//data siswa ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<A href="'.$sumber.'/admbk/d/siswa.php" class="menuku"><strong>Data Siswa</strong>&nbsp;&nbsp;</A> | ';
//data siswa ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////






//absensi ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<A href="#" data-flexmenu="flexmenu2" class="menuku"><strong>ABSENSI</strong>&nbsp;&nbsp;</A> |
<UL id="flexmenu2" class="flexdropdownmenu">
<LI>
<a href="'.$sumber.'/admbk/abs/harian.php" title="Absensi Harian Siswa">Absensi Harian Siswa</a>
</LI>
<LI>
<a href="'.$sumber.'/admbk/abs/rekap_kelas.php" title="Rekap Absensi per Kelas Ruang">Rekap Absensi Per Kelas Ruang</a>
</LI>
<LI>
<a href="'.$sumber.'/admbk/abs/rekap_bulanan.php" title="Rekap Absensi per Bulan">Rekap Absensi Per Bulan</a>
</LI>
</UL>';
//absensi ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





//pribadi ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<A href="'.$sumber.'/admbk/p/pribadi.php" class="menuku"><strong>Kelakuan/Pribadi</strong>&nbsp;&nbsp;</A> | ';
//pribadi ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





//pelanggaran ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<A href="#" data-flexmenu="flexmenu12" class="menuku"><strong>PELANGGARAN</strong>&nbsp;&nbsp;</A> |
<UL id="flexmenu12" class="flexdropdownmenu">
<LI>
<a href="'.$sumber.'/admbk/k/jenis_pelanggaran.php" title="Data Master Jenis Pelanggaran">Data Master Jenis Pelanggaran</a>
</LI>
<LI>
<a href="'.$sumber.'/admbk/k/pelanggaran.php" title="Data Master Pelanggaran">Data Master Pelanggaran</a>
</LI>
<LI>
<a href="'.$sumber.'/admbk/k/pelanggaran_siswa.php" title="Data Pelanggaran Siswa">Data Pelanggaran Siswa</a>
</LI>
<LI>
<a href="'.$sumber.'/admbk/k/rekap_siswa.php" title="Data Rekap Siswa">Data Rekap Siswa</a>
</LI>
</UL>';
//pelanggaran ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////







//perpustakaan //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<A href="#" data-flexmenu="flexmenu29" class="menuku"><strong>PERPUSTAKAAN</strong>&nbsp;&nbsp;</A> |
<UL id="flexmenu29" class="flexdropdownmenu">
<LI>
<a href="'.$sumber.'/admbk/p/pinjam_sedang.php" title="Sedang Pinjam">Sedang Pinjam</a>
</LI>
<LI>
<a href="'.$sumber.'/admbk/p/pinjam_pernah.php" title="Pernah Pinjam">Pernah Pinjam</a>
</LI>
<LI>
<a href="'.$sumber.'/admbk/p/baru.php" title="Koleksi Item Terbaru">Koleksi Item Terbaru</a>
</LI>
</UL>';
//perpustakaan //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





//logout ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '</td>
<td width="10%" align="right">
<A href="'.$sumber.'/admbk/logout.php" title="Logout / KELUAR" class="menuku"><strong>LogOut</strong></A>
</td>
</tr>
</table>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>