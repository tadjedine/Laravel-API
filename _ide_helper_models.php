<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * Class PsAddress
 *
 * @property int $id_address
 * @property int $id_country
 * @property int|null $id_state
 * @property int $id_customer
 * @property int $id_manufacturer
 * @property int $id_supplier
 * @property int $id_warehouse
 * @property string $alias
 * @property string|null $company
 * @property string $lastname
 * @property string $firstname
 * @property string $address1
 * @property string|null $address2
 * @property string|null $postcode
 * @property string $city
 * @property string|null $other
 * @property string|null $phone
 * @property string|null $phone_mobile
 * @property string|null $vat_number
 * @property string|null $dni
 * @property Carbon $date_add
 * @property Carbon $date_upd
 * @property int $active
 * @property int $deleted
 * @package App\Models
 * @property-read \App\Models\Country|null $country
 * @property-read \App\Models\Customer|null $customer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereDateAdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereDateUpd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereDni($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereIdAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereIdCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereIdCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereIdManufacturer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereIdState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereIdSupplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereIdWarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereOther($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address wherePhoneMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereVatNumber($value)
 */
	class Address extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PsCart
 *
 * @property int $id_cart
 * @property int $id_shop_group
 * @property int $id_shop
 * @property int $id_carrier
 * @property string $delivery_option
 * @property int $id_lang
 * @property int $id_address_delivery
 * @property int $id_address_invoice
 * @property int $id_currency
 * @property int $id_customer
 * @property int $id_guest
 * @property string $secure_key
 * @property int $recyclable
 * @property int $gift
 * @property string|null $gift_message
 * @property bool $mobile_theme
 * @property int $allow_seperated_package
 * @property Carbon $date_add
 * @property Carbon $date_upd
 * @property string|null $checkout_session_data
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartRule> $cartRules
 * @property-read int|null $cart_rules_count
 * @property-read \App\Models\Customer|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartProduct> $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $order
 * @property-read int|null $order_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $productModels
 * @property-read int|null $product_models_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartProduct> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereAllowSeperatedPackage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereCheckoutSessionData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereDateAdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereDateUpd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereDeliveryOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereGift($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereGiftMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereIdAddressDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereIdAddressInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereIdCarrier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereIdCart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereIdCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereIdCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereIdGuest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereIdLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereIdShop($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereIdShopGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereMobileTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereRecyclable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereSecureKey($value)
 */
	class Cart extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_cart
 * @property int $id_cart_rule
 * @property-read \App\Models\Cart|null $cart
 * @property-read \App\Models\CartRule|null $cartRule
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartCartRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartCartRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartCartRule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartCartRule whereIdCart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartCartRule whereIdCartRule($value)
 */
	class CartCartRule extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PsCartProduct
 *
 * @property int $id_cart
 * @property int $id_product
 * @property int $id_address_delivery
 * @property int $id_shop
 * @property int $id_product_attribute
 * @property int $id_customization
 * @property int $quantity
 * @property Carbon $date_add
 * @property-read \App\Models\Cart|null $cart
 * @property-read \App\Models\ProductAttribute|null $combination
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\ProductAttribute|null $productAttribute
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartProduct whereDateAdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartProduct whereIdAddressDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartProduct whereIdCart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartProduct whereIdCustomization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartProduct whereIdProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartProduct whereIdProductAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartProduct whereIdShop($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartProduct whereQuantity($value)
 */
	class CartProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * Class CartRule
 *
 * @package App\Models
 * @property int $id_cart_rule
 * @property int $id_customer
 * @property \Illuminate\Support\Carbon $date_from
 * @property \Illuminate\Support\Carbon $date_to
 * @property string|null $description
 * @property int|null $quantity
 * @property int|null $quantity_per_user
 * @property int $priority
 * @property int $partial_use
 * @property string $code
 * @property float $minimum_amount
 * @property bool $minimum_amount_tax
 * @property int $minimum_amount_currency
 * @property bool $minimum_amount_shipping
 * @property int $country_restriction
 * @property int $carrier_restriction
 * @property int $group_restriction
 * @property int $cart_rule_restriction
 * @property int $product_restriction
 * @property int $shop_restriction
 * @property bool $free_shipping
 * @property float $reduction_percent
 * @property float $reduction_amount
 * @property int $reduction_tax
 * @property int $reduction_currency
 * @property int $reduction_product
 * @property int $reduction_exclude_special
 * @property int $gift_product
 * @property int $gift_product_attribute
 * @property int $highlight
 * @property int $active
 * @property \Illuminate\Support\Carbon $date_add
 * @property \Illuminate\Support\Carbon $date_upd
 * @property int|null $id_cart_rule_type
 * @property int $minimum_product_quantity
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartRuleCarrier> $cartRuleCarriers
 * @property-read int|null $cart_rule_carriers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartRuleCountry> $cartRuleCountries
 * @property-read int|null $cart_rule_countries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartRuleGroup> $cartRuleGroups
 * @property-read int|null $cart_rule_groups_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cart> $carts
 * @property-read int|null $carts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartRuleLang> $langs
 * @property-read int|null $langs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereCarrierRestriction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereCartRuleRestriction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereCountryRestriction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereDateAdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereDateUpd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereFreeShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereGiftProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereGiftProductAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereGroupRestriction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereHighlight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereIdCartRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereIdCartRuleType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereIdCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereMinimumAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereMinimumAmountCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereMinimumAmountShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereMinimumAmountTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereMinimumProductQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule wherePartialUse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereProductRestriction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereQuantityPerUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereReductionAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereReductionCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereReductionExcludeSpecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereReductionPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereReductionProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereReductionTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRule whereShopRestriction($value)
 */
	class CartRule extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_cart_rule
 * @property int $id_carrier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleCarrier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleCarrier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleCarrier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleCarrier whereIdCarrier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleCarrier whereIdCartRule($value)
 */
	class CartRuleCarrier extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_cart_rule
 * @property int $id_country
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleCountry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleCountry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleCountry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleCountry whereIdCartRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleCountry whereIdCountry($value)
 */
	class CartRuleCountry extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_cart_rule
 * @property int $id_group
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleGroup whereIdCartRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleGroup whereIdGroup($value)
 */
	class CartRuleGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_cart_rule
 * @property int $id_lang
 * @property string $name
 * @property-read \App\Models\CartRule|null $cartRule
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleLang query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleLang whereIdCartRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleLang whereIdLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartRuleLang whereName($value)
 */
	class CartRuleLang extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PsCategory
 *
 * @property int $id_category
 * @property int $id_parent
 * @property int $id_shop_default
 * @property int $level_depth
 * @property int $nleft
 * @property int $nright
 * @property int $active
 * @property Carbon $date_add
 * @property Carbon $date_upd
 * @property string $redirect_type
 * @property int $id_type_redirected
 * @property int $position
 * @property bool $is_root_category
 * @package App\Models
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read string|null $link_rewrite
 * @property-read string|null $name
 * @property-read \App\Models\CategoryLang|null $lang
 * @property-read Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDateAdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDateUpd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIdCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIdParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIdShopDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIdTypeRedirected($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIsRootCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereLevelDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereNleft($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereNright($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereRedirectType($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PsCategoryLang
 *
 * @property int $id_category
 * @property int $id_shop
 * @property int $id_lang
 * @property string $name
 * @property string|null $description
 * @property string|null $additional_description
 * @property string $link_rewrite
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryLang query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryLang whereAdditionalDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryLang whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryLang whereIdCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryLang whereIdLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryLang whereIdShop($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryLang whereLinkRewrite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryLang whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryLang whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CategoryLang whereName($value)
 */
	class CategoryLang extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PsCountry
 *
 * @property int $id_country
 * @property int $id_zone
 * @property int $id_currency
 * @property string $iso_code
 * @property int $call_prefix
 * @property int $active
 * @property bool $contains_states
 * @property bool $need_identification_number
 * @property bool $need_zip_code
 * @property string $zip_code_format
 * @property bool $display_tax_label
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCallPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereContainsStates($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereDisplayTaxLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereIdCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereIdCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereIdZone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereIsoCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereNeedIdentificationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereNeedZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereZipCodeFormat($value)
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PsCustomer
 *
 * @property int $id_customer
 * @property int $id_shop_group
 * @property int $id_shop
 * @property int $id_gender
 * @property int $id_default_group
 * @property int|null $id_lang
 * @property int $id_risk
 * @property string|null $company
 * @property string|null $siret
 * @property string|null $ape
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $passwd
 * @property Carbon $last_passwd_gen
 * @property Carbon|null $birthday
 * @property int $newsletter
 * @property string|null $ip_registration_newsletter
 * @property Carbon|null $newsletter_date_add
 * @property int $optin
 * @property string|null $website
 * @property float $outstanding_allow_amount
 * @property int $show_public_prices
 * @property int $max_payment_days
 * @property string $secure_key
 * @property string|null $note
 * @property int $active
 * @property bool $is_guest
 * @property bool $deleted
 * @property Carbon $date_add
 * @property Carbon $date_upd
 * @property string|null $reset_password_token
 * @property Carbon|null $reset_password_validity
 * @package App\Models
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cart> $carts
 * @property-read int|null $carts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereApe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereDateAdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereDateUpd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIdCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIdDefaultGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIdGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIdLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIdRisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIdShop($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIdShopGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIpRegistrationNewsletter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIsGuest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLastPasswdGen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereMaxPaymentDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereNewsletter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereNewsletterDateAdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereOptin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereOutstandingAllowAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePasswd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereResetPasswordToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereResetPasswordValidity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereSecureKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereShowPublicPrices($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereSiret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereWebsite($value)
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PsOrder
 *
 * @property int $id_order
 * @property string|null $reference
 * @property int $id_shop_group
 * @property int $id_shop
 * @property int $id_carrier
 * @property int $id_lang
 * @property int $id_customer
 * @property int $id_cart
 * @property int $id_currency
 * @property int $id_address_delivery
 * @property int $id_address_invoice
 * @property int $current_state
 * @property string $secure_key
 * @property string $payment
 * @property float $conversion_rate
 * @property string|null $module
 * @property int $recyclable
 * @property int $gift
 * @property string|null $gift_message
 * @property bool $mobile_theme
 * @property float $total_discounts
 * @property float $total_discounts_tax_incl
 * @property float $total_discounts_tax_excl
 * @property float $total_paid
 * @property float $total_paid_tax_incl
 * @property float $total_paid_tax_excl
 * @property float $total_paid_real
 * @property float $total_products
 * @property float $total_products_wt
 * @property float $total_shipping
 * @property float $total_shipping_tax_incl
 * @property float $total_shipping_tax_excl
 * @property float $carrier_tax_rate
 * @property float $total_wrapping
 * @property float $total_wrapping_tax_incl
 * @property float $total_wrapping_tax_excl
 * @property bool $round_mode
 * @property bool $round_type
 * @property int $invoice_number
 * @property int $delivery_number
 * @property Carbon $invoice_date
 * @property Carbon $delivery_date
 * @property int $valid
 * @property Carbon $date_add
 * @property Carbon $date_upd
 * @property string|null $note
 * @property-read \App\Models\Cart|null $cart
 * @property-read \App\Models\Customer|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderDetail> $details
 * @property-read int|null $details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCarrierTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereConversionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCurrentState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDateAdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDateUpd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeliveryNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereGift($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereGiftMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIdAddressDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIdAddressInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIdCarrier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIdCart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIdCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIdCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIdLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIdOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIdShop($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereIdShopGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereInvoiceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereMobileTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRecyclable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRoundMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereRoundType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereSecureKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalDiscounts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalDiscountsTaxExcl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalDiscountsTaxIncl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalPaidReal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalPaidTaxExcl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalPaidTaxIncl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalProducts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalProductsWt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalShippingTaxExcl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalShippingTaxIncl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalWrapping($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalWrappingTaxExcl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalWrappingTaxIncl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereValid($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PsOrderDetail
 *
 * @property int $id_order_detail
 * @property int $id_order
 * @property int|null $id_order_invoice
 * @property int|null $id_warehouse
 * @property int $id_shop
 * @property int $product_id
 * @property int|null $product_attribute_id
 * @property int|null $id_customization
 * @property string $product_name
 * @property int $product_quantity
 * @property int $product_quantity_in_stock
 * @property int $product_quantity_refunded
 * @property int $product_quantity_return
 * @property int $product_quantity_reinjected
 * @property float $product_price
 * @property float $reduction_percent
 * @property float $reduction_amount
 * @property float $reduction_amount_tax_incl
 * @property float $reduction_amount_tax_excl
 * @property float $group_reduction
 * @property float $product_quantity_discount
 * @property string|null $product_ean13
 * @property string|null $product_isbn
 * @property string|null $product_upc
 * @property string|null $product_mpn
 * @property string|null $product_reference
 * @property string|null $product_supplier_reference
 * @property float $product_weight
 * @property int|null $id_tax_rules_group
 * @property int $tax_computation_method
 * @property string $tax_name
 * @property float $tax_rate
 * @property float $ecotax
 * @property float $ecotax_tax_rate
 * @property bool $discount_quantity_applied
 * @property string|null $download_hash
 * @property int|null $download_nb
 * @property Carbon|null $download_deadline
 * @property float $total_price_tax_incl
 * @property float $total_price_tax_excl
 * @property float $unit_price_tax_incl
 * @property float $unit_price_tax_excl
 * @property float $total_shipping_price_tax_incl
 * @property float $total_shipping_price_tax_excl
 * @property float $purchase_supplier_price
 * @property float $original_product_price
 * @property float $original_wholesale_price
 * @property float $total_refunded_tax_excl
 * @property float $total_refunded_tax_incl
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\ProductAttribute|null $productAttribute
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereDiscountQuantityApplied($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereDownloadDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereDownloadHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereDownloadNb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereEcotax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereEcotaxTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereGroupReduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereIdCustomization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereIdOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereIdOrderDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereIdOrderInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereIdShop($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereIdTaxRulesGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereIdWarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereOriginalProductPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereOriginalWholesalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductEan13($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductMpn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductQuantityDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductQuantityInStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductQuantityRefunded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductQuantityReinjected($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductQuantityReturn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductSupplierReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductUpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereProductWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail wherePurchaseSupplierPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereReductionAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereReductionAmountTaxExcl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereReductionAmountTaxIncl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereReductionPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereTaxComputationMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereTaxName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereTotalPriceTaxExcl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereTotalPriceTaxIncl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereTotalRefundedTaxExcl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereTotalRefundedTaxIncl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereTotalShippingPriceTaxExcl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereTotalShippingPriceTaxIncl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereUnitPriceTaxExcl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetail whereUnitPriceTaxIncl($value)
 */
	class OrderDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $tokenable_type
 * @property int $tokenable_id
 * @property string $name
 * @property string $token
 * @property array<array-key, mixed>|null $abilities
 * @property \Illuminate\Support\Carbon|null $last_used_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $tokenable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereAbilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereTokenableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereTokenableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonalAccessToken whereUpdatedAt($value)
 */
	class PersonalAccessToken extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PsProduct
 *
 * @property int $id_product
 * @property int|null $id_supplier
 * @property int|null $id_manufacturer
 * @property int|null $id_category_default
 * @property int $id_shop_default
 * @property int $id_tax_rules_group
 * @property int $on_sale
 * @property int $online_only
 * @property string|null $ean13
 * @property string|null $isbn
 * @property string|null $upc
 * @property string|null $mpn
 * @property float $ecotax
 * @property int $quantity
 * @property int $minimal_quantity
 * @property int|null $low_stock_threshold
 * @property bool $low_stock_alert
 * @property float $price
 * @property float $wholesale_price
 * @property string|null $unity
 * @property float $unit_price
 * @property float $unit_price_ratio
 * @property float $additional_shipping_cost
 * @property string|null $reference
 * @property string|null $supplier_reference
 * @property string $location
 * @property float $width
 * @property float $height
 * @property float $depth
 * @property float $weight
 * @property int $out_of_stock
 * @property int $additional_delivery_times
 * @property bool|null $quantity_discount
 * @property int $customizable
 * @property int $uploadable_files
 * @property int $text_fields
 * @property int $active
 * @property string $redirect_type
 * @property int $id_type_redirected
 * @property bool $available_for_order
 * @property Carbon|null $available_date
 * @property bool $show_condition
 * @property string $condition
 * @property bool $show_price
 * @property bool $indexed
 * @property string $visibility
 * @property bool $cache_is_pack
 * @property bool $cache_has_attachments
 * @property bool $is_virtual
 * @property int|null $cache_default_attribute
 * @property Carbon $date_add
 * @property Carbon $date_upd
 * @property bool $advanced_stock_management
 * @property int $pack_stock_type
 * @property int $state
 * @property string $product_type
 * @package App\Models
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartProduct> $cartProducts
 * @property-read int|null $cart_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cart> $carts
 * @property-read int|null $carts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \App\Models\ProductImage|null $coverImage
 * @property-read \App\Models\Category|null $defaultCategory
 * @property-read string|null $description
 * @property-read string|null $description_short
 * @property-read string|null $link_rewrite
 * @property-read string|null $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductImage> $images
 * @property-read int|null $images_count
 * @property-read \App\Models\ProductLang|null $lang
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductAttribute> $productAttribute
 * @property-read int|null $product_attribute_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereAdditionalDeliveryTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereAdditionalShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereAdvancedStockManagement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereAvailableDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereAvailableForOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCacheDefaultAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCacheHasAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCacheIsPack($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCustomizable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDateAdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDateUpd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereEan13($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereEcotax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIdCategoryDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIdManufacturer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIdProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIdShopDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIdSupplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIdTaxRulesGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIdTypeRedirected($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIndexed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsVirtual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereLowStockAlert($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereLowStockThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereMinimalQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereMpn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereOnSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereOnlineOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereOutOfStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePackStockType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereQuantityDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereRedirectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereShowCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereShowPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSupplierReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereTextFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUnitPriceRatio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUnity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUploadableFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereWholesalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereWidth($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PsProductAttribute
 *
 * @property int $id_product_attribute
 * @property int $id_product
 * @property string|null $reference
 * @property string|null $supplier_reference
 * @property string|null $ean13
 * @property string|null $isbn
 * @property string|null $upc
 * @property string|null $mpn
 * @property float $wholesale_price
 * @property float $price
 * @property float $ecotax
 * @property float $weight
 * @property float $unit_price_impact
 * @property int|null $default_on
 * @property int $minimal_quantity
 * @property int|null $low_stock_threshold
 * @property bool $low_stock_alert
 * @property Carbon|null $available_date
 * @package App\Models
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CartProduct> $cartProducts
 * @property-read int|null $cart_products_count
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereAvailableDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereDefaultOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereEan13($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereEcotax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereIdProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereIdProductAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereIsbn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereLowStockAlert($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereLowStockThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereMinimalQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereMpn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereSupplierReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereUnitPriceImpact($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereUpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductAttribute whereWholesalePrice($value)
 */
	class ProductAttribute extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PsImage
 *
 * @property int $id_image
 * @property int $id_product
 * @property int $position
 * @property int|null $cover
 * @package App\Models
 * @property-read array $urls
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereIdImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereIdProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage wherePosition($value)
 */
	class ProductImage extends \Eloquent {}
}

namespace App\Models{
/**
 * Class PsProductLang
 *
 * @property int $id_product
 * @property int $id_shop
 * @property int $id_lang
 * @property string|null $description
 * @property string|null $description_short
 * @property string $link_rewrite
 * @property string|null $meta_description
 * @property string|null $meta_title
 * @property string $name
 * @property string|null $available_now
 * @property string|null $available_later
 * @property string|null $delivery_in_stock
 * @property string|null $delivery_out_stock
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang whereAvailableLater($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang whereAvailableNow($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang whereDeliveryInStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang whereDeliveryOutStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang whereDescriptionShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang whereIdLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang whereIdProduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang whereIdShop($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang whereLinkRewrite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLang whereName($value)
 */
	class ProductLang extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 */
	class User extends \Eloquent {}
}

