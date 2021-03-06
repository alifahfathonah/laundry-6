<?php include '../inc/header.php'; ?>

<div class="container-sm row">
	<div class="col-12">
		<h1>Barang</h1>
		<nav aria-label="breadcrumb">
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="<?=base_url()?>">Laundrying</a></li>
		    <li class="breadcrumb-item text-primary">Master</a></li>
		    <li class="breadcrumb-item active" aria-current="page">List Barang</li>
		  </ol>
		</nav>
		<div class="float-right">
			<a href="" class="btn btn-default btn-sm text-muted">Refresh</a>
			<a href="generate.php" class="btn btn-success btn-sm">Tambah Barang</a>
		</div>
		<div style="margin-bottom: 20px; margin-top: 20px;">
			<form class="form-inline" action="" method="post">
				<div class="form-group">
					<input type="text" name="cari_barang" class="form-control" placeholder="Cari Barang">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary ml-1">Cari</button>
				</div>
			</form>
		</div>
		<div class="float-right mb-3">
			<button class="btn btn-danger btn-sm" onclick="pdf_barang()">Export to PDF</button>
			<button class="btn btn-danger btn-sm" onclick="hapus()">Hapus</button>
		</div>
		<form name="proses" method="post">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>
								<center>
									<input type="checkbox" id="select_all" value="">
								</center>
							</th>
							<th>Nomor</th>
							<th>Nama Barang</th>
							<th>Stok Barang</th>
							<th>Tanggal Masuk</th>
							<th>Harga Barang</th>
							<th>Opsi</th>
						</tr>
					</thead>
					<?php 
					$no = 1;
					$batas = 10;
					$hal = @$_GET['hal'];
					if(empty($hal)){
						$posisi = 0;
						$hal = 1;
					} else {
						$posisi = ($hal - 1) * $batas;
					}

					if($_SERVER['REQUEST_METHOD'] == "POST"){
						$cari_barang = trim(mysqli_real_escape_string($db, $_POST['cari_barang']));
						if($cari_barang != ''){
							$sql = "SELECT * FROM barang_laundry WHERE nama_barang like '%$cari_barang%' OR stok like '%$cari_barang%' OR tgl_update like '%$cari_barang%' OR harga like '%$cari_barang%'";
							$query = $sql;
							$queryJml = $sql;
						} else {
							$query = "SELECT * FROM barang_laundry LIMIT $posisi, $batas";
							$queryJml = "SELECT * FROM barang_laundry";
							$no = $posisi + 1;
						}
					} else {
						$query = "SELECT * FROM barang_laundry LIMIT $posisi, $batas";
						$queryJml = "SELECT * FROM barang_laundry";
						$no = $posisi + 1;
					}
					$sql_data_barang = mysqli_query($db, $query) or die ($db->error);
					if (mysqli_num_rows($sql_data_barang) > 0) {
					while ($data = mysqli_fetch_array($sql_data_barang)) {
					?>
					<tbody>
						<tr>
							<td align="center">
								<input type="checkbox" name="checked[]" class="checked" value="<?=$data['id_barang']?>">
							</td>
							<td><?=$no++?>.</td>
							<td><?=$data['nama_barang']?></td>
							<td><?=$data['stok']?></td>
							<td><?=$data['tgl_update']?></td>
							<td>Rp.<?= number_format($data['harga'])?></td>
							<td align="center">
								<a href="edit.php?id_barang=<?=$data['id_barang']?>" class="badge badge-warning">Edit</a>
							</td>
						</tr>
					<?php
						}
					} else {
						echo "<tr><td colspan=\"8\" align=\"center\">Data Tidak Ditemukan</td></tr>";
					}
					?>
					</tbody>
				</table>
			</div>
			<?php 
				if(@$_POST['cari_barang'] == '') { ?>
					<div style="float: left;">
						<?php 
							$jml = mysqli_num_rows(mysqli_query($db, $queryJml));
							echo "Jumlah Data : <b>$jml</b>";
						 ?>
					</div>

					<nav aria-label="Page navigation example" class="float-right">
						<ul class="pagination">
						    <?php 
									$jml_hal = ceil($jml / $batas);
									for ($i=1; $i<= $jml_hal; $i++){
										if($i != $hal){
											echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?hal=$i\">$i</a></li>";
										} else {
											echo "<li class=\"page-item\"><a class=\"page-link text-muted\">$i</a></li>";
										}
									}
							?>
						</ul>
					</nav>
			<?php 
				} else {
					echo "<div style=\"float: left;\">";
					$jml = mysqli_num_rows(mysqli_query($db, $queryJml));
					echo "Data Hasil Pencarian : <b>$jml</b>";
					echo "</div>";
				}
			 ?>
		</form>
	</div>
	<script type="text/javascript">
	$(document).ready(function(){

		//script untuk fungsi dari id select_all ke checked/checkbox
		$('#select_all').on('click', function(){
			if(this.checked){
				$('.checked').each(function(){
					this.checked = true;
				})
			} else {
				$('.checked').each(function(){
					this.checked = false;
				})
			}
		});
		//script untuk fungsi dari class checked click id select_all
		$('.checked').on('click', function(){
			if($('.checked:checked').length == $('.checked').length) {
				$('#select_all').prop('checked',true)
			} else {
				$('#select_all').prop('checked',false)
			}
		})
	})

	//script untuk fungsi dari button hapus
	function hapus() {
		//konfirmasi dari menghapus data
		var conf = confirm('Menghapus data-data ini?');
		if(conf){
			document.proses.action = 'delete.php';	
			document.proses.submit();
		}
		
	}

	function pdf_barang(){
		document.proses.action = 'pdf_barang.php';
		document.proses.submit();
	}

</script>
</div>
<?php include '../inc/footer.php'; ?>