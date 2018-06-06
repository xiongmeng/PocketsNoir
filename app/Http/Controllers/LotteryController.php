<?php
/**
 * Created by PhpStorm.
 * User: fangyushuai
 * Date: 2018/5/21
 * Time: 下午1:38
 */

namespace App\Http\Controllers;

use App\Jobs\DuiJiangQueryForChouJIangGame;
use DB;
use App\Lottery;
use App\LotteryMember;
use App\LotteryPresent;
use Illuminate\Http\Request;
use App\Services\LotteryService;
use Exception;
use Cache;

class LotteryController extends Controller
{


    public function chooseShop()
    {
        $lottery = DB::select('select id,shop from lottery where status = ?', [1]);
        if (empty($lottery)) {
            throw new Exception("未找见指定的门店", -200);
        }
        return response()->json($lottery);
    }

    public function lotteryDraw(Request $request)
    {


        $id = $request->get('id');
        if ($id) {
            $lottery = lottery::where('id', $id)->first();
            $lotteryNum = $lottery->lottery_num;  //当前已抽人数
            $totalNum = $lottery->first_prize + $lottery->second_prize + $lottery->forth_prize;
            $secondPrize = $lottery->second_prize;// 奖池中二等奖数
            $secondPrizetoTotal = $lottery->second_prize_total;  //二等奖总数（计算二等奖抽奖用）
            //抽二等奖

            $x = floor(($totalNum / $secondPrizetoTotal) * ($secondPrizetoTotal - $secondPrize + 1));
            if ($x == $lotteryNum + 1)   //中奖
            {
                $data['prize'] = 2;
                $data['present_id'] = $lottery->second_present_id;
                $lotteryPresent = LotteryPresent::where('id', $lottery->second_present_id)->first();
                $data['present_name'] = $lotteryPresent->present_name;

            } else {
                $prize = rand(1, $totalNum);
                if ($prize <= $lottery->first_prize) {
                    $data['prize'] = 1;
                    $data['massage'] = "success";
                    $data['present_id'] = $lottery->first_present_id;
                    $lotteryPresent = LotteryPresent::where('id', $lottery->first_present_id)->first();
                    $data['present_name'] = $lotteryPresent->present_name;

                } else if ($prize > $lottery->first_prize && $prize <= ($lottery->third_prize + $lottery->first_prize)) {
                    $data['prize'] = 3;
                    $data['massage'] = "success";
                    $data['present_id'] = $lottery->third_present_id;
                    $lotteryPresent = LotteryPresent::where('id', $lottery->third_present_id)->first();
                    $data['present_name'] = $lotteryPresent->present_name;

                } else if ($prize > ($lottery->third_prize + $lottery->first_prize) && $prize <= $totalNum) {
                    $data['prize'] = 0;  //四等奖
//                    $data['present_id']  = $lottery->forth_present_id;
                    $data['present_id'] = 0;// 如果是奖券 传0
                    $lotteryPresent = LotteryPresent::where('id', $lottery->forth_present_id)->first();

                }
            }
            $lottery->lottery_sum += 1;
            $lottery->save();
            $data['present_name'] = $lotteryPresent->present_name;

            return response()->json($data);
        } else {
            throw new Exception("请检查您的id值", -200);
        }


    }


    public function addShopRule(Request $request)  //添加店铺抽奖   暂时没zuo
    {


        try {
            $result = Lottery::create([
                'shop' => $request->post('shop'),
                'first_prize' => $request->post('first_prize'),
                'second_prize' => $request->post('second_prize'),
                'third_prize' => $request->post('third_prize'),
                'forth_prize' => $request->post('forth_prize'),
                'second_prize_total' => $request->post('second_prize'),
                'first_present_id' => $request->post('first_present_id'),
                'second_present_id' => $request->post('second_present_id'),
                'third_present_id' => $request->post('third_present_id'),
                'forth_present_id' => $request->post('forth_present_id'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1
            ]);
            return response()->json($result);
        } catch (Exception $e) {
            \App\Libiary\Context\Fact\FactException::instance()->recordException($e);
            return response()->json(['code' => $e->getCode(), 'msg' => $e->getMessage()]);
        }

    }


    public function LotterySave(Request $request)
    {

        /* 抽奖客户信息储存入数据库
         * 是否需要对客户重复插入做处理？
         *
         * */
        $code = $request->post('code');
        $shopId = $request->post('shop_id');
        $presentId = $request->post('present_id');
        $imageID = $request->post('imageID');
        $phone = $request->post('phone');
        $member_name = $request->post('member_name');


        if (empty($code)) {
            throw new Exception("验证码不能为空!");
        }
        if (empty($shopId)) {
            throw new Exception("店id不能为空!");
        }
        if (!is_numeric($presentId)) {
            throw new Exception("赠品id不能为空!如果是发劵 id为0");
        }
        if (empty($imageID)) {
            throw new Exception("必须传入人脸Id!");
        }
        if (empty($phone)) {
            throw new Exception("必须传入手机号!");
        }
        if (empty($member_name)) {
            $member_name = $phone;
        }

        /*增加验证手机号*/
        LotteryService::checkIn($code, $phone);

        //避免重复抽奖
        $member = LotteryMember::where('phone', $phone)->first();
        if ($member) {
            throw new \Exception('该客户已参加过活动！');
        } else {
            $data = LotteryMember::create([
                'shop_id' => $shopId,
                'present_id' => $presentId,
                'imageID' => $imageID,
                'phone' => $phone,
                'member_name' => $member_name,
//                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1,
            ]);

            $lottery = lottery::where('id', $shopId)->first();
            switch ($presentId) {
                case 0:
                    $lottery->forth_prize -= 1;
                    break;
                case 1:
                    $lottery->first_prize -= 1;
                    break;
                case 2:
                    $lottery->second_prize -= 1;
                    break;
                case 3:
                    $lottery->third_prize -= 1;
            }

            $lottery->lottery_num += 1;
            $lottery->updated_at = date('Y-m-d H:i:s', time());
            $lottery->save();

            \App\Vip::createFromJiChang($phone);  //给客户开卡
            LotteryService::pushFacePlusPlus($phone);

            dispatch(new DuiJiangQueryForChouJIangGame($phone))->onQueue('choujiang');

            return response()->json($data);
        }

    }


    public function getMobileCode(Request $request)
    {
        $mobile = $request->post('mobile');

        if (empty($mobile)) {
            throw new Exception("必须传入手机号!");
        }

        $cacheKey = "vip_mobile_code_$mobile";
        $cacheExpired = "vip_mobile_expired_$mobile";

        if (Cache::has($cacheExpired)) {
            throw new Exception("一分钟内不能重复发送验证码！");
        }

        $code = rand(100000, 999999);
        Cache::put($cacheExpired, '', 1);
        Cache::put($cacheKey, $code, 5);

        $aliSms = new \Mrgoon\AliSms\AliSms();

        $response = $aliSms->sendSms($mobile, 'SMS_111890588', ['code' => $code]);
        return response()->json($response);
    }


    /*发奖测试*/
    public function presentTest(Request $request)
    {
        $mobile = $request->post('phone');
        LotteryService::sendLotteryByMobile($mobile);
        return response()->json("领奖成功！");
//        LotteryService::sendLottery("18500353096");

    }



    public function  importFaceBase64(){
        $base64_image_content = $_POST['imgBase64'];
//匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $content = base64_decode(str_replace($result[1], '', $base64_image_content));
            $temp = tmpfile();
            fwrite($temp, $content);
            $res = \App\Services\VipFaceImportService::detectFormFile($temp);
            is_resource($temp) && fclose($temp);
            return response()->json($res);
        } else {
            throw new Exception("base64格式不正确！");
        }
    }


}