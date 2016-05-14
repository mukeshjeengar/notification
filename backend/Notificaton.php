<?php

require("SqlConnection.php");
header('Content-type: application/json');

/**
* Generate random notifications
*/
class Notificaton extends SqlConnection{

    public $per_page = 10;

    public function read_notification($id){
        $q = "UPDATE notifications set is_read = 1 where id = $id";
        if($this->mysqli->query($q)){
            return json_encode(array("error"=>false, "message"=>"Read marked!"));
        }else{
            return json_encode(array("error"=>true, "message"=>"Failed to mark read!"));
        }
    }

    public function get_notifications($page){
        $offset = ($page<=1)?0:(($page-1)*$this->per_page);
        
        $q = "SELECT sum(if(is_read=0,1,0)) as unread_count, count(*) as total_count from notifications";
        $stats = $this->mysqli->query($q)->fetch_assoc();

        $list = array("unread_count"=>$stats['unread_count'], "total_count"=>$stats['total_count'], "notifications"=>array());

        $q = "SELECT * from notifications limit ".$offset.",".$this->per_page;
        $data = $this->mysqli->query($q);

        while($row = $data->fetch_assoc()){            
            $list['notifications'][] = $row;
        }
        return $list;
    }
}

if (!isset($_GET['action'])) {
    echo json_encode(array("error"=>true, "message"=>"Missing action parameter"));
}
else{
    $noti = new Notificaton();
    if ($_GET['action'] == "read" && isset($_GET['ids']) && count($_GET['ids']) > 0) {
        echo $noti->read_notification($_GET['ids']);
    } 
    elseif ($_GET['action'] == "list" && isset($_GET['page'])) {
        echo json_encode(array("error"=>false, "message"=>"Successfull.", "data"=>$noti->get_notifications($_GET['page'])));
    }
    else{
        echo json_encode(array("error"=>true, "message"=>"Missing parameter"));       
    }
}