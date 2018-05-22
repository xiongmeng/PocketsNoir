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
use Illuminate\Http\Request;

class lotteryController extends Controller
{


    public function chooseShop()
    {

        $lottery = DB::select('select id,shop from lottery where status = ?', [1]);
        return json_encode($lottery, true);

    }

    public function lotteryDraw(Request $request)
    {
        $id = $request->get('id');
        $lottery = lottery::where('id', $id)->first();
        $lotteryNum = $lottery->lottery_num;  //当前已抽人数
        $totalNum = $lottery->first_prize + $lottery->second_prize + $lottery->forth_prize;
        $secondPrize = $lottery->second_prize;// 奖池中二等奖数
        $secondPrizetoTotal = $lottery->second_prize_total;  //二等奖总数（计算二等奖抽奖用）
        //抽二等奖


        $x = floor(($totalNum / $secondPrizetoTotal) * ($secondPrizetoTotal - $secondPrize + 1));
        if ($x == $lotteryNum + 1)   //中奖
        {
            $result['prize'] = 2;
            $result['massage'] = "success";
            $lottery->second_prize -= 1;
            $lottery->lottery_num += 1;
        } else {
            $prize = rand(1, $totalNum);

            if ($prize <= $lottery->first_prize) {
                $result['prize'] = 1;
                $result['massage'] = "success";
                $lottery->first_prize -= 1;
                $lottery->lottery_num += 1;
            } else if ($prize > $lottery->first_prize && $prize <= ($lottery->third_prize + $lottery->first_prize)) {
                $result['prize'] = 3;
                $result['massage'] = "success";
                $lottery->third_prize -= 1;
                $lottery->lottery_num += 1;
            } else if ($prize > ($lottery->third_prize + $lottery->first_prize) && $prize <= $totalNum) {
                $result['prize'] = 4;
                $result['massage'] = "success";
                $lottery->forth_prize -= 1;
                $lottery->lottery_num += 1;
            } else {
                $result['massage'] = "error";
            }

            $lottery->updated_at = date('Y-m-d H:i:s', time());
            $lottery->save();

            return json_encode($result, true);
        }

    }

    public function addShopRule()  //添加店铺抽奖
    {

        if ($_POST) {
            $result['info'] = Lottery::create([
                'shop' => $_POST['shop'],
                'first_prize' => $_POST['first_prize'],
                'second_prize' => $_POST['second_prize'],
                'third_prize' => $_POST['third_prize'],
                'forth_prize' => $_POST['forth_prize'],
                'second_prize_total' => $_POST['second_prize'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1
            ]);

            if ($result) {
                $result['massage'] = "success";
                return json_encode($result, true);
            }
        }

    }


    public function LotterySave(Request $request)
    {

        if ($request) {
            $result['info'] = LotteryMember::create([
                'shop' => $request->post('shop'),
                'prize_type' => $request->post('prize_type'),
                'prize_name' => $request->post('prize_name'),
                'imageID' => $request->post('imageID'),
                'phone' => $request->post('phone'),
                'member_name' => $request->post('member_name'),
                'created_at' => date('Y-m-d H:i:s', time()),
                'status' => 1
            ]);
        }
    }


    //推送奖券到有赞   推送数据线奖品到有赞
    public function pushToYouZan()
    {

    }


}