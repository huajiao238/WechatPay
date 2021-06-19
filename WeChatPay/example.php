<?php
/**
 *作者：誓言
 *日期：2020-12-21
 */
header('Content-type:text/html; Charset=utf-8');
require_once 'WxPayJSAPI.php';
$mchid = 'xxxxx';          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
$appid = 'xxxxx';  //微信支付申请对应的公众号的APPID
$appKey = 'xxxxx';   //微信支付申请对应的公众号的APP Key
$apiKey = 'xxxxx';   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
//①、获取用户openid
$wxPay = new WxPayJSAPI($mchid,$appid,$appKey,$apiKey);
$openId = $wxPay->GetOpenid();      //获取openid
if(!$openId) exit('获取openid失败');
//②、统一下单
$outTradeNo = uniqid();     //你自己的商品订单号
$payAmount = 0.01;          //付款金额，单位:元
$orderName = '支付测试';    //订单标题
$notifyUrl = 'https://www.xxx.com/wx/notify.php';     //付款成功后的回调地址(不要有问号)
$payTime = time();      //付款时间
$jsApiParameters = $wxPay->createJsBizPackage($openId,$payAmount,$outTradeNo,$orderName,$notifyUrl,$payTime);
$jsApiParameters = json_encode($jsApiParameters);
?>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>微信支付样例-支付</title>
    <script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                <?php echo $jsApiParameters; ?>,
                function(res){
                    WeixinJSBridge.log(res.err_msg);
                    if(res.err_msg=='get_brand_wcpay_request:ok'){
                        alert('支付成功！');
                    }else{
                        alert('支付失败：'+res.err_code+res.err_desc+res.err_msg);
                    }
                }
            );
        }
        function callpay()
        {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
    </script>
</head>
<body>
<br/>
<font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:50px"><?php echo $payAmount?>元</span>钱</b></font><br/><br/>
<div align="center">
    <button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >立即支付</button>
</div>
</body>
</html>