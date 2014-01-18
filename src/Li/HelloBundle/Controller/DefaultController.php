<?php

namespace Li\HelloBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

define('TOKEN', 'hahahaha');

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LiHelloBundle:Default:index.html.twig', array('name' => $name));
    }

    public function testAction(){
    	$xml = ' <xml>
			 <ToUserName><![CDATA[toUser]]></ToUserName>
			 <FromUserName><![CDATA[fromUser]]></FromUserName> 
			 <CreateTime>1348831860</CreateTime>
			 <MsgType><![CDATA[text]]></MsgType>
			 <Content><![CDATA[this is a test]]></Content>
			 <MsgId>1234567890123456</MsgId>
		 </xml>';
	$s = new Serializer();
	$d = $s->decode($xml, 'xml');
	var_dump($d);
	exit();
    	$data = array(
    		'toUser' => '12312',
    		'fromUser' => '123123',
    		'time' => time(),
    		'type' => 'text',
    		'content' => 'hello',
    		);
    	return $this->render('LiHelloBundle:Default:text.xml.twig', $data);
    }

    public function weixinAction(Request $request){
    	$signature = $request->query->get('signature');
    	$timestamp = $request->query->get('timestamp');
    	$nonce = $request->query->get('nonce');
    	$echostr = $request->query->get('echostr');
	$token = TOKEN;
	if ($this->checkSignature($token, $signature, $timestamp, $nonce)) {
		echo $echostr;
	}
    }

    private function checkSignature($token, $signature, $timestamp, $nonce) {
	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr);
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );
	
	if( $tmpStr == $signature ){
		return true;
	}else{
		return false;
	}
    }
}
