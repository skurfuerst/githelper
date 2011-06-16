<?php

class Project {
	const PROJECT_TYPE_UNKNOWN = 'Unknown';
	const PROJECT_TYPE_EXTENSION = 'Extension';
	const PROJECT_TYPE_CORE_EXTENSION = 'Core Extension';
	const PROJECT_TYPE_PACKAGE = 'Package';
	const PROJECT_TYPE_DISTRIBUTION = 'Distribution';
	const PROJECT_TYPE_APPLICATION = 'Application';

	/**
	 * one of PROJECT_TYPE_* constants
	 * @var string
	 */
	protected $type = self::PROJECT_TYPE_EXTENSION;

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var string
	 */
	protected $path = '';

	/**
	 * @param string $name
	 * @param string $path
	 * @param string $type one of PROJECT_TYPE_* constants
	 */
	public function __construct($name, $path, $type) {
		$this->name = $name;
		$this->path = $path;
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		$label = $this->name;
		if ($this->type === Project::PROJECT_TYPE_DISTRIBUTION || $this->type === Project::PROJECT_TYPE_DISTRIBUTION) {
			$name = substr($this->path, 0, strpos($this->path, '/'));
			$label = sprintf('%s (%s)', $name, $label);
		}
		return $label;
	}

	/**
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}
}

?>