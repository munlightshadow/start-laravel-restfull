<?php 
namespace App\Helpers;
use App\Models\File;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Http\File as TFile;

class S3Helper {
    
    static public function listOnServer($path = '')
    {
        return Storage::disk('s3')->files($path);
    }  

    static public function addFile($file, $path) {
        $fileName = $file->getClientOriginalName();
        $fileSizeName = $file->getClientSize();
        $user = auth()->user();
        $s3name = Storage::disk('s3')->put($path, $file);
        
        $file = File::updateOrCreate(
            ['user_id' => $user->id, 's3name' => $s3name, 'name' => $fileName, 'path' => $path, 'size' => $fileSizeName]
        );

        return $file;
    }    

    static public function addFileFromUrl($url, $path) {
        $contents = file_get_contents($url);
        $name = substr($url, strrpos($url, '/') + 1);
        $file = Storage::put($name, $contents);

        $s3name = Storage::disk('s3')->put($path, new TFile(storage_path('app/') . $name));

        Storage::delete($name);

        $user = auth()->user();
        $file = File::updateOrCreate(
            ['user_id' => $user->id, 's3name' => $s3name, 'name' => $name, 'path' => $path]
        );

        return $file;
    }    

    static public function deleteFile($id) {
        if($file = File::find($id)){
            Storage::disk('s3')->delete($file->s3name);
            File::where('id', '=', $id)->delete();
        };
        return true;
    }    

    static public function downloadFile($id) {
        $file = File::findOrFail($id);
        $body = Storage::disk('s3')->get($file->s3name);
        $object = Storage::disk('s3')->getAdapter()->getClient()->getObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key'    => $file->s3name
        ]);
        $object->Body = $body;
        return (new Response($body, 200))
                ->header('Content-Type', $object['ContentType']);
                // ->header('Content-Disposition', 'attachment; filename="'.$material->name.'"');
    }

    static public function getUrl($id) {
        $file = File::find($id);
        $url = 'https://s3-' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/' . $file->s3name;

        return $url;
    }    

    static public function getURLbyS3Name($s3name) {
        $url = 'https://s3-' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/' . $s3name;

        return $url;
    }        

}