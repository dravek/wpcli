#!/usr/bin/env php
<?php

define('CLI_SCRIPT', true);
require(getcwd().'/config.php');
require_once($CFG->libdir.'/clilib.php');

cli_separator();
cli_heading('Workplace CLI');

if (isset($argc)) {
    if (isset($argv[1]) && $argv[1] == '-d' && isset($argv[2])) {
        switch ($argv[2]) {
            case 'archivedprograms':
                echo "\n\ndelete ARCHIVED Programs\n\n";
                delete_archived_programs();
                cli_heading('All archived programs deleted');
                break;
            case 'activeprograms':
                echo "\n\ndelete ACTIVE Programs\n\n";
                delete_active_programs();
                cli_heading('All active programs deleted');
                break;
            case 'allprograms':
                echo "\n\ndelete ALL Programs\n\n";
                delete_archived_programs();
                delete_active_programs();
                cli_heading('All programs deleted');
                break;
            default:
                echo "\n\nMissing delete instance\n\n";
                break;

        }
    } if (isset($argv[1]) && $argv[1] == '-h') {
        echo "\n\n Example: wpcli.php -d allprograms";
        echo "\n\n Parameters:";
        echo "\n -d Delete instances";
        echo "\n\n Options:";
        echo "\n allprograms : Deletes all programs";
        echo "\n activeprograms : Deletes all active programs";
        echo "\n archivedprograms : Deletes all archived programs";
        echo "\n\n\n";
    } else {
        echo "\n\nInvalid option\n\n";
    }
}
else {
    echo "Invalid options\n";
}

function delete_archived_programs() {
    $programs = \tool_program\persistent\program::get_records(['archived' => 1]);
    foreach($programs as $program) {
        \tool_program\api::delete_program($program);
    }
}

function delete_active_programs() {
    $programs = \tool_program\persistent\program::get_records(['archived' => 0]);
    foreach($programs as $program) {
        \tool_program\api::archive_program($program);
        \tool_program\api::delete_program($program);
    }
}