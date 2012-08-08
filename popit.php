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
      $call = $this->api->call($entity, "POST", $data);
      return  $call['result'];
  }

  public function update($entity, $id, $data)
  {
      return $this->api->call($entity . "/" . $id, "PUT", $data);
  }

  public function delete($entity, $id)
  {
      return $this->api->call($entity . "/" . $id, "DELETE");
  }

  public function deleteAll($entity)
  {
      $result = $this->get($entity);
      $items = $result['results'];

      if(count($items) > 0)
        foreach($items as $i)
            $this->delete($entity, $i['_id']);
  }

  public function emptyInstance()
  {
      $entities = array('person', 'organisation', 'position');

      foreach($entities as $e)
        $this->deleteAll($e);
  }

  public function getAll()
  {
      $entities = array('person', 'organisation', 'position');
      $final = array();

      foreach($entities as $e)
      {
          $result = $this->get($e);
          $final[$e] = $result['results'];
      }

      return $final;
  }

  public function search($q)
  {
      return $this->get('person', '?name=' . $q);
  }
}
