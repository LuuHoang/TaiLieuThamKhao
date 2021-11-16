<?php

/**
 * @api {get} /app/companies Get List Company
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
                                "represent_company_hire": "Lê Văn Lo ",
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
     *                          "id": 15,
                                "company_name": "Công Ty CP Hoàng Long",
                                "company_code": "long",
                                "color": " ",
                                "logo_path": " ",
                                "logo_url": "http://127.0.0.1:8000/storage/companies/ ",
                                "address": "Số 86 Đường 513",
                                "service_id": 1,
                                "service_package": "Vip 1",
                                "extend_id": 1,
                                "extend_package": "Extend 10",
                                "max_user": 50,
                                "count_user": 0,
                                "count_sub_user": 0,
                                "max_data": 56.56613,
                                "count_data": 0,
                                "created_at": "2021-02-25T08:48:22.000000Z",
                                "ts_created_at": 1614242902,
                            "contracts": [
                            {
                                "id": 54,
                                "sample_contract": {
                                "id": 18,
                                "name_contract": "Hợp đồng dùng thử 11 tháng",
                                "description": "Hợp đồng dùng thử nếu tốt sẽ đăng ký thêm",
                                "tags": "Internet",
                                "category": 2,
                                "created_by": "Hung Hoang",
                                "updated_by": null,
                                "content": "<h1>PDF</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div>",
                                "sample_contract_properties": [
                                {
                                "id": 26,
                                "title": "Mục đích sử dụng",
                                "data_type": 1,
                                "sample_contract_id": 18
                                },
                                {
                                "id": 27,
                                "title": "Vị trí trung tâm",
                                "data_type": 2,
                                "sample_contract_id": 18
                                }
                                ]
                                },
                                "contract_code": null,
                                "represent_company_hire": "Trịnh Đình Nam",
                                "phone_company_hire": "0656515454",
                                "gender_hire": 1,
                                "name_company_rental": "SCSoft Laos",
                                "address_company_rental": "Số 86 Lưu Hoàng",
                                "represent_company_rental": "Nguyễn Văn Minh",
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
                                "type_service_package": 0,
                                "extend_package": null,
                                "tax": 10,
                                "total_price": 44000,
                                "payment_status": 3,
                                "effective_date": "2020-01-01",
                                "end_date": "2021-03-25",
                                "date_signed": "2020-05-01",
                                "cancellation_notice_deadline": 30,
                                "deposit_money": 4000,
                                "payment_term_all": 60,
                                "employee_represent": 3,
                                "created_by": 1,
                                "updated_by": null,
                                "created_at": "2021-02-25T08:48:23.000000Z",
                                "deleted_at": null
                                }
                            ]
                        },
 *                   }
 *               ],
 *               "total": 2,
 *               "current_page": 1,
 *               "limit": 1
 *           }
 *       }
 */

