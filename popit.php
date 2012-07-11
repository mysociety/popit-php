<?php

/**
 * @author Chetan Bansal <chetan1@gmail.com>
 */

require_once("api.php");


class PopIt
{

  protected $api;

  public function __construct($config) {
    $this->api = new PopItAPI($config);
  }

  public function get($entity, $id = null)
  {
      return $this->api->call($entity. "/" . $id);
  }

  public function add($entity, $data)
  {
      return $this->api->call($entity, "POST", $data);
  }

  public function update($entity, $id, $data)
  {
      return $this->api->call($entity . "/" . $id, "PUT", $data);
  }

  public function delete($entity, $id)
  {
      return $this->api->call($entity . "/" . $id, "DELETE");
  }
}
