<?php

/**
 * ECSHOP 验证码图片类
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: cls_captcha.php 17217 2011-01-19 06:29:08Z liubo $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}
//验证码类
class captcha {
	private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789'; //随机因子
	private $code;       //验证码
	private $codelen = 4;     //验证码长度
	private $width = 130;     //宽度
	private $height = 22;     //高度
	private $img;        //图形资源句柄
	private $font;        //指定的字体
	private $fontsize = 12;    //指定字体大小
	private $fontcolor;      //指定字体颜色
	
	/*------------------------------------------------------ */
	//-- 存在session中的名称
	/*------------------------------------------------------ */
	var $session_word = 'captcha_word';

	//构造方法初始化
	public function __construct() {
		$this->font = ROOT_PATH.'/font/elephant.ttf';
	}

	//生成随机码
	private function createCode() {
		$_len = strlen($this->charset)-1;
		for ($i=0;$i<$this->codelen;$i++) {
			$this->code .= $this->charset[mt_rand(0,$_len)];
		}
	}
	
	/**
	 * 检查给出的验证码是否和session中的一致
	 *
	 * @access  public
	 * @param   string  $word   验证码
	 * @return  bool
	 */
	function check_word($word)
	{
		if(isset($_SESSION[$this->session_word])){
			if($_SESSION[$this->session_word] == strtolower($word)){
				return  true;
			}else{
				return  false;
			}
		}
	}
	
	
	//生成背景
	private function createBg() {
		$this->img = imagecreatetruecolor($this->width, $this->height);
		$color = imagecolorallocate($this->img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
		imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
	}

	//生成文字
	private function createFont() {
		$_x = $this->width / $this->codelen;
		for ($i=0;$i<$this->codelen;$i++) {
			$this->fontcolor = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
			imagettftext($this->img,$this->fontsize,mt_rand(-30,30),$_x*$i+mt_rand(1,5),$this->height /1.2,$this->fontcolor,$this->font,$this->code[$i]);
		}
	}

	//生成线条、雪花
	private function createLine() {
		for ($i=0;$i<6;$i++) {
			$color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
			imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
		}
		for ($i=0;$i<100;$i++) {
			$color = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
			imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
		}
	}

	//输出
	private function outPut() {
		header('Content-type:image/png');
		imagepng($this->img);
		imagedestroy($this->img);
	}

	//对外生成
	public function doimg() {
		$this->createBg();
		$this->createCode();
		$this->createLine();
		$this->createFont();
		$this->outPut();
	}

	//获取验证码
	public function getCode() {
		return strtolower($this->code);
	}

}

?>