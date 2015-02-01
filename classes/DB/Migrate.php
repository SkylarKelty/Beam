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
        } else {
            $OUTPUT->send("Running complete install.");
            $this->base_install();
            $OUTPUT->send("Finished installing version: {$CFG->version}.");
            return;
        }

        // Migration scripts here.

        if ($CFG->old_version != $CFG->version) {
	        $OUTPUT->send("Migrated to version: {$CFG->version}.");
	    } else {
	    	$OUTPUT->send("Nothing to do!");
	    }
    }

    /**
     * Initial table layout.
     */
    public function base_install() {
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
                INDEX `role` (`roleid` ASC),
                UNIQUE INDEX `userrole_UNIQUE` (`userid` ASC, `roleid` ASC)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        // Create blog_entry table.
        $DB->execute('
            CREATE TABLE {blog_entry} (
                `id` INT NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(255) NOT NULL,
                `contents` TEXT NOT NULL,
                `userid` INT NOT NULL,
                `updated` INT(11) NULL,
                `created` INT(11) NULL,
                PRIMARY KEY (`id`),
                UNIQUE INDEX `title_UNIQUE` (`title` ASC),
                INDEX `userid` (`userid` ASC)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');

        \Beam\Config::set('version', 2015012600);
    }
}
