<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InitScheme extends AbstractMigration
{
    /**
     * @return void
     */
    public function up()
    {
        $this
            ->table('users')
            ->addColumn('uuid', 'string', ['null' => false, 'length' => 36])
            ->addColumn('created', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(
                [
                    'uuid'
                ],
                [
                    'name' => 'i_uuid',
                    'unique' => true
                ]
            )
            ->save();
        
        $this
            ->table('contacts')
            ->addColumn('uid', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('name', 'string', ['null' => false, 'length' => 255])
            ->addColumn('phone', 'string', ['null' => false, 'length' => 11])
            ->addColumn('created', 'datetime', ['null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(
                [
                    'uid', 'created'
                ],
                [
                    'name' => 'i_uid_created'
                ]
            )
            ->addForeignKey(
                'uid',
                'users',
                'id',
                array('delete' => 'CASCADE', 'update' => 'NO_ACTION', 'constraint' => 'FK_contacts_uid')
            )
            ->create();
    }
    
    /**
     * @return void
     */
    public function down()
    {
        $this->dropTable('contacts');
        $this->dropSchema('users');
    }
}
