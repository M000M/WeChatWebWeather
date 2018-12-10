<?php
    //获得参数 signature nonce token timestamp echostr
    $nonce     = $_GET['nonce'];
    $token     = 'haha';
    $timestamp = $_GET['timestamp'];
    $echostr   = $_GET['echostr'];
    $signature = $_GET['signature'];
    //形成数组，然后按字典序排序
    $array = array();
    $array = array($nonce, $timestamp, $token);
    sort($array);
    //拼接成字符串,sha1加密 ，然后与signature进行校验
    $str = sha1( implode( $array ) );
    if( $str == $signature && $echostr ){
        //第一次接入weixin api接口的时候
        echo  $echostr;
        exit;
    }
	else{
        //1.获取到微信推送过来post数据（xml格式）
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        //2.处理消息类型，并设置回复类型和内容
        $postObj = simplexml_load_string( $postArr );
        //判断该数据包是否是订阅的事件推送
        if(strtolower( $postObj->MsgType) == 'event'){
            //如果是关注 subscribe 事件
            if( strtolower($postObj->Event == 'subscribe') ){
                //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  = '欢迎关注我们的微信公众账号,此公众号为测试公众号！';
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
            }
        }
      
        //判断该数据包是否是文本消息
		if( strtolower( $postObj->MsgType) == 'text'){
        	//接受文本信息
       		$content = $postObj->Content;
        	//回复用户消息(纯文本格式)
        	$toUser   = $postObj->FromUserName;
            $fromUser = $postObj->ToUserName;
            $time     = time();
            $msgType  =  'text';
          
          	$con = new mysqli('localhost', 'sql140_143_197_', 'hCndaYRw8d', 'sql140_143_197_');
          	if (!$con)
            {
            	die('Could not connect: ' . mysql_error());
            }
          
          	//在数据库中查找输入的城市名
          	$sql = "SELECT weather_code, county_name FROM ins_county";
          	$result = mysqli_query($con, $sql);
          	if (mysqli_num_rows($result) == 0) {
              	echo "查找错误";
            }
          	else{
                // 输出数据
              	$tag = 0; //标记是否找到输入城市的数据
                while($row = mysqli_fetch_assoc($result)) {
                  	if($row['county_name'] == $content){
                  		$citycode = $row["weather_code"];
                      	$tag = 1;
                      	break;
                    }
                }
              	if($tag == 1){
                  	//$url = 'https://www.baidu.com';
                  	//header("location:http://140.143.197.227/webweather/test/index.html");
                  	header("Location:https://www.taobao.com");
                  	exit;
                  
                    $url = 'http://t.weather.sojson.com/api/weather/city/'.$citycode;
                    $json = file_get_contents($url);
                  	
                  	$json_array = json_decode($json, true);
                    $city = $content;
                    $city = "当前城市  ".$city;
                    $time = $json_array['time'];
                    $time = "\n发布时间 ".$time;

                    $data = $json_array['data'];

                    $wendu = $data['wendu'].""."℃";
                    $wendu = "\n当前温度   ".$wendu;

                    $pm25 = $data['pm25'];
                    $pm25  = "\nPM2.5    ".$pm25;

                    $quality = $data['quality'];
                    $quality = "\n空气质量    ".$quality;	

                    $shidu = $data['shidu'];
                    $shidu = "\n湿度   ".$shidu;

                    $ganmao = $data['ganmao'];
                    $ganmao = "\n".$ganmao;

                    $forecast = $data['forecast'];
                    $today = $forecast[0];

                    $week = $today['week'];
                    $week = "\n".$week;

                    $fx = $today['fx'];
                    $fx = "\n风向  ".$fx;

                    $fl = $today['fl'];
                    $fl = "\n风力  ".$fl;

                    $low = $today['low'];
                    $high = $today['high'];
                    $temperature = "\n今日温度 ".$low." ~ ".$high;

                    $type = $today['type'];
                    $type = "\n\n今日天气  ".$type;

                    $notice = $today['notice'];
                    $notice = "\nnotice  ".$notice;

                    $condition = $city.$week.$time.$type.$temperature.$wendu.$shidu.$fx.$fl.$notice.$pm25.$quality.$ganmao;

                    $day1 = $forecast[1];
                    $week1 = $day1['week'];
                    $week1 = "\n".$week1;
                    $type1 = $day1['type'];
                    $type1 = "\n".$type1;
                    $low1 = $day1['low'];
                    $high1 = $day1['high'];
                    $temperature1 = "\n".$low1." ~ ".$high1;
                    $condition1 = "\n\n未来3日天气预报".$week1.$type1.$temperature1;

                    $day2 = $forecast[2];
                    $week2 = $day2['week'];
                    $week2 = "\n".$week2;
                    $type2 = $day2['type'];
                    $type2 = "\n".$type2;
                    $low2 = $day2['low'];
                    $high2 = $day2['high'];
                    $temperature2 = "\n".$low2." ~ ".$high2;
                    $condition2 = "\n".$week2.$type2.$temperature2;

                    $day3 = $forecast[3];
                    $week3 = $day3['week'];
                    $week3 = "\n".$week3;
                    $type3 = $day3['type'];
                    $type3 = "\n".$type3;
                    $low3 = $day3['low'];
                    $high3 = $day3['high'];
                    $temperature3 = "\n".$low3." ~ ".$high3;
                    $condition3 = "\n".$week3.$type3.$temperature3;

                    $content = $condition.$condition1.$condition2.$condition3;
                }
              	else{
                  	$content = $content." "."无法查询天气数据！";
                }
            } 
          
            $template = "<xml>
                           <ToUserName><![CDATA[%s]]></ToUserName>
                           <FromUserName><![CDATA[%s]]></FromUserName>
                           <CreateTime>%s</CreateTime>
                           <MsgType><![CDATA[%s]]></MsgType>
                           <Content><![CDATA[%s]]></Content>
                           </xml>";
            $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
            echo $info;
        }
    }