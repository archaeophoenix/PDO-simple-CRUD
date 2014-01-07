<?php
class isi extends PDO{

    function __construct(){
        require_once 'config.php';
        extract($connect);
		parent::__construct("mysql:host=$host;dbname=$dbname", "$user", "$password");
    }

    function field($table){
        $exe=$this->prepare("DESCRIBE ".$table);
        $exe->execute();
        $result=$exe->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function create($table,$data=array()){
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        if(empty($data)){
            $Field=$this->field($table);
            foreach($Field as $Fields){
                if($Fields['Type']!='timestamp'){
                    $data[$Fields['Field']] = $_REQUEST[$Fields['Field']];
                }
            }
        }
        $fields = implode(", ", array_keys($data));
        $values = ":".implode(", :", array_keys($data));
        $val = implode(", ", $data);
        $sql = "INSERT INTO $table($fields) VALUES($val)";
        $exe = $this->prepare("INSERT INTO $table($fields) VALUES($values)");
        if(!$exe){
            $message = $this->errorInfo();
            echo $message[2]."<br>please check this query &rArr; ".$sql;
            die();
        } else {
            foreach ($data as $key => $value) {
                $exe->bindValue(":$key", $value);
            }
            $exe->execute();
        }
    }

    function read($table, $condition = null, $fields = "*"){
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sql = "SELECT $fields FROM $table $condition";
        $exe = $this->prepare($sql);
        if(!$exe){
            $message = $this->errorInfo();
            echo $message[2]."<br>please check this query &rArr; ".$sql;
            die();
        } else {
            $exe->execute();
            $result = $exe->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    }

    function update($table,$where,$data=array()){
        if(empty($data)){
            $Field=$this->field($table);
            foreach($Field as $Fields){
                if($Fields['Type']!='timestamp'){
                    $value[]=$Fields['Field']." = :".$Fields['Field'];
                    $data[$Fields['Field']] = $_REQUEST[$Fields['Field']];
                }
            }
        } else {
            foreach($data as $key => $val ){
                $value[]=$key." = :".$key;
                // $datas[]=$key." = ".$val;
            }
        }
        $values=implode(", ",$value);
        // echo "UPDATE $table SET ".implode(" ,",$datas)." WHERE $where <br/>"
        $exe=$this->prepare("UPDATE $table SET $values WHERE $where");
        if(!$exe){
            $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $message = $this->errorInfo();
            echo $message[2];
            die();
        } else {
            foreach ($data as $key => $value) {
                $exe->bindValue(":$key", $value);
                // echo $key."=>".$value;
            }
            // die;
            $exe->execute();
        }
    }

    function delete($table,$where){
        $this->exec("DELETE FROM $table WHERE $where");
    }

    function upload($data,$url,$rename=NULL){
        $files=$_FILES[$data];
        $name=$files['name'];
        move_uploaded_file($files['tmp_name'],"$url/".$name);
        if (!is_null($rename)) {
            $tipe=explode(".",$name);
            $rename=$rename.".".end($tipe);
            rename("$url/".$name,"$url/".$rename);
            $files['name']=$rename;
            $name=$rename;
        }
        chmod("$url/".$name, 0777);
        return $files;
    }

    function one($table, $condition = null, $fields = "*"){
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sql = "SELECT $fields FROM $table $condition";
        $exe = $this->prepare($sql);
        if(!$exe){
            $message = $this->errorInfo();
            echo $message[2]."<br>please check this query &rArr; ".$sql;
            die();
        } else {
            $exe->execute();
            $result = $exe->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
    }

    function redirect($var){
        echo '<meta http-equiv="refresh"  content="0; url='.$var.'" />';
    }
}