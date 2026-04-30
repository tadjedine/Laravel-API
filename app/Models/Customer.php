<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

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
 *
 * @package App\Models
 */
class Customer extends Authenticatable
{
    use HasApiTokens;

    protected $connection= 'prestashop';

	protected $table = 'ps_customer';
	protected $primaryKey = 'id_customer';
	public $timestamps = false;

	protected $casts = [
		'id_shop_group' => 'int',
		'id_shop' => 'int',
		'id_gender' => 'int',
		'id_default_group' => 'int',
		'id_lang' => 'int',
		'id_risk' => 'int',
		'last_passwd_gen' => 'datetime',
		'birthday' => 'datetime',
		'newsletter' => 'int',
		'newsletter_date_add' => 'datetime',
		'optin' => 'int',
		'outstanding_allow_amount' => 'float',
		'show_public_prices' => 'int',
		'max_payment_days' => 'int',
		'active' => 'int',
		'is_guest' => 'bool',
		'deleted' => 'bool',
		'date_add' => 'datetime',
		'date_upd' => 'datetime',
		'reset_password_validity' => 'datetime'
	];

	protected $hidden = [
        'passwd',
        'secure_key',
		'reset_password_token'
	];

	protected $fillable = [
		'id_shop_group',
		'id_shop',
		'id_gender',
		'id_default_group',
		'id_lang',
		'id_risk',
		'company',
		'siret',
		'ape',
		'firstname',
		'lastname',
		'email',
		'passwd',
		'last_passwd_gen',
		'birthday',
		'newsletter',
		'ip_registration_newsletter',
		'newsletter_date_add',
		'optin',
		'website',
		'outstanding_allow_amount',
		'show_public_prices',
		'max_payment_days',
		'secure_key',
		'note',
		'active',
		'is_guest',
		'deleted',
		'date_add',
		'date_upd',
		'reset_password_token',
		'reset_password_validity'
	];


    public function getAuthPassword(): string
    {
        return $this->passwd;
    }


    public function orders() {
    	return $this->hasMany(Order::class, 'id_customer');
	}

	public function carts() {
    	return $this->hasMany(Cart::class, 'id_customer');
	}
}
