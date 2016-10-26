<?php
/**
 * Created by PhpStorm.
 * User: helmut
 * Date: 2016/10/26
 * Time: PM11:32
 */

namespace App\Services;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp;

class LeetCode
{
    protected $url = "https://leetcode.com/problems/random-one-question/";


    private function getContent($url)
    {

        $finalUrl = '';

        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', $url, [
            'allow_redirects' =>
                [
                    'on_redirect' => function (
                        RequestInterface $request,
                        ResponseInterface $response,
                        UriInterface $uri
                    ) use (&$finalUrl) {
                         $finalUrl = $uri;
                    }


                ]]);
        return ['content' => $res->getBody()->getContents(), 'url' => $finalUrl];

    }


    public function pickup()
    {

        $html = $this->getContent($this->url);
        $crawler = new Crawler($html['content']);

        $body =  $crawler->filter('.question-content')->text();

        $pos = strpos($body,'Subscribe to see which companies');
        $body = substr($body,0,$pos);

        return $body."\n".$html['url'];
    }
}