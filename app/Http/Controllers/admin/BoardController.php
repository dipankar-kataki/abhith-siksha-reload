<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AssignClass;
use App\Models\AssignSubject;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BoardController extends Controller
{
    public function allBoard()
    {
        $board_details = Board::orderBy('created_at', 'DESC')->get();
        return view('admin.course-management.board.board')->with('board', $board_details);
    }

    public function addBoard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'examBoard' => 'required',
            'examLogo' => 'required|image|mimes:jpeg,png,jpg,gif|max:500'
        ]);

        // dd($request->input());
        if ($validator->fails()) {
            // return response()->json(['message' => 'Whoops! Something went wrong', 'error' => $validator->errors()->first()]);
            return response()->json(['message' => $validator->errors()->first(),  $status = 2]);
        } else {
            $document = $request->examLogo;
            if (isset($document) && !empty($document)) {
                $new_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
                // $new_name = '/images/'.$image.'_'.date('d-m-Y-H-i-s');
                $document->move(public_path('/files/examboard/'), $new_name);
                $file = 'files/examboard/' . $new_name;
            } else {
                return response()->json(['message' => 'File not found', 'status' => 2]);
            }

            $board = Board::where('exam_board', $request->examBoard)->first();

            if ($board) {
                return response()->json(['message' => 'Exam Board name already in used', 'status' => 2]);
            }

            $create = Board::create([
                'exam_board' => $request->examBoard,
                'logo' => $file
            ]);

            if ($create) {
                return response()->json(['message' => 'Board added successfully', 'status' => 1]);
            } else {
                return response()->json(['message' => 'Whoops! Something went wrong. Failed to add board', 'status' => 2]);
            }
        }
    }

    public function updateBoard(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'boardId' => 'required',
                    'boardName' => 'required',
                    'examLogo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:500'
                ],
                [
                    'boardId.required' => 'ID mismatch',
                    'boardName.required' => 'Board name cannot be null',
                    'examLogo.image' => 'Logo must be an image',
                    'examLogo.max' => 'Logo size can not exceed 500KB'
                ]
            );

            if ($validator->fails()) {
                return response()->json(['message' => 'Whoops! Something went wrong', 'error' => $validator->errors()]);
            }

            $dec_id = Crypt::decrypt($request->boardId);

            $document = $request->examLogo;

            // If logo
            if (isset($document) && !empty($document)) {

                $new_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
                $document->move(public_path('/files/examboard/'), $new_name);
                $file = 'files/examboard/' . $new_name;

                $update = Board::find($dec_id)->update([
                    'exam_board' => $request->boardName,
                    'logo' => $file
                ]);
            } else {
                // Check if already exists
                $boards = Board::all();
                foreach ($boards as $board) {
                    if (Str::lower($request->boardName) === Str::lower($board->exam_board)) {
                        return response()->json(['message' => 'Board already exists', 'status' => 2]);
                    }
                }

                $update = Board::find($dec_id)->update([
                    'exam_board' => $request->boardName
                ]);
            }

            // $update = Board::find($dec_id)->update([
            //     'exam_board' => $request->boardName
            // ]);

            if (!$update) {
                return response()->json(['message' => 'Error on board update', 'status' => 1]);
            }
            return response()->json(['message' => 'Board updated successfully', 'status' => 1]);
        } catch (\Throwable $th) {
            //throw $th->getMessage();
            return response()->json(['message' => $th->getMessage(), 'status' => 2]);
        }
    }

    public function updateBoardStatus(Request $request)
    {

        $update = Board::where('id', $request->board_id)->update([
            'is_activate' => $request->active
        ]);



        if ($update) {
            if ($request->active == 0) {
                return response()->json(['message' => 'Visibility changed from show to hide', 'status' => 1]);
            } else {
                return response()->json(['message' => 'Visibility changed from hide to show', 'status' => 1]);
            }
        } else {
            return response()->json(['message' => 'Whoops! Something went wrong. Failed to update status', 'status' => 2]);
        }
    }
}
