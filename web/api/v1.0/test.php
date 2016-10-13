<?php


$url = 'www.nigeda.com/api/v1.0/';

$params = array(
    'user'      =>  'api1001',
    'method'    =>  'getBanner',
    'data'      =>  array('class'=>3),
);
$params['sign'] = sign($params);

$array = 'data='.json_encode($params);

//$aa = requests($url, 'PUT', $array);
//echo "<pre>PUT:";print_r($aa);die;

$url_get = $url.'tm/?'.$array;
echo "<pre>params:";print_r($array);
$aa = requests($url_get, 'GET', $array);
echo "<pre>GET:";print_r($aa);//die;


// $url_post = $url.'tm/';
// $aa = requests($url_post, 'POST', $array);
// echo "<pre>POST:";print_r($aa);die;


// $aa = requests($url, 'POST', $array);
// echo "<pre>POST:";print_r($aa);//die;

//$aa = requests($url, 'DELETE', $array);
//echo "<pre>DELETE:";print_r($aa);die;

function sign($data){
    ksort($data, SORT_STRING);
    $apiKey = 'JyZgJxMcHoFn2015ZxKfKeYsBxT';
    $sign   = md5( md5(serialize($data)).$apiKey );
    return $sign;
}

function requests( $url, $type='GET', $params=array() )
    {
        $_type = $type;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        //curl_setopt($ch, CURLOPT_ENCODING,'application/json');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $_type);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt(
            $ch, CURLOPT_POSTFIELDS, $params
        );
        $result = curl_exec($ch);
        
        if($result === false) {
            $result = curl_error($ch);
        }
        curl_close($ch);//return $result;
        $res =  json_decode(trim($result,chr(239).chr(187).chr(191)),true);
        return $res;
    }