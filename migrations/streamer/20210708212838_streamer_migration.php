<?php

namespace Streamer;

use Phoenix\Database\Element\Index;
use Phoenix\Migration\AbstractMigration;

class StreamerMigration extends AbstractMigration
{
    protected function up(): void
    {
        /* $this->table('streamers')
            ->addColumn('code', 'string', ['length' => 8])
            ->addColumn('name', 'string', ['length' => 60])
            ->addColumn('created_at', 'datetime', ['default' => 'now()'])
            ->addColumn('updated_at', 'datetime', ['default' => 'now()'])
            ->addColumn('deleted_at', 'datetime')
            ->addIndex('code', Index::TYPE_UNIQUE)
            ->create(); */
        $this->execute('CREATE TABLE public.streamers (
            id serial PRIMARY KEY NOT NULL,
            code varchar(8) UNIQUE NOT NULL,
            name varchar(60) NOT NULL,
            created_at timestamp,
            updated_at timestamp,
            deleted_at timestamp
        );');
    }

    protected function down(): void
    {
        $this->table('streamers')->drop();
    }
}
