<?php
	require_once 'isi.php';
	$datas = null;
	if (isset($_POST['submit'])){
		$f = new isi();
		$datas = $f->read('tabel',"WHERE nama LIKE '%$_POST[find]%' ","nama");
	}
?>
<form action="#" method="post">
	<input type="text" name="find">
	<input type="submit" name="submit">
</form>
<?php if(!empty($datas)){
	print_r($datas);
} ?>