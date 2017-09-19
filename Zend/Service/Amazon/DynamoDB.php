<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Amazon_DynamoDB
 * @version    $Id: DynamoDB.php 2017-09-19 05:35:18 Zhao<303685256@qq.com> $
 */

/**
 * @see Zend_Service_Amazon_Abstract
 */
#require_once 'Zend/Service/Amazon/Abstract.php';

/**
 * Class for connecting to the Amazon DynamoDB
 *
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Amazon_DynamoDB
 * @author     Zhang Zhao<303685256@qq.com>
 */
class Zend_Service_Amazon_DynamoDB extends Zend_Service_Amazon_Abstract
{
    /**
     * @var string Amazon region
     */
    protected $_endpoint;

    /**
     * @var string API version
     */
    protected $_version = '20120810';
    
    /**
     * Create DynamoDB client.
     *
     * @param  string $access_key
     * @param  string $secret_key
     * @param  string $region
     * @param  string $version
     * @return void
     */
    public function __construct($accessKey=null, $secretKey=null, $region=null, $version=null)
    {
        parent::__construct($accessKey, $secretKey);

        if(! ($this->_endpoint = Zend_Service_Amazon_DynamoDB_Region::getEndpoint($region)) ) {
            #require_once 'Zend/Service/Amazon/DynamoDB/Exception.php';
            throw new Zend_Service_Amazon_DynamoDB_Exception("DynamoDB region were not supplied");
        }

        if($version) {
            $this->_version = $version;
        }
    }

    /**
     * Returns information about the table
     *
     * @param  string $tableName
     * @return string|false
     * @see    http://docs.aws.amazon.com/zh_cn/amazondynamodb/latest/APIReference/API_DescribeTable.html
     */
    public function describeTable($tableName)
    {
        $response = $this->_makeRequest('DescribeTable', array('TableName' => $tableName));

        if ($response->getStatus() != 200) {
            return false;
        }

        return $response->getBody();
    }

    /**
     * Adds a new table to DynamoDB
     *
     * @param  string $tableName
     * @param  array  $request
     * @return string|false
     * @see    http://docs.aws.amazon.com/zh_cn/amazondynamodb/latest/APIReference/API_CreateTable.html#DDB-CreateTable-request-AttributeDefinitions
     */
    public function createTable($tableName, $request)
    {
        $request = array_merge($request, array('TableName' => $tableName));
        $response = $this->_makeRequest('CreateTable', $request);

        if ($response->getStatus() != 200) {
            return false;
        }

        return $response->getBody();        
    }

    /**
     * Deletes a table and all of its items
     *
     * @param  string $tableName
     * @return boolean
     * @see    http://docs.aws.amazon.com/zh_cn/amazondynamodb/latest/APIReference/API_DeleteTable.html
     */
    public function deleteTable($tableName)
    {
        $response = $this->_makeRequest('DeleteTable', array('TableName' => $tableName));

        if ($response->getStatus() != 200) {
            return false;
        }

        return true;        
    }

    /**
     * Returns an array of table names associated with the current account and endpoint
     *
     * @param  string $tableName
     * @param  array  $request
     * @return string|false
     * @see    http://docs.aws.amazon.com/zh_cn/amazondynamodb/latest/APIReference/API_ListTables.html
     */
    public function listTables($request = array())
    {
        $response = $this->_makeRequest('ListTables', $request);

        if ($response->getStatus() != 200) {
            return false;
        }

        return $response->getBody();        
    }

    /**
     * Creates a new item, or replaces an old item with a new item
     *
     * @param  string $table
     * @param  array  $request
     * @return boolean
     * @see    http://docs.aws.amazon.com/zh_cn/amazondynamodb/latest/APIReference/API_PutItem.html
     */
    public function putItem($table, $request)
    {
        $request = array_merge($request, array('TableName' => $table));

        $response = $this->_makeRequest('PutItem', $request);

        if ($response->getStatus() != 200) {
            return false;
        }

        return true;        
    }

    /**
     * Returns a set of attributes for the item with the given primary key.
     *
     * @param  string $table
     * @param  array  $request
     * @return string|false
     * @see    http://docs.aws.amazon.com/zh_cn/amazondynamodb/latest/APIReference/API_GetItem.html
     */
    public function getItem($table, $request)
    {
        $request = array_merge($request, array('TableName' => $table));

        $response = $this->_makeRequest('GetItem', $request);

        if ($response->getStatus() != 200) {
            return false;
        }

        return $response->getBody();        
    }

    /**
     * Deletes a single item in a table by primary key
     *
     * @param  string $table
     * @param  array  $request
     * @return boolean
     * @see    http://docs.aws.amazon.com/zh_cn/amazondynamodb/latest/APIReference/API_DeleteItem.html
     */
    public function deleteItem($table, $request)
    {
        $request = array_merge($request, array('TableName' => $table));

        $response = $this->_makeRequest('DeleteItem', $request);

        if ($response->getStatus() != 200) {
            return false;
        }

        return true;        
    }
    
    /**
     * Make a request to Amazon DynamoDB
     *
     * @param  string $action
     * @param  array  $params
     * @return Zend_Http_Response
     */
    public function _makeRequest($action, $params = array())
    {
        $headers = array();
        $headers['host'] = $this->_endpoint;
        $headers['x-amz-date'] = gmdate(DATE_RFC1123, time());
        $headers['x-amz-target'] = "DynamoDB_{$this->_version}.{$action}";
        $headers['content-type'] = 'application/x-amz-json-1.0';
        ksort($headers);

        $body = json_encode($params);
        if ($body === '[]') {
            $body = '{}';
        }
        $this->addSignature($headers, $body);
        $url = 'http://' . $this->_endpoint . '/';

        $client = self::getHttpClient();

        $client->resetParameters(true);
        $client->setUri($url);
        $client->setAuth(false);
        $client->setRawData($body);

        $client->setHeaders($headers);

         do {
            $retry = false;

            $response = $client->request('POST');//print_r($response);exit;
            $response_code = $response->getStatus();

            // Some 5xx errors are expected, so retry automatically
            if ($response_code >= 500 && $response_code < 600 && $retry_count <= 5) {
                $retry = true;
                $retry_count++;
                sleep($retry_count / 4 * $retry_count);
            }
            else if ($response_code == 307) {
                // Need to redirect, new S3 endpoint given
                // This should never happen as Zend_Http_Client will redirect automatically
            }
            else if ($response_code == 100) {
                // echo 'OK to Continue';
            }
        } while ($retry);

        return $response;
    }

    /**
     * Add the DynamoDB Authorization signature to the request headers
     *
     * @param  array &$headers
     * @param  array $body
     * @return string
     */
    protected function addSignature(&$headers, $body)
    {
        $canonical_string = '';
        foreach ($headers as $k => $v) {
            $canonical_string .= "{$k}:{$v}\n";
        }

        $string_to_sign = "POST\n/\n\n{$canonical_string}\n{$body}";
        $hash_to_sign = hash('sha256', $string_to_sign, true);
        $signature = base64_encode(hash_hmac('sha256', $hash_to_sign, $this->_getSecretKey(), true));
        $auth_params = array();
        $auth_params['AWSAccessKeyId'] = $this->_getAccessKey();
        $auth_params['Algorithm'] = 'HmacSHA256';
        $auth_params['SignedHeaders'] = join(';', array_keys($headers));
        $auth_params['Signature'] = $signature;
        $canonical_auth_string = array();
        foreach ($auth_params as $k => $v) {
            $canonical_auth_string[] = "{$k}={$v}";
        }
        $canonical_auth_string = join(',', $canonical_auth_string);
        $canonical_auth_string = "AWS3 {$canonical_auth_string}";
        $headers['x-amzn-authorization'] = $canonical_auth_string;

        return $canonical_auth_string;
    }
}