
<?php

//Include the component lib
include_once(dirname(__DIR__) . "/component.php");

/**A post's button bar
 */
class PostBarComponent extends Component {
  public function __construct () {
    //call Component constructor so we don't have to create variables.
    parent::__construct();

    $this->make("div")->
    styleItem("flex", "1")->
    styleItem("display", "flex")->
    styleItem("flex-direction", "row")->
    styleItem("justify-content", "center")->
    styleItem("background-color", "#27222f");


    $likeBtn = new Component();
    $likeBtn->
    make("button")->
    styleItem("border", "none")->
    styleItem("clip-path", "polygon(50% 100%, 0 50%, 9% 16%, 32% 14%, 50% 28%, 69% 16%, 91% 19%, 100% 50%)")->
    styleItem("background-color", "#e15fec")->
    styleItem("width", "1em")->
    styleItem("height", "2em")->
    styleItem("margin", "0.5em")->
    mount($this);

    $commentBtn = new Component();
    $commentBtn->
    make("button")->
    styleItem("border", "none")->
    styleItem("clip-path", "polygon(0% 0%, 100% 0%, 100% 75%, 75% 75%, 75% 100%, 50% 75%, 0% 75%)")->
    styleItem("background-color", "#4f86b7")->
    styleItem("width", "1em")->
    styleItem("height", "2em")->
    styleItem("margin", "0.5em")->
    mount($this);

  }
}

/**A post display
 */
class PostComponent extends Component {

  //A component to hold the post title text
  public Component $title;

  //A component to hold the post body text
  public Component $body;

  //A component to hold the post's button bar
  public PostBarComponent $bar;

  public function __construct () {
    //call Component constructor so we don't have to create variables.
    parent::__construct();


    // $this refers to the custom component as it is instanced in memory
    //this code happens every time we create a new instance of this class
    $this->
    make("div")->
    styleItem("margin-top", "5px")->
    styleItem("display", "flex")->
    styleItem("background-color", "#1c1c26")->
    styleItem("color", "#676799")->
    styleItem("flex-direction", "column")->
    styleItem("text-align", "center")->
    styleItem("overflow", "hidden")->
    styleItem("border-radius", "0.5em");

    //Create a title element
    $this->title = new Component();
    
    $this->title->
    make("span")->
    styleItem("flex", "1")->
    styleItem("color", "#b9b9b9")->
    styleItem("background-color", "#27222f")->
    styleItem("font-size", "large")->
    textContent("default title text")->
    mount($this);

    //Create a body element
    $this->body = new Component();
    
    $this->body->
    make("div")->
    styleItem("flex", "10")->
    styleItem("font-size", "medium")->
    styleItem("white-space", "pre-line")->
    textContent("default body text")->
    mount($this);

    //Create the button bar element
    $this->bar = new PostBarComponent();
    $this->bar->mount($this);
  }
  function setTitle ($txt) {
    $this->title->textContent($txt);
    return $this;
  }
  function setBody ($txt) {
    $this->body->textContent($txt);
    return $this;
  }
}

/**Function containing code to test post component out
 */
function test_component_php () {
  //Create a container for our content
  $content = new Component();

  $content->make("div")->
  styleItem("color", "white");

  //Iterate from 0 to 9

  //Declaring variable outside of the loop should save us some performance
  $child = 0;

  for ($i=0; $i < 10; $i++) {
    //Create a post
    $child = new PostComponent();
    //attach to the content as a child
    $child->
    setTitle("Post #" . sprintf("%d", $i+1) )->
    setBody("Some post text here\nAnother line\nYet Another")->
    mount($content);
  }

  //walk the hierarchy and echo the HTML generated
  writeComponent($content);
}

?>
