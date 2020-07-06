<?php
use Migrations\AbstractMigration;

class AddCategoryToRevisingItemHeaders extends AbstractMigration
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
        $table = $this->table('revising_item_headers');
        $table
            ->addColumn('category', 'string', [
                'default' => 'Book',
                'limit' => 20,
                'null' => false,
                'after' => 'item_condition',
            ])
            ->update();
    }
}
