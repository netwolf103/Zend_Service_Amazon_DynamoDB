# Zend_Service_Amazon_DynamoDB
Amazon DynamoDB for Zend v1.12.3
<h2>How to use</h2>
<pre>
$db = new Zend_Service_Amazon_DynamoDB(AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, 'us-east-1');
$r = $db->describeTable('catalog_product_entity');
$r = $db->deleteTable('test');
$r = $db->putItem('catalog_product_entity', array('Item' => array('sku' => array('S' => 'rgg2'))) );
$r = $db->deleteItem('catalog_product_entity', array('Key' => array('sku' => array('S' => 'rgg2'))) );
$r = $db->createTable('test2',  array(
        'AttributeDefinitions' => array(
                array(
                        'AttributeName' => 'name', 
                        'AttributeType' => 'S'
                )
        ),
        'KeySchema' => array(
                array(
                        'AttributeName' => 'name',
                        'KeyType' => 'HASH'
                )
        ),
        'ProvisionedThroughput' => array(
                'ReadCapacityUnits' => 1,
                'WriteCapacityUnits' => 1
        )
) );*/

$r = $db->listTables();

$r = $db->getItem('catalog_product_entity', array(
        'Key' => array(
                'sku' => array(
                        'S' => 'test'
                ),
        ),
));
</pre>