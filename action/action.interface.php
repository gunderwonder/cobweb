<?php

interface Action  {
	public function invoke(array $arguments = NULL);
	
	public function hasAnnotation($annotation);
	public function annotation($annotation);
}