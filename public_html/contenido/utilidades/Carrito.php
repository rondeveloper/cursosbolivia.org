<?php
class Carrito {
	/**
	 * An unique ID for the cart.
	 *
	 * @var string
	 */
	protected $cartId;

	/**
	 * Maximum item allowed in the cart.
	 *
	 * @var int
	 */
	protected $cartMaxItem = 0;

	/**
	 * Maximum quantity of a item allowed in the cart.
	 *
	 * @var int
	 */
	protected $itemMaxQuantity = 1;

	/**
	 * Enable or disable cookie.
	 *
	 * @var bool
	 */
	protected $useCookie = false;

	/**
	 * A collection of cart items.
	 *
	 * @var array
	 */
	private $items = [];

	/**
	 * Initialize cart.
	 *
	 * @param array $options
	 */
	public function __construct($options = [])
	{
		if (!session_id()) {
			session_start();
		}

		if (isset($options['cartMaxItem']) && preg_match('/^\d+$/', $options['cartMaxItem'])) {
			$this->cartMaxItem = $options['cartMaxItem'];
		}

		if (isset($options['itemMaxQuantity']) && preg_match('/^\d+$/', $options['itemMaxQuantity'])) {
			$this->itemMaxQuantity = $options['itemMaxQuantity'];
		}

		if (isset($options['useCookie']) && $options['useCookie']) {
			$this->useCookie = true;
		}

		$this->cartId = md5((isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : 'SimpleCart') . '_cart';

		$this->read();
	}

	/**
	 * Get items in  cart.
	 *
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * Obtiene los ids agregados al carrito
	 *
	 * @return array
	 */
	public function getIDsDelCarrito()
	{
		$array = array();
		foreach ($this->items as $key => $value) {
			if($key != ''){
				array_push($array, $key);
			}
		}
		return $array;
	}

	/**
	 * Obtiene el costo total de los curso sdel carrito
	 *
	 * @return array
	 */
	public function getCostoTotal()
	{
		if($this->isEmpty()){
			return 0;
		}
		$ids_cursos_del_carrito = implode(',', $this->getIDsDelCarrito());
		$rq_carrito_cursos = query("SELECT SUM(costo) AS total_costo, COUNT(*) AS total_cursos FROM cursos WHERE id IN ($ids_cursos_del_carrito)");
		$datos = fetch($rq_carrito_cursos);
		$total_costo = $datos['total_costo'] - $this->montoDescuento($datos['total_cursos']);
		
		return $total_costo;
	}

	/**
	 * Obtiene el costo total de los curso sdel carrito
	 *
	 * @return array
	 */
	public function getCostoTotalSinDescuento()
	{
		$ids_cursos_del_carrito = implode(',', $this->getIDsDelCarrito());
		$rq_carrito_cursos = query("SELECT SUM(costo) AS total_costo FROM cursos WHERE id IN ($ids_cursos_del_carrito)");
		$datos = fetch($rq_carrito_cursos);		
		return $datos['total_costo'];
	}

	/**
	 * Calcula el descuento en base a la cantidad de cursos
	 *
	 * @return array
	 */
	private function montoDescuento($total_cursos)
	{
		$descuento = 0;
		if($total_cursos == 2) {
			$descuento = 20;
		} else if($total_cursos == 3) {
			$descuento = 60;
		} else if($total_cursos == 4) {
			$descuento = 100;
		} else if($total_cursos == 5) {
			$descuento = 150;
		} else if($total_cursos == 6) {
			$descuento = 180;
		} else if($total_cursos == 7) {
			$descuento = 240;
		} else if($total_cursos == 9) {
			$descuento = 355;
		} else if($total_cursos >= 10) {
			$descuento = 400;
		}
		return $descuento;
	}

	/**
	 * Obtiene el total de cursos en el carrito
	 *
	 * @return array
	 */
	public function totalCursos()
	{
		if($this->isEmpty()){
			return 0;
		}
		$ids_cursos_del_carrito = implode(',', $this->getIDsDelCarrito());
		$rq_carrito_cursos = query("SELECT COUNT(*) AS total_cursos FROM cursos WHERE id IN ($ids_cursos_del_carrito)");
		$datos = fetch($rq_carrito_cursos);		
		return $datos['total_cursos'];
	}

	/**
	 * Check if the cart is empty.
	 *
	 * @return bool
	 */
	public function isEmpty()
	{
		return empty(array_filter($this->items));
	}

	/**
	 * Get the total of item in cart.
	 *
	 * @return int
	 */
	public function getTotalItem()
	{
		$total = 0;

		foreach ($this->items as $items) {
			foreach ($items as $item) {
				++$total;
			}
		}

		return $total;
	}

	/**
	 * Get the total of item quantity in cart.
	 *
	 * @return int
	 */
	public function getTotalQuantity()
	{
		$quantity = 0;

		foreach ($this->items as $items) {
			foreach ($items as $item) {
				$quantity += $item['quantity'];
			}
		}

		return $quantity;
	}

	/**
	 * Get the sum of a attribute from cart.
	 *
	 * @param string $attribute
	 *
	 * @return int
	 */
	public function getAttributeTotal($attribute = 'price')
	{
		$total = 0;

		foreach ($this->items as $items) {
			foreach ($items as $item) {
				if (isset($item['attributes'][$attribute])) {
					$total += $item['attributes'][$attribute] * $item['quantity'];
				}
			}
		}

		return $total;
	}

	/**
	 * Remove all items from cart.
	 */
	public function clear()
	{
		$this->items = [];
		$this->write();
	}

	/**
	 * Check if a item exist in cart.
	 *
	 * @param string $id
	 * @param array  $attributes
	 *
	 * @return bool
	 */
	public function isItemExists($id, $attributes = [])
	{
		$attributes = (is_array($attributes)) ? array_filter($attributes) : [$attributes];

		if (isset($this->items[$id])) {
			$hash = md5(json_encode($attributes));
			foreach ($this->items[$id] as $item) {
				if ($item['hash'] == $hash) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Get one item from cart
	 *
	 * @param string $id
	 * @param string $hash
	 *
	 * @return array
	 */
	public function getItem($id, $hash = null)
	{
		if($hash){
			$key = array_search($hash, array_column($this->items[$id], 'hash'));
			if($key !== false)
				return $this->items[$id][$key];
			return false;
		}
		else
			return reset($this->items[$id]);
	}

	/**
	 * Add item to cart.
	 *
	 * @param string $id
	 * @param int    $quantity
	 * @param array  $attributes
	 *
	 * @return bool
	 */
	public function add($id, $quantity = 1, $attributes = [])
	{
		$quantity = (preg_match('/^\d+$/', $quantity)) ? $quantity : 1;
		$attributes = (is_array($attributes)) ? array_filter($attributes) : [$attributes];
		$hash = md5(json_encode($attributes));

		if (count($this->items) >= $this->cartMaxItem && $this->cartMaxItem != 0) {
			return false;
		}

		if (isset($this->items[$id])) {
			foreach ($this->items[$id] as $index => $item) {
				if ($item['hash'] == $hash) {
					$this->items[$id][$index]['quantity'] += $quantity;
					$this->items[$id][$index]['quantity'] = ($this->itemMaxQuantity < $this->items[$id][$index]['quantity'] && $this->itemMaxQuantity != 0) ? $this->itemMaxQuantity : $this->items[$id][$index]['quantity'];

					$this->write();

					return true;
				}
			}
		}

		$this->items[$id][] = [
			'id'         => $id,
			'quantity'   => ($quantity > $this->itemMaxQuantity && $this->itemMaxQuantity != 0) ? $this->itemMaxQuantity : $quantity,
			'hash'       => $hash,
			'attributes' => $attributes,
		];

		$this->write();

		return true;
	}

	/**
	 * Update item quantity.
	 *
	 * @param string $id
	 * @param int    $quantity
	 * @param array  $attributes
	 *
	 * @return bool
	 */
	public function update($id, $quantity = 1, $attributes = [])
	{
		$quantity = (preg_match('/^\d+$/', $quantity)) ? $quantity : 1;

		if ($quantity == 0) {
			$this->remove($id, $attributes);

			return true;
		}

		if (isset($this->items[$id])) {
			$hash = md5(json_encode(array_filter($attributes)));

			foreach ($this->items[$id] as $index => $item) {
				if ($item['hash'] == $hash) {
					$this->items[$id][$index]['quantity'] = $quantity;
					$this->items[$id][$index]['quantity'] = ($this->itemMaxQuantity < $this->items[$id][$index]['quantity'] && $this->itemMaxQuantity != 0) ? $this->itemMaxQuantity : $this->items[$id][$index]['quantity'];

					$this->write();

					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Remove item from cart.
	 *
	 * @param string $id
	 * @param array  $attributes
	 *
	 * @return bool
	 */
	public function remove($id, $attributes = [])
	{
		if (!isset($this->items[$id])) {
			return false;
		}

		if (empty($attributes)) {
			unset($this->items[$id]);

			$this->write();

			return true;
		}
		$hash = md5(json_encode(array_filter($attributes)));

		foreach ($this->items[$id] as $index => $item) {
			if ($item['hash'] == $hash) {
				unset($this->items[$id][$index]);
				$this->items[$id] = array_values($this->items[$id]);

				$this->write();

				return true;
			}
		}

		return false;
	}

	/**
	 * Destroy cart session.
	 */
	public function destroy()
	{
		$this->items = [];

		if ($this->useCookie) {
			setcookie($this->cartId, '', -1);
		} else {
			unset($_SESSION[$this->cartId]);
		}
	}

	/**
	 * Read items from cart session.
	 */
	private function read()
	{
		$this->items = ($this->useCookie) ? json_decode((isset($_COOKIE[$this->cartId])) ? $_COOKIE[$this->cartId] : '[]', true) : json_decode((isset($_SESSION[$this->cartId])) ? $_SESSION[$this->cartId] : '[]', true);
	}

	/**
	 * Write changes into cart session.
	 */
	private function write()
	{
		if ($this->useCookie) {
			setcookie($this->cartId, json_encode(array_filter($this->items)), time() + 604800, "/");
		} else {
			$_SESSION[$this->cartId] = json_encode(array_filter($this->items));
		}
	}
}