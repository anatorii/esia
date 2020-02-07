<?php

namespace tests\unit;

use Esia\Config;
use Esia\OpenId;
use Esia\Signer\CliSignerPKCS7;
use GuzzleHttp\Psr7\Response;

class OpenIdCliGostOpensslTest extends OpenIdTest {
    /**
     * @throws \Esia\Exceptions\InvalidConfigurationException
     */
    public function setUp()
    {
        $this->config = [
            'clientId' => 'INSP03211',
            'redirectUrl' => 'http://my-site.com/response.php',
            'portalUrl' => 'https://esia-portal1.test.gosuslugi.ru/',
            'privateKeyPath' => codecept_data_dir('server-gost.key'),
            'privateKeyPassword' => 'test',
            'certPath' => codecept_data_dir('server-gost.crt'),
            'tmpPath' => codecept_log_dir(),
            'useCli'  => true,
            'useGost' => true
        ];

        $config = new Config($this->config);

        $this->openId = new OpenId($config);
        $this->openId->setSigner(new CliSignerPKCS7(
            $this->config['certPath'],
            $this->config['privateKeyPath'],
            $this->config['privateKeyPassword'],
            $this->config['tmpPath'],
            $this->config['useGost']
        ));
    }

    public function testGetTokenWithGost() {
        $config = new Config($this->config);

        $oid = '123';
        $oidBase64 = base64_encode('{ "urn:esia:sbj_id" : ' . $oid . '}');

        $client = $this->buildClientWithResponses([
            new Response(200, [], '{ "access_token": "test.' . $oidBase64 . '.test"}'),
        ]);
        $openId = new OpenId($config, $client);
        $openId->setSigner(new CliSignerPKCS7(
            $this->config['certPath'],
            $this->config['privateKeyPath'],
            $this->config['privateKeyPassword'],
            $this->config['tmpPath'],
            $this->config['useGost']
        ));
        $token = $openId->getToken('test');
        $this->assertNotEmpty($token);
        $this->assertSame($oid, $openId->getConfig()->getOid());
    }
}