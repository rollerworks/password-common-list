<?php

/*
 * This file is part of the RollerworksPasswordCommonList package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

if (\PHP_SAPI !== 'cli') {
    echo 'Script can only be invoked via CLI';

    exit(1);
}

if (count($argv) !== 2) {
    echo 'php converter.php 10_million_password_list_top_10000000.txt';

    exit(1);
}

$file = new SplFileObject(__DIR__ . '/' . $argv[1]);
$passwordsByLength = [];

while ($file->valid()) {
    $password = mb_strtolower(trim($file->fgets()));
    $length = mb_strlen($password);

    // To reduce the size of this package only passwords of 6 characters or more are included.
    // It's best no to allow passwords that are shorter than 6 characters anyway.
    if ($length >= 6) {
        $passwordsByLength[$length][$password] = true;
    }
}

$file = null;

foreach ($passwordsByLength as $length => $passwords) {
    $exported = trim(var_export($passwords, true));
    $exported = preg_replace(['{^array\s*\(}', '{\)$}'], ['[', ']'], $exported);

    file_put_contents(__DIR__ . '/list-' . $length . '.php', '<?php return ' . $exported . ";\n");
}
