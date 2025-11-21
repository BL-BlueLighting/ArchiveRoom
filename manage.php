<?php

/**
 * Manage Backend for CLI.
 */

include 'functions.php';

if (php_sapi_name() != 'cli') {
    die('This script can only be run from the command line.');
}

// get params
if ($argc < 2) {
    echo 'ArchiveROOM Proj. v1.0.0, CLI Manage Backend.'.PHP_EOL;
    echo 'Usage: php manage.php <command>'.PHP_EOL;
    echo 'Avaliable commands:'.PHP_EOL;
    echo '    - user add <username> <password>'.PHP_EOL;
    echo '    - user remove <username> <password>'.PHP_EOL;
    echo '    - user list'.PHP_EOL;
    echo PHP_EOL;
    echo '    - perm add <username> <permname>'.PHP_EOL;
    exit;
}

// USER PART //
if ($argv[1] == 'user') {
    if ($argc < 3) {
        echo 'Usage: php manage.php user <command>'.PHP_EOL;
        echo 'Avaliable commands:'.PHP_EOL;
        echo '    - add <username> <password>'.PHP_EOL;
        echo '    - remove <username> <password>'.PHP_EOL;
        echo '    - list'.PHP_EOL;
        exit;
    }
    if ($argv[2] == 'add') {
        if ($argc < 5) {
            echo 'Usage: php manage.php user add <username> <password>'.PHP_EOL;
            exit;
        } else {
            $username = $argv[3];
            $password = $argv[4];
            
            $newUser = add_user($username, $password);
            echo "User added successfully.";
        }
    }
    if ($argv[2] == 'remove') {
        if ($argc < 5) {
            echo 'Usage: php manage.php user remove <username> <password>'.PHP_EOL;
            exit;
        } else {
            $username = $argv[3];
            $password = $argv[4];

            $newUser = remove_user($username);
            echo "User removed successfully.";
        }
    }
    if ($argv[2] == 'list') {
        $users = get_users();
        foreach ($users as $user) {
            echo $user['username'].PHP_EOL;
        }
    }
}

// PERMISSION PART //
if ($argv[1] == 'perm') {
    if ($argc < 3) {
        echo 'Usage: php manage.php perm <command>'.PHP_EOL;
        echo 'Avaliable commands:'.PHP_EOL;
        echo '    - add <username> <permname>'.PHP_EOL;
        exit;
    }
    if ($argv[2] == 'add') {
        if ($argc < 5) {
            echo 'Usage: php manage.php perm add <username> <permname>'.PHP_EOL;
            exit;
        } else {
            $username = $argv[3];
            $permname = $argv[4];

            $allUsers = get_users();
            foreach ($allUsers as $user) {
                if ($user['username'] == $username) {
                    $user['permission'] = $permname;
                }
            }
            write_json_file(USERS_FILE, $allUsers);
            echo "Permission added successfully.";
        }
    }
}