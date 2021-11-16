<?php

/**
* @api {post} /admin/sample/contract Create Sample Contract
* @apiVersion 0.1.0
* @apiName CreateSampleContract
* @apiGroup Admin
*
 * @apiParam {Text} [name_contract]  is Name Sample Contract
 * @apiParam {Text} [description] is description Sample Contract
 * @apiParam {Text}  [tags] is tag
 * @apiParam {Text} [content]  is Content
 * @apiParam {Text}  [category] is Category |min:1|max:3|( Trial:1 | fixed :2  | extend:3)
 * @apiParam {Object[]} [sample_contract_properties] Create SampleContract object
 * @apiParam {Text} [sample_contract_properties.title]  Title of Sample Contract Properties
 * @apiParam {Number} [sample_contract_properties.data_type]  Title of Sample Contract Properties (Text:1|Date:2|Number:3)
 * @apiSuccessExample {json} Success-Example:
 *  {
 *      "code": 200,
 *      "data": {
 *          "sample_contract": {
 *          "id": 1,
 *          "name_sample_contract": "Hợp đồng dùng thử 1 tháng",
 *          "description": "Hợp đồng dùng thử ,nếu thấy hợp lý sẽ ký kết thêm",
 *          "tags": "Công ty ABC , XYZ",
 *          "created_by":"Hung Hoang",
 *          "updated_by":"Xuan Tung",
 *          "category" :1,
 *          "content":"<h1>PDF</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div><h1>ABC</h1><div>shared.link</div><div>shared.password</div><div>shared.guest.name</div>",
 *          "sample_contract_properties": [
 *                 {
 *                      "id"   : 1,
 *                      "title": "Mục đích sử dụng",
 *                      "data_type": 1,
 *                      "sample_contract_id": 1
 *
 *                  },
 *                  {
 *                      "id"   : 2,
 *                      "title": "Address",
 *                      "data_type": 1,
 *                      "sample_contract_id": 1

 *                  }
 *              ]
 *          }
 *      }
 *  }
 */
