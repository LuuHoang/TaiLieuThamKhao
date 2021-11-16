<?php

/**
 * @api {get} /companies/:companyCode Get Company Data By Company Code
 * @apiVersion 0.1.0
 * @apiName GetCompany
 * @apiGroup Company
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object} data.company_data
 * @apiSuccess {Integer} data.company_data.id Company - ID
 * @apiSuccess {String} data.company_data.company_name Company name
 * @apiSuccess {String} data.company_data.company_code Company code
 * @apiSuccess {Object} data.contract
 * @apiSuccess {String} data.contract.contract_name is Contract Name
 * @apiSuccess {String} data.contract.contract_code is Contract Code
 * @apiSuccess {Date} data.contract.date_signed is Date Signed Contract
 * @apiSuccess {Number} data.contract.payment_status (unpaid:1|deposit:2|complete the payment :3)
 * @apiSuccess {Number} data.contract.contract_status (are confirming:0|Signed:1|deposited:2|finish:3|is almost expired:4|expired:5|Trial:6|Creating:7|Unfinished payment:8 |Expired Trial :9)
 * @apiSuccess {Date} data.contract.effective_date  is Effective date of the contract
 * @apiSuccess {Date} data.contract.end_date is contract end date
 * @apiSuccess {String} data.contract.employee_represent is Name of the representative of the lessor
 * @apiSuccess {String} data.company_data.color Company color
 * @apiSuccess {String} data.company_data.logo_path Company logo path
 * @apiSuccess {String} data.company_data.address Company address
 * @apiSuccess {Integer} data.company_data.service_id Service package id
 * @apiSuccess {String} data.company_data.service_package Service package name
 * @apiSuccess {Double} data.company_data.service_package_price Service package Price
 * @apiSuccess {Integer} data.company_data.max_user Maximum number of users
 * @apiSuccess {Double} data.company_data.max_user_data Maximum User Data Service_Package (GB)
 * @apiSuccess {Integer} data.company_data.count_user Number of current users
 * @apiSuccess {Float} data.company_data.max_data Maximum number of data media
 * @apiSuccess {Float} data.company_data.count_data Number of current data media
 * @apiSuccess {String} data.company_data.created_at Created at time
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "company_data": {
 *                 "id": 1,
 *                 "company_name": "SC Soft",
 *                 "company_code": "SCSOFT",
 *                 "contract": {
 *                       "contract_name": "Hợp đồng 1 năm",
 *                       "contract_code": "9q6r2weq1b",
 *                       "date_signed": "2021-02-23",
 *                       "payment_status": 2,
 *                       "contract_status": 2,
 *                       "effective_date": "2021-03-01",
 *                       "end_date": "2021-02-23",
 *                       "employee_represent": "Hung Hoang"
 *                   },
 *                 "color": "#444444",
 *                 "logo_path": "1-2020-04-28_10-44-11-078558.png",
 *                 "logo_url": "http://localhost/storage/companies/1-2020-04-28_10-44-11-078558.png",
 *                 "address": "186 Nguyen Tuan",
 *                 "service_id": 1,
 *                 "service_package": "Vip 1",
 *                 "service_package_price": 360,
 *                 "extend_id": null,
 *                 "extend_package": "",
 *                 "max_user": 5,
 *                 "max_user_data": 0.93132,
 *                 "count_user": 3,
 *                 "max_data": 50,
 *                 "count_data": 20,
 *                 "created_at": "2020-03-19T17:37:55.000000Z",
 *                 "ts_created_at": 1584639475
 *             }
 *         }
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System Error!"
 *     }
 */

/**
 * @api {get} /app/user/contract Get List Contracts of User ( APP )
 * @apiVersion 0.1.0
 * @apiName GetListContract
 * @apiGroup Company
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Array} data.list_contracts
 * @apiSuccess {Number} data.list_contracts.id is ID Contract
 * @apiSuccess {String} data.list_contracts.contract_code is Contract Code
 * @apiSuccess {String} data.list_contracts.name_company_rental is Name Company Rental
 *
 * @apiSuccess {Object} data.list_contracts.service_package
 * @apiSuccess {Number} data.list_contracts.service_package.id is ID Service Package.
 * @apiSuccess {String} data.list_contracts.service_package.title is Name Service Package.
 * @apiSuccess {Number} data.list_contracts.service_package.max_user is Max User Service Package.
 * @apiSuccess {Number} data.list_contracts.service_package.max_user_data  is data per user Max of Service Package (GB).
 * @apiSuccess {Number} data.list_contracts.service_package.max_data is  data all user Max Service Package.
 * @apiSuccess {Number} data.list_contracts.service_package.price is Price Service Package.
 * @apiSuccess {Object} data.list_contracts.template_contract
 * @apiSuccess {Number} data.list_contracts.template_contract.id is ID Contract Template.
 * @apiSuccess {String} data.list_contracts.template_contract.name_contract is Name template_contract.
 * @apiSuccess {String} data.list_contracts.template_contract.description is description .
 * @apiSuccess {String} data.list_contracts.template_contract.tags  is tags
 * @apiSuccess {Number} data.list_contracts.template_contract.category is  Category Contract ( Trial:1 | fixed :2 | extend:3).
 * @apiSuccess {String} data.list_contracts.template_contract.created_by is People Created.
 * @apiSuccess {String} data.list_contracts.template_contract.content is Content of Contract
 * @apiSuccess {Array} data.list_contracts.sample_contract_properties
 * @apiSuccess {Number} data.list_contracts.sample_contract_properties.id is ID Sample_Contract_Properties.
 * @apiSuccess {String} data.list_contracts.sample_contract_properties.title is Name  Sample_Contract_Properties.
 * @apiSuccess {Number} data.list_contracts.sample_contract_properties.data_type is Type Data (Text:1|Date:2|Number:3).
 * @apiSuccess {Date} data.list_contracts.effective_date is  Effective Date
 * @apiSuccess {Date} data.list_contracts.end_date is end_date Contract
 * @apiSuccess {Date} data.list_contracts.date_signed is  date_signed Contract
 * @apiSuccess {String} data.list_contracts.represent_company_hire is  represent_company_hire
 * @apiSuccess {Date} data.list_contracts.payment_status is  Payment Status  (unpaid:1|deposit:2|complete the payment :3)
 * @apiSuccess {Date} data.list_contracts.contract_status is  Contract Status (are confirming:0|Signed:1|deposited:2|finish:3|is almost expired:4|expired:5|Trial:6|Creating:7|Unfinished payment:8 |Expired Trial :9)
 * @apiSuccess {String} data.list_contracts.represent_company_rental is  represent_company_rental
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "list_contracts": [
 *                      {
                            "id": 60,
                            "company_name": "Công ty cố phần VTV3",
                            "contract_code": "9q6r2weq1b",
                            "name_company_rental": "4324324",
 *                          "service_package": {
                                    "id": 4,
                                    "title": "Vip 4",
                                    "max_user": 40,
                                    "max_user_data": 15,
                                    "max_data": 600,
                                    "price": 100
                                },
 *                          "template_contract": {
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
                                    },
 *                              ]
 *                           },
                            "service_name": null,
                            "effective_date": "2021-03-01",
                            "end_date": "2021-02-23",
                            "date_signed": "2021-02-23",
                            "represent_company_hire": "null",
                            "phone_company_hire": "432432432",
                            "payment_status": 2,
                            "contract_status": 2,
                            "created_at": "2021-02-26T07:53:08.000000Z",
                            "updated_at": "2021-02-26T07:53:08.000000Z",
                            "represent_company_rental": "324324324"
                         }
 *
 *                  ]
 *             }
 *         }
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System Error!"
 *     }
 */


/**
 * @api {get} /app/contract Get List Contracts of User ( PAGING APP )
 * @apiVersion 0.1.0
 * @apiName GetListContract Paging
 * @apiGroup Company
 *
 * @apiParam {Number} page Current page
 * @apiParam {Number} limit item in page
 * @apiParam {String} search keyword search
 * @apiParam {String} sort[id] sort by id: ASC - Old, DESC - New
 * @apiParam {String} sort[created_at] sort by created at: ASC - Old, DESC - New
 * @apiParam {String} sort[effective_date] sort by effective_date : ASC - Old, DESC - New
 * @apiParam {String} sort[end_date] sort by end_date: ASC - Old, DESC - New
 * @apiParam {String} sort[updated_at] sort by updated_at: ASC - Old, DESC - New
 * @apiParam {String} [filter[package_ids]] Filter by user type. Ex ?filter[package_ids]=1,2,3
 * @apiParam {String} [filter[contract_status]] Filter by user type. Ex ?filter[contract_status]=1,2,3
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Array} data.list_contracts
 * @apiSuccess {Number} data.list_contracts.id is ID Contract
 * @apiSuccess {String} data.list_contracts.contract_code is Contract Code
 * @apiSuccess {String} data.list_contracts.name_company_rental is Name Company Rental
 *
 * @apiSuccess {Object} data.list_contracts.service_package
 * @apiSuccess {Number} data.list_contracts.service_package.id is ID Service Package.
 * @apiSuccess {String} data.list_contracts.service_package.title is Name Service Package.
 * @apiSuccess {Number} data.list_contracts.service_package.max_user is Max User Service Package.
 * @apiSuccess {Number} data.list_contracts.service_package.max_user_data  is data per user Max of Service Package (GB).
 * @apiSuccess {Number} data.list_contracts.service_package.max_data is  data all user Max Service Package.
 * @apiSuccess {Number} data.list_contracts.service_package.price is Price Service Package.
 * @apiSuccess {Object} data.list_contracts.template_contract
 * @apiSuccess {Number} data.list_contracts.template_contract.id is ID Contract Template.
 * @apiSuccess {String} data.list_contracts.template_contract.name_contract is Name template_contract.
 * @apiSuccess {String} data.list_contracts.template_contract.description is description .
 * @apiSuccess {String} data.list_contracts.template_contract.tags  is tags
 * @apiSuccess {Number} data.list_contracts.template_contract.category is  Category Contract ( Trial:1 | fixed :2 | extend:3).
 * @apiSuccess {String} data.list_contracts.template_contract.created_by is People Created.
 * @apiSuccess {String} data.list_contracts.template_contract.content is Content of Contract
 * @apiSuccess {Array} data.list_contracts.sample_contract_properties
 * @apiSuccess {Number} data.list_contracts.sample_contract_properties.id is ID Sample_Contract_Properties.
 * @apiSuccess {String} data.list_contracts.sample_contract_properties.title is Name  Sample_Contract_Properties.
 * @apiSuccess {Number} data.list_contracts.sample_contract_properties.data_type is Type Data (Text:1|Date:2|Number:3).
 * @apiSuccess {Date} data.list_contracts.effective_date is  Effective Date
 * @apiSuccess {Date} data.list_contracts.end_date is end_date Contract
 * @apiSuccess {Date} data.list_contracts.date_signed is  date_signed Contract
 * @apiSuccess {String} data.list_contracts.represent_company_hire is  represent_company_hire
 * @apiSuccess {Date} data.list_contracts.payment_status is  Payment Status  (unpaid:1|deposit:2|complete the payment :3)
 * @apiSuccess {Date} data.list_contracts.contract_status is  Contract Status (are confirming:0|Signed:1|deposited:2|finish:3|is almost expired:4|expired:5|Trial:6|Creating:7|Unfinished payment:8 |Expired Trial :9)
 * @apiSuccess {String} data.list_contracts.represent_company_rental is  represent_company_rental
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "list_contracts": [
 *                      {
                            "id": 60,
                            "company_name": "null",
                            "contract_code": "9q6r2weq1b",
                            "name_company_rental": "Công ty TNHH SC Soft Việt Nam",
 *                          "service_package": {
                                "id": 4,
                                "title": "Vip 4",
                                "max_user": 40,
                                "max_user_data": 15,
                                "max_data": 600,
                                "price": 100
                            },
 *                          "template_contract": {
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
                                    },
 *                              ]
 *                           },
                            "service_name": null,
                            "effective_date": "2020-01-01",
                            "end_date": "2025-01-01",
                            "date_signed": "2021-01-10",
                            "represent_company_hire": "Trịnh Đình Nam",
                            "phone_company_hire": "0656515454",
                            "payment_status": 3,
                            "contract_status": 3,
                            "created_at": "2021-02-24T11:20:28.000000Z",
                            "updated_at": "2021-02-24T11:20:28.000000Z",
                            "represent_company_rental": "Nguyễn Văn Minh"
                        },
 *                      {
                        "id": 54,
                        "company_name": null,
                        "contract_code": null,
                        "name_company_rental": "4324324",
                        "service_package": {
                            "id": 4,
                            "title": "Vip 4",
                            "max_user": 40,
                            "max_user_data": 15,
                            "max_data": 600,
                            "price": 100
                        },
                        "template_contract":{
 *                          "id": 18,
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
                            },
                            {
                                "id": 29,
                                "title": "Điều khoản đặt biệt",
                                "data_type": 1,
                                "sample_contract_id": 18
 *                           }
 *                         ]
                        },
                        "service_name": null,
                        "effective_date": "2021-03-01",
                        "end_date": "2021-02-23",
                        "date_signed": "2021-02-23",
                        "represent_company_hire": "Nguyen Tuan",
                        "phone_company_hire": "432432432",
                        "payment_status": 2,
                        "contract_status": 2,
                        "created_at": "2021-02-25T08:48:23.000000Z",
                        "updated_at": "2021-03-01T08:01:20.000000Z",
                        "represent_company_rental": "324324324"
 *
                    },
 *
 *                  ],
 *                  "total": 2,
 *                  "current_page": 1,
 *                  "limit": 20
 *             }
 *         }
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System Error!"
 *     }
 */

/**
 * @api {get} /web/contracts Get List Contracts of User ( WEB )
 * @apiVersion 0.1.0
 * @apiName GetListContract WEB
 * @apiGroup Company
 *
 * @apiParam {Number} [limit]  is page limit
 * @apiParam {Number} [page]   is page
 * @apiParam {Number} [sample_contract_id]  is id of Sample Contract
 * @apiParam {Text} [search]  is Key Search
 * @apiParam {Array} [filter]  is filter
 * @apiParam {date} [filter.created_at]  is filter by date created
 * @apiParam {string} [filter.end_date]  is filter end_date
 * @apiParam {string} [filter.employee]  is filter employee rental
 * @apiParam {string} [filter.company_name_hire]  is filter company name hire
 *
 * @apiParam {string} [filter.contract_status]  is filter contract_status (min:1|max:6) |are confirming:1|signed:2|has made a deposit:3|finish:4|is almost expired:5|expired:6|
 * @apiParam {Array} [sort]  is array sort
 * @apiParam {String} [sort.id] is sort by id |asc|desc|
 * @apiParam {String} [sort.company_name_hire] is sort |asc|desc|
 * @apiParam {String} [sort.created_at] is sort by created_at |asc|desc|
 * @apiParam {String} [sort.represent_company_hire] is sort |asc|desc|
 * @apiParam {String} [sort.effective_date] is sort |asc|desc|
 * @apiParam {String} [sort.end_date] is sort |asc|desc|
 * @apiParam {String} [sort.represent_company_rental] is sort |asc|desc|
 * @apiParam {String} [sort.updated_at] is sort |asc|desc|
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Array} data.list_contracts
 * @apiSuccess {Number} data.list_contracts.id is ID Contract
 * @apiSuccess {String} data.list_contracts.contract_code is Contract Code
 * @apiSuccess {String} data.list_contracts.name_company_rental is Name Company Rental
 *
 * @apiSuccess {Object} data.list_contracts.service_package
 * @apiSuccess {Number} data.list_contracts.service_package.id is ID Service Package.
 * @apiSuccess {String} data.list_contracts.service_package.title is Name Service Package.
 * @apiSuccess {Number} data.list_contracts.service_package.max_user is Max User Service Package.
 * @apiSuccess {Number} data.list_contracts.service_package.max_user_data  is data per user Max of Service Package (GB).
 * @apiSuccess {Number} data.list_contracts.service_package.max_data is  data all user Max Service Package.
 * @apiSuccess {Number} data.list_contracts.service_package.price is Price Service Package.
 * @apiSuccess {Object} data.list_contracts.template_contract
 * @apiSuccess {Number} data.list_contracts.template_contract.id is ID Contract Template.
 * @apiSuccess {String} data.list_contracts.template_contract.name_contract is Name template_contract.
 * @apiSuccess {String} data.list_contracts.template_contract.description is description .
 * @apiSuccess {String} data.list_contracts.template_contract.tags  is tags
 * @apiSuccess {Number} data.list_contracts.template_contract.category is  Category Contract ( Trial:1 | fixed :2 | extend:3).
 * @apiSuccess {String} data.list_contracts.template_contract.created_by is People Created.
 * @apiSuccess {String} data.list_contracts.template_contract.content is Content of Contract
 * @apiSuccess {Array} data.list_contracts.sample_contract_properties
 * @apiSuccess {Number} data.list_contracts.sample_contract_properties.id is ID Sample_Contract_Properties.
 * @apiSuccess {String} data.list_contracts.sample_contract_properties.title is Name  Sample_Contract_Properties.
 * @apiSuccess {Number} data.list_contracts.sample_contract_properties.data_type is Type Data (Text:1|Date:2|Number:3).
 * @apiSuccess {Date} data.list_contracts.effective_date is  Effective Date
 * @apiSuccess {Date} data.list_contracts.end_date is end_date Contract
 * @apiSuccess {Date} data.list_contracts.date_signed is  date_signed Contract
 * @apiSuccess {String} data.list_contracts.represent_company_hire is  represent_company_hire
 * @apiSuccess {Date} data.list_contracts.payment_status is  Payment Status  (unpaid:1|deposit:2|complete the payment :3)
 * @apiSuccess {Date} data.list_contracts.contract_status is  Contract Status (are confirming:0|Signed:1|deposited:2|finish:3|is almost expired:4|expired:5|Trial:6|Creating:7|Unfinished payment:8 |Expired Trial :9)
 * @apiSuccess {String} data.list_contracts.represent_company_rental is  represent_company_rental
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "data_list": [
 *                      {
                            "id": 60,
                            "company_name": "Công ty cố phần VTV3",
                            "contract_code": "9q6r2weq1b",
                            "name_company_rental": "4324324",
*                          "service_package": {
                                "id": 4,
                                "title": "Vip 4",
                                "max_user": 40,
                                "max_user_data": 15,
                                "max_data": 600,
                                "price": 100
                            },
 *                          "template_contract": {
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
                                    },
 *                              ]
 *                           },
                        "service_name": null,
                        "effective_date": "2021-03-01",
                        "end_date": "2021-02-23",
                        "date_signed": "2021-02-23",
                        "represent_company_hire": "null",
                        "phone_company_hire": "432432432",
                        "payment_status": 2,
                        "contract_status": 2,
                        "created_at": "2021-02-26T07:53:08.000000Z",
                        "updated_at": "2021-02-26T07:53:08.000000Z",
                        "represent_company_rental": "324324324"
                        }
 *
 *                  ],
 *                  "total": 1,
 *                  "current_page": 1,
 *                  "limit": 10
 *             }
 *         }
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System Error!"
 *     }
 */
