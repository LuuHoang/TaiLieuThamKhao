<?php

/**
 * @api {post} /admin/contract Create Contract
 * @apiVersion 0.1.0
 * @apiName CreateContract
 * @apiGroup Admin
 *
 * @apiParam {Number} sample_contract_id  is id of Sample Contract
 *
 * @apiParam {Text}  name_company_rental is Name Company Rental
 * @apiParam {Text}  [address_company_rental is Address Company Rental
 * @apiParam {Text}  represent_company_rental is Represent Company Rental
 * @apiParam {Number}  gender_rental is gender_rental min:0|max:2 (female:0|male:1|other:2)
 * @apiParam {Text}  phone_number_rental is Phone number rental
 *
 * @apiParam {Number} [company_id]  is Id Company Hire (Be Can Null)
 * @apiParam {Text} [company_name]  is Name Company Hire (Be Can Null).
 * @apiParam {Text} [company_code]  is Code Company Hire  (Be Can Null ,length <=8).
 * @apiParam {Text} represent_company_hire is represent company hire
 * @apiParam {Number}  gender_hire is gender_hire min:0|max:2 (female:0|male:1|other:2)
 * @apiParam {Text} [address]  is address Company Hire  (Be Can Null).
 * @apiParam {String} [tax_code] is Tax_code Company Hire (Be Can Null)
 * @apiParam {Text}  phone_company_hire is phone_company_hire
 *
 * @apiParam {File} [logo]  is logo Company Hire  (Be Can Null).
 * @apiParam {Text} [color]  is color  (Be Can Null).
 *
 * @apiParam {Number}  service_package_id is Id of Table Service_Package
 * @apiParam {Number}  type_service_package is type_service_package (fixed:0 |extend:1)
 * @apiParam {Number} [extend_package_id]  is Id extend_id Hire (Khi thêm mới công ty bắt buộc phải gửi lên|Be Can Null with type_service_package:0)
 *
 * @apiParam {Date}  effective_date is effective_date
 * @apiParam {Date}  end_date is end_date
 * @apiParam {Number}  cancellation_notice_deadline is day cancellation_notice_deadline (30 day)
 * @apiParam {Double}  tax is Tax
 * @apiParam {Double}  total_price is Total amount after tax
 * @apiParam {Array}    additional_content is additional_content
 * @apiParam {Number}   additional_content.sample_contract_property_id is ID Sample_Contract_Property
 * @apiParam {Text}    additional_content.content is Additional content .
 * @apiParam {Number}  payment_status is Payment_Status min:1|max:3 (unpaid:1|deposit:2|complete the payment :3)
 * @apiParam {Number}  deposit_money is deposit Money
 * @apiParam {Number}  payment_term_all is day payment_term_all (20 day)
 * @apiParam {Number} employee_represent  employee_represent (ID of AdminWebSite)
 * @apiParam {Number}  contract_status is contract_status min:0|max:7 (are confirming:0|Signed:1|deposited:2|finish:3|is almost expired:4|expired:5|Trial:6|Creating:7)
 * @apiParam {Date}  date_signed is date_signed
 * @apiSuccessExample {json} Success-Example:
 *  {
 *      "code": 200,
 *      "data": {
 *
 *      }
 *  }
 *
 */

 /**
 * @api {put} /admin/contract/:contractId Update Contract
 * @apiVersion 0.1.0
 * @apiName UpdateContract
 * @apiGroup Admin
 *
 * @apiParam {Number} sample_contract_id  is id of Sample Contract
 *
 * @apiParam {Text}  name_company_rental is Name Company Rental
 * @apiParam {Text}  address_company_rental is Address Company Rental
 * @apiParam {Text}  represent_company_rental is Represent Company Rental
 * @apiParam {Number}  [gender_rental] is gender_rental min:0|max:2 (female:0|male:1|other:2)
 * @apiParam {Text}  [phone_number_rental] is Phone number rental
 *
 * @apiParam {Number} [company_id]  is Id Company Hire (Be Can Null)
 * @apiParam {Text} [company_name]  is Name Company Hire (Be Can Null).
 * @apiParam {Text} [company_code]  is Code Company Hire  (Be Can Null ,length <=8).
 * @apiParam {Text} [represent_company_hire] is represent company hire
 * @apiParam {Number}  [gender_hire] is gender_hire min:0|max:2 (female:0|male:1|other:2)
 * @apiParam {Text} [address]  is address Company Hire  (Be Can Null).
 * @apiParam {String} [tax_code] is Tax_code Company Hire (Be Can Null)
 * @apiParam {Text}  [phone_company_hire] is phone_company_hire
 *
 * @apiParam {Number}  service_package_id is Id of Table Service_Package
 * @apiParam {Number}  [type_service_package] is type_service_package (fixed:0 |extend:1)
 * @apiParam {Number} [extend_package_id]  is Id extend_id Hire (Be Can Null with type_service_package:0)
 *
 * @apiParam {Date}  effective_date is effective_date
 * @apiParam {Date}  end_date is end_date
 * @apiParam {Number}  [cancellation_notice_deadline] is day cancellation_notice_deadline (30 day)
 * @apiParam {Double}  [tax] is Tax
 * @apiParam {Double}  [total_price] is Total amount after tax
 * @apiParam {Array}    [additional_content] is additional_content
 * @apiParam {Number}   [additional_content.id] is Id of Addition_Content (Khi muốn thêm nội dung cho nội dung bổ sung thì không cần gửi nên | chỉ có update mới gửi nên)
 * @apiParam {Number}   [additional_content.sample_contract_property_id] is ID Sample_Contract_Property
 * @apiParam {Text}    [additional_content.content] is Additional content .
 * @apiParam {Number}  [payment_status] is Payment_Status min:1|max:3 (unpaid:1|deposit:2|complete the payment :3)
 * @apiParam {Number}  [deposit_money] is deposit Money
 * @apiParam {Number}  [payment_term_all] is day payment_term_all (20 day)
 * @apiParam {Number} employee_represent  employee_represent (ID of AdminWebSite)
 * @apiParam {Number}  [contract_status] is contract_status min:0|max:7 (are confirming:0|Signed:1|deposited:2|finish:3|is almost expired:4|expired:5|Trial:6|Creating:7|Unfinished payment:8 |Expired Trial :9)
 * @apiParam {Date}  date_signed is date_signed
 * @apiSuccessExample {json} Success-Example:
 *  {
 *      "code": 200,
 *      "data": {
 *             "contract": {
                "id": 54,
                "sample_contract": {
                    "id": 2,
                    "name_contract": "Hợp đồng dùng thử 2 tháng",
                    "description": "Hợp đồng dùng thử ,nếu thấy hợp lý sẽ ký kết thêm",
                    "tags": "Công ty ABC , XYZ",
                    "category": null,
                    "created_by": "Hung Hoang",
                    "updated_by": "Hung Hoang",
                    "content": null,
                    "sample_contract_properties": []
                },
                "company": {
                    "id": 5,
                    "company_name": "SCSOFT SUPENIENT",
                    "company_code": "sotdmy",
                    "contract": {
                        "contract_name": "0656515454",
                        "contract_code": null,
                        "date_signed": "2020-01-01",
                        "payment_status": 3,
                        "contract_status": 3,
                        "effective_date": "2020-01-01",
                        "end_date": "2025-01-01",
                        "employee_represent": "Hung Hoang"
                    },
                    "color": "#1F78F4",
                    "representative": null,
                    "tax_code": null,
                    "logo_path": "6-2020-06-01_07-30-17-685392.png",
                    "logo_url": "http://127.0.0.1:8000/storage/companies/6-2020-06-01_07-30-17-685392.png",
                    "address": "Nguyen Tuan, Thanh Xuan , Ha Noi",
                    "service_id": 1,
                    "service_package": "Vip 1",
                    "extend_id": null,
                    "extend_package": "",
                    "max_user": 50,
                    "count_user": 24,
                    "max_data": 46.56613,
                    "count_data": 0.72557,
                    "count_sub_user": 6,
                    "created_at": "2020-05-04T00:00:00.000000Z",
                    "ts_created_at": 1588550400
                },
                "contract_code": null,
                "represent_company_hire": "Nguyen Tuan",
                "phone_company_hire": "432432432",
                "gender_hire": 1,
                "name_company_rental": "4324324",
                "address_company_rental": "4324324",
                "represent_company_rental": "324324324",
                "gender_rental": 0,
                "phone_number_rental": "432432432",
                "additional_content": [
                    {
                        "id": 35,
                        "contract_id": 54,
                        "sample_contract_property_id": 27,
                        "content": "Nội dung đã sửa 1",
                        "title": "Vị trí trung tâm"
                    },
                    {
                        "id": 36,
                        "contract_id": 54,
                        "sample_contract_property_id": 28,
                        "content": "Giá trị đã sửa 2",
                        "title": "Thông tin người cho thuê"
                    },
                    {
                        "id": 44,
                        "contract_id": 54,
                        "sample_contract_property_id": 29,
                        "content": "Tên điều khoản đặt biệt",
                        "title": "Điều khoản đặt biệt"
                    },
                    {
                        "id": 45,
                        "contract_id": 54,
                        "sample_contract_property_id": 29,
                        "content": "Tên điều khoản đặt biệt",
                        "title": "Điều khoản đặt biệt"
                    }
                ],
                "service_package": {
                    "id": 4,
                    "title": "Vip 4",
                    "max_user": 40,
                    "max_user_data": 15,
                    "max_data": 600,
                    "price": 100
                },
                "type_service_package": 1,
                "extend_package": {
                    "id": 2,
                    "title": "Extend 20",
                    "extend_data": 20,
                    "price": 10
                },
                "tax": "1032",
                "total_price": "432432",
                "payment_status": 2,
                "contract_status": 2,
                "effective_date": "2021-03-01",
                "end_date": "2021-02-23",
                "cancellation_notice_deadline": "4324",
                "deposit_money": "432",
                "payment_term_all": "32432",
                "employee_represent": {
                    "id": 1,
                    "full_name": "Hung Hoang"
                },
                "date_signed": "2021-02-23",
                "created_by": 1,
                "updated_by": null,
                "created_at": "2021-02-25T08:48:23.000000Z",
                "deleted_at": null
            }
 *      }
 *  }
 */

