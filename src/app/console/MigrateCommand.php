<?php

namespace app\console;
use app\Command;
use app\db\drivers\Mysql;
use app\utils\FSL;
use PDO;

class MigrateCommand extends Command {
    public function __construct(public Mysql $db, public FSL $fsl) {}
    /**
     * Checks if there is a migration storage.
     */
    private function storage(): void
    {
        $result = $this->db->query("show tables like 'migrations'")->fetchAll();
        if(empty($result)) {
            $this->db->update("create table migrations(id int auto_increment primary key, name varchar(255) not null, created_at timestamp default current_timestamp)");
        }
    }

    /**
     * Search migrations in path and apply to database.
     */
    public function handle(): void
    {
        $this->storage();
        $migrations = glob($this->fsl->migrations().'/*.up.sql');
        usort($migrations, fn ($a, $b) => strcmp($a, $b));
        array_walk($migrations, function($migration){
            $name = str_replace(".up.sql", "", basename($migration));
            $result = $this->db->query("select `name` from `migrations` where `name` = :name", [':name' => $name])->fetchAll();
            if(empty($result)) {
                $sql = file_get_contents($migration);
                $this->db->exec($sql);
                $id = $this->db->insert("insert into `migrations`(`name`) values(:name)", [
                    'name' => $name,
                ]);
                $this->writeln("Migration {$name} applied, id = {$id}");
            }
        });
    }

    public function down(): void
    {
        $stmt = $this->db->query("select `name` from `migrations` order by `name` desc");

        while(($row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_PRIOR))) {
            $sql = file_get_contents("{$this->fsl->migrations()}/{$row['name']}.down.sql");
            $this->db->exec($sql);
            $this->db->query('delete from `migrations` where `name` = :name', ['name' => $row['name']]);
            $this->writeln("Down {$row['name']} applied");
        }
    }

    /**
     * Creates new empty migration file.
     */
    public function create($name): void
    {
        $time = date('Y-m-d-H-s-i', time());
        foreach(["up", "down"] as $type) {
            $file = __DIR__ . "/../db/migrations/{$time}_{$name}.{$type}.sql";
            file_put_contents($file, "-- migration {$name} created {$time}");
            chmod($file, 0666);
            $this->writeln("Migration created {$file}");
        }
    }
}