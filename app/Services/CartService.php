<?php

namespace App\Services;

use App\Models\Product;
use RuntimeException;
use SimpleXMLElement;

class CartService
{
	public function __construct(private PrestashopService $prestashopService) {}

	public function getOrCreateCart(int $customerId, array $context = []): array
	{
		$cartId = $this->findLatestCartIdByCustomer($customerId);

		if ($cartId === null) {
			$cartId = $this->createCartFromBlankSchema($customerId, $context);
		}

		$cartXml = $this->loadCartXml($cartId);

		return $this->normalizeCart($cartXml->cart);
	}

	public function addItem(int $customerId, int $productId, int $quantity = 1, array $context = []): array
	{
		if ($quantity < 1) {
			throw new RuntimeException('Quantity must be at least 1.');
		}

		$cartId = $this->findLatestCartIdByCustomer($customerId);

		if ($cartId === null) {
			$cartId = $this->createCartFromBlankSchema($customerId, $context);
		}

		$cartXml = $this->loadCartXml($cartId);
		$cart = $cartXml->cart;

		$this->ensureCartRowsNode($cart);

		$existingIndex = $this->findCartRowIndexByProduct((int) $productId, $cart->associations->cart_rows);

		if ($existingIndex !== null) {
            // Update existing row
			$currentQuantity = (int) $cart->associations->cart_rows->cart_row[$existingIndex]->quantity;
			$cart->associations->cart_rows->cart_row[$existingIndex]->quantity = (string) ($currentQuantity + $quantity);
		} else {
            // Add new row
			$cartRow = $cart->associations->cart_rows->addChild('cart_row');
			$cartRow->addChild('id_product', (string) $productId);
			$cartRow->addChild('id_product_attribute', '0');
			$cartRow->addChild('id_address_delivery', (string) ((int) ($cart->id_address_delivery ?? 0)));
			$cartRow->addChild('id_customization', '0');
			$cartRow->addChild('quantity', (string) $quantity);
		}

		$updatedXml = $this->saveCartXml($cartId, $cartXml);

		return $this->normalizeCart($updatedXml->cart);
	}

	public function updateItemQuantity(int $customerId, int $productId, int $quantity): array
	{
		if ($quantity < 0) {
			throw new RuntimeException('Quantity cannot be negative.');
		}

		$cartId = $this->findLatestCartIdByCustomer($customerId);

		if ($cartId === null) {
			throw new RuntimeException('No active cart found for this customer.');
		}

		$cartXml = $this->loadCartXml($cartId);
		$cart = $cartXml->cart;

		$this->ensureCartRowsNode($cart);

		$index = $this->findCartRowIndexByProduct($productId, $cart->associations->cart_rows);

		if ($index === null) {
			throw new RuntimeException('Product is not present in the cart.');
		}

		if ($quantity === 0) {
			unset($cart->associations->cart_rows->cart_row[$index]);
		} else {
			$cart->associations->cart_rows->cart_row[$index]->quantity = (string) $quantity;
		}

		$updatedXml = $this->saveCartXml($cartId, $cartXml);

		return $this->normalizeCart($updatedXml->cart);
	}

	public function removeItem(int $customerId, int $productId): array
	{
		return $this->updateItemQuantity($customerId, $productId, 0);
	}

	public function clearItems(int $customerId): array
	{
		$cartId = $this->findLatestCartIdByCustomer($customerId);

		if ($cartId === null) {
			throw new RuntimeException('No active cart found for this customer.');
		}

		$cartXml = $this->loadCartXml($cartId);
		$cart = $cartXml->cart;

		$this->ensureCartRowsNode($cart);

		foreach ($cart->associations->cart_rows->cart_row as $index => $row) {
			unset($cart->associations->cart_rows->cart_row[$index]);
		}

		$updatedXml = $this->saveCartXml($cartId, $cartXml);

		return $this->normalizeCart($updatedXml->cart);
	}

    //************** */
    // Private helpers
    //************** */

    private function findLatestCartIdByCustomer(int $customerId): ?int
	{
		$response = $this->prestashopService->request('GET', 'carts', [
			'display' => '[id]',
			'filter[id_customer]' => '[' . $customerId . ']',
            'filter[orderered]'   => '[0]',
			'sort' => '[id_DESC]',
			'limit' => '1',
		]);

		if (! isset($response->carts) || ! isset($response->carts->cart)) {
			return null;
		}

		return (int) $response->carts->cart->id;
	}

	private function createCartFromBlankSchema(int $customerId, array $context): int
	{
		$schema = $this->prestashopService->request('GET', 'carts', ['schema' => 'blank']);

		if (! isset($schema->cart)) {
			throw new RuntimeException('Unable to load blank cart schema from PrestaShop.');
		}

		$cart = $schema->cart;

		$idShop = (string) ($context['id_shop'] ?? config('prestashop.default_shop_id'));
		$idShopGroup = (string) ($context['id_shop_group'] ?? config('prestashop.default_shop_group_id'));
		$idCurrency = (string) ($context['id_currency'] ?? config('prestashop.default_currency_id'));
		$idLang = (string) ($context['id_lang'] ?? config('prestashop.default_lang_id'));
		$idAddressDelivery = (string) ($context['id_address_delivery'] ?? 0);
		$idAddressInvoice = (string) ($context['id_address_invoice'] ?? 0);
		$idCarrier = (string) ($context['id_carrier'] ?? 0);

		if ($idShop === '' || $idShopGroup === '' || $idCurrency === '' || $idLang === '') {
			throw new RuntimeException('Missing required cart defaults. Set prestashop defaults or pass context values.');
		}

		$cart->id_shop = $idShop;
		$cart->id_shop_group = $idShopGroup;
		$cart->id_currency = $idCurrency;
		$cart->id_lang = $idLang;
		$cart->id_customer = (string) $customerId;
		$cart->id_guest = '0';
		$cart->id_carrier = $idCarrier;
		$cart->id_address_delivery = $idAddressDelivery;
		$cart->id_address_invoice = $idAddressInvoice;
		$cart->secure_key = (string) ($context['secure_key'] ?? md5($customerId . '|' . microtime(true)));

		if (! isset($cart->associations)) {
			$cart->addChild('associations');
		}

		if (! isset($cart->associations->cart_rows)) {
			$cart->associations->addChild('cart_rows');
		}

		$created = $this->prestashopService->request('POST', 'carts', [], $schema->asXML());

		if (! isset($created->cart->id)) {
			throw new RuntimeException('PrestaShop cart creation response does not contain cart id.');
		}

		return (int) $created->cart->id;
	}

	private function loadCartXml(int $cartId): SimpleXMLElement
	{
		$cartXml = $this->prestashopService->request('GET', 'carts/' . $cartId, ['display' => 'full']);

		if (! isset($cartXml->cart)) {
			throw new RuntimeException('Unable to load cart from PrestaShop.');
		}

		return $cartXml;
	}

	private function saveCartXml(int $cartId, SimpleXMLElement $cartXml): SimpleXMLElement
	{
		return $this->prestashopService->request('PUT', 'carts/' . $cartId, [], $cartXml->asXML());
	}

	private function ensureCartRowsNode(SimpleXMLElement $cart): void
	{
		if (! isset($cart->associations)) {
			$cart->addChild('associations');
		}

		if (! isset($cart->associations->cart_rows)) {
			$cart->associations->addChild('cart_rows');
		}
	}

	private function findCartRowIndexByProduct(int $productId, SimpleXMLElement $cartRows): ?int
	{
		foreach ($cartRows->cart_row as $index => $row) {
			if ((int) $row->id_product === $productId) {
				return (int) $index;
			}
		}

		return null;
	}

	private function normalizeCart(SimpleXMLElement $cart): array
	{
		$items = [];
		$totalQuantity = 0;
		$subtotal = 0.0;

		if (isset($cart->associations) && isset($cart->associations->cart_rows)) {
			foreach ($cart->associations->cart_rows->cart_row as $row) {
				$productId = (int) $row->id_product;
				$quantity = (int) $row->quantity;
				$product = Product::query()->find($productId);
				$unitPrice = (float) ($product?->price ?? 0);

				$lineSubtotal = $unitPrice * $quantity;

				$items[] = [
					'product_id' => $productId,
					'product_attribute_id' => (int) ($row->id_product_attribute ?? 0),
					'quantity' => $quantity,
					'unit_price' => $unitPrice,
					'line_subtotal' => round($unitPrice * $quantity, 2),
                    '_price_note'  => 'base_price_only',
				];

				$totalQuantity += $quantity;
				$subtotal += $lineSubtotal;
			}
		}

		return [
			'id' => (int) $cart->id,
			'customer_id' => (int) $cart->id_customer,
			'currency_id' => (int) $cart->id_currency,
			'language_id' => (int) $cart->id_lang,
			'shop_id' => (int) $cart->id_shop,
			'items' => $items,
			'total_quantity' => $totalQuantity,
			'subtotal' => round($subtotal, 2),
			'updated_at' => isset($cart->date_upd) ? (string) $cart->date_upd : null,
		];
	}
}