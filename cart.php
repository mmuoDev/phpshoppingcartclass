<?php
session_start(); //Starts session 
require_once "db/db_handle.php"; //Require your DB details
ob_start();

/**
This shopping cart class contains properties and methods for adding,
removing and updating a fucking shopping cart.
MmuoDev......
**/
//Checks if a shopping cart session array has already been created.
if (!isset($_SESSION['cart_items'])){
  $_SESSION['cart_items'] = array();
}
if(!isset($_SESSION['total'])){
  $_SESSION['total'] = array();
}
if(!isset($_SESSION['grand_total'])){
  $_SESSION['grand_total'] = array();
}
//Shopping cart class
class cart{
  public $product_id;
  public $quantity;
  public $name;

//Adds items to the shopping cart.
  public function add_to_cart($product_id, $name, $quantity){
    global $db;
    if (array_key_exists($product_id, $_SESSION['cart_items'])){
      header("Location: shop.php");   //Redirects to shopping page if already in cart and exits the fucking program!
      exit;
    }
    else{
      //Else add the item to the shopping cart bitch!
      $_SESSION['cart_items'][$product_id] = $quantity;
      $select = "select * FROM items WHERE id = :product_id"; //Get details of the product being added from the DB
      foreach($_SESSION['cart_items'] AS $id=>$value){
        foreach ($db->query($select, array('product_id' => $id)) AS $items){
          $grand_total += $items['price'] * $value;
          //$total = $items['price'] * $value;
        }
      }
      $_SESSION['total'] = $grand_total; //Total
      $_SESSION['grand_total'] = $grand_total; //Payable Total

    }
  }
  public function add_to_cart_to_checkout($product_id, $name, $quantity){ //Adds to checkout
    if (array_key_exists($product_id, $_SESSION['cart_items'])){
      header("Location: main_cart.php");   //Redirects to products page if already in cart and exits the fucking program!
      exit;
    }
    else{
      //Else add the item to the shopping cart bitch!
      $_SESSION['cart_items'][$product_id] = $quantity;
      //header("Location: main_cart.php");
    }
  }
  //Gets the total of a particular item
  public function sum($price, $quantity){
    echo $price * $quantity;
  }
  public function grand_total($grand_total, $coupon){
    if ($coupon != 0){ //If a coupon is added, calculate the discount and the total
      $discount = $grand_total * 0.1;
      $actual = $grand_total - $discount;
      return $actual;
      //return $_SESSION[$actual] ? $_SESSION[$actual] : 0;
    }else{
      return $grand_total;
      //  return $_SESSION[$grand_total] ? $_SESSION[$grand_total] : 0;
    }
  }
  public function discount($discount){ 
    if ($discount != 0){
      return $discount = $discount;
    }else{
      return "N/A";
    }
  }

  //Increments the value of a particular item
  public function increment($product_id){ 
    foreach ($_SESSION['cart_items'] AS $id => $quantity){
      if ($id == $product_id){
      $_SESSION['cart_items'][$id]++;
      }
    }
  }
  //decrements the value of a particular Item
  public function decrement($product_id){
    foreach($_SESSION['cart_items'] AS $id => $quantity){
      if($id == $product_id){
        $_SESSION['cart_items'][$id]--;
        if ($_SESSION['cart_items'][$id] == ""){ //If user removes the last quantity i.e. subtracts one from one, removove this item from cart.
          unset($_SESSION['cart_items'][$id]);
        }
      }
    }
  }
  public function delete_from_cart($product_id){ //deletes from the shopping cart
    unset($_SESSION['cart_items'][$product_id]);
  }




}


 ?>
