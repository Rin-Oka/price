<?php
use Migrations\AbstractMigration;

class AddDisplayNameToCategories extends AbstractMigration
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
        $table = $this->table('categories');
        $table->addColumn('display_name', 'string', [
            'default' => '',
            'limit' => 40,
            'null' => false,
            'after' => 'name',
        ])
            ->update();
    }
}
