<?php

namespace App\Http\Controllers;

use App\Helpers\S3Helper;
use Illuminate\Http\Request;
use App\Models\File;

class FileController extends BaseController
{  

    /**
     * @SWG\Post(
     *   path="/api/file/listOnBase",
     *   tags={"Files"},
     *   summary="List files from database (need auth token)",
     *   operationId="listOnBase",     
     *   @SWG\Parameter(
     *     name="path",
     *     in="query",
     *     description="Path for file. For example 'product/avatar'",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="The access_token with token_type bearer",
     *     required=true,
     *     type="string"
     *   ),          
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=400, description="error"),
     * )
     *
     */
    public function index(Request $request)
    {
        return json_encode(File::all());
    }

    /**
     * @SWG\Post(
     *   path="/api/file/listOnServer",
     *   tags={"Files"},
     *   summary="List files from s3 server (need auth token)",
     *   operationId="listOnServer",     
     *   @SWG\Parameter(
     *     name="path",
     *     in="query",
     *     description="Path for file. For example 'product/avatar'",
     *     required=true,
     *     type="string"
     *   ),     
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="The access_token with token_type bearer",
     *     required=true,
     *     type="string"
     *   ),     
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=400, description="error"),
     * )
     *
     */
    public function listOnServer(Request $request)
    {
    	$path = $request->input('path');

        return json_encode(S3Helper::listOnServer($path));
    }    

    /**
     * @SWG\Post(
     *   path="/api/file/store",
	 *   tags={"Files"},
     *   summary="Add file to s3 (need auth token)",
     *   operationId="addFile",
     *   @SWG\Parameter(
     *     name="file",
     *     in="formData",
     *     description="File",
     *     required=true,
     *     type="file"
     *   ),
     *   @SWG\Parameter(
     *     name="path",
     *     in="query",
     *     description="Path for file. For example 'product/avatar'",
     *     required=true,
     *     type="string"
     *   ), 
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="The access_token with token_type bearer",
     *     required=true,
     *     type="string"
     *   ),         
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=400, description="error"),
     * )
     *
     */
    public function store(Request $request)
    {
    	$file = $request->file;
        $path = $request->input('path');

        // print_r($file); die();

        $file_row = S3Helper::addFile($file, $path);

        return json_encode($file_row);
    }

    /**
     * @SWG\Delete(
     *   path="/api/file/destroy/{id}",
     *   tags={"Files"},
     *   summary="Destroy file by Id (need auth token)",
     *   operationId="destroyFile",
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id file for destroy",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="The access_token with token_type bearer",
     *     required=true,
     *     type="string"
     *   ),     
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=400, description="error"),
     * )
     *
     */
    public function destroy(Request $request, $id)
    {        
        return json_encode(S3Helper::deleteFile($id));
    }    

    /**
     * @SWG\Get(
     *   path="/api/file/download/{id}",
     *   tags={"Files"},
     *   summary="Download file by Id (need auth token)",
     *   operationId="downloadFile",
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id file for download",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="The access_token with token_type bearer",
     *     required=true,
     *     type="string"
     *   ),     
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=400, description="error"),
     * )
     *
     */
	public function download(Request $request, $id)
    {        
        return S3Helper::downloadFile($id);
    }     


    /**
     * @SWG\Get(
     *   path="/api/file/getURL/{id}",
     *   tags={"Files"},
     *   summary="Get file URL)",
     *   operationId="getUrlFile",
     *   @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     description="Id file for download",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=400, description="error"),
     * )
     *
     */
    public function getURL(Request $request, $id)
    {        
        return S3Helper::getURL($id);
    }     
}
