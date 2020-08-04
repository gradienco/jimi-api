<?php
    date_default_timezone_set("GMT");
  
    function getParams($method, $priParams) {
        $timeNow = Date("Y-m-d H:i:s");
        $APP_SECRECT = "163119ceac1849e0875d44b7d9d18ecf";

        // -------- Common Parameter
        $comParams = array(
            "method"=>utf8_encode($method), 
            "timestamp"=>utf8_encode($timeNow),
            "app_key"=>utf8_encode("8FB345B8693CCD00E869F0C604EAB556"),
            "sign"=>"",
            "sign_method"=>utf8_encode("md5"),
            "v"=>utf8_encode("1.0"),
            "format"=>utf8_encode("json"),
        );

        $params = array_merge($comParams, $priParams);
        uksort($params, 'strnatcasecmp');

        // -------- Getting Sign
        $sign = $APP_SECRECT;
        foreach($params as $request=>$value){
            if($request != "sign"){
                $sign .= $request . $value;
            }
        }
        $sign .= $APP_SECRECT;
        $encrypt = md5($sign);
        $params["sign"] = strtoupper(utf8_encode($encrypt));

        return $params;
    }

    function API($params) {
        // -------- Setting API Target
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($params)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents("http://open.10000track.com/route/rest", false, $context);

        return $result;
    }
    
    
    // -------- Private Parameter for Get Token
    $priParams = array(
        "user_id"=>"automata",
        "user_pwd_md5"=>"70c8baabcbfbdaf39960c428fef35cdf",
        "expires_in"=>7200,
    );

    // -------- Private Method
    $method = "jimi.oauth.token.get";

    // -------- Get Parameter & Result
    $params = getParams($method, $priParams);
    $result = API($params);

    // -------- String Testing
    echo '[Request] Parameter: <br>------------- <pre>' . json_encode($params, JSON_PRETTY_PRINT) . '<pre><br>';
    echo '[Response] Result: <br>------------- <pre>' . $result . '<pre><br>';
?>