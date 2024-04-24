<?php

namespace App\Models;

use CodeIgniter\Model;

class S3Model extends Model
{
    public function parseDataFromUrl($url) {
        $resultObject = ([
            'Location' => $url
        ]);

        $splittedUrl = explode('/', preg_replace('/https{0,1}:\/\//', '', $url));
        $splittedUrl[0] = preg_replace('/(\.s3|^s3).*$/', '', $splittedUrl[0]);
        $filteredDatas = array_filter($splittedUrl, function($value) {
            return $value != '';
        }, ARRAY_FILTER_USE_KEY);
        $resultObject['Bucket'] = $filteredDatas[1];
        array_splice($filteredDatas, 0, 2);
        $resultObject['Key'] = join('/', $filteredDatas);
        if ($resultObject['Bucket'] == 'no-name-community') {
            $resultObject['LocationCNAME'] = 'https://s3.ap-northeast-2.amazonaws.com/no-name-community/'.$resultObject['Key'];
        } else {
            $resultObject['LocationCNAME'] = 'https://'.$resultObject['Bucket'].'/'.$resultObject['Key'].'}';
        }

        return $resultObject;
    }
}
