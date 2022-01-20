<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Member;
use Inertia\Inertia;
use App\Helpers\Export;
use App\Helpers\Import;

class MemberController extends Controller
{

    public function showMembers(Request $request) {
        $members = Member::all();
        return Inertia::render('Members',[
            'members' => $members
        ]);
    }

    public function saveMember(Request $request) {
        $image_path = '';
        if ($request->hasFile('avatar')) {
            $image_path = Storage::url($request->file('avatar')->store('avatar','public'));
        }
        $member = new Member;
        $member->name = $request->input('name');
        $member->email = $request->input('email');
        $member->avatar = $image_path;
        $member->save();
        return redirect()->route('members');
    }

    public function viewMember($id) {
        $member = Member::where('id',$id)->first();
        return Inertia::render('EditMember',[
            'member' => $member
        ]);
    }

    public function deleteMembers(Request $request) {
        if ( $request->has('id') ) {
            $id = $request->id;
            Member::where('id',$id)->delete();
        }
        return redirect()->route('members');
    }

    public function updateMember(Request $request) {
        if ($request->has('id')) {
            $member = Member::find($request->id);
            $member->name = $request->input('name');
            $member->email = $request->input('email');
            if ($request->hasFile('avatar')) {
                $member->avatar = Storage::url($request->file('avatar')->store('avatar','public'));
            }
            $member->save();
            return redirect()->route('members');
        }
    }

    public function ExportMembers() {

        $members = Member::all()->toArray();
        $file = new Export("xlsx");
        $file->fromArray(['id','email','name','avatar','created_at','updated_at']);
        if (count($members) > 0) {
            foreach ($members as $member) {
                $file->fromArray($member);
            }
        }
        return $file->download("Members.xlsx");

    }

    public function isMemberExist($email) {
        return Member::where('email',$email)->first();
    }

    public function bulkSaveMembers(Request $request) {
        if ($request->hasFile('importfile')) {
            $file = $request->file('importfile');
            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $import = Import::readFile($file->path(), function ($row) {
                if ( isset($row['email']) && $row['email'] != '' && $row['email'] != null && 
                isset($row['name']) && $row['name'] != '' && $row['name'] != null ) {
                    if ( $this->isMemberExist($row['email']) === NULL ) {
                        $member = new Member;
                        $member->email = $row['email'];
                        $member->name = $row['name'];
                        if ( isset($row['avatar']) && $row['avatar'] != '' && $row['avatar'] != null ) {
                            $member->avatar = $row['avatar'];
                        }
                        $member->save();
                    }
                }
            });
        }
        return redirect()->route('members');
    }

}
