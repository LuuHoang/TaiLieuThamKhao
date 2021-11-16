<?php
/**
 * @api {post} /admin/list/sample/contract Get List Sample Contract
 * @apiVersion 0.1.0
 * @apiName List Sample Contract
 * @apiGroup Admin
 *
 * @apiHeader {String} token_access : token of Admin
 *
 * @apiParam {Number} [limit]  is page limit
 * @apiParam {Number} [sample_contract_id]  is id of Sample Contract
 * @apiParam {Text} [search]  is Key Search
 * @apiParam {Array} [filter]  is filter
 * @apiParam {date} [filter.created_at]  is filter by date created
 * @apiParam {string} [filter.created_by]  is filter by created_by
 *  * @apiParam {string} [filter.category]  is filter by category
 * @apiParam {Array} [sort]  is array sort
 * @apiParam {String} [sort.id] is sort by id |asc|desc|
 * @apiParam {String} [sort.name] is sort |asc|desc|
 * @apiParam {String} [sort.created_at] is sort by created_at |asc|desc|
 * @apiParam {String} [sort.created_at] is sort by sort[created_by] |asc|desc|
 *
 * @apiSuccessExample {json} Success-Example:
 *   {
 *       "code": 200,
 *       "data": {
 *           "data_list": [
 *               {
 *                  "id": 1,
 *                  "created_at": "2021-02-04T03:16:58.000000Z",
 *                  "name_sample_contract": "Hợp đồng dùng thử 6 tháng",
 *                  "description": "Dùng thử nếu tốt dùng thử tiếp",
 *                  "tags": "VTC,123123,Vet",
 *                  "created_by": "Hung Hoang",
 *                  "totalContracts": 1,
 *                  "category":1,
 *               },
 *               {
 *                   "id": 2,
 *                  "created_at": "2021-02-04T04:18:21.000000Z",
 *                  "name_sample_contract": "Hợp đồng dùng thử 1 tháng",
 *                  "description": "Hợp đồng dùng thử nếu tốt sẽ đăng ký thêm",
 *                  "tags": "VNT,VTC,Game",
 *                  "created_by": "Hung Hoang",
 *                  "totalContracts": 1,
 *                  "category":2,
 *               }
 *           ],
 *           "total": 2,
 *           "current_page": 1,
 *           "limit": 10
 *       }
 *   }
 */


