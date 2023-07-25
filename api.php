<?php
require_once('database.php');
Class API extends DBConnection{
    public function __construct(){
        parent::__construct();
    }
    public function __destruct(){
		parent::__destruct();
	}
    function save_member(){
        print_r($_POST);
        exit;
        $data = "";
        $id = $_POST['id'];
        
        foreach($_POST as $k => $v){
            // excluding id 
            if(!in_array($k,array('id'))){
                // add comma if data variable is not empty
                if(!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
        }
        if(empty($id)){
            // Insert New Member
            $sql = "INSERT INTO `members` set {$data}";
        }else{
            // Update Member's Details
          $sql = "UPDATE `members` set {$data} where id = '{$id}'"; 
        }
        $save = $this->conn->query($sql);
        if($save && !$this->conn->error){
            $resp['status'] = 'success';
            if(empty($id))
                $resp['msg'] = 'New Member successfully added';
            else
            $resp['msg'] = 'Member\'s Details successfully updated';

        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'There\'s an error occured while saving the data';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }
    function delete_member(){
        $id = $_POST['id'];
        $delete = $this->conn->query("DELETE FROM `members` where id = '{$id}'");
        if($delete){
            $resp['status'] = 'success';
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = 'There\'s an error occured while deleting the data';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }

}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$api = new API();
switch ($action){
    case('save'):
        echo $api->save_member();
        break;
    case('delete'):
        echo $api->delete_member();
        break;
    default:
        echo json_encode(array('status'=>'failed','error'=>'unknown action'));
        break;  
}

// delete multiple data

$connection = mysqli_connect("localhost","root","","inline_db");

if(isset($_POST['search_data']))
{
    $id = $_POST['id'];
    $visible = $_POST['visible'];

    $query = "UPDATE `members` SET visible='$visible' WHERE id= '{$id}'";
    $query_run = mysqli_query($connection, $query);
}

if(isset($_POST['delete_multiple_data']))
{
    $id = "1";
    $query = "DELETE FROM `members` WHERE visible= '{$id}' ";

    $query_run = mysqli_query($connection, $query);
}

    if( $delete)
    {
        $_SESSION['success'] = "Your Data is DELETED";
        header('Location: index.php');
    }

    else
    {
        
        $_SESSION['status'] = "Your Data is NOT DELETED";
        header('Location: index.php');
    }

?>