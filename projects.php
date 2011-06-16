<?php

require_once('Classes/ProjectFactory.php');

$projectPaths = file('http://git.typo3.org/?a=project_index');

$jsonString = '{"projects":[';
$projectFactory = new ProjectFactory();
foreach($projectPaths as $projectPath) {
	$project = $projectFactory->createProjectByPath(trim($projectPath));
	$jsonString .= jsonEncodeProject($project);
}
$jsonString .= ']}';

function jsonEncodeProject(Project $project) {
	return sprintf('{"name":"%s","label":"%s","path":"%s","type":"%s"},', $project->getName(), $project->getLabel(), $project->getPath(), $project->getType());
}

echo $jsonString;

?>