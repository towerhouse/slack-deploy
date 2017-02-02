<?php

class GitBuilder
{
	public $path    = null;
	public $branch  = null;

	public $cmd    = '';

	public $output = [];
	public $status = 0;

	private function optGitDir () {
		if ( !$this->path ) {
			throw new Exception('Repo path missing');
		}
		return '--git-dir="' . $this->path .'/.git" --work-tree="' . $this->path .'"';
	}

	public function reset () {
		$this->cmd    = '';
		$this->output = [];
		$this->status = 0;

		return $this;
	}

	public function status () {
		$this->cmd .= 'git ' . $this->optGitDir() . ' status';
		return $this;
	}

	public function outputMustHave ( array $arr, $error ) {
		$this->cmd .= ' | grep -q ';
		foreach ($arr as $value) {
			$this->cmd .= ' -e "' . $value . '"';
		}
		$this->cmd .= ';if [[ $? -ne 0 ]]; then echo "' . $error .'"; exit 1; fi;';
		return $this;
	}

	public function deploy () {
		$this->cmd  = 'git ' . $this->optGitDir() . ' fetch --quiet';
		$this->cmd .= ' && ';
		$this->cmd .= 'git ' . $this->optGitDir() . ' checkout ' . $this->branch;
		$this->cmd .= ' && ';
		$this->cmd .= 'git ' . $this->optGitDir() . ' pull';
		return $this;
	}

	public function runAs ( $user ){
	 	$this->cmd = "sudo -u " . $user ." bash -c '" . $this->cmd . "'";
	 	return $this->run();
	}

	public function run () {
	 	return exec ( $this->cmd . ' 2>&1', $this->output,  $this->status);
	}

}
