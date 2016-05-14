<?php

require("SqlConnection.php");

/**
* Generate random notificatons
*/
class RandomNotificaton extends SqlConnection{    

    public $names = array('Gidget','Roberto','Vernita','Karima','Ginny','Cuc','Terrance','Lauran','Rutha','Margurite','Verdie','Breana','Kaitlyn','Dyan','Denisse','Latanya','Teri','Cherryl','Jeffry','Micki');

    public $messages = array('Now for manners use has company believe parlors',
        'Least nor party who wrote while did',
        'Excuse formed as is agreed admire so on result parish',
        'Put use set uncommonly announcing and travelling',
        'Allowance sweetness direction to as necessary',
        'Principle oh explained excellent do my suspected conveying in',
        'Excellent you did therefore perfectly supposing described',
        'Wise busy past both park when an ye no',
        'Nay likely her length sooner thrown sex lively income',
        'The expense windows adapted sir',
        'Wrong widen drawn ample eat off doors money',
        'Offending belonging promotion provision an be oh consulted ourselves it',
        'Blessing welcomed ladyship she met humoured sir breeding her',
        'Six curiosity day assurance bed necessary',
        'Two before narrow not relied how except moment myself',
        'Dejection assurance mrs led certainly',
        'So gate at no only none open',
        'Betrayed at properly it of graceful on',
        'Dinner abroad am depart ye turned hearts as me wished',
        'Therefore allowance too perfectly gentleman supposing man his now',
        'Families goodness all eat out bed steepest servants',
        'Explained the incommode sir improving northward immediate eat',
        'Man denoting received you sex possible you',
        'Shew park own loud son door less yet',
        'Abilities forfeited situation extremely my to he resembled',
        'Old had conviction discretion understood put principles you',
        'Match means keeps round one her quick',
        'She forming two comfort invited',
        'Yet she income effect edward',
        'Entire desire way design few',
        'Mrs sentiments led solicitude estimating friendship fat',
        'Meant those event is weeks state it to or',
        'Boy but has folly charm there its',
        'Its fact ten spot drew');    

    public $host = "localhost";

    public $port = "9080";

    public $channel = "my_channel_1";

    public function send_notifications($data){
        $url = "http://" . $this->host . ":" . $this->port . "/pub?id=" . $this->channel;        
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20); //timeout after 20 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);;
        curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function get_random_name(){
        return $this->names[rand(0,count($this->names)-1)];
    }

    public function get_random_message(){
        return $this->messages[rand(0,count($this->messages)-1)];
    }

    public function get_random_icon(){
        return "avatar".rand(1,6).".svg";
    }

    public function add_random_notification(){
        $random_name = $this->get_random_name();
        $random_message = $this->get_random_message();
        $random_icon = $this->get_random_icon();
        $created_at = date("Y-m-d H:i:s");
        $q = "INSERT into notifications (name, message, icon, created_at, updated_at) values('$random_name', '$random_message', '$random_icon', '$created_at', '$created_at')";        
        if($this->mysqli->query($q)){
            $data = array("id"=>$this->mysqli->insert_id, "name"=>$random_name, "message"=>$random_message, "icon"=>$random_icon, "created_at"=>$created_at, "updated_at"=>$created_at);
            var_dump($data);
            if($json_data = json_encode($data)){
                $response = $this->send_notifications(json_encode($data));
                var_dump($response);
            }
        }
    }
}

(new RandomNotificaton())->add_random_notification();