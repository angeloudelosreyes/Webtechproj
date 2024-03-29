<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Crypt;
use Storage;
use PhpOffice\PhpWord\IOFactory;
class DriveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = DB::table('users_folder_files')->where(['users_id' => auth()->user()->id])->paginate(18);
        $title = 'My Drive';
        return view('drive',compact('title','query'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $query     = DB::table('users_folder_files')->where(['id' => Crypt::decryptString($id)])->first();
        $folder    = DB::table('users_folder')->where(['id' => $query->users_folder_id])->first()->title;
        $title     = $query->files;
        $extension = $query->extension;
        if($query->extension == 'txt') {
            $filePath = 'public/users/'.$query->users_id.'/'.$folder.'/'.$title;
            if (Storage::exists($filePath)) {
                $content = Storage::get($filePath);
            }
        } elseif($query->extension == 'pdf') { 
            $content = 'public/users/'.$query->users_id.'/'.$folder.'/'.$title;
        } elseif($query->extension == 'docx') { 
            $filePath = 'public/users/'.$query->users_id.'/'.$folder.'/'.$title;
            $phpWord = IOFactory::load(storage_path('app/' . $filePath));
            
            $tempFile = tempnam(sys_get_temp_dir(), 'phpword');
            $writer = IOFactory::createWriter($phpWord, 'HTML');
            $writer->save($tempFile);
            $htmlContent = file_get_contents($tempFile);
            unlink($tempFile);
            $content = $htmlContent;
        } else {
            $content = '';
        }
        return view('read',compact('title','query','content','extension'));
    }

    public function display_pdf($title,$content) {
        $path = Crypt::decryptString($content);
        return response()->stream(function () use ($path) {
            echo Storage::disk('local')->get($path);
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="$title"',
        ]);
    }

    public function download($id) {
        $query     = DB::table('users_folder_files')->where(['id' => Crypt::decryptString($id)])->first();
        $folder    = DB::table('users_folder')->where(['id' => $query->users_folder_id])->first()->title;
        $title     = $query->files;
        $extension = $query->extension;
        $filePath = 'public/users/'.$query->users_id.'/'.$folder.'/'.$title;
        
        if (Storage::exists($filePath)) {
            return response()->download(storage_path('app/' . $filePath), $title);
        } else {
            return "File not found.";
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $query = DB::table('users_folder_files')->where(['id' => Crypt::decryptString($id)])->first(); 
        $title = DB::table('users_folder')->where(['id' => $query->users_folder_id])->first()->title;
        $directory = 'public/users/'.$query->users_id.'/'.$title.'/'.$query->files;
        if (Storage::exists($directory)) {
            Storage::delete($directory);
            DB::table('users_folder_files')->where(['id' => Crypt::decryptString($id)])->delete(); 
            return back()->with([
                'message' => 'Selected file has been deleted.',
                'type'    => 'success',
                'title'   => 'System Notification'
            ]);
        } else {
            return back()->with([
                'message' => 'File does not exist.',
                'type'    => 'error',
                'title'   => 'System Notification'
            ]);
        }
    }
}
