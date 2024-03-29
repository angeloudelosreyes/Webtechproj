<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
use Crypt;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        $request->validate([
            'files.*' => ['required','mimes:pdf,docx,txt']
        ]);
        // auth()->user()->id,  eto yong ginagamit mo para ma kuha mo yong details mo "ikaw na naka login," ang format nyan ay auth()->user()->column
        $id        = auth()->user()->id;
        // Crypt::decryptString(), eto yong ginagamit mo para idecrypt mo yong string na inencrypt mo, kasi pag aaccess ka ng specific file, page,
        // or mag eedit ka ng kong ano,  ang ipapakita padin ay hindi plain na id, kasi delikado yun. instead ididisplay mo e encrypted value,
        // so para mabasa mo sya kelangan mo sya idecrypt,
        $folder_id = Crypt::decryptString($request->folder_id);
        $title     = $request->folder;
        
        // etong hasFile ang purpose neto e para ma sure mo na yong files e meron laman.
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            // foreach, eto e isa sa mga loops, ang tinatangap ng loops na to ay array,
            foreach ($request->file('files') as $file) {
                // getClientOriginalName / getClientOriginalExtension / storeAs / getSize
                // etongyang mga yan e para sa 
                // 1.  makuha mo yong file name na inupload mo mismo kasi diba concern mo sir is bakit nag iiba yong name pag ina upload, now kong ano yong name ng inupload mong file, yun na yong masesave din.
                // 2. etong original extension e kinukuha nya kong anong file extension yong inupload mo,  pdf ba, docx, or what.
                // 3. etong part na to e sabi after mo daw i upload san mo daw ilalagay yong file na inupload mo? 
                // 4. eto naman e para makuha mo specific size nung inupload mo. ang pag kuha neto ay by byte.
                
                $name      = $file->getClientOriginalName(). '.' . $file->getClientOriginalExtension();
                $extension = $file->getClientOriginalExtension();
                $path      = $file->storeAs("public/users/$id/$title",$name); // Store in the 'uploads' 
                $fileSize  = $file->getSize();
                DB::table('users_folder_files')->insert([
                    'users_id'        => $id,
                    'users_folder_id' => $folder_id,
                    'files'           => $name,
                    'size'            => $fileSize,
                    'extension'       => $extension
                ]);
            }
            return back()->with([
                'message' => 'New file has been uploaded.',
                'type'    => 'success',
                'title'   => 'System Notification'
            ]);
        } else {
            return back()->with([
                'message' => 'Upload failed.',
                'type'    => 'error',
                'title'   => 'System Notification'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }
}
