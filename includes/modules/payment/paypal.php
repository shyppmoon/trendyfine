<?php

/**
 * ECSHOP 贝宝插件
 * ============================================================================
 * 版权所有 2005-2010 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: paypal.php 17063 2010-03-25 06:35:46Z liuhui $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/paypal.php';

if (file_exists($payment_lang))
{
    global $_LANG;

    include_once($payment_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = 'paypal_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.paypal.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.0';

    /* 配置信息 */
    $modules[$i]['config'] = array(
        array('name' => 'paypal_account', 'type' => 'text', 'value' => ''),
        array('name' => 'paypal_currency', 'type' => 'select', 'value' => 'USD')
    );

    return;
}

/**
 * 类
 */
class paypal
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function paypal()
    {
    }

    function __construct()
    {
        $this->paypal();
    }

    /**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
		//print_r($order);

        $data_order_id      = $order['log_id'];
        $data_amount        = price_format($order['order_amount'],false,false,$order['curr_to'],$order['curr_rate'],$order['curr_format']);

        $data_return_url    = return_url(basename(__FILE__, '.php'))."&payok=payok&tf_order_amount=".$order['order_amount']."&tf_curr_rate=".$order['curr_rate']."&tf_order_sn=".$order['order_sn']."&tf_curr_to=".$order['curr_to'];		
        $data_pay_account   = $payment['paypal_account'];
        //$currency_code      = $payment['paypal_currency'];
		$currency_code      = $order['curr_to'];
        $data_notify_url    = return_url(basename(__FILE__, '.php'));
        $cancel_return      = $GLOBALS['ecs']->url();

	    $order['country'] = $GLOBALS['db']->getOne("select region_name from ".$GLOBALS['ecs']->table('region')." where region_id = '".$order['country']."'");
		$order['province'] = $GLOBALS['db']->getOne("select region_name from ".$GLOBALS['ecs']->table('region')." where region_id = '".$order['province']."'");


        $def_url  = '<form name="payform" id="payform" action="https://www.paypal.com/cgi-bin/webscr" method="post">' .   // 不能省略
            "<input type='hidden' name='cmd' value='_xclick'>" .                             // 不能省略
            "<input type='hidden' name='business' value='$data_pay_account'>" .                 // 贝宝帐号
            "<input type='hidden' name='item_name' value='$order[order_sn]'>" .                 // payment for
            "<input type='hidden' name='amount' value='$data_amount'>" .                        // 订单金额
            "<input type='hidden' name='currency_code' value='$currency_code'>" .            // 货币
            "<input type='hidden' name='return' value='$data_return_url'>" .                    // 付款后页面
            "<input type='hidden' name='invoice' value='$data_order_id'>" .                      // 订单号
            "<input type='hidden' name='charset' value='utf-8'>" .                              // 字符集
            "<input type='hidden' name='no_shipping' value='1'>" .                              // 不要求客户提供收货地址
            "<input type='hidden' name='no_note' value=''>" .                                  // 付款说明
            "<input type='hidden' name='notify_url' value='$data_notify_url'>" .
            "<input type='hidden' name='rm' value='2'>" .
            "<input type='hidden' name='cancel_return' value='$cancel_return'>" .
			"<input type='hidden' name='address1' value='$order[billing_address]'>" .
			"<input type='hidden' name='address2' value='$order[billing_address_2]'>" .
			"<input type='hidden' name='first_name' value='$order[firstname]'>" .
			"<input type='hidden' name='last_name' value='$order[lastname]'>" .
			"<input type='hidden' name='email' value='$order[email]'>" .
			"<input type='hidden' name='zip' value='$order[zipcode]'>" .
			"<input type='hidden' name='state' value='$order[province]'>" .
			"<input type='hidden' name='country' value='$order[country]'>" .
			"<input type='hidden' name='city' value='$order[city]'>" .
            "<input type='submit' value='" . $GLOBALS['_LANG']['paypal_button'] . "'>" .                      // 按钮
            "</form><br />";

        return $def_url;
    }

    /**
     * 响应操作
     */
    function respond()
    {
        //phpsir start 
		/*
        if($_GET["payok"] == "payok")
        {
			    return true;
        }
		*/
		 
		$myf = dirname(__FILE__)."/../../../temp/caches/phpsir.txt";
		$phpsirdebug = 1;
		//$phpsirdebug = 0;



		if($phpsirdebug){
			file_put_contents($myf,"\r\n\r\n 开始执行一个新Respond函数",FILE_APPEND);
			file_put_contents($myf,"\r\n \$_POST = " . print_r($_POST,true),FILE_APPEND);
			file_put_contents($myf,"\r\n \$_GET =" . print_r($_GET,true),FILE_APPEND);
		}
        //phpsir end 

 

        $payment        = get_payment('paypal');
        $merchant_id    = $payment['paypal_account'];               ///获取商户编号

        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';
        foreach ($_POST as $key => $value)
        {
             $value = urlencode(stripslashes($value));
             $req .= "&$key=$value";
        }

	    //测试测试
		if($phpsirdebug){
			file_put_contents($myf,"\r\n \$_POST = " . $req,FILE_APPEND);
		}


        // post back to PayPal system to validate
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
		//$header .= "HOST: www.paypal.com:80\r\n"; // phpsir comment this line
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) ."\r\n\r\n";
        $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

         
		/*order_paid(98, PS_PAYED);
		return true;*/
        // assign posted variables to local variables
        $item_name = $_POST['item_name'];				//PP传递过来的订单号
        $item_number = $_POST['item_number'];			//是空
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];			//PP传递过来的金额(对应币种的，如果是GBP，则是GBP的金额)
        $payment_currency = $_POST['mc_currency'];		//PP支付的币种
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];
        $order_sn = $_POST['invoice'];
        $memo = !empty($_POST['memo']) ? $_POST['memo'] : '';
        $action_note = $txn_id . '（' . $GLOBALS['_LANG']['paypal_txn_id'] . '）' . $memo;

        if (!$fp)
        {
            fclose($fp);
			if($phpsirdebug){
			file_put_contents($myf,"\r\n fail at  = " . __LINE__ ,FILE_APPEND);//phpsir
			}
            return false;
        }
        else
        {
            fputs($fp, $header . $req);
            while (!feof($fp))
            {
                $res = fgets($fp, 1024);
								
				if($phpsirdebug){
                file_put_contents($myf,"res = " . print_r($res,true),FILE_APPEND);//phpsir
				}
                if (strcmp($res, 'VERIFIED') == 0)
                {
                    // check the payment_status is Completed
                    if ($payment_status != 'Completed' && $payment_status != 'Pending')
                    {
                        
						if($phpsirdebug){
						file_put_contents($myf,"\r\n fail at  = " . __LINE__ ,FILE_APPEND);//phpsir
						}
						fclose($fp);
							
                        return false;
                    }

                    // check that txn_id has not been previously processed
                    /*$sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('order_action') . " WHERE action_note LIKE '" . mysql_like_quote($txn_id) . "%'";
                    if ($GLOBALS['db']->getOne($sql) > 0)
                    {
                        fclose($fp);

                        return false;
                    }*/

                    // check that receiver_email is your Primary PayPal email
					// PHPSIR NOTICE : must use main email as your paypal account in your administration pages
                    if ($receiver_email != $merchant_id)
                    {
						if($phpsirdebug){
						file_put_contents($myf,"\r\n  fail at  = " . __LINE__ ." Because receiver_email = " . $receiver_email . " and merchant_id " . $merchant_id  ,FILE_APPEND);//phpsir
						}
						fclose($fp);
						
                        return false;
                    }

                    // check that payment_amount/payment_currency are correct
                    /*$sql = "SELECT order_amount FROM " . $GLOBALS['ecs']->table('pay_log') . " WHERE log_id = '$order_sn'";
                    if ($GLOBALS['db']->getOne($sql) != $payment_amount)
                    {
						if($phpsirdebug){
						file_put_contents($myf,"\r\n  fail at  = " . __LINE__ ,FILE_APPEND);//phpsir
						}
						fclose($fp);
                        return false;
                    }*/
                   /* if ($payment['paypal_currency'] != $payment_currency)
                    {
						if($phpsirdebug){
						file_put_contents($myf,"\r\n  fail at  = " . __LINE__ ,FILE_APPEND);//phpsir
						}
						fclose($fp);
                        return false;
                    }
                   */

                    // process payment
					if($phpsirdebug)
					{
					   file_put_contents($myf,"\r\n Change Order Status, now at  = " . __LINE__ ,FILE_APPEND);//phpsir
					}
					 
                    order_paid($order_sn, PS_PAYED, $action_note);

					/* 这里执行shareasale的联盟营销代码*/ 

					if($payment_currency != "USD")		//其他币种
					{
						//获取这个币种的汇率
						$sql_curr = 'SELECT `rate` FROM ' . $GLOBALS['ecs']->table('currency') .  " WHERE `currency_to` = '$payment_currency'";
						$arr_curr = $GLOBALS['db']->getRow($sql_curr);
						$payment_amount = $payment_amount / $arr_curr["rate"]; 	  //重新计算对应的美元金额 
					}
					else								
					{
						//美元币种，不需要动作
					}

					
					
					$url = 'https://shareasale.com/sale.cfm?amount='.$payment_amount.'&tracking='.$item_name.'&transtype=sale&merchantID=55809';
					if(!function_exists('file_get_contents'))
					{
						$file_contents = file_get_contents($url);

						if($phpsirdebug)
						{
						   file_put_contents($myf,"\r\n << File_get_contents >> line=" . __LINE__ . $file_contents ,FILE_APPEND);//phpsir
						}				
					} 
					else 
					{
						$ch = curl_init();
						$timeout = 8;
						curl_setopt ($ch, CURLOPT_URL, $url);
						curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
						$file_contents = curl_exec($ch);
						curl_close($ch);

						if($phpsirdebug)
						{
						   file_put_contents($myf,"\r\n << CURL_init_EXEC >> line=" . __LINE__ . $file_contents ,FILE_APPEND);//phpsir
						}
					}

					//file_get_contents('https://shareasale.com/sale.cfm?amount='.$payment_amount.'&tracking='.$item_name.'&transtype=sale&merchantID=55809');

					/*
					$url = 'https://shareasale.com/sale.cfm?amount='.$payment_amount.'&tracking='.$item_name.'&transtype=sale&merchantID=55809';
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$rsp = curl_exec($ch);
					curl_close($ch); 
					*/

					if($phpsirdebug)
					{
					   file_put_contents($myf,"\r\n Shareasale:".$url.", now at  = " . __LINE__ ,FILE_APPEND);//phpsir
					}
					/*结束联盟营销代码 */


                    fclose($fp);

					if($phpsirdebug)
					{
					     file_put_contents($myf,"\r\n  done  = " . $order_sn ,FILE_APPEND);
					}

                    return true;
                }
                elseif (strcmp($res, 'INVALID') == 0)
                {
					/*
					paypal的支付调用respond函数是有两次调用，第1次是在用户执行完付款后从pp自动回跳到我们的网站，第二次是pp的ipn进行一次异步调用。两次调用得到的get和post信息分别如下：
					【如下这个是面向用户的时候调用respond时返回的get和post信息】面向用户的时候，pp是不告诉具体的支付结果的，只有异步调用的时候才会通过post信息告诉我们他完成了支付，面向用户的时候这个post信息是空的
						$_POST = Array
						(
						) 
						 $_GET =Array
						(
							[code] => paypal
							[payok] => payok
							[tf_order_amount] => 0.10
							[tf_curr_rate] => 40.798
							[tf_order_sn] => 2014101687842
							[tf_curr_to] => RUB
							[tx] => 78466786S9799770A
							[st] => Completed
							[amt] => 4.08
							[cc] => RUB
							[cm] => 
							[item_number] => 
						) 
						$_POST = cmd=_notify-validateres = HTTP/1.0 200 OK
						res = Server: Apache
						res = X-Frame-Options: SAMEORIGIN
						res = Content-Type: text/html; charset=UTF-8
						res = DC: slc-b-origin-www-1.paypal.com
						res = Date: Thu, 16 Oct 2014 08:54:58 GMT
						res = Content-Length: 7
						res = Connection: close
						res = 
						res = INVALID
						fail at  = 332

					【如下这个是异步调用respond函数的时候返回的get和post信息】以下是异步调用的时候post的具体信息，是有正确信息的
					$_POST = Array
					(
						[mc_gross] => 4.08
						[invoice] => 200
						[protection_eligibility] => Ineligible
						[payer_id] => Q8H79D9EQTJUY
						[tax] => 0.00
						[payment_date] => 02:26:37 Oct 16, 2014 PDT
						[payment_status] => Completed
						[charset] => gb2312
						[first_name] => Jun
						[mc_fee] => 4.08
						[notify_version] => 3.8
						[custom] => 
						[payer_status] => verified
						[business] => payment@trendyfine.com
						[quantity] => 1
						[verify_sign] => AxcMFSB32JIHwoK-TwCLBIYw4DhlAeQM8QMiMUmIs3g6H3L8BWbaxRGJ
						[payer_email] => susanenvystore@outlook.com
						[txn_id] => 6SJ15014F84733746
						[payment_type] => instant
						[last_name] => Xue
						[receiver_email] => payment@trendyfine.com
						[payment_fee] => 
						[receiver_id] => TDM9D5TRLDA6A
						[txn_type] => web_accept
						[item_name] => 2014101634658
						[mc_currency] => RUB
						[item_number] => 
						[residence_country] => CN
						[handling_amount] => 0.00
						[transaction_subject] => 
						[payment_gross] => 
						[shipping] => 0.00
						[ipn_track_id] => 3fced83583a0
					) 
					 $_GET =Array
					(
						[code] => paypal
					)  
					 $_POST = cmd=_notify-validate&mc_gross=4.08&invoice=200&protection_eligibility=Ineligible&payer_id=Q8H79D9EQTJUY&tax=0.00&payment_date=02%3A26%3A37+Oct+16%2C+2014+PDT&payment_status=Completed&charset=gb2312&first_name=Jun&mc_fee=4.08&notify_version=3.8&custom=&payer_status=verified&business=payment%40trendyfine.com&quantity=1&verify_sign=AxcMFSB32JIHwoK-TwCLBIYw4DhlAeQM8QMiMUmIs3g6H3L8BWbaxRGJ&payer_email=susanenvystore%40outlook.com&txn_id=6SJ15014F84733746&payment_type=instant&last_name=Xue&receiver_email=payment%40trendyfine.com&payment_fee=&receiver_id=TDM9D5TRLDA6A&txn_type=web_accept&item_name=2014101634658&mc_currency=RUB&item_number=&residence_country=CN&handling_amount=0.00&transaction_subject=&payment_gross=&shipping=0.00&ipn_track_id=3fced83583a0res = HTTP/1.0 200 OK
					res = Server: Apache
					res = X-Frame-Options: SAMEORIGIN
					res = Content-Type: text/html; charset=UTF-8
					res = DC: slc-b-origin-www-2.paypal.com
					res = Date: Thu, 16 Oct 2014 09:26:48 GMT
					res = Content-Length: 8
					res = Connection: close
					res = 
					res = VERIFIED
					 Change Order Status, now at  = 301
					 Shareasale:https://shareasale.com/sale.cfm?amount=166.45584&tracking=2014101634658&transtype=sale&merchantID=51383, now at  = 335
					  done  = 200
					*/
				   //基于以上分析，所以用户这边执行回调函数respond()这个函数的时候直接返回 true，告诉用户去查看后台订单状态是否已经完成付款。


				   return true;		//严学文，直接返回true在这里

							// log for manual investigation
							if($phpsirdebug){
							file_put_contents($myf,"\r\n  fail at  = " . __LINE__ ,FILE_APPEND);//phpsir
							}
							fclose($fp);
							
							return false;
                }
            }//结束while循环
        }//结束else fp
    }
}

?>
