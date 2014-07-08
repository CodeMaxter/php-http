<?php

/**
 * This class allows to make HTTP requests
 *
 * @author Alvaro José Agámez Licha - @CodeMaxter
 * @version 0.2
 */
class Http
{

    protected $defaultOptions = null;

    protected $optionMap = null;

    public function __construct()
    {
        $this->defaultOptions = [
            'returnHeader' => false,
            'returnTransfer' => true,
            'timeout' => 30,
            'userAgent' => 'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0',
        ];

        $this->optionMap = [
            'auth' => CURLOPT_USERPWD,
            'customHeader' => CURLOPT_CUSTOMREQUEST,
            'data' => CURLOPT_POSTFIELDS,
            'header' => CURLOPT_HTTPHEADER,
            'post' => CURLOPT_POST,
            'returnHeader' => CURLOPT_HEADER,
            'returnTransfer' => CURLOPT_RETURNTRANSFER,
            'sslVerifyHost' => CURLOPT_SSL_VERIFYHOST,
            'sslVerifyPeer' => CURLOPT_SSL_VERIFYPEER,
            'timeout' => CURLOPT_CONNECTTIMEOUT,
            'url' => CURLOPT_URL,
            'userAgent' => CURLOPT_USERAGENT,
        ];
    }

    /**
     * 
     * @param array $options
     * @return array
     */
    protected function buildOptions(array $options)
    {
        $result = [];
        foreach ($options as $key => $value) {
            if ('auth' === $key) {
                $value = $value['username'] . ':' . $value['password'];
            }

            $result[$this->optionMap[$key]] = $value;
        }

        return $result;
    }

    /**
     * 
     * @param string $url
     * @throws Exception
     */
    protected function validateUrl($url)
    {
        // throw some nice exception
        if ('' === $url) {
            throw new Exception("URL param can't be empty");
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)){
            throw new Exception("Invalid URL");
        }
    }

    /**
     * 
     * @param string $url
     * @param array $data
     * @param array $options
     * @return string
     */
    protected function request($url, array $data = [], array $options = [])
    {
        // validate url
        $this->validateUrl($url);

        // set the request url
        $options['url'] = $url;

        // init the curl session
        $curl = curl_init();

        // build the options
        $requestOptions = $this->buildOptions($options);
//        var_dump($requestOptions);die;

        // set the curl options
        curl_setopt_array($curl, $requestOptions);

        // execute the curl request
        $response = curl_exec($curl);

        // very important, close the curl request after complete
        curl_close($curl);

        return $response;
    }

    /**
     * Make a get request
     * 
     * @param string $url
     * @param array $data
     * @param array $options
     * @return string
     */
    public function get($url, array $data = [], array $options = [])
    {
        // validate url
        $this->validateUrl($url);

        // init the curl session
        $curl = curl_init();

        // build query params from array data
        $query = http_build_query($data);

        // build the complete url with the query params
        $url .= $query !== '' ? '?' . $query : '';

        // merge default options  with the user options for get request
        $options = array_merge($this->defaultOptions, $options);

        // If scheme is https we need two more curl options
        $scheme = parse_url($url)['scheme'];
        if ('https' === $scheme) {
            $options['sslVerifyHost'] = false;
            $options['sslVerifyPeer'] = false;
        }

        $requestOptions = array_merge($this->defaultOptions, $options);
        return $this->request($url, $data, $requestOptions);
    }

    /**
     * 
     * @param string $url
     * @param array $data
     * @param array $options
     */
    public function post($url, array $data, array $options = [])
    {
        // we set the post curl option to true
        $options['post'] = true;

        // if data is present, we set the data in the curl options
        if (true === is_array($data) && count($data) > 0) {
            $options['data'] = http_build_query($data);
        }

        $requestOptions = array_merge($this->defaultOptions, $options);
        return $this->request($url, $data, $requestOptions);
    }

    public function put($url, array $data, array $options = [])
    {
        $options['customHeader'] = 'PUT';

        // if data is present, we set the data in the curl options
        if (true === is_array($data) && count($data) > 0) {
            $options['data'] = http_build_query($data);
        }

        $requestOptions = array_merge($this->defaultOptions, $options);
        return $this->request($url, $data, $requestOptions);
    }

    public function delete($url, array $data, array $options = [])
    {
        $options['customHeader'] = 'DELETE';

        // if data is present, we set the data in the curl options
        if (true === is_array($data) && count($data) > 0) {
            $options['data'] = http_build_query($data);
        }

        $requestOptions = array_merge($this->defaultOptions, $options);
        return $this->request($url, $data, $requestOptions);
    }
}
