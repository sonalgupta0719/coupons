<?php 
class Coupon {
    public $id;
    public $code;
    public $type;
    public $startDateTime;
    public $endDateTime;
    public $minimumRechargeAmount;
    public $maximumRechargeAmount;
    public $isFirstWalletRecharge;
    public $perUserLimit;
    public $deliveryType;
    public $userId;
    public $maximumCashbackAmount;
    public $couponGroupId;
    public $couponGroupPerLimit;

    public function __construct($data) {
        $this->id = Utils::xssClean($data['id']);
        $this->code = Utils::xssClean($data['coupon_code']);
        $this->type = Utils::xssClean($data['coupon_type']);
        $this->startDateTime = date('Y-m-d H:i:s', strtotime(Utils::xssClean($data['start_time'])));
        $this->endDateTime = date('Y-m-d H:i:s', strtotime(Utils::xssClean($data['end_time'])));
        $this->minimumRechargeAmount = Utils::xssClean($data['minimum_recharge_amount']);
        $this->maximumRechargeAmount = Utils::xssClean($data['maximum_recharge_amount']);
        $this->isFirstWalletRecharge = Utils::xssClean($data['is_first_wallet_recharge']);
        $this->perUserLimit = Utils::xssClean($data['per_user_limit']);
        $this->deliveryType = Utils::xssClean($data['delivery_type']);
        $this->userId = Utils::xssClean($data['user_id']);
        $this->maximumCashbackAmount = Utils::xssClean($data['maximum_cashback_amount']);
        $this->couponGroupId = Utils::xssClean($data['coupon_group_id']);
        $this->couponGroupPerLimit = Utils::xssClean($data['coupon_group_per_limit']);
    }

    function isExpired()
    {
        $current_date_time 	= date('Y-m-d H:i:s');
        return !($current_date_time >= $this->startDateTime && $this->endDateTime >= $current_date_time);
    }
}
?>