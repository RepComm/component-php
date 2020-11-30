<?php

function getStdObjPropCount ($stdObj) {
  $i=0;
  foreach ($stdObj as $key =>$value) {
    $i++;
  }
  return $i;
}

function writeComponent ($comp, $recursive = true) {
  //write the first part of the tag
  echo $comp->buildTagBegin();

  //Build immediate content of the element
  echo $comp->buildTagContent();

  //If we should render the children, do it
  if ($recursive && isset($comp->children)) {
    foreach ($comp->children as $child) {
      //The actual recursive part
      echo writeComponent($child, true);
    }
  }

  echo $comp->buildTagEnd();
}

/**Create an HTML element
 */
class Component {
  protected string $type;
  protected StdClass $style;
  
  protected bool $cacheTagBeginDirty;
  protected string $cacheTagBegin;

  protected bool $cacheTagContentDirty;
  protected string $cacheTagContent;

  protected string $textContent;
  
  public $children;
  protected $parent;

  public function __construct () {
    $this->type = "invalid";
    $this->style = new StdClass;
    $this->cacheTagBeginDirty = true;
    $this->cacheTagContentDirty = true;
    $this->children = array();
  }

  function hasSimilarChild ($child) {
    return in_array($child, $this->children, false);
  }

  function hasChild ($child) {
    return in_array($child, $this->children, true);
  }

  function hasParent () {
    return isset($this->parent);
  }

  function mountChild ($child) {
    if ($this->hasChild($child)) return $this;
    if ($child->hasParent()) {
      $child->unmount();
    }
    array_push($this->children, $child);
    return $this;
  }

  function mount ($parent) {
    $parent->mountChild($this);
    return $this;
  }

  function unmountChild ($child) {
    $ind = array_search($child, $this->children);
    if ($ind > -1) {
      array_splice($this->children, $ind, 1);
    }
    return $this;
  }

  function unmount () {
    $p = $this->parent;
    if (isset($p)) {
      $this->parent = null;
      $p->unmountChild($this);
    }
    return $this;
  }

  function make ($type) {
    $this->type = $type;
    return $this;
  }

  function buildTagBegin () {
    if ($this->cacheTagBeginDirty) {
      $this->cacheTagBegin = "<" . $this->type;

      if (isset($this->style) && getStdObjPropCount($this->style)) {
        $this->cacheTagBegin .= " style='";
        foreach ($this->style as $key => $value) {
          $this->cacheTagBegin .= $key . ":" . $value . ";";
        }
        $this->cacheTagBegin .= "'";
      }

      //close the begin tag
      $this->cacheTagBegin .= ">";

      $this->cacheTagBeginDirty = false;
    }
    return $this->cacheTagBegin;
  }

  function buildTagContent () {
    if ($this->cacheTagContentDirty) {
      $this->cacheTagContent = "";
      if (isset($this->textContent)) {
        $this->cacheTagContent .= $this->textContent;
      }
      $this->cacheTagContentDirty = false;
    }
    return $this->cacheTagContent;
  }

  function buildTagEnd () {
    return "</" . $this->type . ">";
  }

  function styleItem ($key, $value) {
    $style = $this->style;
    $style->{$key} = $value;
    return $this;
  }

  function textContent ($txt) {
    $this->textContent = $txt;
    return $this;
  }
}

function test_component_php () {
  //Create a container for our content
  $content = new Component();

  $content->make("div")->
  textContent("Hello World")->

  //Styling
  styleItem("position", "absolute")->
  styleItem("top", "25%")->
  styleItem("left", "25%")->
  styleItem("width", "50vw")->
  styleItem("height", "50vh")->
  styleItem("background-color", "black")->
  styleItem("color", "white");

  //Iterate from 0 to 9

  //Declaring variable outside of the loop should save us some performance
  $child = 0;

  for ($i=0; $i < 10; $i++) {
    //Create a line break
    $child = new Component();
    $child->make("br")->

    //attach to the content as a child
    mount($content);

    //Create text span
    $child = new Component();
    $child->make("span")->

    //set the text content to "span " + iteration number
    textContent("span " . sprintf("%d", $i) )->

    //attach to the content as a child
    mount($content);
  }

  //walk the hierarchy and echo the HTML generated
  writeComponent($content);
}

?>