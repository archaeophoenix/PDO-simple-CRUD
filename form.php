<?php
error_reporting(~E_NOTICE);
require_once 'isi.php';

/*deklarasi object dari class isi*/
$f = new isi();

/*checking kondisi submit*/
if (isset($_POST['submit'])) {

	/*jika $_POST['id'] kosong maka execute insert data*/
	if (empty($_POST['id'])) {
		$f->create('tabel');
		/*jika $_POST['id'] ada maka execute update data*/
	} else {
		$f->update('tabel','id = :id');
	}
	/*direct kehalaman list.php*/
	$f->redirect('list.php');
}
if (isset($_GET['id'])) {
	/*pengambilan data detail*/
	$data = $f->one('tabel',"WHERE id = '$_GET[id]'");
}
?>
<a href="list.php">list</a>
<form method="post" action="#">
	<input type="hidden" name="id" value="<?php echo $data['id'] ?>">
	<table>
		<tr>
			<td>nama</td>
			<td><input type="text" name="nama" value="<?php echo $data['nama'] ?>" required oninvalid="setCustomValidity('harus diisi')"></td>
		</tr>
		<tr>
			<td>alamat</td>
			<td><input type="text" name="alamat" value="<?php echo $data['alamat'] ?>" required oninvalid="setCustomValidity('harus diisi')"></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit"></td>
		</tr>
	</table>
</form>