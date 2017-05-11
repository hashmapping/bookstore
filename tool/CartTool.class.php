<?php
/*购物车类 技术(session+单例子)
全局有效(数据库/session/cookie)，说明只能有1个实例(单例)


判断某个商品是否存在
添加商品
删除商品
修改商品的数量

某商品数量加1
某商品数量减1


查询购物车的商品种类
查询购物车的商品数量
查询购物车里的商品总金额
返回购物里的所有商品

清空购物车
*/

defined('ACC')||exit('Acc Deined');
// session_start();

class CartTool {
    private static $ins = null;
    private $items = array();

    final protected function __construct() {
    }
    // 防止别人修改
    final protected function __clone() {
    }

    // 获取实例
    protected static function getIns() {
        if(!(self::$ins instanceof self)) {
            self::$ins = new self();
        }

        return self::$ins;
    }


    // 把购物车的单例对象放到session里
    public static function getCart() {
        if(!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof self)) {
            $_SESSION['cart'] = self::getIns();
        }

        return $_SESSION['cart'];
    }


    //添加商品
    
    public function addItem($id,$name,$price,$market_price,$author,$num=1) {
        
        /*if($this->hasItem($id)) { // 如果该商品已经存在,则直接加其数量
            $this->incNum($id,$num);
            return;
        }*/
        
        $item = array();
        $item['name'] = $name;
        $item['price'] = $price;
        $item['market_price']=$market_price;
        $item['num'] = $num;
        $item['author']=$author;
        
        $this->items[$id] = $item;
    }



    //修改购物车中的商品数 某个商品修改后的数量,即直接把某商品的数量改为$num

    public function modNum($id,$num=1) {
        if(!$this->hasItem($id)) {
            return false;
        }

        $this->items[$id]['num'] = $num;

    }


    //商品数量增加1
    
    public function incNum($id,$num=1) {
        if($this->hasItem($id)) {
            $this->items[$id]['num'] += $num;
        }
    }



    //商品数量减少1
    public function decNum($id,$num=1) {
        if($this->hasItem($id)) {
            $this->items[$id]['num'] -= $num;
        }

        // 如果减少后,数量为0了,则把这个商品从购物车删掉
        if($this->items[$id]['num'] < 1) {
            $this->delItem($id);
        }
    }



    //判断某商品是否存在

    public function hasItem($id) {
        return array_key_exists($id,$this->items);
    }
      


    //删除商品

    public function delItem($id) {
        unset($this->items[$id]);
    }



    //查询购物车中商品的种类

    public function getCnt() {
        return count($this->items);
    }


  
    //查询购物车中商品的个数

    public function getNum() {
        if($this->getCnt() == 0) {
            return 0;
        }
        
        $sum = 0;

        foreach($this->items as $item) {
            $sum += $item['num'];
        }

        return $sum;
    }



    //查询购物车中商品的总金额
    public function getPrice() {
        if($this->getCnt() == 0) {
            return 0;
        }
        
        $price = 0.0;

        foreach($this->items as $item) {
            $price += $item['num'] * $item['price'];
        }

        return $price;
    }
    
   // 返回购物车中的所有商品
    public function all() {
        return $this->items;
    }

    //清空购物车

    public function clear() {
        $this->items = array();
    }
}

// print_r(CartTool::getCart());
/*$cart=CartTool::getCart();
if ($_GET['test']=='add') {
	$cart->addItem(1,'wangba','34.3',1);
	echo 'Ok';
}else if($_GET['test']=='clear'){
	$cart->clear();
	echo 'clear';
}
else{
	print_r($cart);
}*/
