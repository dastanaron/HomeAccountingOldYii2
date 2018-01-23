<?php

namespace common\components\vkAPI;

/**
 * Class ApiMethods
 * @package common\components\vkAPI
 */
class ApiMethods{

    /**
     * @var string
     */
    protected $userToken = '';
    /**
     * @var string
     */
    protected $api = 'https://api.vk.com/method/';
    /**
     * @var mixed
     */
    protected $error;

    /**
     * ApiMethods constructor.
     * @param $userToken
     */
    function __construct($userToken)
    {
        $this->userToken = $userToken;
    }

    /**
     * @return $this
     */
    public function getProfileInfo()
    {
        $this->api .= 'account.getProfileInfo';
        return $this;
    }

    /**
     * @return $this
     */
    public function AccountSetOnline()
    {
        $this->api .= 'account.setOnline?voip=0';
        return $this;
    }

    /**
     * @param $userid
     * @param $message
     * @param $peer_id
     * @param bool $repeat
     * @return $this
     */
    public function SendMessageUser($userid, $message, $peer_id, $repeat = false)
    {
        if (empty($peer_id)) {
            $peer_id = $userid;
        }

        $this->api .= 'messages.send?user_id='.$userid.'&peer_id='.$peer_id.'&message='.urlencode($message).'';

        if($repeat === true) {
            $random_id = crc32($userid .$message);

            $this->api .= '&random_id='.$random_id.'';
        }

        return $this;
    }

    /**
     * @param bool $response
     * @return bool|mixed
     */
    public function APIExecute($response = true)
    {
        if ($this->addAccessTocken() && $this->addVersionApi()) {

            if ($response) {
                return $this->CURLExec();
            }
            else {
                return json_decode($this->CURLExec(), true);
            }
        }
        else {
            $this->error = 'Ошибка запроса не добавлены обязательные свойства запроса';
            return false;
        }
    }

    /**
     * @param int $time_offset
     * @return $this
     */
    public function getMessage($time_offset = 60)
    {
        $this->api .= 'messages.get?time_offset='.$time_offset.'';
        return $this;
    }

    /**
     * @param $users_id
     * @return $this
     */
    public function getUsers($users_id)
    {
        $this->api .= 'users.get?user_ids='.$users_id.'';
        return $this;
    }

    /**
     * @return bool
     */
    protected function addAccessTocken()
    {
        if (preg_match('#method/(.+)#', $this->api)){

            if (preg_match('#\?#', $this->api)) {
                $this->api .= '&access_token='.$this->userToken;
            }
            else {
                $this->api .= '?access_token='.$this->userToken;
            }
            return true;

        }
        else {
            $this->error = 'Ошибка не вызван ни один метод апи';
            return false;
        }
    }

    /**
     * @return bool
     */
    protected function addVersionApi()
    {
        $this->api .= '&v=5.64';
        return true;
    }

    /**
     * @return mixed
     */
    protected function CURLExec()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api,
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

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getAPI()
    {
        return $this->api;
    }

    /**
     * @return $this
     */
    public function ClearAPI() {
        $this->api = 'https://api.vk.com/method/';
        return $this;
    }

}