<?php

namespace common\components\vkAPI;

class AuthorizeVK {

    protected $client_id;
    protected $groups_id;
    protected $redirect_uri;
    protected $version;
    public $url;


    public function __construct($client_id, $groups_id, $redirect_uri, $version = '5.67')
    {
        $this->client_id = $client_id;
        $this->groups_id = $groups_id;
        $this->redirect_uri = $redirect_uri;
        $this->version = $version;
        $this->url = 'https://oauth.vk.com/authorize?client_id='.$this->client_id.'&groups_ids='.$this->groups_id.'&display=page&redirect_uri='.$this->redirect_uri.'&scope=messages&response_type=code&v='.$this->version.'';
    }

    public function BuildRequestAccessTocken($secret_key, $code)
    {
        $this->url = 'https://oauth.vk.com/access_token?client_id='.$this->client_id.'&client_secret='.$secret_key.'&redirect_uri='.$this->redirect_uri.'&code='.$code.'';
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function exec()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $this->error = $err;
        } else {
            return $response;
        }
    }

}