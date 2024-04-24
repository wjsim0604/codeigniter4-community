<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Aws\S3\Exception\S3Exception;
use App\Models\S3Model;

class S3Controller extends BaseController
{
    public function uploadImage () {
        // try {
            $log = service('logger');
            $s3 = service('s3');
            $imageData = $this->request->header('File-Name')->getValue();
            $mime = $this->request->header('File-Type')->getValue();
            $body = $this->request->getBody();
            $key = 'board/'.date('YmdHis').'/'.$imageData;
            $result = $s3->putObject([
                'Bucket' => 'no-name-community',
                'Key'    => $key,
                'Body'   => $body,
                'Content-type' => $mime,
                'ACL'    => 'public-read'
            ]);
            if ($result) {
                $data = '&bNewLine=true&sFileName='.$imageData.'&sFileURL='.$result['ObjectURL'].'&';
                return $data;
            } else {
                $log->debug('failed');
            }
    }
}
