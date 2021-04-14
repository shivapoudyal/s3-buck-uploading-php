<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class CloudUpload extends CI_Controller {
    
    function index(){
        $this->load->view("cloudUploadView");
    }
 
 function file_upload_to_s3(){
 
	    $this->load->library('S3'); //load S3 library
	    $this->load->model('cloudUploadModel'); //load model
            
            $fileTempName = $_FILES['image']['tmp_name'];
            $image_name = $_FILES['image']['name'];
 
	    $upload_folder   = 'uploadimages';  //folder name
            $bucket_name     =  'imageuploadingshiva'; //Bucket name
            $awsstatus       =  $this->cloudUploadModel->amazons3Upload($image_name, $fileTempName, $upload_folder); //call model function
            $awss3filepath   =  "http://".$bucket_name.'.'."s3.amazonaws.com/".$upload_folder.'/'.$image_name;
            
            printdie($awsstatus);
 }
 
 function liveChat(){
     $this->load->view("liveChataView");
 }
 
 function getLastChatMsg(){
     
     $userId = $this->input->get("userId");
     $lastId = $this->input->get("lastId");
     $isAll = $this->input->get("isAll");
     
     
     if($isAll == 1){
         $query = $this->db->query("SELECT id, userId, chat_msg, msg_datetime from live_chat ORDER BY id DESC");
     }
     else{
         $query = $this->db->query("SELECT id, userId, chat_msg, msg_datetime from live_chat ORDER BY id DESC LIMIT 1");
     }
     
     if($isAll == 1 ){
        $res['status'] = true;
        $res['msg'] = 'chat msg';
        $res['data'] = $query->result_array();
     }
     else if($query->num_rows() > 0 && $isAll == 0 && ($query->row_array()['id'] == $lastId) && $lastId > 0 ){
        $res['status'] = true;
        $res['msg'] = 'chat msg';
        $res['data'] = $query->row_array();
     }
     else{
        $res['status'] = false;
        $res['msg'] = 'no msg';
        $res['data'] = '';
     }
     
     
     echo json_encode($res);
 }
 
 function sendMsg(){
     $msg_body = $this->input->post("msg_body");
     $userId = $this->input->post("userId");
     
     if( $this->db->query("INSERT INTO live_chat(userId, chat_msg) VALUES ($userId, '$msg_body')") ){
         $res['status'] = true;
         $res['data'] = $this->db->insert_id();
     }
     else{
         $res['status'] = false;
     }
     echo json_encode($res);
 }
 
 
}