<?php
/**
 * Created by PhpStorm.
 * User: fangyushuai
 * Date: 2018/5/21
 * Time: 下午1:38
 */

namespace App\Http\Controllers;

use DB;
use App\Lottery;
use App\LotteryMember;
use App\LotteryPresent;
use Illuminate\Http\Request;
use App\Services\LotteryService;
use Exception;

class lotteryController extends Controller
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
        header("Access-Control-Allow-Origin: *");

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
                $lottery->second_prize -= 1;
                $lottery->lottery_num += 1;
            } else {
                $prize = rand(1, $totalNum);
                if ($prize <= $lottery->first_prize) {
                    $data['prize'] = 1;
                    $data['massage'] = "success";
                    $data['present_id'] = $lottery->first_present_id;
                    $lotteryPresent = LotteryPresent::where('id', $lottery->first_present_id)->first();
                    $data['present_name'] = $lotteryPresent->present_name;
                    $lottery->first_prize -= 1;
                    $lottery->lottery_num += 1;
                } else if ($prize > $lottery->first_prize && $prize <= ($lottery->third_prize + $lottery->first_prize)) {
                    $data['prize'] = 3;
                    $data['massage'] = "success";
                    $data['present_id'] = $lottery->third_present_id;
                    $lotteryPresent = LotteryPresent::where('id', $lottery->third_present_id)->first();
                    $data['present_name'] = $lotteryPresent->present_name;
                    $lottery->third_prize -= 1;
                    $lottery->lottery_num += 1;
                } else if ($prize > ($lottery->third_prize + $lottery->first_prize) && $prize <= $totalNum) {
                    $data['prize'] = 0;  //四等奖
//                    $data['present_id']  = $lottery->forth_present_id;
                    $data['present_id'] = 0;// 如果是奖券 传0
                    $lotteryPresent = LotteryPresent::where('id', $lottery->forth_present_id)->first();
                    $lottery->forth_prize -= 1;
                    $lottery->lottery_num += 1;
                }

            }
            $data['present_name'] = $lotteryPresent->present_name;
            $lottery->updated_at = date('Y-m-d H:i:s', time());
            $lottery->save();
            $result['data'] = $data;
            $result['massage'] = "success";
            $result['error_code'] = "200";
            return json_encode($result, true);

        } else {
            $result['massage'] = "请检查您的id值！";
            $result['code'] = "-200";
            return json_encode($result, true);
        }


    }


    public function addShopRule(Request $request)  //添加店铺抽奖   暂时没zuo
    {
        header("Access-Control-Allow-Origin: *");

        if ($request) {
            $result['info'] = Lottery::create([
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

            if ($result) {
                $result['massage'] = "success";
                $result['code'] = "-200";
                return json_encode($result, true);
            }
        }

    }


    public function LotterySave(Request $request)
    {
        header("Access-Control-Allow-Origin: *");

        /* 抽奖客户信息储存入数据库
         * 是否需要对客户重复插入做处理？
         *
         * */

        $shopId = $request->post('shop_id');
        $presentId = $request->post('present_id');
        $imageID = $request->post('imageID');
        $phone = $request->post('phone');
        $member_name = $request->post('member_name');

        if (empty($shopId)) {
            throw new Exception("店id不能为空!");
        }
        if (empty($presentId)) {
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
        //避免重复抽奖
        $member = LotteryMember::where('phone', $phone)->first();
        if ($member) {
            $result['message'] = "该手机号已参与过活动！";
            $result['code'] = -200;
        } else {
            $result['data'] = LotteryMember::create([
                'shop_id' => $shopId,
                'present_id' => $presentId,
                'imageID' => $imageID,
                'phone' => $phone,
                'member_name' => $member_name,
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1,
            ]);
            $result['code'] = 200;
            $result['message'] = "success";

        }
        return json_encode($result);
    }


    /*发奖测试*/
    public function presentTest()
    {
        header("Access-Control-Allow-Origin: *");

        LotteryService::sendLottery("18500353096");

    }


}