<?php

namespace Tests\Unit;

use App\Jobs\DisposeYouZanPush;
use App\Services\YouZanService;
use App\Vip;
use Tests\TestCase;
use Youzan\Open\Client;

class VipTest extends TestCase
{
    public function testYZTradeEvent()
    {
        $post = <<<POST
{"client_id":"cd0e03a4099eb5c933","id":"E20180127173141101500008","kdt_id":17284278,"kdt_name":"Pocket  黑店","mode":1,"msg":"%7B%22update_time%22:%222018-01-27%2017:31:41%22,%22extra_info%22:%22%7B%5C%22is_retail_offline%5C%22:false%7D%22,%22payment%22:%220.02%22,%22pay_type%22:%22%22,%22book_id%22:%22201801271730285a6c46b4a82c518438%22,%22tid%22:%22E20180127173141101500008%22,%22status%22:%22WAIT_BUYER_PAY%22%7D","sendCount":0,"sign":"ed23594fb6f1b62684ecd04a22300394","status":"WAIT_BUYER_PAY","test":false,"type":"TRADE_ORDER_STATE","version":1517045501}
POST;

        dispatch(new DisposeYouZanPush($post))->onConnection('sync');
    }

    public function testYZCustomerCardEvent()
    {
        $post = <<<POST
{"client_id":"cd0e03a4099eb5c933","id":"230597762425525741","kdt_id":17284278,"kdt_name":"Pocket  黑店","mode":1,"msg":"%7B%22fans_id%22%3A0%2C%22fans_type%22%3A0%2C%22mobile%22%3A%22%22%2C%22card_alias%22%3A%222flg1h77ias9yA%22%2C%22card_no%22%3A%22230597762425525741%22%2C%22event_time%22%3A%222018-01-27+15%3A56%3A20%22%7D","sendCount":0,"sign":"06fbf619c63b95b4e3ac30518aa0b28d","status":"CUSTOMER_CARD_TAKEN","test":false,"type":"SCRM_CUSTOMER_CARD","version":1517039780}
POST;

        dispatch(new DisposeYouZanPush($post))->onConnection('sync');
    }

    /**
     * 测试交易第一版本的数据格式
     */
    public function testYZTradeV1Event()
    {
        $post = <<<POST
{"client_id":"cd0e03a4099eb5c933","id":"E20180128102507028100005","kdt_id":17284278,"kdt_name":"Pocket  黑店","mode":1,"msg":"%7B%22trade%22:%7B%22consign_time%22:%22%22,%22buyer_area%22:%22%22,%22original_post_fee%22:%220.00%22,%22num%22:1,%22adjust_fee%22:%7B%22pay_change%22:%220.00%22,%22change%22:%220.00%22,%22post_change%22:%220.00%22%7D,%22relation_type%22:%22%22,%22type%22:%22QRCODE%22,%22order_mark%22:%22%22,%22buyer_id%22:%223659868401%22,%22tid%22:%22E20180128102507028100005%22,%22send_num%22:0,%22feedback%22:0,%22delivery_start_time%22:0,%22outer_user_id%22:0,%22qr_id%22:%225717391%22,%22price%22:%221699.00%22,%22button_list%22:[%7B%22tool_parameter%22:%22%7B%7D%22,%22tool_type%22:%22goto_native:trade_memo%22,%22create_time%22:%22%22,%22tool_value%22:%22%22,%22tool_title%22:%22%E5%A4%87%E6%B3%A8%22,%22new_sign%22:%220%22,%22tool_icon%22:%22https://img.yzcdn.cn/upload_files/2015/08/28/FpO1UIXyOEZO026tWIgUOm9uZnT2.png%22%7D],%22total_fee%22:%221699.00%22,%22payment%22:%221699.00%22,%22order_type%22:%226%22,%22weixin_user_id%22:%220%22,%22is_tuan_head%22:0,%22lat%22:%22%22,%22sub_trades%22:[],%22delivery_time_display%22:%22%22,%22buyer_message%22:%22%22,%22delivery_end_time%22:0,%22lng%22:%22%22,%22created%22:%222018-01-28%2010:25:07%22,%22kind%22:1,%22delivery_list%22:[],%22is_retail_offline%22:false,%22offline_id%22:%220%22,%22goods_kind%22:1,%22status_str%22:%22%E5%B7%B2%E5%AE%8C%E6%88%90%22,%22pay_time%22:%222018-01-28%2010:25:15%22,%22group_no%22:%22%22,%22shop_id%22:%2217284278%22,%22out_trade_no%22:[],%22points_price%22:0,%22user_id%22:%22543853849%22,%22tuan_no%22:%22%22,%22orders%22:[%7B%22is_virtual%22:0,%22outer_item_id%22:%22%22,%22pic_thumb_path%22:%22https://img.yzcdn.cn/public_files/2016/12/29/33e6c838cefa614c5121c63c80f860e9.png%3FimageView2/2/w/200/h/0/q/75/format/png%22,%22item_type%22:30,%22num%22:%221%22,%22refunded_fee%22:%220.00%22,%22num_iid%22:%222147483647%22,%22oid%22:18240443,%22title%22:%22%E9%87%8D%E5%BA%86%E6%9C%BA%E5%9C%BA3%E5%BA%97%E6%94%B6%E6%AC%BE%22,%22fenxiao_payment%22:%220.00%22,%22discount_fee%22:%220.00%22,%22buyer_messages%22:[],%22is_present%22:0,%22price%22:%221699.00%22,%22fenxiao_price%22:%220.00%22,%22total_fee%22:%221699.00%22,%22payment%22:%221699.00%22,%22outer_sku_id%22:%22%22,%22sku_unique_code%22:%22%22,%22is_send%22:0,%22item_id%22:%222147483647%22,%22sku_id%22:%220%22,%22sku_properties_name%22:%22%22,%22pic_path%22:%22https://img.yzcdn.cn/public_files/2016/12/29/33e6c838cefa614c5121c63c80f860e9.png%22,%22item_refund_state%22:%22NO_REFUND%22,%22state_str%22:%22%E5%BE%85%E5%8F%91%E8%B4%A7%22,%22unit%22:%22%E4%BB%B6%22,%22order_promotion_details%22:[],%22allow_send%22:1,%22seller_nick%22:%22Pocket%20%20%E9%BB%91%E5%BA%97%22%7D],%22promotion_details%22:[],%22refund_state%22:%22NO_REFUND%22,%22status%22:%22TRADE_BUYER_SIGNED%22,%22post_fee%22:%220.00%22,%22pic_thumb_path%22:%22https://img.yzcdn.cn/public_files/2016/12/29/33e6c838cefa614c5121c63c80f860e9.png%3FimageView2/2/w/200/h/0/q/75/format/png%22,%22invoice_title%22:%22%22,%22receiver_city%22:%22%22,%22shipping_type%22:%22express%22,%22refunded_fee%22:%220.00%22,%22num_iid%22:%222147483647%22,%22title%22:%22%E9%87%8D%E5%BA%86%E6%9C%BA%E5%9C%BA3%E5%BA%97%E6%94%B6%E6%AC%BE%22,%22discount_fee%22:%220.00%22,%22buy_way_str%22:%22%22,%22hotel_info%22:%22%22,%22pf_buy_way_str%22:%22%E8%BF%90%E8%B4%B9%E5%88%B0%E4%BB%98%22,%22receiver_state%22:%22%22,%22update_time%22:%222018-01-28%2010:25:07%22,%22coupon_details%22:[],%22receiver_zip%22:%22%22,%22receiver_name%22:%22%22,%22pay_type%22:%22WEIXIN_DAIXIAO%22,%22fans_info%22:%7B%22fans_type%22:%229%22,%22buyer_id%22:%22543853849%22,%22fans_nickname%22:%22so%CA%8A%20hw%C9%91t%22,%22fans_id%22:%223659868401%22%7D,%22delivery_third_party_supported%22:false,%22transaction_tid%22:%22180128102508000008%22,%22buyer_type%22:%229%22,%22receiver_district%22:%22%22,%22box_price%22:%220.00%22,%22pic_path%22:%22https://img.yzcdn.cn/public_files/2016/12/29/33e6c838cefa614c5121c63c80f860e9.png%22,%22feedback_num%22:0,%22receiver_mobile%22:%22%22,%22sign_time%22:%222018-01-28%2010:25:15%22,%22seller_flag%22:0,%22buyer_nick%22:%22so%CA%8A%20hw%C9%91t%22,%22service_phone%22:%2215014278591%22,%22shop_type%22:%221%22,%22receiver_address%22:%22%E9%9D%A2%E5%AF%B9%E9%9D%A2%E6%89%AB%E7%A0%81%E6%94%B6%E6%AC%BE%22,%22trade_memo%22:%22%22,%22relations%22:[],%22outer_tid%22:%224200000058201801282277331440%22%7D%7D","sendCount":0,"sign":"b670b64fb33a2acea2955bb31e8635c1","status":"TRADE_BUYER_SIGNED","test":false,"type":"TRADE","version":1517106315}
POST;

        dispatch(new DisposeYouZanPush($post))->onConnection('sync');
    }
}
