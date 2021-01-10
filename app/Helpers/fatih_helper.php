<?php

if (! function_exists('tgl_indo'))
{
	function tgl_indo($tgl){
        $bln = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $tanggal = substr($tgl,8,2);
        $bulan = substr($tgl,5,2);
        $tahun = substr($tgl,0,4);
        $jam = substr($tgl, 10,9);
        return $tanggal.' '.$bln[(int)$bulan-1].' '.$tahun.' Jam '.$jam;       
	}

	function tanggal($tgl){
        $bln = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $tanggal = substr($tgl,8,2);
        $bulan = substr($tgl,5,2);
        $tahun = substr($tgl,0,4);
        $jam = substr($tgl, 10,9);
        return $tanggal.' '.$bln[(int)$bulan-1].' '.$tahun;       
	}
}
if (! function_exists('checklink'))
{
        function checklink($link){
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
       if(preg_match($reg_exUrl, $link, $url)) {
            return preg_replace($reg_exUrl,"<a href='{$url[0]}'>{$url[0]}</a>", $link);
        } else {
            return $link;
        }
        }
}

function seo_title($s) {
        $c = array (' ');
        $d = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+','–');
        $s = str_replace($d, '', $s); // Hilangkan karakter yang telah disebutkan di array $d
        $s = strtolower(str_replace($c, '-', $s)); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua
        return $s;
    }

function getbulanthn($tgl)
{
    $romawi = array("I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
    $bulan = substr($tgl,5,2);
    $tahun = substr($tgl,0,4);
    return $romawi[$bulan-1].'/'.$tahun;
}

function gethari($tgl){
    $Hari = array("Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
    $tanggal = substr($tgl,8,2);
    $hari = date("w",strtotime($tgl));
    return $Hari[$hari].' / '.$tanggal;       
}

function getjam($tgl)
{
    $jam = substr($tgl, 10,9);
    return $jam;       
}

function getbulan($tgl)
{
    $bln = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
    $bulan = substr($tgl,5,2);
    $tahun = substr($tgl,0,4);
    return $bln[(int)$bulan-1].', '.$tahun;
}

function rupiah($angka){
     $hasil_rupiah = number_format($angka,0,',','.');
     return $hasil_rupiah;
}
