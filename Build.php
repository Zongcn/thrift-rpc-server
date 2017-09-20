<?php
$file = 'Sample.phar';
$phar = new Phar(__DIR__ . '/' . $file, FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, $file);
$phar->startBuffering();
$phar->buildFromDirectory(__DIR__, '/\.php$/');
$phar->delete('Build.php');
$phar->delete('Server.php');
$phar->setStub("<?php
Phar::mapPhar('{$file}');
require 'phar://{$file}/Portal/Server.php';
__HALT_COMPILER();
?>");
$phar->stopBuffering();
// 打包完成
echo "Finished {$file}\n";