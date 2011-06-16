<?php

require_once('Project.php');

class ProjectFactory {

	/**
	 * @param string $projectPath
	 * @return Project
	 */
	public function createProjectByPath($projectPath) {
		$pathParts = explode('/', $projectPath);
		if (count($pathParts) < 2) {
			$pathParts[] = '';
		}
		if ($pathParts[1] === 'Extensions') {
			$projectType = Project::PROJECT_TYPE_EXTENSION;
		} elseif ($pathParts[1] === 'CoreProjects') {
			$projectType = Project::PROJECT_TYPE_CORE_EXTENSION;
		} elseif ($pathParts[1] === 'Packages') {
			$projectType = Project::PROJECT_TYPE_PACKAGE;
		} elseif ($pathParts[1] === 'Distributions') {
			$projectType = Project::PROJECT_TYPE_DISTRIBUTION;
		} elseif ($pathParts[1] === 'Applications') {
			$projectType = Project::PROJECT_TYPE_APPLICATION;
		} else {
			$projectType = Project::PROJECT_TYPE_UNKNOWN;
		}
		$projectName = array_pop($pathParts);
		$projectName = substr($projectName, 0, -4);
		return new Project($projectName, $projectPath, $projectType);
	}
}
?>