<?php

namespace Tests\Unit;

use App\Jobs\DisposeChangesWithYZUid;
use App\Jobs\DisposeGuanJiaPoPush;
use App\Jobs\DisposeYouZanPush;
use App\Jobs\RecalculateVip;
use App\Jobs\SyncVip;
use App\Libiary\Utility\CurlWrapper;
use Tests\TestCase;

class VipTest extends TestCase
{
    public function testYZTradeEvent()
    {
        $post = <<<POST
{"client_id":"cd0e03a4099eb5c933","id":"E20180127173141101500008","kdt_id":17284278,"kdt_name":"Pocket  黑店","mode":1,"msg":"%7B%22update_time%22:%222018-01-27%2017:31:41%22,%22extra_info%22:%22%7B%5C%22is_retail_offline%5C%22:false%7D%22,%22payment%22:%220.02%22,%22pay_type%22:%22%22,%22book_id%22:%22201801271730285a6c46b4a82c518438%22,%22tid%22:%22E20180127173141101500008%22,%22status%22:%22WAIT_BUYER_PAY%22%7D","sendCount":0,"sign":"ed23594fb6f1b62684ecd04a22300394","status":"WAIT_BUYER_PAY","test":false,"type":"TRADE_ORDER_STATE","version":1517045501}
POST;

        dispatch(new DisposeYouZanPush($post))->onConnection('sync');
    }

    public function testYzTradeEventTradeSuccess()
    {
        $post = <<<POST
{"client_id":"cd0e03a4099eb5c933","id":"E20180126122044073700005","kdt_id":17284278,"kdt_name":"Pocket  黑店","mode":1,"msg":"%7B%22update_time%22:%222018-01-28%2015:48:15%22,%22extra_info%22:%22%7B%5C%22is_retail_offline%5C%22:false%7D%22,%22payment%22:%220.00%22,%22pay_type%22:%22%E4%BC%98%E6%83%A0%E5%85%91%E6%8D%A2%22,%22book_id%22:%22201801261218575a6aac311ad0744099%22,%22tid%22:%22E20180126122044073700005%22,%22status%22:%22TRADE_SUCCESS%22%7D","sendCount":0,"sign":"e792cda10bf20d27e715deb549ddc8f3","status":"TRADE_SUCCESS","test":false,"type":"TRADE_ORDER_STATE","version":1517126843}
POST;

        dispatch(new DisposeYouZanPush($post))->onConnection('sync');
    }

    public function testYZCustomerCardEvent()
    {
        $post = <<<POST
{"client_id":"cd0e03a4099eb5c933","id":"230597762487315507","kdt_id":17284278,"kdt_name":"Pocket  黑店","mode":1,"msg":"%7B%22fans_id%22%3A0%2C%22fans_type%22%3A0%2C%22mobile%22%3A%2218611367408%22%2C%22card_alias%22%3A%222flg1h77ias9yA%22%2C%22card_no%22%3A%22230597762487315507%22%2C%22event_time%22%3A%222018-01-28+16%3A00%3A29%22%7D","sendCount":0,"sign":"40ffe29d66bd20ebaf2d061245b537f4","status":"CUSTOMER_CARD_GIVEN","test":false,"type":"SCRM_CUSTOMER_CARD","version":1517126429}
POST;

        dispatch(new DisposeYouZanPush($post))->onConnection('sync');
    }

    public function testYZCustomerCardEventTaken()
    {
        $post = <<<POST
{"client_id":"cd0e03a4099eb5c933","id":"231382285018826151","kdt_id":17284278,"kdt_name":"Pocket  黑店","mode":1,"msg":"%7B%22fans_id%22%3A0%2C%22fans_type%22%3A0%2C%22mobile%22%3A%2218611367408%22%2C%22card_alias%22%3A%22365dfnbl8ly1yD%22%2C%22card_no%22%3A%22231382285018826151%22%2C%22event_time%22%3A%222018-01-29+19%3A50%3A12%22%7D","sendCount":0,"sign":"e73a3b78ffe123833496dbaa37f4d0ea","status":"CUSTOMER_CARD_TAKEN","test":false,"type":"SCRM_CUSTOMER_CARD","version":1517226612}
POST;

        dispatch(new DisposeYouZanPush($post))->onConnection('sync');
    }

    public function testYZCustomerEvent()
    {
        $post = <<<POST
{"client_id":"cd0e03a4099eb5c933","id":"725462007","kdt_id":17284278,"mode":1,"msg":"%7B%22account_id%22%3A%22725462007%22%2C%22account_type%22%3A%22YouZanAccount%22%2C%22birthday%22%3A%22%22%2C%22gender%22%3A1%2C%22name%22%3A%22%E7%BB%A7%E6%89%BF%22%7D","sendCount":0,"sign":"583bbf16572f252c3872ef7186602d5e","status":"CUSTOMER_UPDATED","test":false,"type":"SCRM_CUSTOMER_EVENT","version":1517055397112}
POST;

        dispatch(new DisposeYouZanPush($post))->onConnection('sync');
    }

    public function testYZCustomerUpdateEvent()
    {
        $post = <<<POST
{"client_id":"cd0e03a4099eb5c933","id":"725462007","kdt_id":17284278,"mode":1,"msg":"%7B%22account_id%22%3A%22725462007%22%2C%22account_type%22%3A%22YouZanAccount%22%2C%22birthday%22%3A%22%22%2C%22gender%22%3A1%2C%22name%22%3A%22%E7%BB%A7%E6%89%BF%22%7D","sendCount":0,"sign":"583bbf16572f252c3872ef7186602d5e","status":"CUSTOMER_UPDATED","test":false,"type":"SCRM_CUSTOMER_EVENT","version":1517055397112}
POST;

        dispatch(new DisposeYouZanPush($post))->onConnection('sync');
    }

    /**
     * 测试交易第一版本的数据格式
     */
    public function testYZTradeV1Event()
    {
        $post = <<<POST
{"client_id":"cd0e03a4099eb5c933","id":"E20180126122044073700005","kdt_id":17284278,"kdt_name":"Pocket  黑店","mode":1,"msg":"%7B%22trade%22:%7B%22consign_time%22:%222018-01-28%2015:48:15%22,%22buyer_area%22:%22%E5%8C%97%E4%BA%AC%E5%B8%82%E5%8C%97%E4%BA%AC%E5%B8%82%22,%22original_post_fee%22:%220.00%22,%22num%22:1,%22adjust_fee%22:%7B%22pay_change%22:%220.00%22,%22change%22:%220.00%22,%22post_change%22:%220.00%22%7D,%22relation_type%22:%22%22,%22type%22:%22FIXED%22,%22order_mark%22:%22%22,%22buyer_id%22:%22400767613%22,%22tid%22:%22E20180126122044073700005%22,%22send_num%22:1,%22feedback%22:0,%22delivery_start_time%22:0,%22outer_user_id%22:0,%22qr_id%22:0,%22price%22:%220.01%22,%22button_list%22:[%7B%22tool_parameter%22:%22%7B%5C%22detail_url%5C%22:%5C%22https://h5.youzan.com/v2/kdtapp/order/expresslist%3Forder_no=E20180126122044073700005&kdt_id=17284278%5C%22,%5C%22required_keys%5C%22:[%5C%22access_token%5C%22]%7D%22,%22tool_type%22:%22goto_webview:web%22,%22create_time%22:%22%22,%22tool_value%22:%22%22,%22tool_title%22:%22%E6%9F%A5%E7%9C%8B%E7%89%A9%E6%B5%81%22,%22new_sign%22:%220%22,%22tool_icon%22:%22https://img.yzcdn.cn/upload_files/2015/08/28/FjeHlaPYCjO0KAgbJliv1FwCFSoj.png%22%7D,%7B%22tool_parameter%22:%22%7B%7D%22,%22tool_type%22:%22goto_native:trade_memo%22,%22create_time%22:%22%22,%22tool_value%22:%22%22,%22tool_title%22:%22%E5%A4%87%E6%B3%A8%22,%22new_sign%22:%220%22,%22tool_icon%22:%22https://img.yzcdn.cn/upload_files/2015/08/28/FpO1UIXyOEZO026tWIgUOm9uZnT2.png%22%7D],%22total_fee%22:%220.01%22,%22payment%22:%220.00%22,%22order_type%22:%220%22,%22weixin_user_id%22:%220%22,%22is_tuan_head%22:0,%22lat%22:40.006571509027,%22sub_trades%22:[],%22delivery_time_display%22:%22%22,%22buyer_message%22:%22%E6%8A%80%E6%9C%AF%E6%B5%8B%E8%AF%95%E8%AF%B7%E5%BF%BD%E7%95%A5%22,%22delivery_end_time%22:0,%22lng%22:116.40972911781,%22created%22:%222018-01-26%2012:20:44%22,%22kind%22:1,%22delivery_list%22:[],%22is_retail_offline%22:false,%22offline_id%22:%220%22,%22goods_kind%22:1,%22status_str%22:%22%E5%B7%B2%E5%8F%91%E8%B4%A7%22,%22pay_time%22:%222018-01-26%2012:20:44%22,%22group_no%22:%22%22,%22shop_id%22:%220%22,%22out_trade_no%22:[],%22points_price%22:0,%22user_id%22:%229968353%22,%22tuan_no%22:%22%22,%22orders%22:[%7B%22is_virtual%22:0,%22outer_item_id%22:%22%22,%22pic_thumb_path%22:%22https://img.yzcdn.cn/upload_files/2016/10/17/FjeWec4SM6jEJ84hyWsVB5GO5E3Z.jpg%3FimageView2/2/w/200/h/0/q/75/format/jpg%22,%22item_type%22:0,%22num%22:%221%22,%22refunded_fee%22:%220.00%22,%22num_iid%22:%22307861007%22,%22oid%22:18239548,%22title%22:%22%E5%8D%9A%E8%8E%B1%E5%BE%B7%E8%BF%B7%E4%BD%A0%E5%AF%B9%E8%AE%B2%E6%9C%BA%20%E7%99%BD-%E6%B5%8B%E8%AF%95%E8%BD%AC%E7%94%A8%EF%BC%8C%E8%AF%B7%E5%8B%BF%E4%B9%B1%E6%8B%8D%EF%BC%81%22,%22fenxiao_payment%22:%220.00%22,%22discount_fee%22:%220.00%22,%22buyer_messages%22:[],%22is_present%22:0,%22price%22:%220.01%22,%22fenxiao_price%22:%220.00%22,%22total_fee%22:%220.01%22,%22alias%22:%223f439vmdzw8p2%22,%22payment%22:%220.01%22,%22outer_sku_id%22:%22%22,%22sku_unique_code%22:%22%22,%22is_send%22:1,%22item_id%22:%22307861007%22,%22sku_id%22:%220%22,%22sku_properties_name%22:%22%22,%22pic_path%22:%22https://img.yzcdn.cn/upload_files/2016/10/17/FjeWec4SM6jEJ84hyWsVB5GO5E3Z.jpg%22,%22item_refund_state%22:%22NO_REFUND%22,%22state_str%22:%22%E5%B7%B2%E5%8F%91%E8%B4%A7%22,%22unit%22:%22%E4%BB%B6%22,%22order_promotion_details%22:[],%22allow_send%22:0,%22seller_nick%22:%22Pocket%20%20%E9%BB%91%E5%BA%97%22%7D],%22promotion_details%22:[],%22refund_state%22:%22NO_REFUND%22,%22status%22:%22WAIT_BUYER_CONFIRM_GOODS%22,%22post_fee%22:%220.00%22,%22pic_thumb_path%22:%22https://img.yzcdn.cn/upload_files/2016/10/17/FjeWec4SM6jEJ84hyWsVB5GO5E3Z.jpg%3FimageView2/2/w/200/h/0/q/75/format/jpg%22,%22invoice_title%22:%22%22,%22receiver_city%22:%22%E5%8C%97%E4%BA%AC%E5%B8%82%22,%22shipping_type%22:%22express%22,%22refunded_fee%22:%220.00%22,%22num_iid%22:%22307861007%22,%22title%22:%22%E5%8D%9A%E8%8E%B1%E5%BE%B7%E8%BF%B7%E4%BD%A0%E5%AF%B9%E8%AE%B2%E6%9C%BA%20%E7%99%BD-%E6%B5%8B%E8%AF%95%E8%BD%AC%E7%94%A8%EF%BC%8C%E8%AF%B7%E5%8B%BF%E4%B9%B1%E6%8B%8D%EF%BC%81%22,%22discount_fee%22:%220.01%22,%22buy_way_str%22:%22%22,%22hotel_info%22:%22%22,%22pf_buy_way_str%22:%22%E8%BF%90%E8%B4%B9%E5%88%B0%E4%BB%98%22,%22receiver_state%22:%22%E5%8C%97%E4%BA%AC%E5%B8%82%22,%22update_time%22:%222018-01-28%2015:48:15%22,%22coupon_details%22:[%7B%22coupon_description%22:%22%22,%22used_at%22:%222018-01-26%2012:20:44%22,%22coupon_condition%22:%22%E4%B8%8B%E5%8D%95%E7%AB%8B%E5%87%8F20.00%E5%85%83%22,%22coupon_id%22:%222298819%22,%22coupon_content%22:%22%22,%22coupon_name%22:%22%E6%8A%B5%E7%8E%B0%E9%87%91-%E7%86%8A%E6%B5%8B%22,%22coupon_type%22:%22PROMOCARD%22,%22discount_fee%22:%220.01%22%7D],%22receiver_zip%22:%22100101%22,%22receiver_name%22:%22%E7%86%8A%E7%8C%9B%22,%22pay_type%22:%22COUPONPAY%22,%22fans_info%22:%7B%22fans_type%22:%229%22,%22buyer_id%22:%229968353%22,%22fans_nickname%22:%22%E7%86%8A%E7%8C%9B%22,%22fans_id%22:%22400767613%22%7D,%22delivery_third_party_supported%22:false,%22buyer_type%22:%229%22,%22receiver_district%22:%22%E6%9C%9D%E9%98%B3%E5%8C%BA%22,%22box_price%22:%220.00%22,%22pic_path%22:%22https://img.yzcdn.cn/upload_files/2016/10/17/FjeWec4SM6jEJ84hyWsVB5GO5E3Z.jpg%22,%22feedback_num%22:0,%22receiver_mobile%22:%2218611367408%22,%22sign_time%22:%22%22,%22seller_flag%22:0,%22buyer_nick%22:%22%E7%86%8A%E7%8C%9B%22,%22service_phone%22:%2215014278591%22,%22shop_type%22:%221%22,%22receiver_address%22:%22%E6%85%A7%E5%BF%A0%E9%87%8C%E5%8D%A7%E9%BE%99%E5%B0%8F%E5%8C%BA219%E5%8F%B7%E5%88%AB%E5%A2%85%E4%B8%9C%E5%8D%95%E5%85%83%22,%22trade_memo%22:%22%22,%22relations%22:[],%22outer_tid%22:%22%22%7D%7D","sendCount":0,"sign":"b925b11505a38cf66c4296605a2e30cd","status":"WAIT_BUYER_CONFIRM_GOODS","test":false,"type":"TRADE","version":1517125695}
POST;


        dispatch(new DisposeYouZanPush($post))->onConnection('sync');
    }

    /**
     * 积分回调由手机号的情况
     */
    public function testPointsWithMobileEvent()
    {
        $post = <<<POST
{"client_id":"cd0e03a4099eb5c933","id":"yzuser_2493731","kdt_id":17284278,"kdt_name":"Pocket  黑店","mode":1,"msg":"%7B%22amount%22%3A6%2C%22client_hash%22%3A%22%22%2C%22create_time%22%3A%222018-03-06+15%3A30%3A37%22%2C%22description%22%3A%22%E9%A2%86%E5%8D%A1%E9%80%81%E7%A7%AF%E5%88%86%22%2C%22fans_id%22%3A0%2C%22mobile%22%3A%2213608372960%22%2C%22open_user_id%22%3A%22%22%2C%22total%22%3A6%7D","sendCount":0,"sign":"d1165da5bc2bdf991aa0667c8d10e13c","test":false,"type":"POINTS","version":1520321437}
POST;

        dispatch(new DisposeYouZanPush($post))->onConnection('sync');
    }

    /**
     * 积分回调无手机号的情况
     */
    public function testPointsWithoutMobileEvent()
    {
        $post = <<<POST
{"client_id":"cd0e03a4099eb5c933","id":"yzuser_2493731","kdt_id":17284278,"kdt_name":"Pocket  黑店","mode":1,"msg":"%7B%22amount%22%3A6%2C%22client_hash%22%3A%22%22%2C%22create_time%22%3A%222018-03-06+15%3A30%3A37%22%2C%22description%22%3A%22%E9%A2%86%E5%8D%A1%E9%80%81%E7%A7%AF%E5%88%86%22%2C%22fans_id%22%3A0%2C%22open_user_id%22%3A%22%22%2C%22total%22%3A6%7D","sendCount":0,"sign":"d1165da5bc2bdf991aa0667c8d10e13c","test":false,"type":"POINTS","version":1520321437}
POST;

        dispatch(new DisposeYouZanPush($post))->onConnection('sync');
    }

    public function testReplicateGrant()
    {
        dispatch(new SyncVip('18611367408'))->onConnection('sync');
        dispatch(new SyncVip('18611367408'))->onConnection('sync');
    }

    public function testRecalculateVip()
    {
//        dispatch(new DisposeChangesWithYZUid('561305219'))->onConnection('sync');
        dispatch(new RecalculateVip('18980554564'))->onConnection('sync');
    }

    public function testGuanJiaPoAddCardUnExist()
    {
        $post = [
            'id' => '15911094367',
            'status'=> '银口袋',
            'type' => 'SCRM_CUSTOMER_CARD'
        ];

        dispatch(new DisposeGuanJiaPoPush($post))->onConnection('sync');
    }

    public function testGuanJiaPoAddLingShouDan()
    {
        $post = [
            'id' => 'LS-T-2018-02-06-0009',
            'status'=> '零售单过账',
            'buyer_mobile' => '15911094367',
            'type' => 'TRADE_ORDER_STATE'
        ];

        dispatch(new DisposeGuanJiaPoPush($post))->onConnection('sync');
    }

    public function testBatchRefreshCard()
    {
        $mobiles = [
            '13050520033',
            '13052587777',
            '13118513339',
            '13167891781',
            '13212318793',
            '13251277766',
            '13301005341',
            '13311866591',
            '13430593618',
            '13436122289',
            '13484622269',
            '13501076571',
            '13508348638',
            '13550635791',
            '13564555028',
            '13572191799',
            '13580457703',
            '13590335481',
            '13599518991',
            '13599520074',
            '13601870234',
            '13612962203',
            '13631288028',
            '13631665643',
            '13679674690',
            '13693649446',
            '13701038950',
            '13709468197',
            '13736326261',
            '13759596859',
            '13774271123',
            '13775180345',
            '13775235232',
            '13801086355',
            '13802938124',
            '13808012386',
            '13810815552',
            '13811800737',
            '13816695191',
            '13826451863',
            '13828409348',
            '13828683862',
            '13828888623',
            '13839973272',
            '13859941370',
            '13883569269',
            '13887277329',
            '13888758008',
            '13896207195',
            '13901060960',
            '13908302819',
            '13908311121',
            '13909538899',
            '13910778855',
            '13911386370',
            '13911473442',
            '13916914071',
            '13918443230',
            '13931601688',
            '13938562998',
            '13963925560',
            '13967418697',
            '13971186996',
            '13982045761',
            '13983000848',
            '13984348431',
            '13986285373',
            '13996269889',
            '15000470016',
            '15013831711',
            '15033931526',
            '15052199713',
            '15079882377',
            '15111165388',
            '15151862262',
            '15166829221',
            '15167279790',
            '15223176477',
            '15267879990',
            '15277375998',
            '15364930000',
            '15388177047',
            '15603072325',
            '15621630718',
            '15817825882',
            '15825996873',
            '15902302113',
            '15903993332',
            '15921889705',
            '15923247101',
            '15940927613',
            '15988099766',
            '17600737350',
            '17711076627',
            '17722413220',
            '18001126203',
            '18011133399',
            '18018667667',
            '18030686015',
            '18073169237',
            '18113414109',
            '18113536838',
            '18138283718',
            '18163553535',
            '18166450314',
            '18200257100',
            '18225025962',
            '18228033896',
            '18267186561',
            '18280486891',
            '18382666923',
            '18523375715',
            '18553526900',
            '18565250836',
            '18580058888',
            '18603613574',
            '18605320607',
            '18618302527',
            '18623180629',
            '18623433771',
            '18623693623',
            '18627862677',
            '18650809062',
            '18663792008',
            '18676699554',
            '18677071105',
            '18678777785',
            '18684671199',
            '18696522092',
            '18696666565',
            '18718009300',
            '18766186617',
            '18771855333',
            '18787544775',
            '18805270069',
            '18851719077',
            '18858079363',
            '18910353982',
            '18990829888',
            '18996323888',
            '19989696180',
        ];

        foreach ($mobiles as $mobile){
            var_dump($mobile);

            $res = CurlWrapper::post(['mobile' => $mobile],"http://dc.sylicod.com/refreshCard");

            $resJson = json_decode($res);

            if(empty($resJson)){
                var_dump("exception {$resJson}");
            }
        }
    }
}
