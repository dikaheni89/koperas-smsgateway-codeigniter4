<html>
	<head>
		<style>
			table {
			  font-family: arial, sans-serif;
			  border-collapse: collapse;
			  width: 100%;
			}

			td, th {
			  border: .5px solid #000000;
			  /*text-align: center;*/
			  /*height: 20px;*/
			  margin: 8px;
			}

		</style>
	</head>
	<body>
        	
		<p>
			<div style="font-size:14px; text-align: center; color:'#dddddd';">REKAP DAFTAR SIMPANAN AKHIR</div>
			<div style="font-size:14px; text-align: center; color:'#dddddd';">PERIODE <?= $start; ?> - <?= $end; ?></div>
		</p>
		<table style="font-size:12px;">
			<tr>
				<td align="center" width="5%" rowspan="2"><strong>No</strong></td>
				<td width="30%" align="center" rowspan="2"><strong>Nama</strong></td>
				<td colspan="3" width="45%" align="center"><strong>SIMPANAN</strong></td>
				<td align="center" width="15%" rowspan="2"><strong>Jumlah</strong></td>
			</tr>
			<tr>
				<td align="center" width="15%"><strong>Pokok</strong></td>
				<td align="center" width="15%"><strong>Wajib</strong></td>
				<td align="center" width="15%"><strong>Sukarela</strong></td>
			</tr>
			<?php
				$no=1;
				foreach ($lap as $key):
				$hasil = (((float)$key['jml_pokok']+(float)$key['jml_wajib'])+(float)$key['jml_sukarela']);
			?>
			<tr nobr="true">
				<td><?= $no;?></td>
				<td align="center"><?= $key['nm_anggota'] ?></td>
				<td>Rp. <?= number_format($key['jml_pokok'],0) ?></td>
				<td>Rp. <?= number_format($key['jml_wajib'],0) ?></td>
				<td>Rp. <?= number_format($key['jml_sukarela'],0) ?></td>
				<td>Rp. <?= number_format($hasil,0) ?></td>
			</tr>
			<?php $no++; endforeach;?>
		</table>
	</body>
</html>