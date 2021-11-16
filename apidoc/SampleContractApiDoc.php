<?php




/**
 * @api {get} /admin/sample/contract/{SampleContractId} Get Info Sample Contract
 * @apiVersion 0.1.0
 * @apiName GetInfoSampleContract
 * @apiGroup Admin
 *
 *
 *
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
 *          "category":1,
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
 *
 *                  }
 *              ]
 *          }
 *      }
 *  }
 *
 */
