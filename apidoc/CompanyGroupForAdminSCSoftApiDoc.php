<?php

/**
 * @api {post} /admin/companies Create company
 * @apiVersion 0.1.0
 * @apiName CreateCompany
 * @apiGroup Company For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} company_name Name company
 * @apiParam {String} [company_code] Company code
 * @apiParam {String} address Address company
 * @apiParam {String} representative Company representative
 * @apiParam {String} tax_code Company tax code
 * @apiParam {Number} service_id Id service package
 * @apiParam {Number} [extend_id] Id extend package
 * @apiParam {String} color Color code.
 * @apiParam {File} logo File logo image
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "company_data": {
 *                   "id": 24,
 *                   "company_name": "Viettel",
 *                   "company_code": "VIETTEL",
 *                   "address": "Hà Nội",
 *                   "representative": "Hoàng Lâm",
 *                   "tax_code": "123456",
 *                   "logo_path": "1589522192.png",
 *                   "logo_url": "http://localhost/AlbumMakerV2/public/storage/companies/1589522192.png",
 *                   "color": "#ffffff",
 *                   "service_package": {
 *                       "id": 1,
 *                       "title": "Vip 1",
 *                       "max_user": 5,
 *                       "max_user_data": 10,
 *                       "max_data": 50,
 *                       "price": 10000000
 *                   },
 *                   "extend_package": {
 *                       "id": 1,
 *                       "title": "Gói extend 50GB",
 *                       "extend_data": 500,
 *                       "price": 10000000
 *                   },
 *                   "company_usage": {
 *                       "count_user": 0,
 *                       "count_sub_user": 0,
 *                       "count_data": 0,
 *                       "count_extend_data": 0
 *                   }
 *               }
 *           }
 *       }
 */

/**
 * @api {get} /admin/companies/:companyId Get company detail
 * @apiVersion 0.1.0
 * @apiName GetCompany
 * @apiGroup Company For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "company_data": {
 *                   "id": 24,
 *                   "company_name": "Viettel",
 *                   "company_code": "VIETTEL",
 *                   "address": "Hà Nội",
 *                   "representative": "Hoàng Lâm",
 *                   "tax_code": "123456",
 *                   "logo_path": "1589522192.png",
 *                   "logo_url": "http://localhost/AlbumMakerV2/public/storage/companies/1589522192.png",
 *                   "color": "#ffffff",
 *                   "service_package": {
 *                       "id": 1,
 *                       "title": "Vip 1",
 *                       "max_user": 5,
 *                       "max_user_data": 10,
 *                       "max_data": 50,
 *                       "price": 10000000
 *                   },
 *                   "extend_package": {
 *                       "id": 1,
 *                       "title": "Gói extend 50GB",
 *                       "extend_data": 500,
 *                       "price": 10000000
 *                   },
 *                   "company_usage": {
 *                       "count_user": 0,
 *                       "count_sub_user": 0,
 *                       "count_data": 0,
 *                       "count_extend_data": 0
 *                   }
 *               }
 *           }
 *       }
 */

/**
 * @api {get} /admin/companies Get List Company
 * @apiVersion 0.1.0
 * @apiName GetListCompany
 * @apiGroup Company For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} page Current page
 * @apiParam {Number} limit item in page
 * @apiParam {String} search keyword search
 * @apiParam {String} sort[id] sort by id: ASC - Old, DESC - New
 * @apiParam {String} sort[name] sort by company name: ASC - Old, DESC - New
 * @apiParam {String} sort[code] sort by company code: ASC - Old, DESC - New
 * @apiParam {String} sort[address] sort by address: ASC - Old, DESC - New
 * @apiParam {String} sort[package] sort by package: ASC - Old, DESC - New
 * @apiParam {String} sort[extend_package] sort by package: ASC - Old, DESC - New
 * @apiParam {String} sort[user] sort by number user: ASC - Old, DESC - New
 * @apiParam {String} [filter[package_ids]] Filter by user type. Ex ?filter[package_ids]=1,2,3
 * @apiParam {String} [filter[end_date]]   Filter by user end_date. Ex ?filter[end_date]=2021-02-23
 * @apiParam {String} [filter[created_at]]   Filter by user end_date. Ex ?filter[created_at]=2021-02-23
 * @apiParam {String} [filter[sample_contract_id]]   Filter by user end_date. Ex ?filter[sample_contract_id]=1
 * @apiParam {String} [filter[contract_status]]   Filter by user end_date. Ex ?filter[contract_status]=2
 * @apiSuccess data_list
 * @apiSuccess total
 * @apiSuccess current_page
 * @apiSuccess limit
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "data_list": [
 *                   {
 *                       "id": 2,
 *                       "company_name": "FPT Soft Company",
 *                       "company_code": "FPTSOFT",
 *                       "color": "#333333",
 *                       "logo_path": "logo.png",
 *                       "logo_url": "http://localhost/AlbumMakerV2/public/storage/companies/logo.png",
 *                       "address": "Ha Noi",
 *                       "service_id": 1,
 *                       "service_package": "Vip 1",
 *                       "extend_id": null,
 *                       "extend_package": "",
 *                       "max_user": 5,
 *                       "count_user": 0,
 *                       "count_sub_user": 0,
 *                       "max_data": 0,
 *                       "count_data": 0,
 *                       "created_at": "2020-03-19T17:37:55.000000Z",
 *                       "ts_created_at": 1584639475,
 *                       "contracts": [
                            {
                                "id": 1,
                                "sample_contract": {
                                    "id": 1,
                                    "name_sample_contract": "Hợp đồng dùng thử 1 tháng",
                                    "description": "Hợp đồng dùng thử nếu tốt sẽ đăng ký thêm",
                                    "tags": "VNT,VTC,Game",
                                    "category": 1,
                                    "created_by": "Hung Hoang",
                                    "updated_by": null,
                                    "sample_contract_properties": []
                                },
                                "represent_company_hire": "Lưu Văn Long",
                                "phone_company_hire": "0323564545",
                                "gender_hire": 1,
                                "name_company_rental": "VNExpress",
                                "address_company_rental": "Số 1 Tôn Thất Tùng",
                                "represent_company_rental": "Đỗ Trung Kiên",
                                "gender_rental": 1,
                                "phone_number_rental": "09892265658",
                                "service_package": {
                                    "id": 1,
                                    "title": "Vip 1",
                                    "max_user": 50,
                                    "max_user_data": 0.93132,
                                    "max_data": 46.56613,
                                    "price": 360
                                },
                                "type_service_package": 1,
                                "extend_package": {
                                    "id": 1,
                                    "title": "Extend 10",
                                    "extend_data": 10,
                                    "price": 100
                                },
                                "tax": 10.5,
                                "total_price": 440000,
                                "payment_status": 0,
                                "effective_date": "2020-02-27",
                                "end_date": "2020-08-27",
                                "cancellation_notice_deadline": 150,
                                "deposit_money": 140000,
                                "payment_term_all": 30,
                                "employee_represent": "Hà kế toán",
                                "created_by": 1,
                                "updated_by": null,
                                "created_at": "2021-02-04T08:02:28.000000Z",
                                "deleted_at": null
                                },
                            {
                                "id": 2,
                                "sample_contract": {
                                    "id": 2,
                                    "name_sample_contract": "Hợp đồng dùng thử 2 tháng",
                                    "description": "Hợp đồng dùng thử ,nếu thấy hợp lý sẽ ký kết thêm",
                                    "tags": "Công ty ABC , XYZ",
                                    "category": 1,
                                    "created_by": "Hung Hoang",
                                    "updated_by": "Hung Hoang",
                                    "sample_contract_properties": [
                                        {
                                            "id": 3,
                                            "title": "Mục đích sử dụng",
                                            "data_type": 1,
                                            "sample_contract_id": 2
                                        },
                                        {
                                            "id": 5,
                                            "title": "Number Phone User",
                                            "data_type": 1,
                                            "sample_contract_id": 2
                                        }
                                    ]
                                },
                                "represent_company_hire": "Lưu Văn Hoàng",
                                "phone_company_hire": "0323564545",
                                "gender_hire": 1,
                                "name_company_rental": "SamSung",
                                "address_company_rental": "Số 1 Tôn Thất Tùng",
                                "represent_company_rental": "Đỗ Trung Kiên",
                                "gender_rental": 1,
                                "phone_number_rental": "09892265658",
                                "service_package": {
                                    "id": 1,
                                    "title": "Vip 1",
                                    "max_user": 50,
                                    "max_user_data": 0.93132,
                                    "max_data": 46.56613,
                                    "price": 360
                                },
                                "type_service_package": 1,
                                "extend_package": {
                                    "id": 2,
                                    "title": "Extend 20",
                                    "extend_data": 20,
                                    "price": 10
                                },
                                "tax": 10.5,
                                "total_price": 540000,
                                "payment_status": 0,
                                "effective_date": "2020-01-21",
                                "end_date": "2020-09-27",
                                "cancellation_notice_deadline": 140,
                                "deposit_money": 140000,
                                "payment_term_all": 30,
                                "employee_represent": "Hà kế toán",
                                "created_by": 1,
                                "updated_by": null,
                                "created_at": "2021-02-04T09:17:34.000000Z",
                                "deleted_at": null
                                },
                            {
                                "id": 3,
                                "sample_contract": {
                                    "id": 5,
                                    "name_sample_contract": "Hợp đồng 2 năm",
                                    "description": "Hợp đồng Internet 2 năm ",
                                    "tags": "Internet,Wifi,",
                                    "category": 2,
                                    "created_by": "Hung Hoang",
                                    "updated_by": null,
                                    "sample_contract_properties": []
                                },
                                "represent_company_hire": "Đỗ Văn Thủy",
                                "phone_company_hire": "065656",
                                "gender_hire": 1,
                                "name_company_rental": "SCSoft",
                                "address_company_rental": "Số 1 Tôn Thất Tùng",
                                "represent_company_rental": "Nguyễn Xuân Tùng",
                                "gender_rental": 1,
                                "phone_number_rental": "09892265658",
                                "service_package": {
                                    "id": 1,
                                    "title": "Vip 1",
                                    "max_user": 50,
                                    "max_user_data": 0.93132,
                                    "max_data": 46.56613,
                                    "price": 360
                                },
                                "type_service_package": 1,
                                "extend_package": {
                                    "id": 2,
                                    "title": "Extend 20",
                                    "extend_data": 20,
                                    "price": 10
                                },
                                "tax": 10.5,
                                "total_price": 640000,
                                "payment_status": 1,
                                "effective_date": "2020-01-20",
                                "end_date": "2020-09-28",
                                "cancellation_notice_deadline": 140,
                                "deposit_money": 140000,
                                "payment_term_all": 30,
                                "employee_represent": "Hà kế toán",
                                "created_by": 1,
                                "updated_by": null,
                                "created_at": "2021-02-04T09:58:01.000000Z",
                                "deleted_at": null
                            }
                        ]
 *                   }
 *               ],
 *               "total": 2,
 *               "current_page": 1,
 *               "limit": 1
 *           }
 *       }
 */

/**
 * @api {post} /admin/companies/:companyId Update company
 * @apiVersion 0.1.0
 * @apiName UpdateCompany
 * @apiGroup Company For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} [company_name] Name company
 * @apiParam {String} [company_code] Company code
 * @apiParam {String} [address Address] company
 * @apiParam {String} [representative] Company representative
 * @apiParam {String} [tax_code] Company tax code
 * @apiParam {Number} [service_id] Id service package
 * @apiParam {Number} [extend_id] Id extend package
 * @apiParam {String} [color] Color code.
 * @apiParam {File} [logo] File logo image
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "company_data": {
 *                   "id": 24,
 *                   "company_name": "Viettel",
 *                   "company_code": "VIETTEL",
 *                   "address": "Hà Nội",
 *                   "representative": "Hoàng Lâm",
 *                   "tax_code": "123456",
 *                   "logo_path": "1589522192.png",
 *                   "logo_url": "http://localhost/AlbumMakerV2/public/storage/companies/1589522192.png",
 *                   "color": "#ffffff",
 *                   "service_package": {
 *                       "id": 1,
 *                       "title": "Vip 1",
 *                       "max_user": 5,
 *                       "max_user_data": 10,
 *                       "max_data": 50,
 *                       "price": 10000000
 *                   },
 *                   "extend_package": {
 *                       "id": 1,
 *                       "title": "Gói extend 50GB",
 *                       "extend_data": 500,
 *                       "price": 10000000
 *                   },
 *                   "company_usage": {
 *                       "count_user": 0,
 *                       "count_sub_user": 0,
 *                       "count_data": 0,
 *                       "count_extend_data": 0
 *                   }
 *               }
 *           }
 *       }
 */

/**
 * @api {delete} /admin/companies/:companyId Delete company
 * @apiVersion 0.1.0
 * @apiName DeleteCompany
 * @apiGroup Company For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {}
 *       }
 */

/**
 * @api {get} /admin/companies/:companyId/roles Get list Role In Company
 * @apiVersion 0.1.0
 * @apiName GetListCompanyRole
 * @apiGroup Company For Admin SCSoft
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} page Current page
 * @apiParam {Number} limit item in page
 * @apiParam {String} search keyword search
 * @apiParam {String} [filter[package_companyIds]] Filter by user type. Ex ?filter[package_companyIds]=4,5,7,8
 *
 * @apiSuccess data_list
 * @apiSuccess total
 * @apiSuccess current_page
 * @apiSuccess limit
 *
 * @apiSuccessExample {json} Success-Example:
 *      {
 *          "code": 200,
 *          "data": {
 *              "data_list": [
 *                  {
 *                      "id":45,
 *                      "name": "User",
 *                      "description": "",
 *                      "is_admin": false,
 *                      "is_default": true
 *                  }
 *              ],
 *              "total": 5,
 *              "current_page": 2,
 *              "limit": 1
 *          }
 *      }
 */
