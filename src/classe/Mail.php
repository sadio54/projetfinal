<?php

namespace App\classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{ 
    private $api_key = '30906cc2ec1c17006705b170d7212498 ';
    private $api_key_secret = '8fce47f6e69ce4c7e6b42d6dda940a9e';

    public function send($to_email, $to_name, $subject , $content)
    {
        $mj = new Client($this -> api_key, $this -> api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "sadiofof54@gmail.com",
                        'Name' => "Nation Baller"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 5015728,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && dd($response->getData());  
    }
 }