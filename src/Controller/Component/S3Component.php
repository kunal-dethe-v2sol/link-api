<?php
namespace App\Controller\Component;

require VENDOR_PATH . '/autoload.php';

use Cake\Controller\Component;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Credentials\CredentialsInterface;

// upload large files using multipart upload
use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;

class S3Component extends Component
{
    private $_accessKey;    // AWS Access key
    private $_secretKey;    // AWS Secret key
    
    public $client;        //AWS Client
    public $bucket;        //AWS Bucket
    
    
    public function initialize(array $config) {
        parent::initialize($config);
        $this->version = 'latest';
        $this->region = 'us-east-1';
        
        $this->_accessKey   =   'AKIAIRXPZCZQVHGE3SGQ';
        $this->_secretKey   =   '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';
        
        //Instantiate an S3 bucket
        $this->bucket = "linkcxo-test";
        
        // Instantiate an S3 client
        $this->client = S3Client::factory(array(
                    'region' => $this->region,
                    'version' => $this->version,
                    'credentials' => [
                            'key'    => $this->_accessKey,
                            'secret' => $this->_secretKey,
                        ],

        ));
    }

   
    public function upload($filePath,$fileName)
    {
        $uploader = new MultipartUploader($this->client, $filePath, [
            'bucket' => $this->bucket,
            'key'    => $fileName,
        ]);
        
        try {
            $result = $uploader->upload();
            return TRUE;
            
        } catch (MultipartUploadException $e) {
            
            $uploader->abort();
            return FALSE;
        }       
  
    }
    
    public function delete($filePaths)
    {
        foreach($filePaths as $key) {
            $arrFilesToDelete[] = array('Key' => $key);
        }        
        $result = $this->client->deleteObjects([
            'Bucket' => $this->bucket,
            'Delete' => [
                'Objects' => $arrFilesToDelete
            ],
        ]);
  
    }
    
        
    function download($s3FileKey, $downloadFilePath) 
    {
        $result = $this->client->getObject(array(
            'Bucket' => $this->bucket,
            'Key'    => $s3FileKey,
            'SaveAs' => $downloadFilePath
        ));        
    }
    
    public function getObjectInfo($bucket,$file){
        
        if($this->client->doesObjectExist($bucket,$file))
        { 
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function copyObject($input) 
    {
        $sourceBucket   = $input['sourceBucket'];
        $sourceKeyname  = $input['sourceKeyname'];
        $targetBucket   = $input['targetBucket'];
        $targetKeyname  = $input['targetKeyname'];
        try{
            $this->client->copyObject(array(
            'Bucket'     => $targetBucket,
            'Key'        => $targetKeyname,
            'CopySource' => "{$sourceBucket}/{$sourceKeyname}",
            ));
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    public function listObjects($bucket){
        try {
            
            $objects = $this->client->getIterator('ListObjects', array(
                'Bucket' => $bucket,
                'Prefix' => 'm'
            ));
            return $objects;
        } catch (S3Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }
    
} 