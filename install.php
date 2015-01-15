<?php
require_once __DIR__."/../../autoload.php";

use Mouf\Actions\InstallUtils;
use Mouf\MoufManager;

// Let's init Mouf
InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();

if ($moufManager->instanceExists("fileUploaderLibrary")) {
	$fileUploaderLib = $moufManager->getInstanceDescriptor("fileUploaderLibrary");
} else {
	$fileUploaderLib = $moufManager->createInstance("Mouf\\Html\\Utils\\WebLibraryManager\\WebLibrary");
	$fileUploaderLib->setName("fileUploaderLibrary");
}
$fileUploaderLib->getProperty("jsFiles")->setValue(array(
	'vendor/mouf/javascript.fileuploader/fileuploader.js'
));
$fileUploaderLib->getProperty("cssFiles")->setValue(array(
	'vendor/mouf/javascript.fileuploader/fileuploader.css'
));
if ($moufManager->has('defaultWebLibraryRenderer')) {
	$renderer = $moufManager->getInstanceDescriptor('defaultWebLibraryRenderer');
	$fileUploaderLib->getProperty("renderer")->setValue($renderer);
}
$webLibraryManager = $moufManager->getInstanceDescriptor('defaultWebLibraryManager');
if ($webLibraryManager) {
	$libraries = $webLibraryManager->getProperty("webLibraries")->getValue();
	$libraries[] = $fileUploaderLib;
	$webLibraryManager->getProperty("webLibraries")->setValue($libraries);
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();