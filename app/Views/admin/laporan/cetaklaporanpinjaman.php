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
			<div style="font-size:14px; text-align: center; color:'#dddddd';">REKAP DAFTAR PINJAMAN AKHIR</div>
			<div style="font-size:14px; text-align: center; color:'#dddddd';">PERIODE <?= $start; ?> - <?= $end; ?></div>
		</p>
		<table style="font-size:12px;">
			<tr>
				<td align="center" width="5%"><strong>NO</strong></td>
				<td align="center" width="35%"><strong>NAMA</strong></td>
				<td align="center" width="20%"><strong>PINJAMAN</strong></td>
				<td align="center" width="20%"><strong>ANGSURAN PINJAMAN</strong></td>
				<td align="center" width="20%"><strong>SISA PINJAMAN</strong></td>
			</tr>
			<?php
				$no=1;
				foreach ($lap as $key):
				$hasil = ((float)$key['nominal_pinjam']-(float)$key['total_angsuran']);
			?>
			<tr nobr="true">
				<td><?= $no;?></td>
				<td align="center"><?= $key['nm_anggota'] ?></td>
				<td>Rp. <?= number_format($key['nominal_pinjam'],0) ?></td>
				<td>Rp. <?= number_format($key['total_angsuran'],0) ?></td>
				<td>Rp. <?= number_format($hasil,0) ?></td>
			</tr>
			<?php $no++; endforeach;?>
		</table>
		<table style="font-size:12px;">
			<tr>
				<td colspan="2" width="40%">Total</td>
				<td width="17%"><?= 'Rp.'.number_format($total['totalhrg'],0); ?></td>
			</tr>
		</table>
	</body>
</html>