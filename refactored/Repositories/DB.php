<?php 
class DB
{
    function getCouponMaster(string $couponCode): ?Coupon
    {
        //TODO :: the result from query convert it to Coupon class and then return
        $allCouponDataArray = "";
        $coupon = new Coupon($allCouponDataArray);

        return $coupon;
    }

    function getSourceForUserId(int $userId)
    {

    }

    function getWalletForUserId(int $userId)
    {}
}

?>