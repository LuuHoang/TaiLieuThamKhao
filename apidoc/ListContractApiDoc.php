<?php


/**
 * @api {post} /admin/list/contract Get List Contract
 * @apiVersion 0.1.0
 * @apiName List Contract
 * @apiGroup Admin
 *
 * @apiHeader {String} token_access : token of Admin
 *
 * @apiParam {Number} [limit]  is page limit
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
 * @apiSuccessExample {json} Success-Example:
 *   {
 *       "code": 200,
 *       "data": {
 *           "data_list": [
 *               {
 *                  "id": 1,
 *                   "company_name": "SCSOFT SUPENIENT",
 *                   "name_company_rental": "VNExpress",
 *                   "title": "Vip 1",
 *                   "effective_date": "2020-02-27",
 *                   "end_date": "2020-08-27",
 *                   "date_signed": "2020-02-25",
 *                   "represent_company_hire": "Lưu Văn Long",
 *                   "phone_company_hire": "0323564545",
 *                   "payment_status": 0,
 *                   "contract_status": 2,
 *                   "created_at": "2021-02-04T08:02:28.000000Z",
 *                   "updated_at": "2021-02-04T08:02:28.000000Z",
 *                   "represent_company_rental": "Đỗ Trung Kiên"
 *               },
 *               {
 *                   "id": 2,
 *                   "company_name": "SCSOFT SUPENIENT",
 *                   "name_company_rental": "SamSung",
 *                   "title": "Vip 2",
 *                   "effective_date": "2020-01-21",
 *                   "end_date": "2020-09-27",
 *                   "date_signed": "2020-02-26",
 *                   "represent_company_hire": "Lưu Văn Hoàng",
 *                   "phone_company_hire": "0323564545",
 *                   "payment_status": 0,
 *                   "contract_status": 2,
 *                   "created_at": "2021-02-04T09:17:34.000000Z",
 *                   "updated_at": "2021-02-04T09:17:34.000000Z",
 *                   "represent_company_rental": "Đỗ Trung Kiên"
 *               }
 *           ],
 *           "total": 2,
 *           "current_page": 1,
 *           "limit": 10
 *       }
 *   }
 */
