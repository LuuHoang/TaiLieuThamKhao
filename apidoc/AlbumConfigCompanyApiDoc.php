<?php

/**
 * @api {post} /web/config/stamp/ Create Album Config
 * @apiVersion 0.1.0
 * @apiName CreateAlbumConfigCompany
 * @apiGroup Albums
 *
 * @apiParam {Number} [stamp_type]  is type Stamp Config (ICON:0, TEXT:1)
 * @apiParam {Number} [mounting_position] is position
 * @apiParam {File}  [icon] is file image type(PNG,JPEG,PNG,SVG) required with stamp_type:0
 * @apiParam {Text} [text] is text                               required with stamp_type:1
 *
 *
 * @apiSuccessExample {json} Success-Example:
 *  {
 *      "code": 200,
 *      "data": {
 *          "album_config_company": [
 *              {
 *                  "id": 27,
 *                  "stamp_type": 0,
 *                  "mounting_position": 4,
 *                  "icon_path": "http://127.0.0.1:8000/storage/company/5/16123451264.jpeg",
 *                  "text": "Tổng công ty ABC",
 *                  "company_id": 5
 *              }
 *          ]
 *      ]
 *  }
 */

