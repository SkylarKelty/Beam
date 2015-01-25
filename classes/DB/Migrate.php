<?php
/**
 * Yet another blogging system.
 *
 * @license Apache 2.0 (http://www.apache.org/licenses/LICENSE-2.0.txt)
 * @author Skylar Kelty <skylarkelty@gmail.com>
 */

namespace Beam\DB;

class Migrate
{
    /**
     * Run migrations.
     */
    public function run() {
        global $CFG, $OUTPUT;

    	$CFG->old_version = 0;
        if (isset($CFG->version)) {
        	$OUTPUT->send("Current version: {$CFG->version}.");
        	$CFG->old_version = $CFG->version;
        }

        if (!isset($CFG->version) || $CFG->version < 2015012500) {
            $OUTPUT->send(" -> Migrating to version: 2015012500.");
            $this->migration_2015012500();
        	\Beam\Config::set('version', 2015012500);
        }

        if ($CFG->version < 2015012600) {
            $OUTPUT->send(" -> Migrating to version: 2015012600.");
            $this->migration_2015012600();
        	\Beam\Config::set('version', 2015012600);
        }

        if ($CFG->old_version != $CFG->version) {
	        $OUTPUT->send("Migrated to version: {$CFG->version}.");
	    } else {
	    	$OUTPUT->send("Nothing to do!");
	    }
    }

    /**
     * Initial table layout.
     */
    public function migration_2015012500() {
        global $DB;

        // Create config table.
        $DB->execute('
			CREATE TABLE IF NOT EXISTS {config} (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(255) NULL,
				`value` varchar(255) NULL,
				PRIMARY KEY (`id`),
				UNIQUE INDEX `name` (`name` ASC)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');
    }

    /**
     * Initial table layout.
     */
    public function migration_2015012600() {
        global $DB;

        // Create users table.
        $DB->execute('
			CREATE TABLE {users} (
				`id` INT NOT NULL AUTO_INCREMENT,
				`username` VARCHAR(45) NOT NULL,
				`password` VARCHAR(60) NOT NULL,
				`email` VARCHAR(255) NOT NULL,
				`firstname` VARCHAR(45) NULL,
				`lastname` VARCHAR(45) NULL,
				`updated` INT(11) NULL,
				`created` INT(11) NULL,
				PRIMARY KEY (`id`),
				UNIQUE INDEX `username_UNIQUE` (`username` ASC),
				UNIQUE INDEX `email_UNIQUE` (`email` ASC)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        // Create roles table.
        $DB->execute('
			CREATE TABLE {roles} (
				`id` INT NOT NULL AUTO_INCREMENT,
				`userid` INT NOT NULL,
				`roleid` INT NOT NULL,
				PRIMARY KEY (`id`),
				INDEX `user` (`userid` ASC),
				INDEX `role` (`roleid` ASC)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');
    }
}
