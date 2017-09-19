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
 * @version    $Id: Region.php 2017-09-19 01:37:59 Zhao<303685256@qq.com> $
 */

/**
 * @category   Zend
 * @package    Zend_Service
 * @author     Zhang Zhao<303685256@qq.com>
 */
class Zend_Service_Amazon_DynamoDB_Region
{
	public static function lists()
	{
		return array(
			'us-east-2'			=> 'dynamodb.us-east-2.amazonaws.com',
			'us-east-1'			=> 'dynamodb.us-east-1.amazonaws.com',
			'us-west-1'			=> 'dynamodb.us-west-1.amazonaws.com',
			'us-west-2'			=> 'dynamodb.us-west-2.amazonaws.com',
			'ca-central-1'		=> 'dynamodb.ca-central-1.amazonaws.com',
			'ap-south-1'		=> 'dynamodb.ap-south-1.amazonaws.com',
			'ap-northeast-2'	=> 'dynamodb.ap-northeast-2.amazonaws.com',
			'ap-southeast-1'	=> 'dynamodb.ap-southeast-1.amazonaws.com',
			'ap-southeast-2'	=> 'dynamodb.ap-southeast-2.amazonaws.com',
			'ap-northeast-1'	=> 'dynamodb.ap-northeast-1.amazonaws.com',
			'eu-central-1'		=> 'dynamodb.eu-central-1.amazonaws.com',
			'eu-west-1'			=> 'dynamodb.eu-west-1.amazonaws.com',
			'eu-west-2'			=> 'dynamodb.eu-west-2.amazonaws.com',
			'sa-east-1'			=> 'dynamodb.sa-east-1.amazonaws.com',
		);
	}

	public static function getEndpoint($region)
	{
		$lists = self::lists();

		return isset($lists[$region]) ? $lists[$region] : false;
	}
}
