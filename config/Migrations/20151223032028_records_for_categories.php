<?php
use Migrations\AbstractMigration;

class RecordsForCategories extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $categoriesTable = \Cake\ORM\TableRegistry::get('categories');
        $category = $categoriesTable->newEntity([
            'name' => 'Book',
            'display_name' => '本',
            'shipping_price' => 257,
            'amazon_fixed_commission' => 60,
            'amazon_commission' => 0.162
        ]);
        $categoriesTable->save($category);
        $category = $categoriesTable->newEntity([
            'name' => 'Music',
            'display_name' => 'CD',
            'shipping_price' => 350,
            'amazon_fixed_commission' => 60,
            'amazon_commission' => 0.162
        ]);
        $categoriesTable->save($category);
        $category = $categoriesTable->newEntity([
            'name' => 'DVD',
            'display_name' => 'DVD',
            'shipping_price' => 350,
            'amazon_fixed_commission' => 60,
            'amazon_commission' => 0.162
        ]);
        $categoriesTable->save($category);
        $category = $categoriesTable->newEntity([
            'name' => 'Video',
            'display_name' => 'ビデオ',
            'shipping_price' => 391,
            'amazon_fixed_commission' => 60,
            'amazon_commission' => 0.162
        ]);
        $categoriesTable->save($category);
        $category = $categoriesTable->newEntity([
            'name' => 'Toy',
            'display_name' => 'おもちゃ',
            'shipping_price' => 0,
            'amazon_fixed_commission' => 60,
            'amazon_commission' => 0.108
        ]);
        $categoriesTable->save($category);
        $category = $categoriesTable->newEntity([
            'name' => 'others',
            'display_name' => 'その他',
            'shipping_price' => 0,
            'amazon_fixed_commission' => 60,
            'amazon_commission' => 0.162
        ]);
        $categoriesTable->save($category);
    }

    public function down()
    {
        $categoriesTable = \Cake\ORM\TableRegistry::get('categories');
        /** @var \Cake\Database\Connection $connection */
        $connection = $categoriesTable->connection();
        $connection->query('TRUNCATE TABLE `categories`;');
    }
}
