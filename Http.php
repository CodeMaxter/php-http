<?php

/**
 * This class allows to make HTTP requests
 *
 * @author Alvaro José Agámez Licha - @CodeMaxter
 * @version 0.2
 */
class Http
{
    
    private $headers = null;

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
            'noBody' => CURLOPT_NOBODY,
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

        // if data is present, we set the data in the curl options
        if (true === is_array($data) && count($data) > 0) {
            $options['data'] = http_build_query($data);
        }

        // init the curl session
        $curl = curl_init();

        // build the options
        $requestOptions = $this->buildOptions($options);

        // set the curl options
        curl_setopt_array($curl, $requestOptions);

        // execute the curl request
        $response = curl_exec($curl);

        // very important, close the curl request after complete
        curl_close($curl);

        if (true === $options['returnHeader']) {
            list($this->headers, $response) = explode("\r\n\r\n", $response, 2);
        }
        return trim($response);
    }

    public function delete($url, array $data, array $options = [])
    {
        $options['customHeader'] = 'DELETE';

        $requestOptions = array_merge($this->defaultOptions, $options);
        return $this->request($url, $data, $requestOptions);
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
        $defaultOptions = $this->defaultOptions;

        // If scheme is https we need two more curl options
        if ('https' === parse_url($url)['scheme']) {
            $defaultOptions['sslVerifyHost'] = false;
            $defaultOptions['sslVerifyPeer'] = false;
        }

        // merge default options  with the user options for get request
        $requestOptions = array_merge($defaultOptions, $options);

        return $this->request($url, $data, $requestOptions);
    }

    public function getHeaders()
    {
        return $this->headers;
    }

        /**
     * 
     * @param string $url
     * @param array $data
     * @param array $options
     * @return string
     */
    public function head($url, array $data = [], array $options = [])
    {
        $options['customHeader'] = 'HEAD';
        $options['noBody'] = true;
        $options['returnHeader'] = true;

        $requestOptions = array_merge($this->defaultOptions, $options);
        $this->request($url, $data, $requestOptions);
        return $this->headers;
    }

    /**
     * 
     * @param string $url
     * @param array $data
     * @param array $options
     * @return string
     */
    public function options($url, array $data = [], array $options = [])
    {
        $options['customHeader'] = 'OPTIONS';

        $requestOptions = array_merge($this->defaultOptions, $options);
        return $this->request($url, $data, $requestOptions);
    }

        /**
     * 
     * @param string $url
     * @param array $data
     * @param array $options
     * @return string
     */
    public function patch($url, array $data = [], array $options = [])
    {
        return false;
    }

    /**
     * 
     * @param string $url
     * @param array $data
     * @param array $options
     * @return string
     */
    public function post($url, array $data, array $options = [])
    {
        // we set the post curl option to true
        $options['post'] = true;

        $requestOptions = array_merge($this->defaultOptions, $options);
        return $this->request($url, $data, $requestOptions);
    }

    /**
     * 
     * @param string $url
     * @param array $data
     * @param array $options
     * @return string
     */
    public function put($url, array $data, array $options = [])
    {
        $options['customHeader'] = 'PUT';

        $requestOptions = array_merge($this->defaultOptions, $options);
        return $this->request($url, $data, $requestOptions);
    }

}
