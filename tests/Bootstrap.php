<?php
namespace Bricks\Log;

$apiMock;

function file_put_contents($filename, $data, $flags = 0, $context = null){
  global $apiMock;                                                              
  return $apiMock->file_put_contents($filename, $data, $flags, $context);
}

class PDOMock extends \PDO {
  public function __construct(){
  }
}
