<?php
error_reporting(~E_NOTICE);
require_once 'isi.php';
/*deklarasi object dari class isi*/
$f = new isi();
/*execute query select*/
$datas = $f->read('tabel');
/*checking kondisi submit*/
if (isset($_GET['delete'])) {
	/*execute query delete*/
	$data = $f->delete('tabel',"id = '$_GET[delete]'");
	/*direct halaman ke list.php*/
	$f->redirect('list.php');
}
?>
<a href="form.php">post</a>
<table>
	<tr>
		<td>nama</td>
		<td>alamat</td>
		<td colspan="2">action</td>
	</tr>
	<?php foreach ($datas as $data){ ?>
	<tr>
		<td><?php echo $data['nama'] ?></td>
		<td><?php echo $data['alamat'] ?></td>
		<td><a href="form.php?id=<?php echo $data['id'] ?>" title="edit">edit</a></td>
		<td><a href="list.php?delete=<?php echo $data['id'] ?>" title="delete">delete</a></td>
	</tr>
	<?php } ?>
</table>