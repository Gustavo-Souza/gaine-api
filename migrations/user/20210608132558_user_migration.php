<?php

namespace User;

use Phoenix\Migration\AbstractMigration;

class UserMigration extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('users')
            ->addColumn('firebase_auth_id', 'string', ['length' => 150])
            ->addColumn('firebase_auth_name', 'string', ['length' => 150])
            ->addColumn('fcm_device_id', 'string', ['length' => 400])
            ->addColumn('notification', 'boolean', ['default' => true])
            ->addColumn('created_at', 'datetime', ['default' => 'now()'])
            ->addColumn('updated_at', 'datetime', ['default' => 'now()'])
            ->create();
    }

    protected function down(): void
    {
        $this->table('users')->drop();
    }
}
