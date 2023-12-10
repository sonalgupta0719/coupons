<?php

function validate_coupon_code_v3($all_parameter_data_array)
{
    $db = new DB();
    
	$get_coupon_code        = $all_parameter_data_array['get_coupon_code'];
    $db_mysqli              = $all_parameter_data_array['db_mysqli'];
    $slave_db_mysqli        = $all_parameter_data_array['slave_db_mysqli'];
    $get_user_id            = $all_parameter_data_array['get_user_id'];
    $get_recharge_amount    = $all_parameter_data_array['get_recharge_amount']; 
    $is_onboard_user        = $all_parameter_data_array['is_onboard_user'];
    $user_obd_id            = $all_parameter_data_array['user_obd_id'];
	$get_coupon_code 	    = strtoupper($get_coupon_code);
	$return_array 		    = array();

	//step1:check validity of coupon code
    $all_coupon_master_data_array = $db->getCouponMaster($get_coupon_code);
    $coupon = $db->getCouponMaster($get_coupon_code);
	// $get_coupon_master_data_query 			= "SELECT `id`, `coupon_code`, `coupon_type`, `start_time`, `end_time`, "
	// 										. "`minimum_recharge_amount`,`maximum_recharge_amount`, `maximum_cashback_amount`,`coupon_group_id`, "
	// 										. "`discount_in_percentage`, `flat_discount`, `user_id`,`coupon_group_per_limit`, "
	// 										. "`per_user_limit`,`delivery_type`,`is_first_wallet_recharge`, "
	// 										. "`status`,`is_deleted`,`utm_source` "
	// 										. "FROM `coupon_master` "
	// 										. "WHERE `status` = 1 and `is_deleted` = 0 and "
	// 										. "upper(coupon_code) = '$get_coupon_code' LIMIT 1;";
	// $result_get_coupon_master_data_query	= itl_query_v3($slave_db_mysqli, $get_coupon_master_data_query);
	$all_coupon_master_data_array 			= array();
    // TODO :: $all_coupon_master_data_array it is not an array convert this into a simple if statement
    while($row_get_coupon_master_data_query	= mysqli_fetch_assoc($result_get_coupon_master_data_query))
	{
	    $all_coupon_master_data_array[] 		= $row_get_coupon_master_data_query;
	}
	if(count($all_coupon_master_data_array) == 0)
	{
        return ['message'=>'Invalid Coupon Code', 'status'=>'error','is_coupon_applied'=>0,'coupon_code'=>NULL];
	}
	// else
	// {
            // $coupon_id 				    = Utils::xssClean($all_coupon_master_data_array['id']);
            // $coupon_id 				    = $coupon->id;
            // $coupon_code 			    = Utils::xssClean($all_coupon_master_data_array['coupon_code']);
            // $coupon_type 			    = Utils::xssClean($all_coupon_master_data_array['coupon_type']);
            // $coupon_start_datetime 	    = Utils::xssClean($all_coupon_master_data_array['start_time']);
            // $coupon_start_datetime      = date('Y-m-d H:i:s', strtotime($coupon_start_datetime));
            
            // $coupon_end_datetime 	    = Utils::xssClean($all_coupon_master_data_array['end_time']);
            // $coupon_end_datetime        = date('Y-m-d H:i:s', strtotime($coupon_end_datetime));

            // $discount_in_percentage     = Utils::xssClean($all_coupon_master_data_array['discount_in_percentage']); //not used
            // $minimum_recharge_amount    = Utils::xssClean($all_coupon_master_data_array['minimum_recharge_amount']);
            // $maximum_recharge_amount    = Utils::xssClean($all_coupon_master_data_array['maximum_recharge_amount']);
            // $is_first_wallet_recharge   = Utils::xssClean($all_coupon_master_data_array['is_first_wallet_recharge']);
            // $per_user_limit 		    = Utils::xssClean($all_coupon_master_data_array['per_user_limit']);
            // $delivery_type 			    = Utils::xssClean($all_coupon_master_data_array['delivery_type']);
            // $user_id 			        = Utils::xssClean($all_coupon_master_data_array['user_id']);
            // $maximum_cashback_amount    = Utils::xssClean($all_coupon_master_data_array['maximum_cashback_amount']);
            // $coupon_group_id            = Utils::xssClean($all_coupon_master_data_array['coupon_group_id']);
            // $coupon_group_per_limit     = Utils::xssClean($all_coupon_master_data_array['coupon_group_per_limit']);
            $user_id_array              = array();
            $dumy = new Coupon();
            $dumy->
            if($coupon->userId != "")
            {
                $user_id_array = explode(",",$coupon->userId);
            }

            /*--- UTM source code for payoneer onboard vendors start ---*/
                $utm_source 			    = strtolower(xss_clean_v3($all_coupon_master_data_array['utm_source']));
                $utm_source_array           = array();
                if($utm_source != "")
                {
                    $utm_source_array = explode(",",$utm_source);
                }

                if($is_onboard_user == 1)
                {
                    $get_utm_source_data_query = "SELECT `id`,`utm_source` FROM `onboard_user` WHERE `id` = $get_user_id AND `is_deleted` = 0";
                }
                else
                {
                    $get_utm_source_data_query = "SELECT `id`,`utm_source` FROM `onboard_user` WHERE `user_id` = $get_user_id AND `is_deleted` = 0";
                }
                    
                $result_get_utm_source_data_query = itl_query_v3($db_mysqli, $get_utm_source_data_query);
                $ou_user_utm_source = mysqli_fetch_assoc($result_get_utm_source_data_query)['utm_source'];
                $ou_user_utm_source = strtolower($ou_user_utm_source);

                $wallet_recharge_records 	= 0;
                $db_obd_user_id             = 0;

                if($is_first_wallet_recharge == 1 && $is_onboard_user == 0)
                {
                    $get_wallet_data_query 				= "SELECT count(`id`) as `wallet_recharge_records` "
                                                        . "FROM `credit_transaction_history` "
                                                        . "WHERE `user_id` = $get_user_id AND `is_deleted` = 0 AND "
                                                        . "`remarks` LIKE 'Payment Made FROM RZP pay_%';";
                    $result_get_wallet_data_query    	= itl_query_v3($slave_db_mysqli, $get_wallet_data_query);
                    while ($row_get_wallet_data_query	= mysqli_fetch_assoc($result_get_wallet_data_query))
                    {
                        $wallet_recharge_records 		= xss_clean_v3($row_get_wallet_data_query['wallet_recharge_records']);
                    }
                }
                
                $current_date_time 	= date('Y-m-d H:i:s');
            /*--- UTM source code for payoneer onboard vendors end ---*/

            /*--- Coupon check for intl user start ---*/
                if($delivery_type == 1)
                {
                    $get_intl_user_data_query 		    = "SELECT `obd_user_id` "
                                                        . "FROM `user` "
                                                        . "WHERE `obd_user_id` = $user_obd_id "
                                                        . "AND `status` = '1' AND `is_deleted` = '0' limit 1;";
                    $result_get_intl_user_data_query    = itl_query_v3($slave_db_mysqli, $get_intl_user_data_query);
                    while($row_get_intl_user_data_query	= mysqli_fetch_assoc($result_get_intl_user_data_query))
                    {
                        $db_obd_user_id 				= $row_get_intl_user_data_query['obd_user_id'];
                    }
                }
            /*--- Coupon check for intl user end ---*/

            /*--- Check if coupon in expired or not start ---*/
                $is_expired = 1;
                if($current_date_time >= $coupon_start_datetime && $coupon_end_datetime >= $current_date_time) //condition changed by sonal on 07-09-2023
                {
                    $is_expired = 0;
                }
            /*--- Check if coupon in expired or not end ---*/

            /*--- get coupon details data start ---*/
                $custome_query = "";
                if($coupon_group_id == 0 || $coupon_group_id == "")
                {
                    $custome_query .= "`coupon_id` = '$coupon->id' AND ";
                }
                else
                {
                    $custome_query .= "`coupon_group_id` = '$coupon_group_id' AND ";
                }
                
                $get_coupon_details_data_query 				= "SELECT `id`, `user_id`, `coupon_id`, `recharge_amount`, "
                                                                . "`coupon_discount`, `created_date`, `modified_date`, "
                                                                . "`status`, `is_deleted`, `coupon_group_id` "
                                                                . "FROM `coupon_details` "
                                                                . "WHERE `status` = 1 AND `is_deleted` = 0 AND "
                                                                . "$custome_query `user_id` = $get_user_id;";
                $result_get_coupon_details_data_query		= itl_query_v3($slave_db_mysqli, $get_coupon_details_data_query);
                $all_coupon_details_data_array 				= array();
                while($row_get_coupon_details_data_query	= mysqli_fetch_assoc($result_get_coupon_details_data_query))
                {
                    $all_coupon_details_data_array[] 	    = $row_get_coupon_details_data_query;
                    $db_coupon_group_id = $row_get_coupon_details_data_query['coupon_group_id'];
                }
            /*--- get coupon details data end ---*/

            if($coupon->isExpired()) //check if coupon is expired or not
            {

                $return_array['message'] = 'Coupon Code Expired';
                $return_array['status']  = 'error';
            }
            elseif($delivery_type == 1 && $db_obd_user_id == 0) //check for coupons for intl user
            {
                $return_array['message'] = 'Coupon Only Valid for International User';
                $return_array['status']  = 'error';
            }
            elseif($utm_source != '' && !in_array($ou_user_utm_source,$utm_source_array)) //check for utm source coupons
            {
                $return_array['message'] = 'Coupon not applicable.';
                $return_array['status']  = 'error';
            }
            elseif($get_recharge_amount >= 0 && $get_recharge_amount < $minimum_recharge_amount) //check for Minimum recharge amount
            {
                $return_array['message'] = 'Minimum '.$minimum_recharge_amount.' Recharge Amount is required';
                $return_array['status']  = 'error';
            }
            elseif($maximum_recharge_amount > 0 && $get_recharge_amount >= 0 && $get_recharge_amount > $maximum_recharge_amount) //check for Maximum recharge amount
            {
                $return_array['message'] = 'Maximum '.$maximum_recharge_amount.' Recharge Amount is required';
                $return_array['status']  = 'error';
            }
            elseif($user_id != '' && !in_array($get_user_id,$user_id_array)) //check for user specific coupons
            {
                $return_array['message'] = 'Coupon not applicable.';
                $return_array['status']  = 'error';
            }
            elseif(count($all_coupon_details_data_array) > 0 && $coupon_group_id > 0 && $db_coupon_group_id == $coupon_group_id && (count($all_coupon_details_data_array) >= $coupon_group_per_limit)) //check for coupon group id per coupon group limit
            {
                $return_array['message'] = 'Only 1 coupon can be redeemed from Offer.';
                $return_array['status']  = 'error';
            }
            elseif(count($all_coupon_details_data_array) > 0 && $coupon_group_id > 0 && $db_coupon_group_id == $coupon_group_id && $per_user_limit > 0 && (count($all_coupon_details_data_array) >= $per_user_limit)) //check for coupon group id per coupon user limit
            {
                $return_array['message'] = 'Coupon Limit Exceeds for User.';
                $return_array['status']  = 'error';
            }
            elseif(($coupon_group_id == 0 || $coupon_group_id == "") && count($all_coupon_details_data_array) > 0 && $per_user_limit > 0 && count($all_coupon_details_data_array) > $per_user_limit) //check per user limit for non group coupons
            {
                $return_array['message'] = 'Coupon Limit Exceeds for User';
                $return_array['status']  = 'error';
            }
            elseif($is_first_wallet_recharge == 1 and $wallet_recharge_records > 0 && $is_onboard_user == 0)
            {
                $return_array['message'] = 'Coupon Code applicable only for First time wallet users';
                $return_array['status']  = 'error';
            }
            else
            {
                // if($coupon_start_datetime >= $current_date_time && $coupon_end_datetime <= $current_date_time) //commented by sonal on 07-09-2023
                // if($current_date_time >= $coupon_start_datetime && $coupon_end_datetime >= $current_date_time) //condition changed by sonal on 07-09-2023
                // {
                    $return_array['coupon_type'] 		= $coupon_type;
                    //step2:check coupon used by user
                    if($coupon_type == 3) //only for coupon type 3
                    {
                        if($minimum_recharge_amount > 0)
                        {
                            $current_month                  = date('n');
                            $get_coupon_details_query 		= "SELECT count(`id`) as total_count FROM `coupon_details` where `user_id` = '$get_user_id' and `coupon_id` = '$coupon_id' and `status` = 1 and `is_deleted` = 0 and MONTH(`created_date`) = '$current_month' group by MONTH(`created_date`);";
                            $result_get_coupon_details    	= itl_query_v3($slave_db_mysqli, $get_coupon_details_query);
                            $coupon_details_array           = array();
                            while ($row_get_coupon_details 	= mysqli_fetch_assoc($result_get_coupon_details))
                            {
                                $coupon_details_array[] 	= $row_get_coupon_details;
                            }

                            $total_coupon_used_count 		= $coupon_details_array[0]['total_count'];
                            if($per_user_limit <= $total_coupon_used_count)
                            {
                                $return_array['message'] 	= 'Coupon Limit Exceeds for User';
                                $return_array['status']  	= 'error';
                            }
                            else
                            {
                                $current_date_time                              = date('Y-m-d H:i:s');

                                //insert coupon details in credit transaction & wallet transaction
                                $all_parameter_data_array                       = array();
                                $all_parameter_data_array['db_mysqli']          = $db_mysqli;
                                $all_parameter_data_array['loggedin_user_id']   = $get_user_id;
                                $all_parameter_data_array['post_amount']        = $minimum_recharge_amount;
                                $all_parameter_data_array['coupon_discount']    = $minimum_recharge_amount;
                                $all_parameter_data_array['current_date_time']  = $current_date_time; 
                                $all_parameter_data_array['coupon_id']          = $coupon_id;
                                $all_parameter_data_array['coupon_code']        = $coupon_code;
                                fn_wallet_recharge_v3($all_parameter_data_array);
                                
                                $return_array['coupon_type'] 		= 3;
                                $return_array['recharge_amount'] 	= $minimum_recharge_amount;
                                $return_array['status']  			= 'success';
                            }
                        }
                        else
                        {
                            $return_array['message'] = 'Minimum '.$minimum_recharge_amount.' Recharge Amount is required';
                            $return_array['status']  = 'error';
                        }
                    }
                    else
                    {
                        // $get_coupon_details_data_query 				= "SELECT `id`, `user_id`, `coupon_id`, `recharge_amount`, "
                        // 											. "`coupon_discount`, `created_date`, `modified_date`, "
                        // 											. "`status`, `is_deleted` "
                        // 											. "FROM `coupon_details` "
                        // 											. "WHERE `status` = 1 AND `is_deleted` = 0 AND "
                        // 											. "`coupon_id` = '$coupon_id' AND `user_id` = $get_user_id;";
                        //                                             // echo $get_coupon_details_data_query;die;
                        // $result_get_coupon_details_data_query		= itl_query_v3($slave_db_mysqli, $get_coupon_details_data_query);
                        // $all_coupon_details_data_array 				= array();
                        // while($row_get_coupon_details_data_query	= mysqli_fetch_assoc($result_get_coupon_details_data_query))
                        // {
                        //     $all_coupon_details_data_array 			= $row_get_coupon_details_data_query;
                        // }

                        // echo "all_coupon_details_data_array";
                        // print_r($all_coupon_details_data_array);die;
                        // if(($coupon_group_id == 0 || $coupon_group_id == "") && count($all_coupon_details_data_array) > 0 && $per_user_limit > 0 && count($all_coupon_details_data_array) > $per_user_limit)
                        // {
                        // 	$return_array['message'] = 'Coupon Limit Exceeds for User';
                        // 	$return_array['status']  = 'error';
                        // }
                        // elseif(count($all_coupon_details_data_array) > 0 && $per_user_limit > 0 && count($all_coupon_details_data_array) > $per_user_limit)
                        // {
                        //     $return_array['message'] = 'Coupon Limit Exceeds for User';
                        // 	$return_array['status']  = 'error';
                        // }
                        // else
                        // {
                            // if($is_first_wallet_recharge == 1 and $wallet_recharge_records > 0 && $is_onboard_user == 0)
                            // {
                            //     $return_array['message'] = 'Coupon Code applicable only for First time wallet users';
                            //     $return_array['status']  = 'error';
                            // }
                            // else
                            // {
                                $return_array['message'] = '';
                                $return_array['status']  = 'success';
                            // }
                        // }
                    }
			// }
			// else
			// {
            //     $return_array['message'] = 'Coupon Code Expired';
			// 	$return_array['status']  = 'error';
			// }
		}
	// }
	// if($return_array['status'] == 'success')
	// {
        return ['status'=>'success','is_coupon_applied'=>1,'coupon_id'=>$coupon_id,'coupon_code'=>$coupon_code];
    // }
	// else
	// {
    //     $return_array["status"] 	        = "error";
    //     $return_array["is_coupon_applied"] 	= 0;
	// 	$return_array["coupon_id"] 			= $coupon_id;
	// 	$return_array["coupon_code"] 		= $coupon_code;
    // }
	// return $return_array;
}

?>