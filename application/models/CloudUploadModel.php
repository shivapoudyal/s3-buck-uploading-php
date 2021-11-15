<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class CloudUploadModel extends CI_Model {
 
function amazons3Upload( $image_name , $fileTempName, $upload_folder ){
 
    $awsAccessKey = 'AKIAVDSQRBUEJGDB3FOA'; //AWS account access key
    $awsSecretKey = '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'; //AWS account secret key
    $bucket_name  = 'mywebapp-15nov';  //Bucket name 
    $s3           = new S3($awsAccessKey, $awsSecretKey);
    $s3->putBucket($bucket_name);
        
    //move the file
    if ($s3->putObjectFile($fileTempName, $bucket_name, $upload_folder.'/'.$image_name, S3::ACL_PUBLIC_READ)) {
      return '1'; //return 1 it will success
    }else{
      return '7';
    }
  }
}
