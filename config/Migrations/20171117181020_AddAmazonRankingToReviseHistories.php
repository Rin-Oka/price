<?php
use Migrations\AbstractMigration;

class AddAmazonRankingToReviseHistories extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('revise_histories');
        $table
            ->addColumn('amazon_ranking', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
                'after' => 'asin',
            ])
            ->addColumn('purchase_channel', 'string', [
                'default' => '',
                'limit' => 20,
                'null' => false,
                'after' => 'listing_date',
            ])
            ->addColumn('all_offers_count', 'integer', [
                'default' => 0,
                'limit' => 11,
                'null' => false,
                'after' => 'user_name',
            ])
            ->update();
    }
}
