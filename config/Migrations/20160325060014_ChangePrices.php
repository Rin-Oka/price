<?php
use Migrations\AbstractMigration;

class ChangePrices extends AbstractMigration
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
        $table = $this->table('prices');
        $table->changeColumn('uploaded', 'datetime', [
            'default' => null,
            'limit' => null,
            'null' => true,
            ])
            ->update();
    }

    public function down()
    {
        $table = $this->table('prices');
        $table->changeColumn('uploaded', 'date', [
            'default' => null,
            'limit' => null,
            'null' => true,
        ])
            ->update();
    }
}
