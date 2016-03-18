<?php

/**
 * BaseTransaction
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $car
 * @property timestamp $date
 * @property decimal $price
 * @property decimal $insurance
 * @property decimal $commission
 * @property decimal $fuel
 * @property integer $user_id
 * @property integer $TransactionType_id
 * @property integer $reserve_id
 * @property boolean $completed
 * @property boolean $show_success
 * @property boolean $impulsive
 * @property integer $transaccion_original
 * @property integer $numero_factura
 * @property User $User
 * @property TransactionType $TransactionType
 * @property Reserve $Reserve
 * @property string $codpagocompra
 * @property integer $metodo_id
 * @property decimal $reverse_discount
 * @property boolean $is_paid_user
 * 
 * @method integer         getId()                 Returns the current record's "id" value
 * @method string          getCar()                Returns the current record's "car" value
 * @method timestamp       getDate()               Returns the current record's "date" value
 * @method decimal         getPrice()              Returns the current record's "price" value
 * @method decimal         getInsurance()          Returns the current record's "insurance" value
 * @method decimal         getCommission()         Returns the current record's "commission" value
 * @method decimal         getFuel()               Returns the current record's "fuel" value
 * @method integer         getUserId()             Returns the current record's "user_id" value
 * @method integer         getTransactionTypeId()  Returns the current record's "TransactionType_id" value
 * @method integer         getReserveId()          Returns the current record's "reserve_id" value
 * @method boolean         getCompleted()          Returns the current record's "completed" value
 * @method User            getUser()               Returns the current record's "User" value
 * @method TransactionType getTransactionType()    Returns the current record's "TransactionType" value
 * @method Reserve         getReserve()            Returns the current record's "Reserve" value
 * @method string          getCodpagocompra()      Returns the current record's "codpagocompra" value
 * @method integer         getMetodoId()           Returns the current record's "metodo_id" value
 * @method integer         getShowSuccess()        Returns the current record's "show_success" value
 * @method boolean         getImpulsive()          Returns the current record's "impulsive" value
 * @method integer         getTransaccionOriginal()      Returns the current record's "transaccion_original" value
 * @method integer         getNumeroFactura()      Returns the current record's "numero_factura" value
 * @method decimal         getReverseDiscount()    Returns the current record's "reverse_discount" value
 * @method boolean         getIsPaidUser()         Returns the current record's "is_paid_user" value
 * @method integer         getPaymentMethodId()    Returns the current record's "payment_method_id" value
 * @method string          getWebpayType()         Returns the current record's "webpay_type" value
 * @method string          getWebpaySharesNumber() Returns the current record's "webpay_shares_number" value
 * @method string          getWebpayAuthorization() Returns the current record's "webpay_authorization" value
 * @method string          getWebpayLastDigits()   Returns the current record's "webpay_last_digits" value
 * @method string          getWebpayToken()        Returns the current record's "webpay_token" value
 * 
 * @method Transaction     setId()                 Sets the current record's "id" value
 * @method Transaction     setCar()                Sets the current record's "car" value
 * @method Transaction     setDate()               Sets the current record's "date" value
 * @method Transaction     setPrice()              Sets the current record's "price" value
 * @method Transaction     setInsurance()          Sets the current record's "insurance" value
 * @method Transaction     setCommission()         Sets the current record's "commission" value
 * @method Transaction     setFuel()               Sets the current record's "fuel" value
 * @method Transaction     setUserId()             Sets the current record's "user_id" value
 * @method Transaction     setTransactionTypeId()  Sets the current record's "TransactionType_id" value
 * @method Transaction     setReserveId()          Sets the current record's "reserve_id" value
 * @method Transaction     setCompleted()          Sets the current record's "completed" value
 * @method Transaction     setUser()               Sets the current record's "User" value
 * @method Transaction     setTransactionType()    Sets the current record's "TransactionType" value
 * @method Transaction     setReserve()            Sets the current record's "Reserve" value
 * @method Transaction     setCodpagocompra()      Sets the current record's "codpagocompra" value
 * @method Transaction     setMetodoId()           Sets the current record's "metodo_id" value
 * @method Transaction     setShowSuccess()        Sets the current record's "show_sucess" value
 * @method Transaction     setImpulsive()          Sets the current record's "impulsive" value
 * @method integer         setTransaccionOriginal()      Sets the current record's "transaccion_original" value
 * @method integer         setNumeroFactura()      Sets the current record's "numero_factura" value
 * @method Transaction     setReverseDiscount()    Sets the current record's "reverse_discount" value
 * @method Transaction     setIsPaidUser()         Sets the current record's "is_paid_user" value
 * @method Transaction     setPaymentMethodId()    Sets the current record's "payment_method_id" value
 * @method Transaction     setWebpayType()         Sets the current record's "webpay_type" value
 * @method Transaction     setWebpaySharesNumber() Sets the current record's "webpay_shares_number" value
 * @method Transaction     setWebpayAuthorization() Sets the current record's "webpay_authorization" value
 * @method Transaction     setWebpayLastDigits()   Sets the current record's "webpay_last_digits" value
 * @method Transaction     setWebpaytoken()        Sets the current record's "webpay_token" value
 * 
 * @package    CarSharing
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTransaction extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('Transaction');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('car', 'string', 45, array(
             'type' => 'string',
             'length' => 45,
             ));
        $this->hasColumn('date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('price', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => 10,
             'scale' => '2',
             ));
        $this->hasColumn('insurance', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => 10,
             'scale' => '2',
             ));
        $this->hasColumn('commission', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => 10,
             'scale' => '2',
             ));
        $this->hasColumn('fuel', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => 10,
             'scale' => '2',
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
        $this->hasColumn('TransactionType_id', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
        $this->hasColumn('reserve_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             ));
        $this->hasColumn('completed', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->hasColumn('correlativo_pago', 'integer', 11, array(
             'type' => 'integer',
             'length'=> 11,
             ));
        $this->hasColumn('reverse_discount', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => 10,
             'scale' => '2',
             ));
        $this->hasColumn('is_paid_user', 'boolean', null, array(
             'type' => 'boolean',
             ));
        $this->index('fk_Transaction_User1', array(
             'fields' => 
             array(
              0 => 'user_id',
             ),
             ));
        $this->index('fk_Transaction_TransactionType1', array(
             'fields' => 
             array(
              0 => 'TransactionType_id',
             ),
             ));
        $this->index('id_UNIQUE', array(
             'fields' => 
             array(
              0 => 'id',
             ),
             'type' => 'unique',
             ));
        $this->index('fk_Transaction_Reserve1', array(
             'fields' => 
             array(
              0 => 'reserve_id',
             ),
             ));
        $this->hasColumn('codpagocompra', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('metodo_id', 'integer', 12, array(
             'type' => 'integer',
             'length' => 12,
             )); 
        $this->hasColumn('discountfb', 'integer', 12, array(
             'type' => 'integer',
             'length' => 12,
             )); 
        $this->hasColumn('discountamount', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => 10,
             'scale' => '2',
             ));
        $this->hasColumn('customerio', 'boolean', null, array(
            'type' => 'boolean',
            'notnull' => '0',
            'default' => 0,
            ));
        $this->hasColumn('show_success', 'boolean', null, array(
            'type' => 'boolean',
            'notnull' => '0',
            'default' => 0,
            ));
        $this->hasColumn('impulsive', 'boolean', null, array(
            'type' => 'boolean',
            'notnull' => '0',
            'default' => 0,
            ));
        $this->hasColumn('numero_factura', 'integer', 7, array(
            'type' => 'integer',
            'length' => 7,
            ));
        $this->hasColumn('transaccion_original', 'integer', null, array(
            'type' => 'integer',
            'length' => 11,
            'default' => 'null',
            ));
        $this->hasColumn('payment_method_id', 'integer', null, array(
            'type' => 'integer',
            'length' => 11,
            'default' => 'null'
            ));
        $this->hasColumn('webpay_type', 'string', 2, array(
             'type' => 'string',
             'length' => 2,
             'default' => null
             ));
        $this->hasColumn('webpay_shares_number', 'integer', null, array(
             'type' => 'integer',
             'length' => 11,
             'default' => null
             ));
        $this->hasColumn('webpay_authorization', 'string', 6, array(
             'type' => 'string',
             'length' => 6,
             'default' => null
             ));
        $this->hasColumn('webpay_last_digits', 'string', 16, array(
             'type' => 'string',
             'length' => 16,
             'default' => null
             ));

        $this->hasColumn('webpay_token', array(
             'type' => 'string',
             'length' => 255,
             'default' => null
             ));

        $this->index('fk_Transaction_PaymentMethod1', array(
             'fields' => 
             array(
              0 => 'payment_method_id',
             ),
             ));

        $this->hasColumn('base_commission', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => 10,
             'scale' => '2',
             ));

        $this->hasColumn('transbank_commission', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => 10,
             'scale' => '2',
             ));

        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'no action',
             'onUpdate' => 'no action'));

        $this->hasOne('TransactionType', array(
             'local' => 'TransactionType_id',
             'foreign' => 'id',
             'onDelete' => 'no action',
             'onUpdate' => 'no action'));

        $this->hasOne('Reserve', array(
             'local' => 'reserve_id',
             'foreign' => 'id'));
    }
}
