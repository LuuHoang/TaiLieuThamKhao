<?php

/**
 * @api {get} /admin/list-sample-contract Get ALL List Sample Contract no paging
 * @apiVersion 0.1.0
 * @apiName List All Sample Contract
 * @apiGroup Admin
 *
 * @apiHeader {String} token_access : token of Admin
 *
 *
 * @apiSuccessExample {json} Success-Example:
 *   {
 *       "code": 200,
 *       "data": {
 *              "list_sample_contract": [
 *               {
 *                   "id": 1,
 *                   "name_sample_contract": "Hợp đồng dùng thử 1 tháng",
 *                   "description": "Hợp đồng dùng thử nếu tốt sẽ đăng ký thêm",
 *                   "tags": "Internet",
 *                   "category": 1,
 *                   "content": "<h1>PDF</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div>"
 *               },
 *               {
 *                   "id": 2,
 *                   "name_sample_contract": "Hợp đồng dùng thử 2 tháng",
 *                   "description": "Hợp đồng dùng thử ,nếu thấy hợp lý sẽ ký kết thêm",
 *                   "tags": "Date",
 *                   "category": 2,
 *                   "content": "<h1>PDF</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div>"
 *               },
 *               {
 *                   "id": 3,
 *                   "name_sample_contract": "Hợp đồng dùng thử 3 tháng",
 *                   "description": "Hợp đồng dùng thử nếu tốt sẽ đăng ký thêm",
 *                   "tags": "Date",
 *                   "category": 2,
 *                   "content": "<h1>PDF</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div>"
 *
 *               },
 *               {
 *                   "id": 4,
 *                   "name_sample_contract": "Hợp đồng dùng thử 6 tháng",
 *                   "description": "Hợp đồng dùng thử nếu tốt sẽ đăng ký thêm",
 *                   "tags": "Date",
 *                   "category": 2,
 *                   "content": "<h1>PDF</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div>"
 *
 *               },
 *               {
 *                   "id": 5,
 *                   "name_sample_contract": "Hợp đồng 2 năm",
 *                   "description": "Hợp đồng Internet 2 năm ",
 *                   "tags": "Date",
 *                   "category": 2,
 *                   "content": "<h1>PDF</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div>"
 *
 *               }
 *           }
 *   }
 */
