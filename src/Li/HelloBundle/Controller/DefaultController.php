<?php

namespace Li\HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

define('TOKEN', 'hahahaha');

class DefaultController extends Controller {

    public function indexAction($name) {
        return $this->render('LiHelloBundle:Default:index.html.twig', array('name' => $name));
    }

    public function testAction(){
    	$data = array(
    		'toUser' => '12312',
    		'fromUser' => '123123',
    		'time' => time(),
    		'type' => 'text',
    		'content' => 'lalala',
    		);
    	return $this->render('LiHelloBundle:Default:text.xml.twig', $data);
    }

    public function weixinAction(Request $request){
    	// 检查是否是微信的请求
	if (!$this->checkSignature($request)) {
		echo 'opps...';
		exit();
	}
	// 微信验证请求
    	if($request->server->get('REQUEST_METHOD') == 'GET'){
		echo $request->query->get('echostr');
		exit();
    	}
    	// 正常请求
    	$post_data = file_get_contents('php://input'); // 微信服务器POST的xml数据
	$d = simplexml_load_string($post_data, 'SimpleXMLElement', LIBXML_NOCDATA);
    	$data = array(
    		'toUser' => $d->FromUserName,
    		'fromUser' => $d->ToUserName,
    		'time' => time(),
    		'type' => 'text',
    		'content' => $d->Content,
		);
    	return $this->render('LiHelloBundle:Default:text.xml.twig', $data);
    }

    private function checkSignature($request) {
	$token = TOKEN;
    	$signature = $request->query->get('signature');
    	$timestamp = $request->query->get('timestamp');
    	$nonce = $request->query->get('nonce');
	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr);
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );
	
	if( strtolower($tmpStr) == strtolower($signature)){
		return true;
	}else{
		return false;
	}
    }
}
