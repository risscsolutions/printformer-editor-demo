<?php
require __DIR__ . './../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Uri;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

try {
    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../');
    $dotenv->safeLoad();

    $PRINTFORMER_USER = getenv('PRINTFORMER_USER');
    $PRINTFORMER_BEARER_TOKEN = getenv('PRINTFORMER_BEARER_TOKEN');
    $PRINTFORMER_URL = getenv('PRINTFORMER_URL');
    $PRINTFORMER_CLIENT_IDENTIFIER = getenv('PRINTFORMER_CLIENT_IDENTIFIER');
    $PRINTFORMER_TEMPLATE_IDENTIFIER = getenv('PRINTFORMER_TEMPLATE_IDENTIFIER');
    $PRINTFORMER_INTENT = getenv('PRINTFORMER_INTENT');

    // Create JWT config
    $client = new Client();
    $configuration = Configuration::forSymmetricSigner(
        new Sha256(),
        InMemory::base64Encoded(base64_encode($PRINTFORMER_BEARER_TOKEN))
    );

    try {
        $response = $client->request('POST', $PRINTFORMER_URL . '/api-ext/draft', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $PRINTFORMER_BEARER_TOKEN
            ],
            'form_params' => [
                'templateIdentifier' => $PRINTFORMER_TEMPLATE_IDENTIFIER,
                'user_identifier' => $PRINTFORMER_USER,
                'intent' => $PRINTFORMER_INTENT
            ]
        ]);

        $body = json_decode($response->getBody());
        $draftHash = $body->data->draftHash;

        try {
            $now = new DateTimeImmutable();
            $token = $configuration->builder()
                ->withHeader('jti', md5(random_bytes(64)))
                ->issuedAt($now)
                ->issuedBy($PRINTFORMER_URL)
                ->expiresAt($now->add(new DateInterval('P1W')))
                ->withClaim('user', $PRINTFORMER_USER)
                ->withClaim('client', $PRINTFORMER_CLIENT_IDENTIFIER)
                ->withClaim('redirect',
                    (string)(new Uri($PRINTFORMER_URL))
                        ->withPath('/editor/' . $draftHash . '/embed')
                )
                ->getToken($configuration->signer(), $configuration->signingKey());

            $jwt = $token->toString();
            $response = $client->request('GET', $PRINTFORMER_URL . '/auth?jwt=' . $jwt, [
                'allow_redirects' => false
            ]);

            # html content haxor Anno 2002
            echo str_replace('__REPLACE_ME_WITH_PFURL__', $response->getHeader('Location')[0], file_get_contents('index.html'));
        } catch (Exception $e) {
            echo $e->getMessage();
            exit(0);
        }
    } catch (GuzzleException $e) {
        echo $e->getMessage();
        exit(0);
    }
} catch (RuntimeException $e) {
    echo $e->getMessage();
    exit(0);
}
