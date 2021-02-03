<?php

namespace Interkassa\Tests;

use Interkassa\Helper\Signature;
use PHPUnit\Framework\TestCase;

class SignatureTest extends TestCase
{
    /**
     * @dataProvider providerWithoutSignature
     */
    public function testMakeSignature(array $data, string $secretKey, string $expectedSignature, string $algorithm)
    {
        $signatureHelper = new Signature();
        $signature = $signatureHelper->makeSignature($data, $secretKey, $algorithm);

        $this->assertEquals($expectedSignature, $signature);
    }

    /**
     * @dataProvider signaturesProvider
     */
    public function testCheckSignature(array $data, string $secretKey, string $algorithm, bool $expectedResult)
    {
        $signatureHelper = new Signature();
        $isEqual = $signatureHelper->checkSignature($data, $secretKey, $algorithm);

        $this->assertEquals($expectedResult, $isEqual);
    }

    /**
     * @return array[]
     */
    public function providerWithoutSignature(): array
    {
        return [
            [
                [
                    'amount' => '20',
                    'currency' => 'UAH',
                    'description' => 'Test',
                ],
                'secret',
                '7cI9rBdQPpPTI5LLPa7QDw==',
                'md5',
            ],
            [
                [
                    'amount' => '20',
                    'currency' => 'UAH',
                    'description' => 'Test',
                ],
                'secret',
                'seBy/eSLWfKc7Y52JDlHnlWEBZqc9hipLkrQuGLIwHg=',
                'sha256',
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function signaturesProvider(): array
    {
        return [
            [
                [
                    'amount' => '20',
                    'currency' => 'UAH',
                    'description' => 'Test',
                    'ik_sign' => '7cI9rBdQPpPTI5LLPa7QDw==',
                ],
                'secret',
                'md5',
                true,
            ],
            [
                [
                    'amount' => '20',
                    'currency' => 'UAH',
                    'description' => 'Test',
                    'ik_sign' => 'seBy/eSLWfKc7Y52JDlHnlWEBZqc9hipLkrQuGLIwHg=',
                ],
                'secret',
                'sha256',
                true,
            ],
            [
                [
                    'amount' => '20',
                    'currency' => 'UAH',
                    'description' => 'Test',
                    'ik_sign' => 'wrong_signature==',
                ],
                'secret',
                'md5',
                false,
            ],
            [
                [
                    'amount' => '20',
                    'currency' => 'UAH',
                    'description' => 'Test',
                    'ik_sign' => 'wrong_signature==',
                ],
                'secret',
                'sha256',
                false,
            ],
            [
                [
                    'amount' => '20',
                    'currency' => 'UAH',
                    'description' => 'Test',
                ],
                'secret',
                'sha256',
                true,
            ],
        ];
    }
}
