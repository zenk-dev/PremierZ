/*-----------------------------------------------------------------------------
-- VIEW名           ：WORKSTAFF_MOBILE_LIST_V
-- 作成者           ：zenk
-- 作成日           ：2012-02-28
-- 更新履歴         ：
-----------------------------------------------------------------------------*/
CREATE OR REPLACE VIEW `WORKSTAFF_MOBILE_LIST_V` AS
	SELECT
			ws.`DATA_ID`						 AS `DATA_ID`
		   ,es.`ESTIMATE_ID`					 AS `ESTIMATE_ID`
		   ,es.`ESTIMATE_CODE`					 AS `ESTIMATE_CODE`
		   ,es.`SUB_NUMBER`						 AS `SUB_NUMBER`
		   ,es.`ESTIMATE_USER_ID`				 AS `ESTIMATE_USER_ID`
		   ,es.`ESTIMATE_REQUEST_DATE`			 AS `ESTIMATE_REQUEST_DATE`
		   ,es.`SCHEDULE_FROM_DATE`				 AS `SCHEDULE_FROM_DATE`
		   ,es.`SCHEDULE_TO_DATE`				 AS `SCHEDULE_TO_DATE`
		   ,es.`WORK_NAME`						 AS `WORK_NAME`
		   ,es.`ENDUSER_COMPANY_ID`				 AS `ENDUSER_COMPANY_ID`
		   ,es.`ENDUSER_USER_ID`				 AS `ENDUSER_USER_ID`
		   ,es.`REQUEST_COMPANY_ID`				 AS `REQUEST_COMPANY_ID`
		   ,es.`REQUEST_USER_ID`				 AS `REQUEST_USER_ID`
		   ,es.`SUBMITTING_DATE1`				 AS `SUBMITTING_DATE1`
		   ,es.`SUBMITTING_DATE2`				 AS `SUBMITTING_DATE2`
		   ,es.`SUBMITTING_DATE3`				 AS `SUBMITTING_DATE3`
		   ,es.`SUBMITTING_DATE4`				 AS `SUBMITTING_DATE4`
		   ,es.`SUBMITTING_DATE5`				 AS `SUBMITTING_DATE5`
		   ,es.`FINAL_PRESENTATION_AMOUNT`		 AS `FINAL_PRESENTATION_AMOUNT`
		   ,es.`ORDER_DIVISION`					 AS `ORDER_DIVISION`
		   ,es.`WORK_COMPLETION_DATE`			 AS `WORK_COMPLETION_DATE`
		   ,es.`WORK_DIVISION`					 AS `WORK_DIVISION`
		   ,es.`BOOK_INPUT_DATE`				 AS `BOOK_INPUT_DATE`
		   ,es.`BILL_SENDING_DATE`				 AS `BILL_SENDING_DATE`
		   ,wc.`WORK_DATE`						 AS `WORK_DATE`
		   ,wc.`DEFAULT_ENTERING_SCHEDULE_TIMET` AS `DEFAULT_ENTERING_SCHEDULE_TIMET`
		   ,wc.`DEFAULT_LEAVE_SCHEDULE_TIMET`	 AS `DEFAULT_LEAVE_SCHEDULE_TIMET`
		   ,wc.`DEFAULT_WORKING_TIME`			 AS `DEFAULT_WORKING_TIME`
		   ,wc.`DEFAULT_BREAK_TIME`				 AS `DEFAULT_BREAK_TIME`
		   ,wc.`AGGREGATE_TIMET`				 AS `AGGREGATE_TIMET`
		   ,wc.`AGGREGATE_POINT`				 AS `AGGREGATE_POINT`
		   ,wc.`WORK_ARRANGEMENT_ID`			 AS `WORK_ARRANGEMENT_ID`
		   ,wc.`WORK_CONTENT_DETAILS`			 AS `WORK_CONTENT_DETAILS`
		   ,wc.`BRINGING_GOODS`					 AS `BRINGING_GOODS`
		   ,wc.`CLOTHES`						 AS `CLOTHES`
		   ,wc.`INTRODUCE`						 AS `INTRODUCE`
		   ,wc.`TRANSPORT_AMOUNT_REMARKS`		 AS `TRANSPORT_AMOUNT_REMARKS`
		   ,wc.`OTHER_REMARKS`					 AS `OTHER_REMARKS`
		   ,wc.`OTHER_COST`						 AS `OTHER_COST`
		   ,wc.`EXCESS_LIQUIDATION_FLAG`		 AS `EXCESS_LIQUIDATION_FLAG`
		   ,wc.`CANCEL_CHARGE`					 AS `CANCEL_CHARGE`
		   ,wc.`WORK_STATUS`					 AS `WORK_STATUS`
		   ,ws.`WORK_STAFF_ID`					 AS `WORK_STAFF_ID`
		   ,ws.`WORK_CONTENT_ID`				 AS `WORK_CONTENT_ID`
		   ,ws.`WORK_BASE_ID`					 AS `WORK_BASE_ID`
		   ,ba.`BASE_CODE`						 AS `WORK_BASE_CODE`
		   ,ba.`BASE_NAME`						 AS `WORK_BASE_NAME`
		   ,ba.`ADDRESS`						 AS `WORK_ADDRESS`
		   ,ba.`CLOSEST_STATION`				 AS `WORK_CLOSEST_STATION`
		   ,ws.`WORK_USER_ID`					 AS `WORK_USER_ID`
		   ,ws.`APPROVAL_DIVISION`				 AS `APPROVAL_DIVISION`
		   ,ws.`TRANSMISSION_FLAG`				 AS `TRANSMISSION_FLAG`
		   ,ws.`CANCEL_DIVISION`				 AS `CANCEL_DIVISION`
		   ,ws.`WORK_UNIT_PRICE`				 AS `WORK_UNIT_PRICE`
		   ,ws.`EXCESS_AMOUNT`					 AS `EXCESS_AMOUNT`
		   ,ws.`BASIC_TIME`						 AS `BASIC_TIME`
		   ,ws.`BREAK_TIME`						 AS `BREAK_TIME`
		   ,ws.`DISPATCH_SCHEDULE_TIMET`		 AS `DISPATCH_SCHEDULE_TIMET`
		   ,ws.`DISPATCH_STAFF_TIMET`			 AS `DISPATCH_STAFF_TIMET`
		   ,ws.`ENTERING_SCHEDULE_TIMET`		 AS `ENTERING_SCHEDULE_TIMET`
		   ,ws.`ENTERING_STAFF_TIMET`			 AS `ENTERING_STAFF_TIMET`
		   ,ws.`ENTERING_MANAGE_TIMET`			 AS `ENTERING_MANAGE_TIMET`
		   ,ws.`LEAVE_SCHEDULE_TIMET`			 AS `LEAVE_SCHEDULE_TIMET`
		   ,ws.`LEAVE_STAFF_TIMET`				 AS `LEAVE_STAFF_TIMET`
		   ,ws.`LEAVE_MANAGE_TIMET`				 AS `LEAVE_MANAGE_TIMET`
		   ,ws.`TRANSPORT_AMOUNT`				 AS `TRANSPORT_AMOUNT`
		   ,ws.`OTHER_AMOUNT`					 AS `OTHER_AMOUNT`
		   ,ws.`REMARKS`						 AS `REMARKS`
		   ,ws.`OVERTIME_WORK_AMOUNT`			 AS `OVERTIME_WORK_AMOUNT`
		   ,ws.`WORK_EXPENSE_AMOUNT_TOTAL`		 AS `WORK_EXPENSE_AMOUNT_TOTAL`
		   ,ws.`PAYMENT_AMOUNT_TOTAL`			 AS `PAYMENT_AMOUNT_TOTAL`
		   ,ws.`REAL_WORKING_HOURS`				 AS `REAL_WORKING_HOURS`
		   ,ws.`REAL_OVERTIME_HOURS`			 AS `REAL_OVERTIME_HOURS`
		   ,ws.`SUPPLIED_AMOUNT_TOTAL`			 AS `SUPPLIED_AMOUNT_TOTAL`
		   ,ws.`STAFF_STATUS`					 AS `STAFF_STATUS`
		   ,cm.`CODE_VALUE`						 AS `STAFF_STATUS_NAME`
		   ,ws.`DISPATCH_DELAY_NOTIFIED`		 AS `DISPATCH_DELAY_NOTIFIED`
		   ,ws.`ENTERING_DELAY_NOTIFIED`		 AS `ENTERING_DELAY_NOTIFIED`
		   ,ws.`LEAVE_DELAY_NOTIFIED`			 AS `LEAVE_DELAY_NOTIFIED`
		   ,ws.`WORK_UNIT_PRICE_DISPLAY_FLAG`	 AS `WORK_UNIT_PRICE_DISPLAY_FLAG`
		   ,ws.`VALIDITY_FLAG`					 AS `WS_VALIDITY_FLAG`
		   ,ws.`REGISTRATION_DATET`				 AS `WS_REGISTRATION_DATET`
		   ,ws.`REGISTRATION_USER_ID`			 AS `WS_REGISTRATION_USER_ID`
		   ,ws.`LAST_UPDATE_DATET`				 AS `WS_LAST_UPDATE_DATET`
		   ,ws.`LAST_UPDATE_USER_ID`			 AS `WS_LAST_UPDATE_USER_ID`
	FROM
			 `WORK_STAFF`			 ws
			,`WORK_CONTENTS`		 wc
			,`ESTIMATES`			 es
			,`BASES`				 ba
			,`COMMON_MASTER`		 cm
	WHERE	ws.`WORK_CONTENT_ID`   = wc.`WORK_CONTENT_ID`
	AND		wc.`ESTIMATE_ID`	   = es.`ESTIMATE_ID`
	AND		wc.`ESTIMATE_ID`	   = es.`ESTIMATE_ID`
	AND		ws.`WORK_BASE_ID`	   = ba.`BASE_ID`
	AND	   (ws.`STAFF_STATUS`	   = cm.`CODE_NAME`
	AND		cm.`CODE_SET`		   = 'STAFF_STATUS'
	AND		ws.`DATA_ID`		   = cm.`DATA_ID`)
;