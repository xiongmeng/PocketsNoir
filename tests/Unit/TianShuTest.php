<?php

namespace Tests\Unit;

use App\Services\KoaLaService;
use Softonic\GraphQL\ClientBuilder;
use Tests\TestCase;

class TianShuTest extends TestCase
{
    public function testImportPocketNoirVIP()
    {
        $client = ClientBuilder::build("https://prism.orgrimmar.tk/prism-pocketnoir-api/graphql");

        $query = <<<'QUERY'
mutation($data: JSON!){
      importPocketNoirVIP(data: $data) {
        isOk
        result
      }
    }
QUERY;

#   名称: 'name',
#   会员号: 'vipNo',
#   电子邮箱: 'email',
#   电话号码: 'mobile',
#   会员等级: 'vipLevel',
#   created_at: 'createdAt',
#   updated_at: 'updatedAt'

        $variables = ['data' => json_encode([
            'vipNo' => '18611367408',
            'mobile' => '18611367408'
        ], JSON_UNESCAPED_UNICODE)];

        $variables = ['data' => [
            'vipNo' => '18611367408',
            'mobile' => '18611367408'
        ]];

        $response = $client->query($query, $variables);

    }

    public function testmportPocketNoirTransaction()
    {
//        mutation($data: JSON!){
//        importPocketNoirTransaction(data: $data) {
//            isOk
//        result
//      }
//    }

        # const keys = {
#   // 订单信息
#   单据编号: 'orderNo',
#   单据时间: 'sheetTime',
#   制单时间: 'orderMadeTime',
#   过账时间: 'postingTime',
#   订单金额: 'orderTotalAmount',
#   商品数量: 'orderTotalQuantity',
#   门店编号: 'shopNo',
#   门店名称: 'shopName',
#   pos编号: 'posNo',
#   pos名称: 'posName',
#   往来单位编号: 'intercourseUnitNo',
#   往来单位名称: 'intercourseUnitName',
#   收银员编号: 'cashierNo',
#   收银员名称: 'cashierName',
#   会员卡号: 'vipNo',
#   会员名称: 'vipName',
#   会员电话: 'vipPhone',
#   单据类型: 'orderType',
#   小票流水号: 'receiptNo',
#   备注: 'orderMemo',
#   经手人: 'operateShop',
#   业务员名称: 'salerName',
#   业务员编号: 'salerNo',
#   付款方式: 'paymentMethod',

#   // 订单商品明细信息
#   商品编号: 'commodityCode',
#   商品名称: 'commodityName',
#   商品规格: 'commoditySpec',
#   基本计量单位: 'basicUnit',
#   // 基本单位数量
#   数量: 'quantity',
#   换算关系: 'conversionRelation',
#   自定义辅助数量: 'customizedAssistantQuantity',
#   原价: 'originalPrice',
#   原价总额: 'originalTotalPrice',
#   折扣: 'discount',
#   折扣总额: 'discountTotalPrice',
#   售价: 'realPrice',
#   售价总额: 'realTotalPrice',
#   促销名称: 'discountName',
#   价格信息: 'priceTypeMemo',
# };
    }
}