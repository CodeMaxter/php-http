<?php

/**
 * This class allows to make HTTP requests
 *
 * @author Alvaro José agámez Licha - @CodeMaxter
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
            'post' => CURLOPT_POST,
            'data' => CURLOPT_POSTFIELDS,
            'timeout' => CURLOPT_CONNECTTIMEOUT,
            'returnHeader' => CURLOPT_HEADER,
            'returnTransfer' => CURLOPT_RETURNTRANSFER,
            'url' => CURLOPT_URL,
            'userAgent' => CURLOPT_USERAGENT,
            'sslVerifyHost' => CURLOPT_SSL_VERIFYHOST,
            'sslVerifyPeer' => CURLOPT_SSL_VERIFYPEER,
        ];
    }

    protected function buildQuery($data)
    {
        $query = [];
        foreach ($data as $key => $value) {
            $query[] = $key . '=' . urlencode($value);
        }
        return implode('&', $query);
    }

    protected function buildOptions($options)
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
        $query = $this->buildQuery($data);

        // build the complete url with the query params
        $url .= $query !== '' ? '?' . $query : '';

        // merge default options  with the user options for get request
        $options = array_merge($this->defaultOptions, $options);
        $options['url'] = $url;

        // If scheme is https we need two more curl options
        $scheme = parse_url($url)['scheme'];
        if ('https' === $scheme) {
            $options['sslVerifyHost'] = false;
            $options['sslVerifyPeer'] = false;
        }

        // build the options
        $options = $this->buildOptions($options);

        // set the curl options
        curl_setopt_array($curl, $options);

        // execute the curl request
        $response = curl_exec($curl);

        // very important, close the curl request after complete
        curl_close($curl);

        return $response;
    }

    /**
     * 
     * @param string $url
     * @param array $data
     * @param array $options
     */
    public function post($url, array $data, array $options = [])
    {
        // validate url
        $this->validateUrl($url);

        // init the curl session
        $curl = curl_init();

        // merge default options  with the user options for get request
        $options = array_merge($this->defaultOptions, $options);
        $options['url'] = $url;

        // we set the post curl option to true
        $options['post'] = true;

        // if data is present, we set the data in the curl options
        if (is_array($data) && count($data) > 0) {
            $options['data'] = $data;
        }

        // build the options
        $options = $this->buildOptions($options);

        // set the curl options
        curl_setopt_array($curl, $options);

        // execute the curl request
        $response = curl_exec($curl);

        // very important, close the curl request after complete
        curl_close($curl);

        return $response;
    }
}
