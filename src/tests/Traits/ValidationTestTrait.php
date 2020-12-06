<?php
declare(strict_types=1);

namespace Tests\Traits;

use App\Http\Requests\Interfaces\FormRequestInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

trait ValidationTestTrait
{
    /**
     * @param FormRequestInterface $formRequest
     * @param array $data
     * @param array $expected
     */
    public function assertValidationRules(
        FormRequestInterface $formRequest,
        array $data,
        array $expected
    ) {
        // Validator を生成
        $validator = Validator::make(
            $data,
            $formRequest->rules(), //formRequest から バリデーションルール を取得
            $formRequest->messages(), //formRequest から カスタムエラーメッセージ を取得
            $formRequest->attributes() //formRequest から カスタムAttributes を取得
        );

        // assert
        $this->assertEquals($expected['isPass'], $validator->passes(), 'バリデーション結果(エラー有無)が期待値通りである事');
        foreach ($expected['errMsg'] as $itemName => $errMsgs) {
            $nameErrMsg = Arr::get($validator->getMessageBag()->getMessages(), $itemName);
            $this->assertNotNull($nameErrMsg, '期待した項目がバリデーションエラーになる事');
            foreach ($errMsgs as $msg) {
                $this->assertTrue(in_array($msg, $nameErrMsg), '期待値通りのエラーメッセージが返る事');
            }
        }
    }
}
