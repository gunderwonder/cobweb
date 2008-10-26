<?php

interface Action  {
	public function invoke(array $arguments = NULL);
}