<?php


/**
 * @api {get} /admin/contract/{contractId} Get Contract Information
 * @apiVersion 0.1.0
 * @apiName Get Contract Information
 * @apiGroup Admin
 *
 *
 * @apiSuccessExample {json} Success-Example:
 *  {
 *      "code": 200,
 *      "data": {
 *          "contract": [
 *              {
 *                  "id": 27,
 *                  "sample_contract": {
 *                        "id":5,
 *                        "name_sample_contract":"Hợp đồng dùng thử 2 tháng",
 *                        "description": "Hợp đồng dùng thử ,nếu thấy hợp lý sẽ ký kết thêm",
 *                        "tags": "Công ty ABC , XYZ",
 *                        "created_by": "Hung Hoang",
 *                        "updated_by": null,
 *                        "category":1,
 *                        "sample_contract_properties": [
 *                               {
 *                                  "id": 18,
 *                                   "title": "muc dich su dung",
 *                                   "data_type": 1,
 *                                   "sample_contract_id": 5
 *                               },
 *                               {
 *                                   "id": 25,
 *                                   "title": "Number Phone User",
 *                                   "data_type": 1,
 *                                   "sample_contract_id": 5
 *                               }
 *                           ]
 *                       },
 *                      "company": {
 *                           "id": 5,
 *                           "company_name": "SCSOFT SUPENIENT",
 *                           "company_code": "sotdmy",
 *                           "color": "#1F78F4",
 *                           "logo_path": "6-2020-06-01_07-30-17-685392.png",
 *                           "logo_url": "http://127.0.0.1:8000/storage/companies/6-2020-06-01_07-30-17-685392.png",
 *                           "address": "Nguyen Tuan, Thanh Xuan , Ha Noi",
 *                           "service_id": 1,
 *                           "service_package": "Vip 1",
 *                           "extend_id": null,
 *                           "extend_package": "",
 *                           "max_user": 50,
 *                           "count_user": 24,
 *                           "max_data": 46.56613,
 *                           "count_data": 0.72557,
 *                           "count_sub_user": 6,
 *                           "created_at": "2020-05-04T00:00:00.000000Z",
 *                           "ts_created_at": 1588550400
 *                           },
 *                  "represent_company_hire": "Lưu Văn Hoàng",
 *                   "phone_company_hire": "0323564545",
 *                   "gender_hire": 1,
 *                   "name_company_rental": "VNExpress",
 *                   "address_company_rental": "Số 1 Tôn Thất Tùng",
 *                   "represent_company_rental": "Đỗ Trung Kiên",
 *                   "gender_rental": 1,
 *                   "phone_number_rental": "09892265658",
 *                   "service_package":{
 *                       "id": 1,
 *                       "title": "Vip 1",
 *                       "max_user": 50,
 *                       "max_user_data": 0.93132,
 *                       "max_data": 46.56613,
 *                       "price": 360
 *                   },
 *                   "type_service_package": 1,
 *                   "extend_package": {
 *                       "id": 1,
 *                       "title": "Extend 10",
 *                       "extend_data": 10,
 *                       "price": 100
 *                     },
 *                   "tax": 10.5,
 *                   "total_price": 440000,
 *                   "payment_status": 0,
 *                   "effective_date": "2020-02-27",
 *                   "end_date": "2020-08-27",
 *                   "cancellation_notice_deadline": 150,
 *                   "deposit_money": 140000,
 *                   "payment_term_all": 30,
 *                   "employee_represent": "Hà kế toán",
 *                   "created_by": 1,
 *                   "updated_by": null,
 *                   "created_at": "2021-02-02T07:34:46.000000Z",
 *                   "deleted_at": null
 *
 *              }
 *         ]
 *      }
 *  }
 */
