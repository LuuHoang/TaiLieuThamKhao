<?php

/**
 * @api {post} /albums/pdf Upload pdf
 * @apiName Upload pdf
 * @apiGroup Upload
 *
 * @apiParam [Files[]] files
 *
 * @apiSuccessExample {json} Success-Example:
 *  {
 *      "code": 200,
 *      "data": [
 *          "files": [
 *              {
 *                  "id": 1,
 *                  "name": "abc.pdf",
 *                  "file_path": "http://localhost/"
 *              }
 *          ]
 *      ]
 *  }
 */
