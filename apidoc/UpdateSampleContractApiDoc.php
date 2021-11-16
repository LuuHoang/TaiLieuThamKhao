<?php


/**
 * @api {put} /admin/sample/contract Update Sample Contract
 * @apiVersion 0.1.0
 * @apiName UpdateSampleContract
 * @apiGroup Admin
 *
 * @apiParam {Text} [name]  is Name Sample Contract
 * @apiParam {Text} [description] is description Sample Contract
 * @apiParam {Text}  [tags] is tags
 * @apiParam {Object[]} [sample_contract_properties] Create SampleContract object
 * @apiParam {Number} [sample_contract_properties.id]  Id of Sample Contract Properties (Can be null)
 * @apiParam {Text} [sample_contract_properties.title]  Title of Sample Contract Properties
 * @apiParam {Number} [sample_contract_properties.data_type]  Number of Sample Contract Properties
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
 *          "sample_contract_properties": [
 *                 {
 *                      "id"   : 1,
 *                      "title": "Mục đích sử dụng",
 *                      "data_type": 1
 *                  },
 *                  {
 *                      "id"   : 2,
 *                      "title": "Địa chỉ",
 *                      "data_type": 1
 *                  },
 *                  {
 *                      "id":3,
 *                      "title": "Số điện thoại liên hệ",
 *                      "data_type": 1,
 *                  }
 *              ]
 *          }
 *      }
 *  }
 */
