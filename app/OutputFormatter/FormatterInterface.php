<?php

namespace SainsBot\OutputFormatter;

interface FormatterInterface {

	public function setCollection($collection);

	public function output();

}