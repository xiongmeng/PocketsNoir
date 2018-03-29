<?php

namespace App\Services;

use App\Vip;
use GuzzleHttp\Client;
use Softonic\GraphQL\ResponseBuilder;

class TianShuService
{
    public static function syncVip(Vip $vip)
    {
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

        $variables = ['data' => [
            'vipNo' => $vip->mobile,
            'mobile' => $vip->mobile,
            'vipLevel' => Vip::$GuanJiaPoCardMaps[$vip->card],
            'createdAt' => $vip->created_at,
            'updatedAt' => $vip->updated_at
        ]];

        $response = self::query($query, $variables);
        return $response;
    }

    private static function query($query, $variables)
    {
        $client = new Client(['base_uri' => env('TIANSHU_HOST')]);
        $responseBuilder = new ResponseBuilder();

        $options = [
            'json' => [
                'query' => $query,
                'variables' => $variables,
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . env('TIANSHU_TOKEN')
            ]
        ];

        $response = $client->request('POST', '', $options);
        $graphQLResponse = $responseBuilder->build($response);

        if($graphQLResponse->hasErrors()){
            $errors = $graphQLResponse->getErrors();

            $count = count($errors);
            $firstMsg = $errors[0]['message'];
            throw new \Exception("TianShuRequestErrors:{$count},firstMsg:{$firstMsg}");
        }

        return $graphQLResponse->getData();
    }
}

